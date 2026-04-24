<?php

namespace Database\Seeders;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoadTestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin-load@test.com'],
            [
                'name' => 'Load Admin',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        foreach (range(1, 50) as $index) {
            User::query()->updateOrCreate(
                ['email' => "load{$index}@test.com"],
                [
                    'name' => "Load User {$index}",
                    'password' => bcrypt('password'),
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ],
            );
        }

        Event::query()->updateOrCreate(
            ['slug' => 'concurrency-demo-event'],
            [
                'title' => 'Concurrency Demo Event',
                'description' => 'Load test event for 50 users racing for 10 seats.',
                'venue' => 'Main Test Hall',
                'starts_at' => now()->addDays(2),
                'ends_at' => now()->addDays(2)->addHours(2),
                'total_seats' => 10,
                'available_seats' => 10,
                'price' => 50.00,
                'status' => EventStatus::Published->value,
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
