<?php

use PHTML\BODY;
use PHTML\DIV;
use PHTML\HEAD;
use PHTML\HTML;
use PHTML\TAG;
use PHTML\TITLE;

$html = new HTML(lang: 'pt-br');
$html->append([
  $head = new HEAD(append: [
    TAG::meta(charset: 'utf-8'),
    TAG::meta('viewport', content: 'width=device-width, initial-scale=1, shrink-to-fit=no'),
    TAG::link('https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css', 'stylesheet', integrity: 'sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT', crossorigin: 'anonymous'),
    $title = new TITLE('Currículo - Felipe de Sousa Martins')
  ]),
  $body = new BODY(append: [
    $bodyContainer = new DIV('container'),
    TAG::script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js', integrity: 'sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO', crossorigin: 'anonymous')
  ])
]);

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

$bodyContainer->append([
  TAG::div('row', append: [
    TAG::div('col-8', append: [
      TAG::h1('display-4', 'Martins, Felipe'),
      TAG::p('lead', html: 'DevOps, brasileiro, solteiro, 31 anos'),
      TAG::p('mb-0', html: 'Github: <a href="https://github.com/cautnew" alt="Link para meu Github">https://github.com/cautnew</a>'),
      TAG::p('mb-0', html: 'LinkedIn: <a href="https://www.linkedin.com/in/felipedesmartins" alt="Link para meu LinkedIn">https://www.linkedin.com/in/felipedesmartins</a>'),
    ]),
    TAG::div('col-4', append: [
      TAG::ul('list-group', append: [
        TAG::li('list-group-item py-1', html: '<i class="fas fa-home mr-2"></i>Salvador, BA, Brasil'),
        TAG::li('list-group-item py-1', html: '<i class="fas fa-phone mr-2"></i><a href="tel:+5571991640905" alt="Meu telefone é +55 71 99164-0905">+55 71 99164-0905</a>'),
        TAG::li('list-group-item py-1', html: '<i class="fas fa-envelope mr-2"></i><a href="mailto:felipedesmartins@gmail.com" alt="Meu e-mail é felipedesmartins@gmail.com">felipedesmartins@gmail.com</a>'),
      ])
    ]),
  ]),
  TAG::div('row mt-3', append: [
    TAG::div('col-8', append: [
      TAG::h4(html: 'Habilidades'),
      TAG::p(html: 'Desenvolvimento de software (backend) utilizando JS, PHP e Python, DBA com experiência em Oracle e MySQL e cientista de dados no ramo da telefonia celular.'),
      TAG::p(html: 'Criatividade que me deixa inquieto ao ver problemas que precisam serem resolvidos, mesmo que não seja da minha área, mas que podem gerar um grande ganho no futuro de toda a empresa.'),
      TAG::hr('w-100'),
      TAG::h4('mb-3', html: 'Tecnologias'),
      TAG::p(html: 'Já trabalhei com todas essas tecnologias. Elas estão por ordem de tempo de experência onde a primeira de cada sessão é a que eu trabalhei por mais tempo e a última é a que eu trabalhei por menos tempo. O menor nível que cheguei em qualquer uma delas é de intermediário.'),
      TAG::div('row', append: [
        TAG::div('col-6', append: [
          TAG::h5(html: 'Backend'),
          TAG::p(html: 'PHP, JavaScript, Python, NodeJS, Shell Script, Java, MySQL, ambientes Linux, MongoDB, Oracle, PL/SQL, C, C++.'),
          TAG::h5(html: 'Testes'),
          TAG::p(html: 'PHPUnit, XDebug, JUnit, Pytest, W3C Markup Validator.'),
        ]),
        TAG::div('col-6', append: [
          TAG::h5(html: 'Frontend'),
          TAG::p(html: 'Tailwind, Bootstrap, VueJS, ReactJS, jQuery, D3.org, ChartJS, Google Charts.'),
          TAG::h5(html: 'Outras'),
          TAG::p(html: 'Laravel, Drupal, WordPress, Photoshop, Adobe Magento, MS Office 365 (Excel, Access), Figma, Power BI, Think-Cell.'),
        ]),
      ]),
      TAG::hr('w-100'),
      TAG::h4('mb-3', html: 'Resumo profissional'),
      TAG::p(html: 'Desenvolvo sistemas para WEB em <strong>PHP e JavaScript</strong> com bom conhecimento de front-end usando <strong>HTML e CSS</strong>. Iniciei um projeto de ferramenta local de análise de indicadores em PHP realizando pesquisas diretamente em DWH (Data Warehouse) com BI (Business Intelligence).'),
      TAG::p(html: 'Desenvolvi toda a ferramenta de pedidos de aparelhos para lojas próprias com status de entrega, aprovação de gestores e acompanhamento de giro. Foi feita em PHP + MySQL + JavaScript.'),
      TAG::p(html: 'Apresentação e análise de informações em big data usando Python. Utilizei esse conhecimento para alimentar uma inteligência artificial que projeta o quanto pode ser atingido de desempenho nas vendas com base no histórico curto. Isso agilizou a atualização do relatório significativamente e, consequência, a preparação da equipe de vendas.'),
      TAG::p(html: '<strong>Experiência de 5 anos com consultas SQL em DWH com BI. Para fazer a demonstração desses dados utilizei de forma avançada a suíte de programas do Office (Access, Excel, Word e PowerPoint) além de frameworks em JS como D3.org, ChartJS, Google Charts, todos realizando as consultas via PHP em Oracle, interpretando os dados e apresentando-os de forma intuitiva e veloz. Toda a parte de UI e UX foi projetada e desenvolvida por mim, documentada e com feedback de aprovação dos clientes.</strong>'),
      TAG::p(html: 'Criei um sistema que consegue coletar as vendas em tempo real de todo o Brasil e as apresenta via Telegram para todos os gestores e diretores. Também as exibe em um painel no escritório para que todos possam acompanhar por DDD, produto, canal e regional. Esse painel foi idealizado, construído e implantado por mim para mostrar noticias da compania e avisos locais.'),
      TAG::p(html: 'Fui vendedor de loja de telefonia celular por 2 anos. Nesse período fui atender no caixa e acabei desenvolvendo um sistema para inventariar todo o estoque de aparelhos e chips da loja. Essa é uma das obrigações do caixa e levava mais de 2 horas para concluir diariamente. Com esse sistema reduzi para apenas 30 minutos e hoje está sendo usado por toda a empresa.'),
      TAG::hr('w-100'),
      TAG::h4('mb-3', html: 'Experiência Profissional'),
      TAG::div('card mb-2', append: [
        TAG::div('card-body', append: [
          TAG::h5('card-title', 'Claro S/A – Analista PL'),
          TAG::p('card-text', html: 'Desenvolvimento e análise de bases de todos os indicadores da empresa utilizando BI com PHP, PL/Sql, SQL Server, Oracle, PowerBI e Tableau.'),
          TAG::p('card-text', html: 'Criação de um sistema onde convergem os dados de indicadores e os dispobiliza em um banco dados público e via página WEB personalizada.'),
          TAG::p('card-text', html: 'Comunicação via Telegram e painel digital no escritório de todos os indicadores de vendas em toda a regional em tempo real.'),
        ]),
        TAG::div('card-footer', html: 'desde 08/2016 (atual - 5 anos)'),
      ]),
      TAG::div('card mb-2', append: [
        TAG::div('card-body', append: [
          TAG::h5('card-title', 'Claro S/A – Lojas Próprias'),
          TAG::p('card-text', html: 'Vendedor de Lojas Próprias - vendas de serviços de telecomunicações e aparelhos celulares.'),
          TAG::p('card-text', html: 'Desenvolvimento do sistema de inventário de aparelhos em lojas que otimizou o processo de 2 horas diárias para apenas 30 minutos.'),
        ]),
        TAG::div('card-footer', html: 'de 02/2011 a 06/2013 (2 anos e 4 meses)'),
      ]),
      TAG::div('card mb-2', append: [
        TAG::div('card-body', append: [
          TAG::h5('card-title', 'Claro S/A – Assistente administrativo'),
          TAG::p('card-text', html: 'Ponto focal para andamento de notas fiscais e monitoramento do estoque de aparelhos, consultas em banco Oracle sobre andamento de vendas.'),
          TAG::p('card-text', html: 'Desenvolvimento do sistema que integra os pedidos das lojas com a equipe de supply que otimizou os processos para garantir o abastecimento a tempo de qualquer evento especial.'),
        ]),
        TAG::div('card-footer', html: 'de 06/2013 a 08/2016 (3 anos e 2 meses)'),
      ]),
      TAG::div('card mb-2', append: [
        TAG::div('card-body', append: [
          TAG::h5('card-title', 'Corproni Informática – Desenvolvimento de Web Sites'),
          TAG::p('card-text', html: 'Desenvolvimento de sites com gestor de conteúdo em PHP e MySQL.'),
          TAG::p('card-text', html: 'Criação do CSS a partir da exportação do design feito no Photoshop (webdesgin).'),
          TAG::p('card-text', html: 'Teste de telas dos sistemas para detectar falhas de utilização dos produtos (UX).'),
        ]),
        TAG::div('card-footer', html: 'de 01/2008 a 04/2011 (3 anos e 3 meses)'),
      ]),
    ]),
    TAG::div('col-4', append: [
      TAG::h4(html: 'Formação'),
      TAG::div('list-group', append: [
        TAG::div('list-group-item', append: [
          TAG::div('d-flex w-100 justify-content-between', append: [
            TAG::h5('mb-1', html: 'Ciência de Dados'),
            TAG::small(html: 'UTFPR')
          ]),
          TAG::p('mb-1', html: 'Especialização'),
          TAG::small(html: 'de mar/2021 a jul/2022')
        ]),
        TAG::div('list-group-item', append: [
          TAG::div('d-flex w-100 justify-content-between', append: [
            TAG::h5('mb-1', html: 'Ciência da Computação'),
            TAG::small(html: 'UNIFACS')
          ]),
          TAG::p('mb-1', html: 'Bacharelado'),
          TAG::small(html: 'de ago/2015 a dez/2018')
        ]),
        TAG::div('list-group-item', append: [
          TAG::div('d-flex w-100 justify-content-between', append: [
            TAG::h5('mb-1', html: 'Técnico em Informática'),
            TAG::small(html: 'Col. Est. Pres. E. Garrastazu M.', title: 'Colégio Estadual Presidente Emílio Garrastazu Médici')
          ]),
          TAG::p('mb-1', html: 'Ensino médio com curso profissionalizante em técnico de informática com ênfase em desenvolvimento de sistemas.'),
          TAG::small(html: 'de jan/2006 a dez/2009')
        ]),
      ]),
      TAG::hr('w-100'),
      TAG::h4(html: 'Idiomas'),
      ...$listPLanguages,
      TAG::hr('w-100'),
      TAG::h4(html: 'Cursos'),
      ...$listPCourses,
    ])
  ]),
  TAG::hr('w-100 mb-2'),
  TAG::p('text-center', html: 'SET/2022'),
]);

echo $html;
