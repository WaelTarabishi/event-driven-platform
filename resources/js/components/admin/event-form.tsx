import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

interface EventFormValues {
    title: string;
    description: string;
    venue: string;
    starts_at: string;
    ends_at: string;
    total_seats: number;
    price: string;
    status: string;
}

interface EventFormProps {
    title: string;
    description: string;
    submitLabel: string;
    submitUrl: string;
    method: 'post' | 'put';
    statuses: string[];
    event?: Partial<EventFormValues>;
}

export function EventForm({ title, description, submitLabel, submitUrl, method, statuses, event }: EventFormProps) {
    const { data, setData, post, put, processing, errors } = useForm<EventFormValues>({
        title: event?.title ?? '',
        description: event?.description ?? '',
        venue: event?.venue ?? '',
        starts_at: event?.starts_at ?? '',
        ends_at: event?.ends_at ?? '',
        total_seats: event?.total_seats ?? 10,
        price: event?.price ?? '0.00',
        status: event?.status ?? 'draft',
    });

    const submit = (formEvent: FormEvent<HTMLFormElement>) => {
        formEvent.preventDefault();

        if (method === 'post') {
            post(submitUrl);
            return;
        }

        put(submitUrl);
    };

    return (
        <>
            <Head title={title} />
            <Card>
                <CardHeader>
                    <CardTitle>{title}</CardTitle>
                    <CardDescription>{description}</CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={submit} className="space-y-6">
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="grid gap-2 md:col-span-2">
                                <Label htmlFor="title">Title</Label>
                                <Input id="title" value={data.title} onChange={(e) => setData('title', e.target.value)} placeholder="Laravel Live Conference" />
                                <InputError message={errors.title} />
                            </div>

                            <div className="grid gap-2 md:col-span-2">
                                <Label htmlFor="description">Description</Label>
                                <textarea
                                    id="description"
                                    className="min-h-32 rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Add event details, agenda, and attendee notes."
                                />
                                <InputError message={errors.description} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="venue">Venue</Label>
                                <Input id="venue" value={data.venue} onChange={(e) => setData('venue', e.target.value)} placeholder="Main hall" />
                                <InputError message={errors.venue} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="status">Status</Label>
                                <select
                                    id="status"
                                    className="h-10 rounded-md border border-input bg-background px-3 text-sm"
                                    value={data.status}
                                    onChange={(e) => setData('status', e.target.value)}
                                >
                                    {statuses.map((status) => (
                                        <option key={status} value={status}>
                                            {status}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.status} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="starts_at">Starts at</Label>
                                <Input id="starts_at" type="datetime-local" value={data.starts_at} onChange={(e) => setData('starts_at', e.target.value)} />
                                <InputError message={errors.starts_at} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="ends_at">Ends at</Label>
                                <Input id="ends_at" type="datetime-local" value={data.ends_at} onChange={(e) => setData('ends_at', e.target.value)} />
                                <InputError message={errors.ends_at} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="total_seats">Total seats</Label>
                                <Input
                                    id="total_seats"
                                    type="number"
                                    min="1"
                                    value={data.total_seats}
                                    onChange={(e) => setData('total_seats', Number(e.target.value))}
                                />
                                <InputError message={errors.total_seats} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="price">Price</Label>
                                <Input id="price" type="number" min="0" step="0.01" value={data.price} onChange={(e) => setData('price', e.target.value)} />
                                <InputError message={errors.price} />
                            </div>
                        </div>

                        <div className="flex flex-wrap items-center gap-3">
                            <Button disabled={processing}>{submitLabel}</Button>
                            <Button asChild variant="outline">
                                <Link href={route('admin.events.index')}>Back to events</Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </>
    );
}
