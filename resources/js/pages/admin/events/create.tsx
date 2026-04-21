import { EventForm } from '@/components/admin/event-form';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Events', href: '/admin/events' },
    { title: 'Create', href: '/admin/events/create' },
];

export default function CreateEvent({ statuses }: { statuses: string[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <div className="p-4">
                <EventForm
                    title="Create event"
                    description="Create a bookable event with pricing, capacity, and publication state."
                    submitLabel="Create event"
                    submitUrl={route('admin.events.store')}
                    method="post"
                    statuses={statuses}
                />
            </div>
        </AppLayout>
    );
}
