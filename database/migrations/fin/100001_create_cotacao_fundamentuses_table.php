<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public string $table = 'cotacao_fundamentus';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('papel');
            $table->string('nome');
            $table->string('segmento');
            $table->decimal('cotacao', 10, 2);
            $table->decimal('ffo_yield', 10, 2);
            $table->decimal('dividend_yield', 10, 2);
            $table->decimal('p_vp', 10, 2);
            $table->decimal('valor_mercado', 20, 2);
            $table->decimal('liquidez', 20, 2);
            $table->integer('qtd_imoveis');
            $table->decimal('preco_m2', 10, 2);
            $table->decimal('aluguel_m2', 10, 2);
            $table->decimal('cap_rate', 10, 2);
            $table->decimal('vacancia_media', 10, 2);
            $table->text('endereco');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
