<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Conferma pagamento</h2>
    </x-slot>

    <div class="p-4">
        <p>Descrizione: {{ $transazione->descrizione }}</p>
        <p>Importo: € {{ number_format($transazione->importo, 2, ',', '.') }}</p>

        <form method="POST" action="{{ route('transazione.autorizza', $transazione->id) }}">
            @csrf
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Conferma Pagamento</button>
        </form>
    </div>
</x-app-layout>
