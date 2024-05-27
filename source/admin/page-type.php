<?php

use Core\Conn\DB;
use HTML\A;
use HTML\B;
use HTML\BODY;
use HTML\BS\CARD;
use HTML\BS\LABEL as BS_LABEL;
use HTML\BUTTON;
use HTML\DIV;
use HTML\H3;
use HTML\HEAD;
use HTML\H1;
use HTML\H2;
use HTML\H4;
use HTML\HTML;
use HTML\INPUT_TEXT;
use HTML\TEXTAREA;
use HTML\LABEL;
use HTML\SPAN;
use HTML\STRONG;
use HTML\TITLE;
use HTML\SCRIPT;
use HTML\LINK;
use HTML\NAV;

$html = new HTML(lang: "pt-br");
$head = new HEAD();
$body = new BODY();

$stm = DB::getConn();

$html->appendList([$head, $body]);

$head->append(new TITLE("Administration" . " | NCMS"));
$head->append(new LINK("/imgs/sys/logo.jpeg", "shortcut icon", "image/x-icon"));
$head->append(new LINK("/styles/bs/bs.ncms.admin.css", "stylesheet"));
$head->append(new LINK("/styles/fa/fa.ncms.admin.css", "stylesheet"));
// $head->append();

$navbarPrinc = new NAV("navbar navbar-expanded-lg bg-dark text-light fixed-top py-1");
$navbarPrinc->append(new DIV("container-fluid py-0", appendList: [
  new A("#", "NCMS", class: "navbar-brand text-light my-0", title: "NCMS - New Content Management System")
]));

$containerPrinc = new DIV("container-fluid");

$regionTitle = new DIV("py-4");
$regionTitle->appendList([
  new H1("title", html: "Page Type"),
  new H2(html:"Settings and definitions for your website")
]);

$cardTeaser = function () {
  $card = new CARD('card-teaser');

  $card->header()->getTag()->appendList([
    new DIV('d-flex align-items-center justify-content-between', appendList: [
      new H4('mb-0', html: 'Teaser'),
      new BUTTON('btn btn-primary disabled', 'btn-save-teaser', html: 'Save')
    ]),
    new DIV('alert alert-danger text-center mt-3', html: '<i class="fa-solid fa-circle-exclamation me-2"></i>Teste')
  ]);
  $card->body()->getTag()->appendList([
    new DIV('col-auto mb-3', appendList: [
      new DIV('input-group', appendList: [
        new SPAN('input-group-text', 'teaser-search', html: "@"),
        new INPUT_TEXT('form-control', 'teaser-search', 'teaser-search', placeholder: 'Search for teaser data')
      ])
    ]),
    new DIV('col-auto mb-3', appendList: [
      new LABEL('teaser-title', 'form-label h5', txt: 'Title'),
      new INPUT_TEXT('form-control', 'teaser-title', 'teaser-title', placeholder: 'Title'),
      new DIV('form-text', 'teaser-text', appendList: [
        'The title of the teaser. Number of characters: ',
        new SPAN('form-text-counter', 'teaser-title-counter', html: "1"),
        '. It must be no more then 100 characters long.'
      ])
    ]),
    new DIV('col-auto mb-3', appendList: [
      new LABEL('teaser-text', 'form-label h5', txt: 'Text'),
      new TEXTAREA('form-control', 'teaser-text', 'teaser-text', placeholder: 'Text'),
      new DIV('form-text', 'teaser-text', appendList: [
        'The text of the teaser. Number of characters: ',
        new SPAN('form-text-counter', 'teaser-text-counter', html: "1"),
        '. It must be no more then 200 characters long.'
      ])
    ])
  ]);

  return $card;
};

