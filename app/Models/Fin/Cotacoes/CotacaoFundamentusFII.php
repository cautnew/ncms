<?php

namespace App\Models\Fin\Cotacoes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotacaoFundamentusFII extends Model
{
    /** @use HasFactory<\Database\Factories\Fin\Cotacoes\CotacaoFundamentusFIIFactory> */
    use HasFactory;

    protected $table = 'cotacao_fundamentus_fiis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'ticker',
        'segment',
        'value',
        'ffo_yield',
        'dividend_yield',
        'p_vp',
        'valor_mercado',
        'liquidez',
        'qtd_imoveis',
        'preco_m2',
        'aluguel_m2',
        'cap_rate',
        'vacancia_media',
        'endereco',
        'name'
    ];
}
