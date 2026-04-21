import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

interface EventDetails {
    id: number;
    title: string;
    venue: string;
    starts_at: string;
    ends_at: string;
    total_seats: number;
    available_seats: number;
    price: string;
    status: string;
    creator_name?: string | null;
}

interface EventAuditLog {
    id: number;
    action: string;
    entity_type: string;
    entity_id: number | null;
    metadata: Record<string, unknown> | null;
    request_id: string | null;
    created_at: string | null;
    user: {
        id: number;
        name: string;
        email: string;
    } | null;
}

interface EventShowPageProps {
    event: EventDetails;
    auditLogs: EventAuditLog[];
}

function formatDate(value: string | null) {
    if (!value) return '-';

    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

export default function EventShow({ event, auditLogs }: EventShowPageProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Events', href: '/admin/events' },
        { title: event.title, href: `/admin/events/${event.id}` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Event: ${event.title}`} />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>{event.title}</CardTitle>
                        <CardDescription>Complete event details and activity timeline.</CardDescription>
                    </CardHeader>
                    <CardContent className="grid gap-4 md:grid-cols-2 text-sm">
                        <div>
                            <p className="text-muted-foreground">Venue</p>
                            <p className="font-medium">{event.venue}</p>
                        </div>
                        <div>
                            <p className="text-muted-foreground">Status</p>
                            <p className="font-medium capitalize">{event.status}</p>
                        </div>
                        <div>
                            <p className="text-muted-foreground">Starts at</p>
                            <p className="font-medium">{formatDate(event.starts_at)}</p>
                        </div>
                        <div>
                            <p className="text-muted-foreground">Ends at</p>
                            <p className="font-medium">{formatDate(event.ends_at)}</p>
                        </div>
                        <div>
                            <p className="text-muted-foreground">Seats</p>
                            <p className="font-medium">
                                {event.available_seats}/{event.total_seats}
                            </p>
                        </div>
                        <div>
                            <p className="text-muted-foreground">Price</p>
                            <p className="font-medium">${event.price}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Event audit timeline</CardTitle>
                        <CardDescription>Latest event-related actions, including user joins and cancellations.</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        {auditLogs.length === 0 ? (
                            <p className="text-sm text-muted-foreground">No audit logs for this event yet.</p>
                        ) : (
                            <div className="overflow-x-auto">
                                <table className="min-w-full text-sm">
                                    <thead className="text-left text-muted-foreground">
                                        <tr className="border-b">
                                            <th className="pb-3">Time</th>
                                            <th className="pb-3">Actor</th>
                                            <th className="pb-3">Action</th>
                                            <th className="pb-3">Request ID</th>
                                            <th className="pb-3">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {auditLogs.map((log) => (
                                            <tr key={log.id} className="border-b align-top last:border-b-0">
                                                <td className="py-3">{formatDate(log.created_at)}</td>
                                                <td className="py-3">
                                                    <div className="font-medium">{log.user?.name ?? 'System'}</div>
                                                    <div className="text-xs text-muted-foreground">{log.user?.email ?? '-'}</div>
                                                </td>
                                                <td className="py-3">{log.action}</td>
                                                <td className="py-3 font-mono text-xs">{log.request_id ?? '-'}</td>
                                                <td className="py-3">
                                                    <pre className="max-w-lg overflow-auto whitespace-pre-wrap break-words rounded-md bg-muted p-2 text-xs">
                                                        {JSON.stringify(log.metadata ?? {}, null, 2)}
                                                    </pre>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