$cardTags = function () {
  $card = new CARD('card-tags');

  $card->header()->append(new DIV('d-flex align-items-center justify-content-between', appendList: [
    new H4('mb-0', html: 'Tags'),
    new BUTTON('btn btn-primary disabled', 'btn-save-tags', html: 'Save')
  ]));
  $card->body()->getTag()->appendList([
    new DIV('col-auto mb-3', appendList: [
      new DIV('input-group', appendList: [
        new SPAN('input-group-text', 'metainfo-search', html: "@"),
        new INPUT_TEXT('form-control', 'metainfo-search', 'metainfo-search', placeholder: 'Search for meta informations')
      ])
    ]),
    new DIV('col-auto mb-3', appendList: [
      new LABEL('teaser-title', 'form-label h5', txt: 'Title'),
      new INPUT_TEXT('form-control', 'teaser-title', 'teaser-title', placeholder: 'Title'),
      new DIV('form-text', 'teaser-text', appendList: [
        'The title of the teaser. Number of characters: ',
        new SPAN('form-text-counter', 'teaser-title-counter', html: "1"),
        '. It must be no more then 80 characters long.'
      ])
    ]),
    new DIV('col-auto mb-3', appendList: [
      new LABEL('teaser-text', 'form-label h5', txt: 'Text'),
      new TEXTAREA('form-control', 'teaser-text', 'teaser-text', placeholder: 'Text'),
      new DIV('form-text', 'teaser-text', appendList: [
        'The text of the teaser. Number of characters: ',
        new SPAN('form-text-counter', 'teaser-text-counter', html: "1"),
        '. It must be no more then 200 characters long.'
      ])
    ])
  ]);

  return $card;
};

$cardMetaInformations = function () {
  $card = new CARD('card-meta-informations');

  $card->header()->append(new DIV('d-flex align-items-center justify-content-between', appendList: [
    new H4('mb-0', html: 'Meta Informations'),
    new BUTTON('btn btn-primary disabled', 'btn-save-metainfo', html: 'Save')
  ]));
  $card->body()->getTag()->appendList([
    new DIV('col-auto mb-3', appendList: [
      new DIV('input-group', appendList: [
        new SPAN('input-group-text', 'metainfo-search', html: "@"),
        new INPUT_TEXT('form-control', 'metainfo-search', 'metainfo-search', placeholder: 'Search for meta informations')
      ])
    ]),
    new DIV('col-auto mb-3', appendList: [
      new LABEL('teaser-title', 'form-label h5', txt: 'Title'),
      new INPUT_TEXT('form-control', 'teaser-title', 'teaser-title', placeholder: 'Title'),
      new DIV('form-text', 'teaser-text', appendList: [
        'The title of the teaser. Number of characters: ',
        new SPAN('form-text-counter', 'teaser-title-counter', html: "1"),
        '. It must be no more then 80 characters long.'
      ])
    ]),
    new DIV('col-auto mb-3', appendList: [
      new LABEL('teaser-text', 'form-label h5', txt: 'Text'),
      new TEXTAREA('form-control', 'teaser-text', 'teaser-text', placeholder: 'Text'),
      new DIV('form-text', 'teaser-text', appendList: [
        'The text of the teaser. Number of characters: ',
        new SPAN('form-text-counter', 'teaser-text-counter', html: "1"),
        '. It must be no more then 200 characters long.'
      ])
    ])
  ]);

  return $card;
};

$cardCreationInformations = new DIV();

$containerPrinc->appendList([
  $regionTitle,
  new DIV('alert alert-warning', html: "<i class=\"fa-solid fa-triangle-exclamation me-2\"></i>All this content is a test. It's not functional."),
  new DIV('row gx-2', appendList: [
    new DIV('col-xl-6', appendList: [
      $cardTeaser(),
    ]),
    new DIV('col-xl-6', appendList: [
      $cardTags(),
    ])
  ]),
  new DIV('row gx-2', appendList: [
    new DIV('col-xl-6', appendList: [
      $cardMetaInformations()
    ])
  ])
]);

$footer = new DIV('footer fixed-bottom bg-dark text-light text-center py-5', html: "NCMS - New Content Management System");

$body->appendList([
  $navbarPrinc,
  $containerPrinc,
  $footer,
  new SCRIPT("/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"),
  new SCRIPT("/scripts/bs/init.js")
]);

return $html;
