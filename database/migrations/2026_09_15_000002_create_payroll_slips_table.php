<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_period_id')->constrained()->restrictOnDelete();
            $table->string('employee_name');
            $table->string('employee_nik');
            $table->string('position');
            $table->decimal('base_salary', 15, 2);
            $table->decimal('overtime', 15, 2)->default(0);
            $table->decimal('employee_loan', 15, 2)->default(0);
            $table->decimal('total_income', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_slips');
    }
};
