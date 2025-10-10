<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = 'cotacao_fundamentus_fiis';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('ticker')->comment('FIIs ticker');
            $table->string('name')->comment('FIIs name');
            $table->string('segment');
            $table->decimal('value', 10, 2);
            $table->decimal('ffo_yield', 10, 2)->comment('FFO yield, percentual');
            $table->decimal('dividend_yield', 10, 2)->comment('Dividend yield, percentual');
            $table->decimal('p_vp', 10, 2);
            $table->double('valor_mercado');
            $table->decimal('liquidez', 20, 2);
            $table->integer('qtd_imoveis');
            $table->decimal('preco_m2', 10, 2);
            $table->decimal('aluguel_m2', 10, 2);
            $table->decimal('cap_rate', 10, 2);
            $table->decimal('vacancia_media', 10, 2);
            $table->text('endereco');
            $table->timestamps();
        });

        DB::unprepared("CREATE OR REPLACE ALGORITHM = UNDEFINED VIEW `cotacao_fundamentus_fiis_base10` AS select `cff`.`id` AS `id`,
    `cff`.`ticker` AS `ticker`,
    `cff`.`name` AS `name`,
    `cff`.`segment` AS `segment`,
    `cff`.`value` AS `value`,
    `cff`.`ffo_yield` AS `ffo_yield`,
    `cff`.`dividend_yield` AS `dividend_yield`,
    `cff`.`dividend_yield` / 100 * `cff`.`value` as `dividend_value_anual`,
    `cff`.`dividend_yield` / 1200 * `cff`.`value` as `dividend_value_mensal`,
    `cff`.`p_vp` AS `p_vp`,
    `cff`.`valor_mercado` AS `valor_mercado`,
    `cff`.`liquidez` AS `liquidez`,
    `cff`.`qtd_imoveis` AS `qtd_imoveis`,
    `cff`.`preco_m2` AS `preco_m2`,
    `cff`.`aluguel_m2` AS `aluguel_m2`,
    `cff`.`cap_rate` AS `cap_rate`,
    `cff`.`vacancia_media` AS `vacancia_media`,
    `cff`.`endereco` AS `endereco`,
    `cff`.`created_at` AS `created_at`,
    `cff`.`updated_at` AS `updated_at` from `$this->table` `cff`
where
    `cff`.`p_vp` between 0.8 and 0.99
    and `cff`.`liquidez` > 100000
    and `cff`.`dividend_yield` > 5.0
    and `cff`.`value` between 7 and 15
order by
    `cff`.`value` desc;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
