<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Le tue carte</h2>
    </x-slot>

    <div class="p-6">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($carte->isEmpty())
            <p>Non hai ancora carte salvate.</p>
        @else
            <table class="w-full text-left border-collapse mt-4">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Ultime 4 cifre</th>
                        <th>Intestatario</th>
                        <th>Scadenza</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carte as $carta)
                        <tr class="border-b">
                            <td class="py-2">* {{ $carta->ultima_quattro }}</td>
                            <td>{{ $carta->nome_intestatario }}</td>
                            <td>{{ $carta->scadenza }}</td>
                            <td>
                                <form action="{{ route('carte.destroy', $carta->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questa carta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Elimina</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="mt-6">
            <a href="{{ route('carte.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Aggiungi nuova carta
            </a>
        </div>
    </div>
</x-app-layout>
