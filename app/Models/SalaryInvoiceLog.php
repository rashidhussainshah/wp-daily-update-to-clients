<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryInvoiceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'invoice_number',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'currency',
        'invoice_path',
        'pdf_path',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    /**
     * Get the user that owns the salary invoice log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
