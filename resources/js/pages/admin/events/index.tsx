import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button, buttonVariants } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { BreadcrumbItem, SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

interface EventRow {
    id: number;
    title: string;
    venue: string;
    starts_at: string;
    total_seats: number;
    available_seats: number;
    price: string;
    status: string;
    creator_name?: string | null;
    confirmed_bookings_count: number;
}

interface EventsPageProps {
    events: {
        data: EventRow[];
        meta: {
            current_page: number;
            last_page: number;
            total: number;
            next_page_url: string | null;
            prev_page_url: string | null;
        };
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Events', href: '/admin/events' },
];

function formatDate(value: string) {
    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

function badgeClass(status: string) {
    if (status === 'published') return 'bg-emerald-100 text-emerald-700';
    if (status === 'cancelled') return 'bg-amber-100 text-amber-700';
    return 'bg-slate-100 text-slate-700';
}

export default function EventsIndex({ events }: EventsPageProps) {
    const { flash } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Events" />
            <div className="flex flex-1 flex-col gap-6 p-4">
                {(flash?.success || flash?.error) && (
                    <Alert variant={flash?.error ? 'destructive' : 'default'}>
                        <AlertTitle>{flash?.error ? 'Action failed' : 'Success'}</AlertTitle>
                        <AlertDescription>{flash?.error ?? flash?.success}</AlertDescription>
                    </Alert>
                )}

                <Card>
                    <CardHeader className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <CardTitle>Manage events</CardTitle>
                            <CardDescription>Create, publish, cancel, and retire events with strict seat controls.</CardDescription>
                        </div>
                        <Button asChild>
                            <Link href={route('admin.events.create')}>Create event</Link>
                        </Button>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="overflow-x-auto">
                            <table className="min-w-full text-sm">
                                <thead className="text-left text-muted-foreground">
                                    <tr className="border-b">
                                        <th className="pb-3">Title</th>
                                        <th className="pb-3">Venue</th>
                                        <th className="pb-3">Starts at</th>
                                        <th className="pb-3">Seats</th>
                                        <th className="pb-3">Price</th>
                                        <th className="pb-3">Status</th>
                                        <th className="pb-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {events.data.map((event) => (
                                        <tr key={event.id} className="border-b last:border-b-0">
                                            <td className="py-4">
                                                <div className="font-medium">{event.title}</div>
                                                <div className="text-xs text-muted-foreground">{event.creator_name ?? 'Unknown admin'}</div>
                                            </td>
                                            <td className="py-4">{event.venue}</td>
                                            <td className="py-4">{formatDate(event.starts_at)}</td>
                                            <td className="py-4">
                                                {event.available_seats}/{event.total_seats}
                                                <div className="text-xs text-muted-foreground">{event.confirmed_bookings_count} confirmed</div>
                                            </td>
                                            <td className="py-4">${event.price}</td>
                                            <td className="py-4">
                                                <span className={cn('inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize', badgeClass(event.status))}>
                                                    {event.status}
                                                </span>
                                            </td>
                                            <td className="py-4">
                                                <div className="flex flex-wrap gap-2">
                                                    <Link className={buttonVariants({ variant: 'secondary', size: 'sm' })} href={route('admin.events.show', event.id)}>
                                                        View
                                                    </Link>
                                                    <Link className={buttonVariants({ variant: 'outline', size: 'sm' })} href={route('admin.events.edit', event.id)}>
                                                        Edit
                                                    </Link>
                                                    {event.status !== 'cancelled' && (
                                                        <Link
                                                            className={buttonVariants({ variant: 'secondary', size: 'sm' })}
                                                            href={route('admin.events.cancel', event.id)}
                                                            method="patch"
                                                            as="button"
                                                        >
                                                            Cancel
                                                        </Link>
                                                    )}
                                                    <Link
                                                        className={buttonVariants({ variant: 'destructive', size: 'sm' })}
                                                        href={route('admin.events.destroy', event.id)}
                                                        method="delete"
                                                        as="button"
                                                    >
                                                        Delete
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {events.data.length === 0 && <p className="text-sm text-muted-foreground">No events have been created yet.</p>}

                        <div className="flex flex-wrap items-center justify-between gap-3 border-t pt-4 text-sm text-muted-foreground">
                            <p>
                                Page {events.meta.current_page} of {events.meta.last_page} · {events.meta.total} total events
                            </p>
                            <div className="flex gap-2">
                                {events.meta.prev_page_url && (
                                    <Link className={buttonVariants({ variant: 'outline', size: 'sm' })} href={events.meta.prev_page_url}>
                                        Previous
                                    </Link>
                                )}
                                {events.meta.next_page_url && (
                                    <Link className={buttonVariants({ variant: 'outline', size: 'sm' })} href={events.meta.next_page_url}>
                                        Next
                                    </Link>
                                )}
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
