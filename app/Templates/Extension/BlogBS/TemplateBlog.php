<?php

namespace App\Templates\Extension\BlogBS;

use App\Templates\TemplateManager;
use App\Templates\TemplateManagerInterface;
use PHTML\Core\HEADER;
use PHTML\Core\MAIN;
use PHTML\Core\TAG;
use PHTML\Core\DIV;
use PHTML\Core\FOOTER;
use PHTML\Templates\HTML5;

class TemplateBlog extends TemplateManager implements TemplateManagerInterface
{
  private HTML5 $html;
  private HEADER $regionHeader;
  private MAIN $regionMain;
  private FOOTER $regionFooter;

  public function __construct()
  {
    $this->html = new HTML5();
    $this->html->getHtml()->setLang('pt-BR');

    $this->html->appendToBody(TAG::div('container', append: $this->regionHeader = new HEADER('blod-header py-3')));
    $this->html->appendToBody($this->regionMain = new MAIN('container'));

    $this->prepareHeader();
    $this->prepareFooter();
  }

  private function prepareHeader(): void
  {
    $this->html->setPageTitle("Cautnew's Blog");
    $this->html->appendToHead(TAG::meta(charset: 'utf-8'));
    $this->html->appendToHead(TAG::meta('viewport', content: 'width=device-width, initial-scale=1'));
    $this->html->appendToHead(TAG::link('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css', 'stylesheet', integrity: 'sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB', crossorigin: 'anonymous'));
    $this->html->appendToHead(TAG::link('https://getbootstrap.com/docs/5.0/examples/blog/blog.css', 'stylesheet'));
  }

  private function prepareFooter(): void
  {
    $this->html->appendToBody($this->regionFooter = new FOOTER(
      'blog-footer',
      style: 'background: linear-gradient(to bottom, #dddddd, #ffffff);',
      append: [
        TAG::p(html: "CautNew's Blog built with NCMS and ❤️.")
      ]
    ));
    $this->html->appendToBody(TAG::script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js', integrity: 'sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI', crossorigin: 'anonymous'));
  }

  public function getPage(): HTML5
  {
    return $this->html;
  }

  public function appendToHeader($element): self
  {
    $this->regionHeader->append($element);

    return $this;
  }

  public function appendToMain($element): self
  {
    $this->regionMain->append($element);

    return $this;
  }

  public function appendToFooter($element): self
  {
    $this->regionFooter->append($element);

    return $this;
  }

  public function definePageTitle(string $title): self
  {
    $this->appendToHeader(TAG::h1(html: $title));

    return $this;
  }
}
