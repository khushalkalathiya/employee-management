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
        Schema::table('salaries', function (Blueprint $table) {
            // Date range support (start_date / end_date replaces single salary_month for hourly)
            $table->date('start_date')->nullable()->after('salary_month');
            $table->date('end_date')->nullable()->after('start_date');

            // Salary type flag
            $table->boolean('is_hourly')->default(false)->after('end_date');

            // Hourly-specific columns
            $table->decimal('total_hours', 8, 2)->default(0)->after('is_hourly');
            $table->decimal('hourly_rate', 12, 4)->default(0)->after('total_hours');

            // Status: pending, paid, cancelled
            $table->string('status')->default('pending')->after('notes');

            // Who processed / approved this payroll
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            $table->timestamp('paid_at')->nullable()->after('processed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn([
                'start_date',
                'end_date',
                'is_hourly',
                'total_hours',
                'hourly_rate',
                'status',
                'processed_by',
                'paid_at',
            ]);
        });
    }
};
