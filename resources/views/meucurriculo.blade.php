<?php

use PHTML\TAG;

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

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <title>Currículo - Felipe de Sousa Martins</title>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-8">
        <h1 class="display-4">Martins, Felipe</h1>
        <p class="lead">DevOps, brasileiro, solteiro, 31 anos</p>
        <p class="mb-0">Github: <a href="https://github.com/cautnew" alt="Link para meu Github">https://github.com/cautnew</a></p>
        <p class="mb-0">LinkedIn: <a href="https://www.linkedin.com/in/felipedesmartins" alt="Link para meu LinkedIn">https://www.linkedin.com/in/felipedesmartins</a></p>
      </div>
      <div class="col-4">
        <ul class="list-group">
          <li class="list-group-item py-1"><i class="fas fa-home mr-2"></i>Salvador, BA, Brasil</li>
          <li class="list-group-item py-1"><i class="fas fa-phone mr-2"></i><a href="tel:+5571991640905" alt="Meu telefone é +55 71 99164-0905">+55 71 99164-0905</a></li>
          <li class="list-group-item py-1"><i class="fas fa-envelope mr-2"></i><a href="mailto:felipedesmartins@gmail.com" alt="Meu e-mail é felipedesmartins@gmail.com">felipedesmartins@gmail.com</a></li>
        </ul>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-8">
        <h4>Habilidades</h4>
        <p>Desenvolvimento de software (backend) utilizando JS, PHP e Python, DBA com experiência em Oracle e MySQL e cientista de dados no ramo da telefonia celular.</p>
        <p>Criatividade que me deixa inquieto ao ver problemas que precisam serem resolvidos, mesmo que não seja da minha área, mas que podem gerar um grande ganho no futuro de toda a empresa.</p>
        <hr class="w-100">
        <h4 class="mb-3">Tecnologias</h4>
        <h5>Backend</h5>
        <p>PHP, JavaScript, Python, NodeJS, Shell Script, Java, MySQL, MongoDB, Oracle, PL/SQL, C, C++, ambientes Linux.</p>
        <h5>Frontend</h5>
        <p>Tailwind, Bootstrap, VueJS, ReactJS, jQuery, D3.org, ChartJS, Google Charts.</p>
        <h5>Testes</h5>
        <p>PHPUnit, XDebug, JUnit, Pytest, W3C Markup Validator.</p>
        <h5>Outras</h5>
        <p>Laravel, Drupal, WordPress, Adobre Magento, Photoshop, Figma, MS Office 365 (Excel, Access), Power BI, Think-Cell.</p>
        <hr class="w-100">
        <h4 class="mb-3">Resumo profissional</h4>
        <p>Desenvolvo sistemas para WEB em <strong>PHP e JavaScript</strong> com bom conhecimento de front-end usando <strong>HTML e CSS</strong>. Iniciei um projeto de ferramenta local de análise de indicadores em PHP realizando pesquisas diretamente em DWH (Data Warehouse) com BI (Business Intelligence).</p>
        <p>Desenvolvi toda a ferramenta de pedidos de aparelhos para lojas próprias com status de entrega, aprovação de gestores e acompanhamento de giro. Foi feita em PHP + MySQL + JavaScript.</p>
        <p>Apresentação e análise de informações em big data usando Python. Utilizei esse conhecimento para alimentar uma inteligência artificial que projeta o quanto pode ser atingido de desempenho nas vendas com base no histórico curto. Isso agilizou a atualização do relatório significativamente e, consequência, a preparação da equipe de vendas.</p>
        <p><strong>Experiência de 5 anos com consultas SQL em DWH com BI. Para fazer a demonstração desses dados utilizei de forma avançada a suíte de programas do Office (Access, Excel, Word e PowerPoint) além de frameworks em JS como D3.org, ChartJS, Google Charts, todos realizando as consultas via PHP em Oracle, interpretando os dados e apresentando-os de forma intuitiva e veloz. Toda a parte de UI e UX foi projetada e desenvolvida por mim, documentada e com feedback de aprovação dos clientes.</strong></p>
        <p>Criei um sistema que consegue coletar as vendas em tempo real de todo o Brasil e as apresenta via Telegram para todos os gestores e diretores. Também as exibe em um painel no escritório para que todos possam acompanhar por DDD, produto, canal e regional. Esse painel foi idealizado, construído e implantado por mim para mostrar noticias da compania e avisos locais.</p>
        <p>Fui vendedor de loja de telefonia celular por 2 anos. Nesse período fui atender no caixa e acabei desenvolvendo um sistema para inventariar todo o estoque de aparelhos e chips da loja. Essa é uma das obrigações do caixa e levava mais de 2 horas para concluir diariamente. Com esse sistema reduzi para apenas 30 minutos e hoje está sendo usado por toda a empresa.</p>
        <hr class="w-100">
        <h4 class="mb-3">Experiência Profissional</h4>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">Claro S/A – Analista PL</h5>
            <p class="card-text">Desenvolvimento e análise de bases de todos os indicadores da empresa utilizando BI com PHP, PL/Sql, SQL Server, Oracle, PowerBI e Tableau.</p>
            <p class="card-text">Criação de um sistema onde convergem os dados de indicadores e os dispobiliza em um banco dados público e via página WEB personalizada.</p>
            <p class="card-text">Comunicação via Telegram e painel digital no escritório de todos os indicadores de vendas em toda a regional em tempo real.</p>
          </div>
          {!! TAG::div('card-footer', html: 'desde 08/2016 (atual - 5 anos)') !!}
        </div>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">Claro S/A – Lojas Próprias</h5>
            <p class="card-text">Vendedor de Lojas Próprias - vendas de serviços de telecomunicações e aparelhos celulares.</p>
            <p class="card-text">Desenvolvimento do sistema de inventário de aparelhos em lojas que otimizou o processo de 2 horas diárias para apenas 30 minutos.</p>
          </div>
          <div class="card-footer">de 02/2011 a 06/2013 (2 anos e 4 meses)</div>
        </div>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">Claro S/A – Assistente administrativo</h5>
            <p class="card-text">Ponto focal para andamento de notas fiscais e monitoramento do estoque de aparelhos, consultas em banco Oracle sobre andamento de vendas.</p>
            <p class="card-text">Desenvolvimento do sistema que integra os pedidos das lojas com a equipe de supply que otimizou os processos para garantir o abastecimento a tempo de qualquer evento especial.</p>
          </div>
          <div class="card-footer">de 06/2013 a 08/2016 (3 anos e 2 meses)</div>
        </div>
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title">Corproni Informática – Desenvolvimento de Web Sites</h5>
            <p class="card-text">Desenvolvimento de sites com gestor de conteúdo em PHP e MySQL.</p>
            <p class="card-text">Criação do CSS a partir da exportação do design feito no Photoshop (webdesgin).</p>
            <p class="card-text">Teste de telas dos sistemas para detectar falhas de utilização dos produtos (UX).</p>
          </div>
          <div class="card-footer">de 01/2008 a 04/2011 (3 anos e 3 meses)</div>
        </div>
      </div>
      <div class="col-4">
        <h4>Formação</h4>
        <div class="list-group">
          <div class="list-group-item">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">Ciência de Dados</h5>
              <small>UTFPR</small>
            </div>
            <p class="mb-1">Especialização</p>
            <small>de mar/2021 a jul/2022</small>
          </div>
          <div class="list-group-item">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">Ciência da Computação</h5>
              <small>UNIFACS</small>
            </div>
            <p class="mb-1">Bacharelado</p>
            <small>de ago/2015 a dez/2018</small>
          </div>
          <div class="list-group-item">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">Técnico em Informática</h5>
              <small title="Colégio Estadual Presidente Emílio Garrastazu Médici">Col. Est. Pres. E. Garrastazu M.</small>
            </div>
            <p class="mb-1">Ensino médio com curso profissionalizante em técnico de informática com ênfase em desenvolvimento de sistemas.</p>
            <small>de jan/2006 a dez/2009</small>
          </div>
        </div>
        <hr class="w-100">
        <h4>Idiomas</h4>
        <?php
        foreach ($languages as $language) {
          echo TAG::p('mb-1', html: "{$language['name']} ({$language['level']})");
        }
        ?>
        <hr class="w-100">
        <h4>Cursos</h4>
        <?php
        foreach ($courses as $course) {
          echo TAG::p('mb-1', html: "{$course['name']} ({$course['institution']})");
        }
        ?>
      </div>
    </div>
    <hr class="w-100">
    <p class="text-center">SET/2022</p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>