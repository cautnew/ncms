<?php

namespace App\Templates\Extension\Blog;

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

    $this->html->getBody()->addClass('flex h-full bg-zinc-50 dark:bg-black');

    $this->html->appendToBody(TAG::div('container', append: $this->regionHeader = new HEADER('blod-header py-3')));
    $this->html->appendToBody($this->regionMain = new MAIN('flex w-full'));

    $this->prepareHead();
    $this->prepareFooter();
  }

  public function getPage(): HTML5
  {
    return $this->html;
  }

  private function prepareHead(): void
  {
    $this->html->setPageTitle("Kautch CMS Blog");
    $this->html->appendToHead(TAG::meta(charset: 'utf-8'));
    $this->html->appendToHead(TAG::meta('viewport', content: 'width=device-width, initial-scale=1'));
    $this->html->appendToHead(TAG::script('https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'));
  }

  private function prepareFooter(): void
  {
    $this->html->appendToBody($this->regionFooter = new FOOTER(
      'bg-gray-900 text-gray-300 py-8 sm:py-12 border-t border-gray-800',
      append: TAG::div(
        'mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl',
        append: [
          TAG::div('grid gap-8 sm:grid-cols-3 mb-8', append: [
            TAG::div(append: [
              TAG::h4('text-white font-semibold mb-4', html: 'Sobre'),
              TAG::p('text-sm', html: 'Um espaço para compartilhar conhecimento sobre desenvolvimento web e tecnologia.')
            ]),
            TAG::div(append: [
              TAG::h4('text-white font-semibold mb-4', html: 'Links'),
              TAG::ul('space-y-2 text-sm', append: [
                TAG::li(append: TAG::a('#', 'Home', 'hover:text-white transition-colors')),
                TAG::li(append: TAG::a('#', 'Posts', 'hover:text-white transition-colors')),
                TAG::li(append: TAG::a('#', 'Categorias', 'hover:text-white transition-colors')),
              ])
            ]),
            TAG::div(append: [
              TAG::h4('text-white font-semibold mb-4', html: 'Redes Sociais'),
              TAG::ul('space-y-2 text-sm', append: [
                TAG::li(append: TAG::a('#', 'Twitter', 'hover:text-white transition-colors')),
                TAG::li(append: TAG::a('#', 'GitHub', 'hover:text-white transition-colors')),
                TAG::li(append: TAG::a('#', 'LinkedIn', 'hover:text-white transition-colors')),
              ])
            ]),
          ]),
          TAG::div(
            'border-t border-gray-800 pt-8 text-center text-sm',
            append: TAG::p(html: '&copy; 2026 Kautch CMS Blog. Todos os direitos reservados.')
          )
        ])
    ));
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

  /**
   * Summary of appendToFooter
   * @param mixed $element
   * @return TemplateBlog
   */
  public function appendToFooter(mixed $element): self
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
