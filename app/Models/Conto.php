<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conto extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'saldo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
