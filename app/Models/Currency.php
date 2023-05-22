<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'created_at'=> 'datetime',
        'updated_at'=> 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_currency', 'currency_id', 'users_id')
            ->withTimestamps();
    }
}
