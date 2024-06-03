<?php

use Core\DPG\DPG;
use HTML\A;
use HTML\B;
use HTML\BODY;
use HTML\BUTTON;
use HTML\DIV;
use HTML\H3;
use HTML\HEAD;
use HTML\H1;
use HTML\H2;
use HTML\HTML;
use HTML\SPAN;
use HTML\STRONG;
use HTML\TITLE;
use HTML\SCRIPT;
use HTML\LINK;
use HTML\NAV;

$dpg = new DPG();
$dpg->setTitleText('Administration');

// $html = new HTML(lang: "pt-br");
// $head = new HEAD();
// $body = new BODY();

// $html->appendList([$head, $body]);

// $head->append(new TITLE("Administtration" . " | NCMS"));
// $head->append(new LINK("/styles/bs/bs.ncms.admin.css", "stylesheet"));
// // $head->append();

// $navbarPrinc = new NAV("navbar navbar-expanded-lg bg-dark text-light py-1");
// $navbarPrinc->append(new DIV("container-fluid py-1", appendList: [
//   new A("#", "NCMS", class: "navbar-brand text-light my-0", title: "NCMS - New Content Management System")
// ]));

// $containerPrinc = new DIV("container-fluid");

// $regionTitle = new DIV("py-4");
// $regionTitle->appendList([
//   new H1("border-bottom", html: "Administration"),
//   new H2(html:"Settings and definitions for your website")
// ]);

// $containerPrinc->appendList([
//   $regionTitle,
//   new DIV('row', appendList: [
//     new DIV('col-4', append: new DIV('bg-light rounded shadow-sm', appendList: [
//       new DIV('col', html: 'asd 1'),
//       new DIV('col', html: 'asd 2'),
//       new DIV('col', html: 'asd 3')
//     ])),
//     new DIV('col-4', append: new DIV('bg-light rounded shadow-sm', appendList: [
//       new DIV('col', html: 'asd 1'),
//       new DIV('col', html: 'asd 2'),
//       new DIV('col', html: 'asd 3')
//     ])),
//     new DIV('col-4', append: new DIV('bg-light rounded shadow-sm', appendList: [
//       new DIV('col', html: 'asd 1'),
//       new DIV('col', html: 'asd 2'),
//       new DIV('col', html: 'asd 3')
//     ]))
//   ])
// ]);

// $body->appendList([
//   $navbarPrinc,
//   $containerPrinc,
//   new SCRIPT("/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"),
//   new SCRIPT("/scripts/bs/init.js")
// ]);

return $dpg;
