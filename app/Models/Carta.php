<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_hash',
        'scadenza',
        'nome_intestatario',
        'cvv_hash',
        'ultima_quattro',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
