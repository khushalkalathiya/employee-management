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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('salary_month');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_hourly')->default(false);
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->decimal('hourly_rate', 12, 4)->default(0);
            $table->integer('working_days');
            $table->decimal('present_days', 5, 2);
            $table->decimal('leave_days', 5, 2);
            $table->decimal('per_day_salary', 12, 4);
            $table->decimal('earned_salary', 12, 4);
            $table->decimal('pf_amount', 12, 4)->default(0);
            $table->decimal('other_deductions', 12, 4)->default(0);
            $table->decimal('hold_amount', 12, 4)->default(0);
            $table->decimal('final_salary', 12, 4);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
