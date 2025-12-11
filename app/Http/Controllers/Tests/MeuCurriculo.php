<?php

namespace App\Http\Controllers\Tests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use PHTML\Core\TAG;
use PHTML\Templates\HTML5;

class MeuCurriculo extends Controller
{
    private Carbon $datRef;
    private Carbon $datBirth;
    private int $ageYears;

    public function __construct()
    {
        $this->datRef = Date::create(2025, 11, 20);
        $this->datBirth = Date::create(1991, 8, 19);
        $this->ageYears = (int) $this->datBirth->diff(Date::now())->totalYears;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $html = new HTML5();
        $html->getHtml()->setLang('pt-BR');
        $html->setPageTitle('Currículo - Felipe de Sousa Martins');
        $html->appendToHead(TAG::meta(charset: 'utf-8'));
        $html->appendToHead(TAG::meta('viewport', content: 'width=device-width, initial-scale=1'));

        $html->appendToHead(TAG::meta('description', content: 'Currículo de Felipe de Sousa Martins'));
        $html->appendToHead(TAG::meta('creation-date', content: $this->datRef));

        $html->appendToHead(TAG::link('/logo.png', 'icon', sizes: 'any'));
        $html->appendToHead(TAG::link('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css', 'stylesheet', integrity: 'sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB', crossorigin: 'anonymous'));

        $footer = [
            TAG::hr('w-100 mb-2'),
            TAG::p('text-center', html: $this->datRef->format('M/Y')),
        ];

        $html->getBody()->append([
            TAG::div('row', append: [
                TAG::div('col-8', append: [
                    TAG::h1('display-4', 'Martins, Felipe'),
                    TAG::p('lead', html: "DevOps, brasileiro, solteiro, {$this->ageYears} anos"),
                    TAG::p('mb-0', html: 'Github: <a href="https://github.com/cautnew" alt="Link para meu Github">https://github.com/cautnew</a>'),
                    TAG::p('mb-0', html: 'LinkedIn: <a href="https://www.linkedin.com/in/felipedesmartins" alt="Link para meu LinkedIn">https://www.linkedin.com/in/felipedesmartins</a>'),
                ]),
                TAG::div('col-4', append: [
                    TAG::ul('list-group', append: [
                        TAG::li('list-group-item d-flex align-items-center py-2', html: '<svg xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M341.8 72.6C329.5 61.2 310.5 61.2 298.3 72.6L74.3 280.6C64.7 289.6 61.5 303.5 66.3 315.7C71.1 327.9 82.8 336 96 336L112 336L112 512C112 547.3 140.7 576 176 576L464 576C499.3 576 528 547.3 528 512L528 336L544 336C557.2 336 569 327.9 573.8 315.7C578.6 303.5 575.4 289.5 565.8 280.6L341.8 72.6zM304 384L336 384C362.5 384 384 405.5 384 432L384 528L256 528L256 432C256 405.5 277.5 384 304 384z"/></svg><span class="ms-2">Salvador, Bahia, Brasil</span>'),
                        TAG::li('list-group-item d-flex align-items-center py-2', html: '<svg xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M224.2 89C216.3 70.1 195.7 60.1 176.1 65.4L170.6 66.9C106 84.5 50.8 147.1 66.9 223.3C104 398.3 241.7 536 416.7 573.1C493 589.3 555.5 534 573.1 469.4L574.6 463.9C580 444.2 569.9 423.6 551.1 415.8L453.8 375.3C437.3 368.4 418.2 373.2 406.8 387.1L368.2 434.3C297.9 399.4 241.3 341 208.8 269.3L253 233.3C266.9 222 271.6 202.9 264.8 186.3L224.2 89z"/></svg><a href="tel:+5571991640905" class="ms-2" alt="Meu telefone é +55 71 99164-0905">+55 71 99164-0905</a>'),
                        TAG::li('list-group-item d-flex align-items-center py-2', html: '<svg xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M112 128C85.5 128 64 149.5 64 176C64 191.1 71.1 205.3 83.2 214.4L291.2 370.4C308.3 383.2 331.7 383.2 348.8 370.4L556.8 214.4C568.9 205.3 576 191.1 576 176C576 149.5 554.5 128 528 128L112 128zM64 260L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 260L377.6 408.8C343.5 434.4 296.5 434.4 262.4 408.8L64 260z"/></svg><a href="mailto:felipedesmartins@gmail.com" class="ms-2" alt="Meu e-mail é felipedesmartins@gmail.com">felipedesmartins@gmail.com</a>'),
                    ])
                ]),
            ]),
            TAG::div('row mt-3', append: [
                TAG::div('col-8', append: [
                    TAG::h4(html: 'Habilidades'),
                    TAG::p(html: 'Desenvolvimento de software (backend) utilizando JS, PHP e Python, DBA com experiência em Oracle e MySQL e cientista de dados no ramo da telefonia celular.'),
                    TAG::p(html: 'Criatividade que me deixa inquieto ao ver problemas que precisam serem resolvidos, mesmo que não seja da minha área, mas que podem gerar um grande ganho no futuro de toda a empresa.'),
                    TAG::hr('w-100'),
                    TAG::h4('mb-3', html: 'Resumo profissional'),
                    TAG::p(append: [
                        'Desenvolvo sistemas para WEB em ',
                        TAG::strong(html: 'PHP e JavaScript'),
                        ' com bom conhecimento de front-end usando ',
                        TAG::strong(html: 'HTML e CSS'),
                        '. Iniciei um projeto de ferramenta local de análise de indicadores em PHP realizando pesquisas diretamente em DWH (Data Warehouse) com BI (Business Intelligence).'
                    ]),
                    TAG::p(html: 'Desenvolvi toda a ferramenta de pedidos de aparelhos para lojas próprias com status de entrega, aprovação de gestores e acompanhamento de giro. Foi feita em PHP + MySQL + JavaScript.'),
                    TAG::p(html: 'Apresentação e análise de informações em big data usando Python. Utilizei esse conhecimento para alimentar uma inteligência artificial que projeta o quanto pode ser atingido de desempenho nas vendas com base no histórico curto. Isso agilizou a atualização do relatório significativamente e, consequência, a preparação da equipe de vendas.'),
                    TAG::p(html: 'Eu criei um script que pode fazer o download e a otimização de imagens para serem usadas em CMSs e não demorarem muito para serem carregadas. Essa ferramenta também serve para baixar o conteúdo das páginas para então traduzí-los automaticamente para diversos idiomas.'),
                    TAG::p(html: TAG::strong(html: 'Experiência de 5 anos com consultas SQL em DWH com BI. Para fazer a demonstração desses dados utilizei de forma avançada a suíte de programas do Office (Access, Excel, Word e PowerPoint) além de frameworks em JS como D3.org, ChartJS, Google Charts, todos realizando as consultas via PHP em Oracle, interpretando os dados e apresentando-os de forma intuitiva e veloz. Toda a parte de UI e UX foi projetada e desenvolvida por mim, documentada e com feedback de aprovação dos clientes.')),
                    TAG::p(html: 'Criei um sistema que consegue coletar as vendas em tempo real de todo o Brasil e as apresenta via Telegram para todos os gestores e diretores. Também as exibe em um painel no escritório para que todos possam acompanhar por DDD, produto, canal e regional. Esse painel foi idealizado, construído e implantado por mim para mostrar noticias da compania e avisos locais.'),
                    TAG::p(html: 'Desenvolvi um sistema para inventariar todo o estoque de aparelhos e chips de lojas. Essa é uma das obrigações de caixas e levava mais de 2 horas para concluir diariamente. Com esse sistema reduzi para apenas 30 minutos.'),
                    TAG::hr('w-100'),
                    TAG::h4('mb-3', html: 'Experiência Profissional'),
                    TAG::div('row', append: [
                        TAG::div('col-6', append: [
                            TAG::div('card mb-2', append: [
                                TAG::div('card-body', append: [
                                    TAG::h5('card-title', 'Nestlé – Desenvolvedor Back-End'),
                                    TAG::p('card-text', html: 'Desenvolvimento de soluções em PHP e Python para toda a parte de websites da companhia. Foco no DataLayer em Drupal para interagir com GTM.'),
                                    TAG::p('card-text', html: 'Usando GIT para versionamento e Jira para DevOps. Usamos o Kanban como a metodologia ágil de desenvolvimento.'),
                                    TAG::p('card-text', html: 'Criação de rotinas em Python para otimizar tarefas em diversas áreas.'),
                                ]),
                                TAG::div('card-footer', html: 'desde 11/2022 (3 anos)'),
                            ]),
                            TAG::div('card mb-2', append: [
                                TAG::div('card-body', append: [
                                    TAG::h5('card-title', 'Corproni Informática – Desenvolvimento de Web Sites'),
                                    TAG::p('card-text', html: 'Desenvolvimento de sites com gestor de conteúdo em PHP e MySQL.'),
                                    TAG::p('card-text', html: 'Criação do CSS a partir da exportação do design feito no Photoshop (webdesgin).'),
                                    TAG::p('card-text', html: 'Teste de telas dos sistemas para detectar falhas de utilização dos produtos (UX).'),
                                ]),
                                TAG::div('card-footer', html: 'de 01/2008 a 04/2011 (3 anos e 3 meses)'),
                            ])
                        ]),
                        TAG::div('col-6', append: [
                            TAG::div('card mb-2', append: [
                                TAG::div('card-body', append: [
                                    TAG::h5('card-title', 'Claro S/A – Analista'),
                                    TAG::p('card-text', html: 'Desenvolvimento e análise de bases de todos os indicadores da empresa utilizando BI com PHP, PL/Sql, SQL Server, Oracle, PowerBI e Tableau.'),
                                    TAG::p('card-text', html: 'Criação de um sistema onde convergem os dados de indicadores e os dispobiliza em um banco dados público e via página WEB personalizada.'),
                                    TAG::p('card-text', html: 'Desenvolvimento do sistema de inventário de aparelhos em lojas que otimizou o processo de 2 horas diárias para apenas 30 minutos.'),
                                    TAG::p('card-text', html: 'Desenvolvimento do sistema que integra os pedidos das lojas com a equipe de supply que otimizou os processos para garantir o abastecimento a tempo de qualquer evento especial.'),
                                    TAG::p('card-text', html: 'Comunicação via Telegram e painel digital no escritório de todos os indicadores de vendas em toda a regional em tempo real.'),
                                ]),
                                TAG::div('card-footer', html: 'desde 02/2011 a 11/2022 (11 anos e 10 meses)'),
                            ])
                        ]),
                    ])
                ]),
                TAG::div('col-4', append: [
                    TAG::h4(html: 'Tecnologias'),
                    ...$this->getTechnologies(),
                    TAG::hr('w-100'),
                    TAG::h4(html: 'Idiomas'),
                    ...$this->getLanguages(),
                    TAG::hr('w-100'),
                    TAG::h4(html: 'Formação'),
                    TAG::div('list-group', append: $this->getSchooling()),
                    TAG::hr('w-100'),
                    TAG::h4(html: 'Cursos'),
                    ...$this->getCourses(),
                ])
            ]),
            ...$footer,
        ]);

        $html->appendToBody(TAG::script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js', integrity: 'sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI', crossorigin: 'anonymous'));

        return $html;
    }

    private function getTechnologies(): array
    {
        return [
            TAG::h5(html: 'Backend'),
            TAG::p(html: 'PHP, JavaScript, Python, NodeJS, Shell Script, Oracle, PL/SQL, MySQL, MongoDB, Linux OS, Java, C, C++.'),
            TAG::h5(html: 'Frontend'),
            TAG::p(html: 'Tailwind, Bootstrap, VueJS, ReactJS, jQuery, D3.org, ChartJS, Google Charts.'),
            TAG::h5(html: 'Outras'),
            TAG::p(html: 'Laravel, Drupal, WordPress, Photoshop, Adobe Magento, MS Office 365 (Excel, Access), Figma, Power BI, Think-Cell.'),
            TAG::p('mb-0 fst-italic small text-muted', html: '* As tecnologias estão por ordem de tempo de experência onde a primeira de cada sessão é a que eu trabalhei por mais tempo e a última é a que eu trabalhei por menos tempo.'),
            TAG::p('fst-italic small text-muted', html: '* O nível mais baixo que cheguei em qualquer uma delas é de intermediário.'),
        ];
    }

    private function getSchooling(): array
    {
        $formacoes = [
            [
                'course' => 'Desenvolvimento de Sistemas para a WEB',
                'institution' => 'UFSCAR',
                'institution-long' => 'Universidade Federal de São Carlos',
                'title' => 'Especialização',
                'period' => 'de Mar/2023 a Jan/2025',
            ],
            [
                'course' => 'Ciência de Dados',
                'institution' => 'UTFPR',
                'institution-long' => 'Universidade Tecnológica Federal do Paraná',
                'title' => 'Especialização',
                'period' => 'de mar/2021 a jul/2022',
            ],
            [
                'course' => 'Ciência da Computação',
                'institution' => 'UNIFACS',
                'institution-long' => 'Universidade Salvador',
                'title' => 'Bracharelado',
                'period' => 'de ago/2015 a dez/2018',
            ],
            [
                'course' => 'Técnico em Informática',
                'institution' => 'Col. Est. Pres. E. Garrastazu M.',
                'institution-long' => 'Colégio Estadual Presidente Emílio Garrastazu Médici',
                'title' => 'Ensino médio com curso profissionalizante em técnico de informática com ênfase em desenvolvimento de sistemas.',
                'period' => 'de jan/2006 a dez/2009',
            ]
        ];

        $formacoesPList = [];
        foreach ($formacoes as $formacao) {
            $formacoesPList[] = TAG::div('list-group-item', append: [
                TAG::div('d-flex w-100 justify-content-between', append: [
                    TAG::h5('mb-1', html: $formacao['course']),
                    TAG::small(html: $formacao['institution'], title: $formacao['institution-long'])
                ]),
                TAG::p('mb-1', html: $formacao['title']),
                TAG::small(html: $formacao['period'])
            ]);
        }

        return $formacoesPList;
    }

    private function getLanguages(): array
    {
        $languages = [
            [
                'name' => 'Português',
                'level' => 'nativo'
            ],
            [
                'name' => 'Inglês',
                'level' => 'fluente'
            ],
            [
                'name' => 'Espanhol',
                'level' => 'Intermediário'
            ]
        ];

        $listPLanguages = [];
        foreach ($languages as $language) {
            $listPLanguages[] = TAG::p('mb-1', html: "{$language['name']} ({$language['level']})");
        }

        return $listPLanguages;
    }

    private function getCourses(): array
    {
        $courses = [
            [
                'name' => 'FullStack PHP Developer',
                'institution' => 'Upinside',
            ],
            [
                'name' => 'Ciência de dados',
                'institution' => 'Data Science Academy',
            ],
            [
                'name' => 'IA',
                'institution' => 'Data Science Academy',
            ],
            [
                'name' => 'GIT',
                'institution' => 'Udemy',
            ],
            [
                'name' => 'Estatística em Python e R',
                'institution' => 'Udemy',
            ],
            [
                'name' => 'Desenvolvimento de WebSites',
                'institution' => 'Udemy',
            ],
            [
                'name' => 'C#',
                'institution' => 'Fundação Bradesco',
            ],
            [
                'name' => 'Certificação ISO 20000',
                'institution' => 'Udemy',
            ],
            [
                'name' => 'Certificação ISO 9001',
                'institution' => 'Udemy',
            ],
            [
                'name' => 'Arduino',
                'institution' => 'UFBA - Labtek',
            ]
        ];

        $listPCourses = [];
        foreach ($courses as $course) {
            $listPCourses[] = TAG::p('mb-1', html: "{$course['name']} ({$course['institution']})");
        }

        return $listPCourses;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
