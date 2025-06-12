<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transazione;
use App\Models\Conto;
use Illuminate\Support\Str;

class CheckoutApiController extends Controller
{
    public function store(Request $request)
    {
        // Validazione dei dati
        $validated = $request->validate([
            'merchant_id'     => 'required|exists:users,id',
            'id_transazione'  => 'required|string|unique:transaziones,id_transazione',
            'descrizione'     => 'required|string',
            'importo'         => 'required|numeric|min:0.01',
            'url_callback'    => 'required|url',
            'url_in'          => 'required|url',
        ]);

        // Trova il conto dell'esercente
        $contoDestinazione = Conto::where('user_id', $validated['merchant_id'])->firstOrFail();

        // Crea la transazione in stato "IN_ATTESA"
        $transazione = Transazione::create([
            'id_transazione'         => $validated['id_transazione'],
            'importo'                => $validated['importo'],
            'descrizione'            => $validated['descrizione'],
            'metodo'                 => 'conto', // o 'carta' in futuro
            'conto_destinazione_id'  => $contoDestinazione->id,
            'esito'                  => 'IN_ATTESA',
            'url_callback'           => $validated['url_callback'],
            'url_in'                 => $validated['url_in'],
        ]);

        return response()->json([
            'status' => 'created',
            'id_transazione' => $transazione->id_transazione,
            'redirect_url' => route('checkout.confirm', ['tx' => $transazione->id_transazione]),

        ], 201);
    }

    public function confirm(Request $request)
    {
        $tx = Transazione::where('id_transazione', $request->tx)->firstOrFail();

        $user = Auth::user();

        if (!$user || !$user->conto) {
            abort(403, 'Utente non loggato o conto non disponibile.');
        }

        $tx->utente_id = $user->id;
        $tx->conto_sorgente_id = $user->conto->id;
        $tx->save();

        $carte = $user->carte; // relazione 1:N

        return view('checkout.confirm', [
            'tx' => $tx,
            'carte' => $carte
        ]);
    }



    public function pay(Request $request)
    {
        $request->validate([
            'tx' => 'required|string|exists:transaziones,id_transazione',
            'metodo' => 'required|in:conto,carta',
            'carta_id' => 'nullable|exists:cartas,id'
        ]);

        $tx = Transazione::where('id_transazione', $request->tx)->firstOrFail();
        $user = Auth::user();

        $tx->utente_id = $user->id;
        $tx->conto_sorgente_id = $user->conto->id;
        $tx->metodo = $request->metodo;

        if ($request->metodo === 'carta') {
            $user->carte()->findOrFail($request->carta_id);
        }

        $tx->esito = 'OK';
        $tx->data_conferma = now();
        $tx->save();
        
        //scala soldi al pagatore e accredita al destinatario
        if ($tx->metodo === 'conto') {
            $contoSorgente = $tx->contoSorgente;
            $contoDestinazione = $tx->contoDestinazione;

            if ($contoSorgente) {
                $contoSorgente->decrement('saldo', $tx->importo);
            }

            if ($contoDestinazione) {
                $contoDestinazione->increment('saldo', $tx->importo);
            }

            Log::info('Saldi aggiornati', [
                'sorgente_id' => $tx->conto_sorgente_id,
                'destinazione_id' => $tx->conto_destinazione_id,
                'importo' => $tx->importo,
            ]);
        }
        // === DEBUG PRIMA DELLA CHIAMATA ===
        Log::info('Invio callback a Progetto 1', [
            'url_callback' => $tx->url_callback,
            'payload' => [
                'url_in' => $tx->url_in,
                'id_transazione' => $tx->id_transazione,
                'esito' => $tx->esito,
            ],
        ]);

        $response = Http::post($tx->url_callback, [
            'url_in' => $tx->url_in,
            'id_transazione' => $tx->id_transazione,
            'esito' => $tx->esito,
        ]);

        // === DEBUG RISPOSTA CALLBACK ===
        Log::info('Risposta callback da Progetto 1', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        $redirectUrl = $response->successful() && isset($response['redirect'])
            ? $response['redirect']
            : 'http://localhost:8000/my-tickets'; // fallback sicuro

        return view('checkout.success', compact('redirectUrl'));
    }




}
