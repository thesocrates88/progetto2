<x-app-layout>
    <div style="display: flex; justify-content: center; align-items: center; height: 90vh;">
        <div style="text-align: center; background-color: #f8f9fa; padding: 2rem; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px;">
            <h1 style="margin-bottom: 1rem; color: #ffc107;">Conferma Pagamento</h1>
            <p>ID Transazione: <strong>{{ $tx->id_transazione }}</strong></p>
            <p>Importo: <strong>€ {{ number_format($tx->importo, 2, ',', '.') }}</strong></p>

            <form method="POST" action="/checkout/pay" style="margin-top: 2rem;">
                @csrf
                <input type="hidden" name="tx" value="{{ $tx->id_transazione }}">

                <div style="text-align: left; margin-bottom: 1rem;">
                    <p><strong>Metodo di pagamento:</strong></p>

                    <label style="display: block; margin-bottom: 0.5rem;">
                        <input type="radio" name="metodo" value="conto" checked>
                        Usa credito disponibile sul conto
                    </label>

                    @if($carte->count() > 0)
                        <label style="display: block; margin-top: 1rem;">
                            <input type="radio" name="metodo" value="carta">
                            Usa una carta salvata:
                        </label>

                        <select name="carta_id" style="margin-top: 0.5rem; padding: 0.5rem; width: 100%;">
                            @foreach ($carte as $carta)
                                <option value="{{ $carta->id }}">
                                    {{ $carta->numero_mascherato }} (scad. {{ $carta->scadenza }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        <p style="margin-top: 1rem;">Nessuna carta salvata. <a href="{{ route('carte.create') }}">Aggiungi una carta</a></p>
                    @endif
                </div>

                <button type="submit" style="background-color: #28a745; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer;">
                    Conferma Pagamento
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
