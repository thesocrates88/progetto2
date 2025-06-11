<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transazione extends Model
{
    protected $fillable = [
        'id_transazione', 'importo', 'descrizione', 'metodo',
        'utente_id', 'conto_sorgente_id', 'conto_destinazione_id',
        'esito', 'url_callback', 'data_conferma', 'note_fallimento'
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'utente_id');
    }

    public function contoSorgente()
    {
        return $this->belongsTo(Conto::class, 'conto_sorgente_id');
    }

    public function contoDestinazione()
    {
        return $this->belongsTo(Conto::class, 'conto_destinazione_id');
    }
}
