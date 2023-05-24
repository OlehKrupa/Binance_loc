<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCurrency extends Model
{
    use HasFactory;

    protected $table = 'user_currency';

    protected $fillable = [
        'user_id',
        'currency_id',
    ];

    protected $casts = [
        'created_at'=> 'datetime',
        'updated_at'=> 'datetime',
    ];

    public function users(){
        return $this->hasMany(User::class, 'user_id','id')->withTimestamps();
    }

    public function currencies(){
        return $this->hasMany(Currency::class,'currency_id','id')->withTimestamps();
    }
}
