<?php

namespace Database\Factories\Fin\Cotacoes;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fin\Cotacoes\CotacaoFundamentusFII>
 */
class CotacaoFundamentusFIIFactory extends Factory
{
    private function getTicker(): string
    {
        return $this->faker->randomElement([
            'ABCP11',
            'ABEV3',
            'ALZR3',
            'ALZR4',
            'ALZR5',
            'ALZR11',
            'BTLG11',
            'BBAS3',
            'BBSE3',
            'BBDC3',
            'BBDC4',
            'BTOW3',
            'CPTS11',
            'CRIV11',
            'CTSA3',
            'CTSA4',
            'HCTR11',
            'HFOF11',
            'HGLG11',
            'IRDM11',
            'KNCR11',
            'MXRF11',
            'NSLU11',
            'RBRF11',
            'RBRL11',
            'RBRP3',
            'RBRP4',
            'RBRP11',
            'RNGO11',
            'SPTW11',
            'VILG11',
            'VINO11',
            'VISC11',
            'VSLH11',
            'VTLT11',
            'VTRT11',
            'VULC11',
            'VVAL11',
            'VVAR11',
        ]);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
