<x-app-layout>
    <div style="display: flex; justify-content: center; align-items: center; height: 80vh;">
        <div style="text-align: center; background-color: #f8f9fa; padding: 2rem; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 500px;">
            <h1 style="margin-bottom: 1rem; color: #28a745;">Pagamento completato</h1>
            <p style="margin-bottom: 2rem;">La transazione è andata a buon fine.</p>

            <a href="{{ $redirectUrl }}" target="_blank" rel="noopener" class="btn btn-primary">
                Torna al sito del venditore
            </a>

        </div>
    </div>
</x-app-layout>
