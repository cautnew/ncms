<?php

namespace App\Models\Fin\Cotacoes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotacaoFundamentus extends Model
{
    /** @use HasFactory<\Database\Factories\Fin\Cotacoes\CotacaoFundamentusFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cotacao_fundamentus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'papel',
        'segmento',
        'cotacao',
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
        'nome'
    ];
}
