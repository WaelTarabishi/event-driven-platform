<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedTinyInteger('seat_count')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();

            $table->unique(['user_id', 'event_id']);
            $table->index(['event_id', 'status']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('gateway');
            $table->string('transaction_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->timestamp('paid_at');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_bookings_seat_count CHECK (seat_count > 0)');
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_bookings_unit_price CHECK (unit_price >= 0)');
        DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payments_amount CHECK (amount >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
    }
};
