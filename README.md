# Event Booking Platform

Laravel + Inertia + React event booking system with:
- Admin event management
- User booking/join flow
- Concurrency-safe seat booking
- Audit logs and request tracing

## Tech Stack

- Backend: Laravel 12, PHP 8.2+
- Frontend: React + TypeScript + Inertia
- Build: Vite, Tailwind
- Testing: Pest
- Database: MySQL (project/CI setup)

## Core Features

### Admin
- Create, edit, cancel, delete events
- Dashboard metrics and recent bookings
- Audit logs page (`/admin/audit-logs`)
- Event details page with event-specific audit timeline (`/admin/events/{id}`)

### User
- Browse available events on dashboard
- Join event (create booking)
- Cancel own join/booking
- See recent booking history

### Reliability / Observability
- Row-level lock concurrency handling on booking (`lockForUpdate`)
- Request tracing with `X-Request-Id`
- Audit logs for admin actions and user booking actions

## Booking Concurrency Behavior

The booking flow uses DB transaction + row lock, so for this scenario:

> 50 users try to book 10 seats at the same time

Only 10 requests succeed, and remaining requests fail (sold out / already booked).

## Project Structure (Important Parts)

- `routes/web.php` - web routes, admin routes, booking endpoints
- `routes/api.php` - public events API
- `app/Http/Controllers/Admin` - admin pages/actions
- `app/Http/Controllers/Api` - API handlers
- `app/Services` - business logic (`BookingService`, `EventService`, `AuditLogService`)
- `app/Repositories` - DB access abstraction
- `app/Http/Middleware/TraceRequestContext.php` - request tracing context
- `resources/js/pages` - Inertia React pages
- `database/migrations` - schema definitions

## Setup (Local)

1. Install dependencies:

```bash
composer install
npm install
```

2. Create env and app key:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database in `.env` (MySQL), then run:

```bash
php artisan migrate
```

4. Start app:

```bash
composer run dev
```

This runs:
- Laravel server
- queue listener
- Vite dev server

## Testing

Run tests:

```bash
./vendor/bin/pest
```

CI workflow is in:
- `.github/workflows/tests.yml`

and uses MySQL service.

## Audit Logging

Audit records are stored in `audit_logs` table and include:
- actor (`user_id`)
- action (`admin.event.updated`, `user.booking.joined`, etc.)
- entity (`entity_type`, `entity_id`)
- details (`metadata` JSON)
- trace id (`request_id`)
- timestamp (`created_at`)

## Tracing

Each request gets `X-Request-Id`:
- returned in response header
- injected into log context
- searchable in logs for end-to-end troubleshooting

## Notes

- For full implementation details and all edited files, see:
  - `IMPLEMENTATION_REPORT.md`
