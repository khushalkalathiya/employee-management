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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('employee_code')->unique();
            $table->date('date_of_birth')->nullable();
            $table->date('joining_date');
            $table->date('probation_end_date')->nullable();
            $table->integer('salary')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
