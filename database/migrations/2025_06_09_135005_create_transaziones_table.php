<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaziones', function (Blueprint $table) {
            $table->id();
            $table->string('id_transazione')->unique();
            $table->decimal('importo', 10, 2);
            $table->string('descrizione');
            $table->enum('metodo', ['conto', 'carta']);
            $table->foreignId('utente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conto_sorgente_id')->nullable()->constrained('contos')->onDelete('set null');
            $table->foreignId('conto_destinazione_id')->constrained('contos')->onDelete('cascade');
            $table->enum('esito', ['IN_ATTESA', 'OK', 'KO'])->default('IN_ATTESA');
            $table->string('url_callback');
            $table->timestamp('data_conferma')->nullable();
            $table->text('note_fallimento')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaziones');
    }
};
