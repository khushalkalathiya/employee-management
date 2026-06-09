<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->string('leave_mode')->default('full_day'); // ['full_day', 'multiple_days', 'half_day', 'hourly']
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->float('total_days')->nullable();
            $table->float('total_hours')->nullable();
            $table->text('reason');
            $table->integer('status')->default(0); // 0: pending, 1: approved, 2: rejected, 3: cancelled
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
