<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Aggiungi una nuova carta</h2>
    </x-slot>

    <div class="p-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('carte.store') }}">
            @csrf

            <div class="mb-4">
                <label for="numero" class="block font-medium">Numero carta (16 cifre)</label>
                <input type="text" id="numero" name="numero" maxlength="16"
                       class="w-full border rounded px-3 py-2" value="{{ old('numero') }}" required>
                @error('numero') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="scadenza" class="block font-medium">Scadenza (MM/AA)</label>
                <input type="text" id="scadenza" name="scadenza" placeholder="es: 12/26"
                       class="w-full border rounded px-3 py-2" value="{{ old('scadenza') }}" required>
                @error('scadenza') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="nome_intestatario" class="block font-medium">Nome intestatario</label>
                <input type="text" id="nome_intestatario" name="nome_intestatario"
                       class="w-full border rounded px-3 py-2" value="{{ old('nome_intestatario') }}" required>
                @error('nome_intestatario') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-6">
                <label for="cvv" class="block font-medium">CVV (3 cifre)</label>
                <input type="text" id="cvv" name="cvv" maxlength="3"
                       class="w-full border rounded px-3 py-2" required>
                @error('cvv') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Salva carta
            </button>
        </form>
    </div>
</x-app-layout>
