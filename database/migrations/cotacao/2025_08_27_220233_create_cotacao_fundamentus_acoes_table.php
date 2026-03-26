<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $table = 'cotacao_fundamentus_acoes';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('ticker');
            $table->string('name');
            $table->decimal('value', 10, 2);
            $table->decimal('p_l', 10, 2);
            $table->decimal('p_vp', 10, 2);
            $table->decimal('psr', 10, 2);
            $table->decimal('dividend_yield', 10, 2);
            $table->decimal('p_ativo', 10, 2);
            $table->decimal('p_cap_giro', 10, 2);
            $table->decimal('p_ebit', 10, 2);
            $table->decimal('p_ativo_circ_liq', 10, 2);
            $table->decimal('ev_ebit', 10, 2);
            $table->decimal('ev_ebitda', 10, 2);
            $table->decimal('marg_ebit', 10, 2);
            $table->decimal('marg_liq', 10, 2);
            $table->decimal('liq_corrente', 10, 2);
            $table->decimal('roic', 10, 2);
            $table->decimal('roe', 10, 2);
            $table->double('liq_2_meses');
            $table->double('patrim_liq');
            $table->decimal('div_brut_patrim', 10, 2);
            $table->decimal('cresc_rec_5a', 10, 2);
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
