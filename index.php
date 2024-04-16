<?php

use Cautnew\HTML\BODY;
use Cautnew\HTML\HEAD;
use Cautnew\HTML\H1;
use Cautnew\HTML\HTML;
use Cautnew\HTML\TITLE;
use Cautnew\HTML\STYLE;
use Boot\Constants\DirConstant as DC;

$body = new BODY();
$head = new HEAD(append: new TITLE("Sudoku"));
$head->append(new STYLE(append: <<<CSS
  body {
    background-color: #000;
    color: #fff;
  }
CSS));
$html = new HTML(lang: "pt-br", appendList: [$head, $body]);

$body->append(new H1(html: "Sudoku " . DC::PSUPPORT));

return $html;
