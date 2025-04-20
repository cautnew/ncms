<?php

namespace App\Console\Commands;

use App\Models\Fin\Cotacoes\CotacaoFundamentusAcao;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

class CrawlerFundamentusAcao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crawler-fundamentus-acao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler para obter dados da página fundamentus.com.br';

    private string $urlToData = 'https://www.fundamentus.com.br/resultado.php';
    private Client $client;
    private Crawler $crawler;

    public function __construct(?Client $client = null)
    {
        parent::__construct();

        if ($client === null) {
            $client = new Client();
        }

        $this->client = $client;
    }

    /**
     * Execute the console command.
     * Run this every day.
     */
    public function handle(): void
    {
        try {
            print("Iniciando o processo de scraping...\n");

            $this->clearDataForToday();

            $rows = $this->getCrawler()->filter('#resultado tbody tr');
            $names = $this->getCrawler()->filter('#resultado tbody tr td span')->extract(['title']);

            $dataToInsert = $this->prepareData($rows, $names);

            $this->insertData($dataToInsert);

            print("Processo concluído com sucesso.\n");
        } catch (\Exception $e) {
            Log::error("Erro ao executar o crawler: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->error("Ocorreu um erro: " . $e->getMessage());
        }
    }

    private function getCrawler(): Crawler
    {
        if (!isset($this->crawler)) {
            $htmlContent = $this->fetchHtmlContent();
            $this->crawler = new Crawler($htmlContent);
        }

        return $this->crawler;
    }

    private function fetchHtmlContent(): string
    {
        $response = $this->client->post($this->urlToData);
        return $response->getBody()->getContents();
    }

    /**
     * Clear data for today.
     */
    private function clearDataForToday(): void
    {
        print("Limpando dados do dia atual...\n");

        $nowStart = now()->startOfDay();
        $nowEnd = now()->endOfDay();

        CotacaoFundamentusAcao::whereBetween('created_at', [$nowStart, $nowEnd])->delete();

        print("Dados do dia atual removidos.\n");
    }

    private function prepareData($rows, $names): array
    {
        print("Preparando os dados para inserção...\n");

        $cotacaoFundamentus = new CotacaoFundamentusAcao();
        $columns = $cotacaoFundamentus->getFillable();
        $dataToInsert = [];

        foreach ($rows as $index => $row) {
            $cells = $row->getElementsByTagName('td');
            $rowData = [];

            foreach ($cells as $cell) {
                $value = $this->sanitizeValue($cell->textContent);
                $rowData[] = $value;
            }

            $rowData[] = $names[$index];
            $rowData = array_combine($columns, $rowData);
            $rowData['created_at'] = now();

            $dataToInsert[] = $rowData;
        }

        return $dataToInsert;
    }

    private function sanitizeValue(string $value): string
    {
        $value = str_replace('%', '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return trim($value);
    }

    private function insertData(array $data): void
    {
        print("Inserindo dados no banco...\n");

        if (!empty($data)) {
            CotacaoFundamentusAcao::insert($data);
            print("Dados inseridos com sucesso.\n");
        } else {
            $this->warn("Nenhum dado para inserir.");
        }
    }
}
