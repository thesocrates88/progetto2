<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-center">Benvenuto su PaySteam</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto text-lg leading-relaxed text-center">
        <p class="mb-6">
            <strong>PaySteam</strong> è la tua piattaforma sicura per i pagamenti online di beni e servizi.
            Con PaySteam puoi gestire il tuo conto digitale personale, memorizzare le tue carte in modo protetto
            e autorizzare transazioni in modo semplice e trasparente.
        </p>

        <p class="mb-6">
            Il nostro sistema è progettato per integrarsi con siti terzi (come venditori di biglietti ferroviari o altri esercenti)
            e permette agli utenti registrati di accettare o rifiutare una richiesta di pagamento prima che venga eseguita.
        </p>

        <p class="mb-6">
            Per iniziare a utilizzare PaySteam, è necessario <strong>registrarsi</strong>. Una volta completata la registrazione,
            verrà creato automaticamente il tuo conto con saldo iniziale pari a 0 €, pronto per essere ricaricato o utilizzato.
        </p>

        <div class="mt-10">
            <a href="{{ route('register') }}"
               class="bg-green-600 hover:bg-green-700 text-white text-lg font-semibold px-6 py-3 rounded shadow inline-block">
                Registrati ora
            </a>
        </div>
    </div>
</x-app-layout>
