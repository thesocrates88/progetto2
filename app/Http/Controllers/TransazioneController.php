<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\Transazione;
use App\Models\Conto;
use Illuminate\Support\Str;

class TransazioneController extends Controller
{
    public function checkout(Request $request)
    {
        // Se è presente un id_transazione, carica quella transazione (caso M2M/API)
        if ($request->has('id_transazione')) {
            $transazione = Transazione::where('id_transazione', $request->id_transazione)
                ->where('esito', 'IN_ATTESA')
                ->firstOrFail();

            return view('transazioni.checkout', compact('transazione'));
        }

        // Altrimenti gestisci la creazione da form locale (interno)
        $request->validate([
            'merchant_id' => 'required|exists:users,id',
            'importo' => 'required|numeric|min:0.01',
            'descrizione' => 'required|string',
            'url_callback' => 'required|url'
        ]);

        $idTransazione = Str::uuid();
        $contoDestinazione = Conto::where('user_id', $request->merchant_id)->firstOrFail();

        $utenteId = auth()->id() ?? null;
        $contoSorgenteId = optional(optional(auth()->user())->conto)->id;

        dd($contoSorgenteId);
        dd(auth()->user()?->conto);


        $transazione = Transazione::create([
            'id_transazione' => $idTransazione,
            'importo' => $request->importo,
            'descrizione' => $request->descrizione,
            'metodo' => 'conto',
            'utente_id' => $utenteId,
            'conto_sorgente_id' => $contoSorgenteId,
            'conto_destinazione_id' => $contoDestinazione->id,
            'esito' => 'IN_ATTESA',
            'url_callback' => $request->url_callback,
        ]);

        return view('transazioni.checkout', compact('transazione'));
    }


    public function index()
    {
        $user = Auth::user();

        // Trova i conti dell'utente
        $contiUtente = $user->conto()->pluck('id');

        // Transazioni in cui l'utente è mittente o destinatario
        $transazioni = Transazione::where('utente_id', $user->id)
            ->orWhereIn('conto_destinazione_id', $contiUtente)
            ->orderByDesc('created_at')
            ->get();

        return view('transazioni.index', compact('transazioni'));
    }

    public function autorizza($id)
    {
        $transazione = Transazione::findOrFail($id);

        if (!$transazione->conto_sorgente_id) {
            $contoUtente = auth()->user()?->conto;
            if ($contoUtente) {
                $transazione->update(['conto_sorgente_id' => $contoUtente->id]);
            } else {
                return redirect()->route('dashboard')->with('error', 'Conto non disponibile per l’utente.');
            }
        }

        // Verifica che la transazione sia ancora in attesa
        if ($transazione->esito !== 'IN_ATTESA') {
            return redirect()->route('dashboard')->with('error', 'Transazione già conclusa.');
        }

        // salva l'esito positivo
        $transazione->update([
            'esito' => 'OK',
            'data_conferma' => now(),
        ]);


        // Esegue chiamata all'URL di callback con esito
        Http::get($transazione->url_callback, [
            'id_transazione' => $transazione->id_transazione,
            'esito' => 'OK',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pagamento autorizzato.');
    }
}
