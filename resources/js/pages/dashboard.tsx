import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { CalendarDays, CreditCard, Ticket, Users } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardMetricSet {
    total_events: number;
    published_events: number;
    total_bookings: number;
    sold_out_events: number;
    revenue: string;
    recent_bookings: Array<{
        booking_number: string;
        customer_name: string;
        event_title: string;
        venue: string;
        amount: string;
        status: string;
        created_at: string;
    }>;
}

interface UserBooking {
    booking_number: string;
    status: string;
    amount: string;
    event_title: string;
    venue: string;
    starts_at: string;
    created_at: string;
}

interface EventRow {
    id: number;
    title: string;
    venue: string;
    starts_at: string;
    price: string;
    available_seats: number;
}

interface DashboardProps {
    isAdmin: boolean;
    metrics: DashboardMetricSet | null;
    userBookings: UserBooking[];
}

function formatDate(value: string) {
    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

function getCsrfToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (metaToken) {
        return metaToken;
    }

    const cookieToken = document.cookie
        .split('; ')
        .find((cookie) => cookie.startsWith('XSRF-TOKEN='))
        ?.split('=')[1];

    return cookieToken ? decodeURIComponent(cookieToken) : '';
}


export default function Dashboard({ isAdmin, metrics, userBookings }: DashboardProps) {
    const { flash } = usePage<SharedData>().props;
    const [bookings, setBookings] = useState<UserBooking[]>(userBookings);
    const [events, setEvents] = useState<EventRow[]>([]);
    const [eventsLoading, setEventsLoading] = useState(false);
    const [eventsError, setEventsError] = useState<string | null>(null);
    const [bookingMessage, setBookingMessage] = useState<string | null>(null);
    const [bookingError, setBookingError] = useState<string | null>(null);
    const [joiningEventId, setJoiningEventId] = useState<number | null>(null);
    const [cancelingBookingNumber, setCancelingBookingNumber] = useState<string | null>(null);

    const joinedEventTitles = useMemo(
        () => new Set(bookings.filter((booking) => booking.status === 'confirmed').map((booking) => booking.event_title.toLowerCase())),
        [bookings],
    );

    useEffect(() => {
        if (isAdmin) {
            return;
        }

        const loadEvents = async () => {
            setEventsLoading(true);
            setEventsError(null);

            try {
                const response = await fetch('/api/events?per_page=50', {
                    headers: {
                        Accept: 'application/json',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error('Could not load events right now.');
                }

                const payload = (await response.json()) as { data?: EventRow[] };
                setEvents(Array.isArray(payload.data) ? payload.data : []);
            } catch (error) {
                setEvents([]);
                setEventsError(error instanceof Error ? error.message : 'Could not load events right now.');
            } finally {
                setEventsLoading(false);
            }
        };

        void loadEvents();
    }, [isAdmin]);

    const handleJoinEvent = async (event: EventRow) => {
        setBookingMessage(null);
        setBookingError(null);
        setJoiningEventId(event.id);

        try {
            const response = await fetch('/api/bookings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-XSRF-TOKEN': getCsrfToken(),
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    event_id: event.id,
                    payment_method: 'fake_card',
                    payment_token: 'tok_demo_success',
                }),
            });

            const payload = (await response.json()) as {
                message?: string;
                data?: { available_seats?: number; booking_number?: string; status?: string };
            };

            if (!response.ok) {
                throw new Error(payload.message ?? 'Could not complete booking.');
            }

            const nextSeats = payload.data?.available_seats;
            setEvents((currentEvents) =>
                currentEvents.map((currentEvent) =>
                    currentEvent.id === event.id
                        ? {
                              ...currentEvent,
                              available_seats:
                                  typeof nextSeats === 'number'
                                      ? nextSeats
                                      : Math.max(currentEvent.available_seats - 1, 0),
                          }
                        : currentEvent,
                ),
            );
            setBookings((currentBookings) => [
                {
                    booking_number: payload.data?.booking_number ?? `PENDING-${event.id}`,
                    status: payload.data?.status ?? 'confirmed',
                    amount: event.price,
                    event_title: event.title,
                    venue: event.venue,
                    starts_at: event.starts_at,
                    created_at: new Date().toISOString(),
                },
                ...currentBookings,
            ]);
            setBookingMessage(payload.message ?? `You joined "${event.title}" successfully.`);
        } catch (error) {
            setBookingError(error instanceof Error ? error.message : 'Could not complete booking.');
        } finally {
            setJoiningEventId(null);
        }
    };

    const handleCancelBooking = async (booking: UserBooking) => {
        setBookingMessage(null);
        setBookingError(null);
        setCancelingBookingNumber(booking.booking_number);

        try {
            const response = await fetch(`/api/bookings/${booking.booking_number}`, {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-XSRF-TOKEN': getCsrfToken(),
                },
                credentials: 'same-origin',
            });

            const payload = (await response.json()) as { message?: string };
            if (!response.ok) {
                throw new Error(payload.message ?? 'Could not cancel booking.');
            }

            setBookings((currentBookings) =>
                currentBookings.map((currentBooking) =>
                    currentBooking.booking_number === booking.booking_number ? { ...currentBooking, status: 'cancelled' } : currentBooking,
                ),
            );
            setEvents((currentEvents) =>
                currentEvents.map((event) =>
                    event.title === booking.event_title ? { ...event, available_seats: event.available_seats + 1 } : event,
                ),
            );
            setBookingMessage(payload.message ?? `Booking ${booking.booking_number} cancelled.`);
        } catch (error) {
            setBookingError(error instanceof Error ? error.message : 'Could not cancel booking.');
        } finally {
            setCancelingBookingNumber(null);
        }
    };

    const statCards = metrics
        ? [
              {
                  title: 'Total events',
                  value: metrics.total_events,
                  description: `${metrics.published_events} published`,
                  icon: CalendarDays,
              },
              {
                  title: 'Confirmed bookings',
                  value: metrics.total_bookings,
                  description: 'Live booking volume',
                  icon: Ticket,
              },
              {
                  title: 'Sold out events',
                  value: metrics.sold_out_events,
                  description: 'Events with no remaining seats',
                  icon: Users,
              },
              {
                  title: 'Revenue',
                  value: `$${metrics.revenue}`,
                  description: 'Successful fake payments',
                  icon: CreditCard,
              },
          ]
        : [];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex flex-1 flex-col gap-6 p-4">
                {(flash?.success || flash?.error) && (
                    <Alert variant={flash?.error ? 'destructive' : 'default'}>
                        <AlertTitle>{flash?.error ? 'Action failed' : 'Success'}</AlertTitle>
                        <AlertDescription>{flash?.error ?? flash?.success}</AlertDescription>
                    </Alert>
                )}

                {isAdmin && metrics ? (
                    <>
                        <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            {statCards.map((card) => (
                                <Card key={card.title} className="border-sidebar-border/60">
                                    <CardHeader className="flex flex-row items-start justify-between space-y-0">
                                        <div>
                                            <CardDescription>{card.title}</CardDescription>
                                            <CardTitle className="mt-2 text-3xl">{card.value}</CardTitle>
                                        </div>
                                        <card.icon className="text-muted-foreground size-5" />
                                    </CardHeader>
                                    <CardContent>
                                        <p className="text-sm text-muted-foreground">{card.description}</p>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>

                        <Card>
                            <CardHeader>
                                <CardTitle>Recent bookings</CardTitle>
                                <CardDescription>Latest successful bookings and payment captures.</CardDescription>
                            </CardHeader>
                            <CardContent>
                                {metrics.recent_bookings.length === 0 ? (
                                    <p className="text-sm text-muted-foreground">No bookings yet.</p>
                                ) : (
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full text-sm">
                                            <thead className="text-left text-muted-foreground">
                                                <tr className="border-b">
                                                    <th className="pb-3">Booking</th>
                                                    <th className="pb-3">Customer</th>
                                                    <th className="pb-3">Event</th>
                                                    <th className="pb-3">Venue</th>
                                                    <th className="pb-3">Amount</th>
                                                    <th className="pb-3">Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {metrics.recent_bookings.map((booking) => (
                                                    <tr key={booking.booking_number} className="border-b last:border-b-0">
                                                        <td className="py-3 font-medium">{booking.booking_number}</td>
                                                        <td className="py-3">{booking.customer_name}</td>
                                                        <td className="py-3">{booking.event_title}</td>
                                                        <td className="py-3">{booking.venue}</td>
                                                        <td className="py-3">${booking.amount}</td>
                                                        <td className="py-3">{formatDate(booking.created_at)}</td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </>
                ) : (
                    <>
                        <Card>
                            <CardHeader>
                                <CardTitle>Your booking dashboard</CardTitle>
                                <CardDescription>Use the event API to browse events and book seats from the authenticated app session.</CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-2 text-sm text-muted-foreground">
                                <p>`GET /api/events` returns published events with remaining seats.</p>
                                <p>`POST /api/bookings` accepts `event_id`, `payment_method`, and `payment_token`.</p>
                                <p>Use `tok_demo_success` for a fake success or `tok_demo_fail_anything` for a fake decline.</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Available events</CardTitle>
                                <CardDescription>Join published events that still have seats.</CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                {bookingMessage && (
                                    <Alert>
                                        <AlertTitle>Joined</AlertTitle>
                                        <AlertDescription>{bookingMessage}</AlertDescription>
                                    </Alert>
                                )}
                                {bookingError && (
                                    <Alert variant="destructive">
                                        <AlertTitle>Join failed</AlertTitle>
                                        <AlertDescription>{bookingError}</AlertDescription>
                                    </Alert>
                                )}
                                {eventsError && (
                                    <Alert variant="destructive">
                                        <AlertTitle>Events unavailable</AlertTitle>
                                        <AlertDescription>{eventsError}</AlertDescription>
                                    </Alert>
                                )}

                                {eventsLoading ? (
                                    <p className="text-sm text-muted-foreground">Loading events...</p>
                                ) : events.length === 0 ? (
                                    <p className="text-sm text-muted-foreground">No events available right now.</p>
                                ) : (
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full text-sm">
                                            <thead className="text-left text-muted-foreground">
                                                <tr className="border-b">
                                                    <th className="pb-3">Event</th>
                                                    <th className="pb-3">Venue</th>
                                                    <th className="pb-3">Starts at</th>
                                                    <th className="pb-3">Price</th>
                                                    <th className="pb-3">Seats</th>
                                                    <th className="pb-3">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {events.map((event) => {
                                                    const alreadyJoined = joinedEventTitles.has(event.title.toLowerCase());
                                                    const canJoin = event.available_seats > 0 && !alreadyJoined;

                                                    return (
                                                        <tr key={event.id} className="border-b last:border-b-0">
                                                            <td className="py-3 font-medium">{event.title}</td>
                                                            <td className="py-3">{event.venue}</td>
                                                            <td className="py-3">{formatDate(event.starts_at)}</td>
                                                            <td className="py-3">${event.price}</td>
                                                            <td className="py-3">{event.available_seats}</td>
                                                            <td className="py-3">
                                                                <Button
                                                                    size="sm"
                                                                    disabled={!canJoin || joiningEventId === event.id}
                                                                    onClick={() => void handleJoinEvent(event)}
                                                                >
                                                                    {joiningEventId === event.id
                                                                        ? 'Joining...'
                                                                        : alreadyJoined
                                                                          ? 'Already joined'
                                                                          : event.available_seats === 0
                                                                            ? 'Sold out'
                                                                            : 'Join event'}
                                                                </Button>
                                                            </td>
                                                        </tr>
                                                    );
                                                })}
                                            </tbody>
                                        </table>
                                    </div>
                                )}
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Recent bookings</CardTitle>
                                <CardDescription>Your latest confirmed bookings.</CardDescription>
                            </CardHeader>
                            <CardContent>
                                {bookings.length === 0 ? (
                                    <p className="text-sm text-muted-foreground">No bookings yet.</p>
                                ) : (
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full text-sm">
                                            <thead className="text-left text-muted-foreground">
                                                <tr className="border-b">
                                                    <th className="pb-3">Booking</th>
                                                    <th className="pb-3">Event</th>
                                                    <th className="pb-3">Venue</th>
                                                    <th className="pb-3">Starts at</th>
                                                    <th className="pb-3">Amount</th>
                                                    <th className="pb-3">Status</th>
                                                    <th className="pb-3">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {bookings.map((booking) => (
                                                    <tr key={booking.booking_number} className="border-b last:border-b-0">
                                                        <td className="py-3 font-medium">{booking.booking_number}</td>
                                                        <td className="py-3">{booking.event_title}</td>
                                                        <td className="py-3">{booking.venue}</td>
                                                        <td className="py-3">{formatDate(booking.starts_at)}</td>
                                                        <td className="py-3">${booking.amount}</td>
                                                        <td className="py-3 capitalize">{booking.status}</td>
                                                        <td className="py-3">
                                                            <Button
                                                                variant="outline"
                                                                size="sm"
                                                                disabled={booking.status !== 'confirmed' || cancelingBookingNumber === booking.booking_number}
                                                                onClick={() => void handleCancelBooking(booking)}
                                                            >
                                                                {cancelingBookingNumber === booking.booking_number ? 'Cancelling...' : 'Cancel join'}
                                                            </Button>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </>
                )}
            </div>
        </AppLayout>
    );
}
