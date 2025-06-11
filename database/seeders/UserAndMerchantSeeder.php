<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conto;
use App\Models\Carta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserAndMerchantSeeder extends Seeder
{
    public function run(): void
    {
        // Crea 20 utenti con conti e carte
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => "Utente $i",
                'email' => "utente$i@example.com",
                'password' => Hash::make('password'),
                'ruolo' => 'utente',
            ]);

            $conto = Conto::create([
                'user_id' => $user->id,
                'saldo' => rand(50, 500),
            ]);

            $numCarte = rand(2, 3);
            for ($j = 1; $j <= $numCarte; $j++) {
                $numero = str_pad(rand(4000000000000000, 4999999999999999), 16, '0', STR_PAD_LEFT);
                $cvv = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
                $scadenza = sprintf('%02d/%02d', rand(1, 12), rand(24, 29));

                Carta::create([
                    'user_id' => $user->id,
                    'numero_hash' => Crypt::encryptString($numero),
                    'cvv_hash' => Crypt::encryptString($cvv),
                    'scadenza' => $scadenza,
                    'nome_intestatario' => $user->name,
                    'ultima_quattro' => substr($numero, -4),
                ]);
            }
        }

        // Crea 5 esercenti con conto
        for ($k = 1; $k <= 5; $k++) {
            $merchant = User::create([
                'name' => "Esercente $k",
                'email' => "esercente$k@example.com",
                'password' => Hash::make('password'),
                'ruolo' => 'esercente',
            ]);

            Conto::create([
                'user_id' => $merchant->id,
                'saldo' => 0,
            ]);
        }
    }
}
