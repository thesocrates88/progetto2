<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Il tuo conto
        </h2>
    </x-slot>

    <div class="p-6">
        <p>Saldo attuale: <strong>{{ number_format($conto->saldo, 2, ',', '.') }} €</strong></p>
    </div>
</x-app-layout>