<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('venue');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('total_seats');
            $table->unsignedInteger('available_seats');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'starts_at']);
        });

        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_events_total_seats CHECK (total_seats > 0)');
        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_events_available_seats CHECK (available_seats >= 0 AND available_seats <= total_seats)');
        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_events_date_order CHECK (ends_at IS NULL OR starts_at IS NULL OR ends_at > starts_at)');
        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_events_price CHECK (price >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
