import { EventForm } from '@/components/admin/event-form';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';

interface EventDetails {
    id: number;
    title: string;
    description: string | null;
    venue: string;
    starts_at: string;
    ends_at: string;
    total_seats: number;
    price: string;
    status: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Events', href: '/admin/events' },
    { title: 'Edit', href: '#' },
];

export default function EditEvent({ event, statuses }: { event: EventDetails; statuses: string[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <div className="p-4">
                <EventForm
                    title="Edit event"
                    description="Update schedule, pricing, and seat capacity while preserving confirmed bookings."
                    submitLabel="Save changes"
                    submitUrl={route('admin.events.update', event.id)}
                    method="put"
                    statuses={statuses}
                    event={{
                        title: event.title,
                        description: event.description ?? '',
                        venue: event.venue,
                        starts_at: event.starts_at,
                        ends_at: event.ends_at,
                        total_seats: event.total_seats,
                        price: event.price,
                        status: event.status,
                    }}
                />
            </div>
        </AppLayout>
    );
}
