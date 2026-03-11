<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('link_id')
                ->nullable()
                ->constrained('links')
                ->nullOnDelete();

            $table->foreignId('announcement_id')
                ->nullable()
                ->constrained('announcements')
                ->nullOnDelete();

            $table->dateTime('schedule');

            $table->enum('type', ['link', 'announcement']);

            $table->timestamps();

            // Ensure only one FK is set at a time
            $table->rawIndex(
                '((link_id IS NOT NULL AND announcement_id IS NULL) OR (link_id IS NULL AND announcement_id IS NOT NULL))',
                'schedules_only_one_fk'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
