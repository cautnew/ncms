<?php

use App\Console\Commands\CrawlerFundamentusAcao;
use App\Console\Commands\CrawlerFundamentusFII;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('crawler-fundamentus-update-fii', function () {
    $this->comment('Iniciando o crawler para atualizar os dados de FIIs de fundamentus.com.br');
    (new CrawlerFundamentusFII())->handle();
})->purpose('Run the crawler for updating FII data from fundamentus.com.br');

Artisan::command('crawler-fundamentus-update-acao', function () {
    $this->comment('Iniciando o crawler para atualizar os dados de ações de fundamentus.com.br');
    (new CrawlerFundamentusAcao())->handle();
})->purpose('Run the crawler for updating stocks data from fundamentus.com.br');
