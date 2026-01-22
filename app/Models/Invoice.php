<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'candidate_name',
        'candidate_email',
        'candidate_address',
        'package',
    ];

    // Optional: explicitly define table if needed
    // protected $table = 'invoices';

    // Optional: ensure deleted_at is treated as date
    protected $dates = ['deleted_at'];
}
