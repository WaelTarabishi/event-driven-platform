import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { buttonVariants } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

interface AuditLogRow {
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

interface AuditLogsPageProps {
    logs: {
        data: AuditLogRow[];
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
    { title: 'Audit Logs', href: '/admin/audit-logs' },
];

function formatDate(value: string | null) {
    if (!value) return '-';

    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

export default function AuditLogsIndex({ logs }: AuditLogsPageProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Audit Logs" />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Audit logs</CardTitle>
                        <CardDescription>Track admin actions with actor, time, and affected entities.</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="overflow-x-auto">
                            <table className="min-w-full text-sm">
                                <thead className="text-left text-muted-foreground">
                                    <tr className="border-b">
                                        <th className="pb-3">Time</th>
                                        <th className="pb-3">Admin</th>
                                        <th className="pb-3">Action</th>
                                        <th className="pb-3">Entity</th>
                                        <th className="pb-3">Request ID</th>
                                        <th className="pb-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {logs.data.map((log) => (
                                        <tr key={log.id} className="border-b align-top last:border-b-0">
                                            <td className="py-3">{formatDate(log.created_at)}</td>
                                            <td className="py-3">
                                                <div className="font-medium">{log.user?.name ?? 'System'}</div>
                                                <div className="text-xs text-muted-foreground">{log.user?.email ?? '-'}</div>
                                            </td>
                                            <td className="py-3">{log.action}</td>
                                            <td className="py-3">
                                                {log.entity_type}
                                                {log.entity_id ? ` #${log.entity_id}` : ''}
                                            </td>
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

                        {logs.data.length === 0 && <p className="text-sm text-muted-foreground">No audit logs available yet.</p>}

                        <div className="flex flex-wrap items-center justify-between gap-3 border-t pt-4 text-sm text-muted-foreground">
                            <p>
                                Page {logs.meta.current_page} of {logs.meta.last_page} · {logs.meta.total} total logs
                            </p>
                            <div className="flex gap-2">
                                {logs.meta.prev_page_url && (
                                    <Link className={buttonVariants({ variant: 'outline', size: 'sm' })} href={logs.meta.prev_page_url}>
                                        Previous
                                    </Link>
                                )}
                                {logs.meta.next_page_url && (
                                    <Link className={buttonVariants({ variant: 'outline', size: 'sm' })} href={logs.meta.next_page_url}>
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
