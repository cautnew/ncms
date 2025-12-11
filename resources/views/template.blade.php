<?php

use PHTML\Core\P;
use PHTML\Core\TAG;
use PHTML\Templates\HTML5;
use Inertia\Directive;

/**
 * @var $appearance string
 */
$appearance;

$myPage = new HTML5();
$myPage->setPageTitle($pageTitle ?? config('app.name', 'Laravel'));

$myPage->preRenderHtml(function () use ($appearance) {
  if (($appearance ?? 'system') == 'dark')
    $this->addClass('dark');

  $this->setLang(str_replace('_', '-', app()->getLocale()));
});

$myPage->addRenderHead(function () use ($appearance) {
  $this->append(TAG::meta('viewport', content: 'width=device-width, initial-scale=1'));
  $this->append(TAG::meta('csrf-token', content: csrf_token()));
  $this->append(TAG::link('/logo.png', 'icon', sizes: 'any'));
  $this->append(TAG::link('/logo.png', 'apple-touch-icon'));

  $this->append(TAG::link('https://fonts.bunny.net', 'preconnect'));
  $this->append(TAG::link('https://fonts.bunny.net/css?family=instrument-sans:400,500,600', 'stylesheet'));

  $this->append(TAG::script(code: <<<JAVASCRIPT
  (function () {
    const appearance = '{ ($appearance ?? "system") }';

    if (appearance === 'system') {
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

      if (prefersDark) {
        document.documentElement.classList.add('dark');
      }
    }
  })();
  JAVASCRIPT));

  $this->append(TAG::style(code: <<<CSS
  html { background-color: oklch(1 0 0); }
  html.dark { background-color: oklch(0.145 0 0); }
  CSS));
});

$myPage->addRenderBody(function () {
  //$content = eval (Directive::compile());
  $this->append('\$content');
});

echo $myPage;
