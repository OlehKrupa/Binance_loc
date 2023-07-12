<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CurrencyHistory extends Model
{
    use HasFactory;

    protected $table = 'currency_history';

    protected $fillable = [
        'currency_id',
        'sell',
        'buy',
    ];

    protected $casts = [
        'created_at'=> 'datetime',
        'updated_at'=> 'datetime',
    ];
}