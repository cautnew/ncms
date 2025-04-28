<?php

namespace App\Models\Fin\Cotacoes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotacaoFundamentusAcao extends Model
{
    /** @use HasFactory<\Database\Factories\Fin\Cotacoes\CotacaoFundamentusFIIFactory> */
    use HasFactory;

    protected $table = 'cotacao_fundamentus_acoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'ticker',
        'value',
        'p_l',
        'p_vp',
        'psr',
        'dividend_yield',
        'p_ativo',
        'p_cap_giro',
        'p_ebit',
        'p_ativo_circ_liq',
        'ev_ebit',
        'ev_ebitda',
        'marg_ebit',
        'marg_liq',
        'liq_corrente',
        'roic',
        'roe',
        'liq_2_meses',
        'patrim_liq',
        'div_brut_patrim',
        'cresc_rec_5a',
        'name'
    ];
}
