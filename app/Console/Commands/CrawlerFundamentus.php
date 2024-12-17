<?php

namespace App\Console\Commands;

use App\Models\Fin\Cotacoes\CotacaoFundamentus;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerFundamentus extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:crawler-fundamentus';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Crawler para obter dados da página fundamentus.com.br';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $client = new Client();
    $response = $client->request('POST', 'https://www.fundamentus.com.br/fii_resultado.php');
    $html = $response->getBody()->getContents();

    $crawler = new Crawler($html);
    $rows = $crawler->filter('#tabelaResultado tbody tr');
    $names = $crawler->filter('#tabelaResultado tbody tr td span')->extract(['title']);

    $cotacaoFundamentus = new CotacaoFundamentus();
    $cols = $cotacaoFundamentus->getFillable();

    /**
     * @var \DOMElement $row
     */
    foreach ($rows as $ind => $row) {
      $cells = $row->getElementsByTagName('td');
      $cotacaoRow = [];
      foreach ($cells as $cell) {
        $val = str_replace(',', '.', str_replace('.', '', str_replace('%', '', $cell->textContent)));
        $cotacaoRow[] = $val;
      }

      $cotacaoRow[] = $names[$ind];
      $cotacaoRow = array_combine($cols, $cotacaoRow);

      CotacaoFundamentus::create($cotacaoRow);
    }
  }
}
