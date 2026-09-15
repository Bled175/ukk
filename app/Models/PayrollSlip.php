<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_period_id',
        'employee_name',
        'employee_nik',
        'position',
        'base_salary',
        'overtime',
        'employee_loan',
        'total_income',
        'net_salary',
    ];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'overtime' => 'decimal:2',
            'employee_loan' => 'decimal:2',
            'total_income' => 'decimal:2',
            'net_salary' => 'decimal:2',
        ];
    }

    public function salaryPeriod(): BelongsTo
    {
        return $this->belongsTo(SalaryPeriod::class);
    }
}
