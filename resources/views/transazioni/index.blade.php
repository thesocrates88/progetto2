<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Le tue transazioni</h2>
    </x-slot>

    <div class="p-6">
        @if ($transazioni->isEmpty())
            <p>Non hai ancora effettuato transazioni.</p>
        @else
            <table class="w-full border-collapse mt-4">
                <thead>
                    <tr class="border-b">
                        <th>ID</th>
                        <th>Descrizione</th>
                        <th>Importo</th>
                        <th>Metodo</th>
                        <th>Esito</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transazioni as $t)
                        <tr class="border-b">
                            <td>{{ $t->id_transazione }}</td>
                            <td>{{ $t->descrizione }}</td>
                            <td>
                                @if($t->utente_id === Auth::id())
                                    -€{{ number_format($t->importo, 2, ',', '.') }}
                                @else
                                    +€{{ number_format($t->importo, 2, ',', '.') }}
                                @endif
                            </td>
                            <td>{{ ucfirst($t->metodo) }}</td>
                            <td>{{ $t->esito }}</td>
                            <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
