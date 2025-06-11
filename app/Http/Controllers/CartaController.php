<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CartaController extends Controller
{
    public function index()
    {
        $carte = Auth::user()->carte;
        return view('carte.index', compact('carte'));
    }

    public function create()
    {
        return view('carte.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|digits:16',
            'scadenza' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'nome_intestatario' => 'required|string',
            'cvv' => 'required|digits:3',
        ]);

        Carta::create([
            'user_id' => Auth::id(),
            'numero_hash' => Crypt::encryptString($request->numero),
            'cvv_hash' => Crypt::encryptString($request->cvv),
            'scadenza' => $request->scadenza,
            'nome_intestatario' => $request->nome_intestatario,
            'ultima_quattro' => substr($request->numero, -4),
        ]);

        return redirect()->route('carte.index')->with('success', 'Carta aggiunta con successo.');
    }

    public function destroy($id)
    {
        $carta = \App\Models\Carta::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $carta->delete();

        return redirect()->route('carte.index')->with('success', 'Carta eliminata con successo.');
    }

}
