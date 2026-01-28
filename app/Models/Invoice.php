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
        'candidate_mobile',
        'candidate_address',
        'package',
        'install_amt',
    ];

    protected $dates = ['deleted_at'];
}
