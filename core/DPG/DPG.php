<?php

namespace Core\DPG;

use Boot\Constants\Constant as C;
use Boot\Constants\DirConstant as DC;
use Core\Support\Session;
use Core\Support\InVar;
use DateTime;

use HTML\HTML;
use HTML\HEAD;
use HTML\BODY;
use HTML\TITLE;
use HTML\FOOTER;
use HTML\LINK;
use HTML\SCRIPT;

/**
 * DPG - Dynamic Page Generator
 */
class DPG
{
  private HTML $html;
  private HEAD $head;
  private TITLE $title;
  private BODY $body;

  private string $titleText;

  private string $lang = 'en';
  private string $titleTextAppended = 'NCMS';
  private string $titleAppendSeparator = '|';

  private array $jsHeader = [];
  private array $jsBody = [];
  private array $cssHeader = [];
  private array $cssBody = [];

  /**
   * If true, the page will be cached and rendered every time it is
   * requested. If false, the page will be rendered only if the cache
   * file does not exist.
   * @var bool $noCache
   */
  private bool $noCache = true;

  private string $pathToCache;

  public function __construct(?string $lang = null)
  {
    $this->setHtml(new HTML());
    $this->setTitle(new TITLE());
    $this->setHead(new HEAD());
    $this->setBody(new BODY());
  }

  public function __toString(): string
  {
    return $this->render();
  }

  protected function prepareTitle(): void
  {
    if (empty($this->getTitleText())) {
      $this->getTitle()->setHtml($this->getTitleTextAppended());
      return;
    }

    $strTitle = implode(' ', [
      $this->getTitleText(),
      $this->getTitleAppendSeparator(),
      $this->getTitleTextAppended()
    ]);

    $this->getTitle()->setHtml($strTitle);
  }

  protected function prepareHead(): void
  {
    $this->prepareTitle();

    foreach ($this->getJSHeader() as $js) {
      $this->getHead()->append($js);
    }

    foreach ($this->getCSSHeader() as $css) {
      $this->getHead()->append($css);
    }

    $this->getHead()->append($this->getTitle());
    $this->getHtml()->append($this->getHead());
  }

  protected function prepareBody(): void
  {
    foreach ($this->getCSSBody() as $css) {
      $this->getBody()->append($css);
    }

    foreach ($this->getJSBody() as $js) {
      $this->getBody()->append($js);
    }

    $this->getHtml()->append($this->getBody());
  }

  public function render(string $path = ''): string
  {
    $this->getHtml()->setLang($this->getLang());
    $this->prepareHead();
    $this->prepareBody();

    $html = $this->getHtml()->getHtml();

    if (!empty($path)) {
      $dir = dirname($path);

      if (!is_dir($dir)) {
        mkdir($dir, recursive: true);
      }

      file_put_contents($path, $html);
    }

    return $html;
  }

  public function getCached(string $path): ?string
  {
    if (!$this->isCached($path)) {
      return null;
    }

    return file_get_contents($path);
  }

  public function isCached(string $path): bool
  {
    if ($this->noCache) {
      return false;
    }

    return file_exists($path);
  }

  public function getHtml(): HTML
  {
    if (!isset($this->html)) {
      $this->setHtml(new HTML());
    }

    return $this->html;
  }

  private function setHtml(HTML $html): self
  {
    $this->html = $html;

    return $this;
  }

  public function getTitle(): TITLE
  {
    if (!isset($this->title)) {
      $this->setTitle(new TITLE());
    }

    return $this->title;
  }

  private function setTitle(TITLE $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getTitleText(): string
  {
    if (!isset($this->titleText)) {
      $this->setTitleText('');
    }

    return $this->titleText;
  }

  public function setTitleText(string $title): self
  {
    $this->titleText = $title;

    return $this;
  }

  public function getHead(): HEAD
  {
    if (!isset($this->head)) {
      $this->setHead(new HEAD());
    }

    return $this->head;
  }

  private function setHead(HEAD $head): self
  {
    $this->head = $head;
    return $this;
  }

  public function getBody(): BODY
  {
    if (!isset($this->body)) {
      $this->setBody(new BODY());
    }

    return $this->body;
  }

  private function setBody(BODY $body): self
  {
    $this->body = $body;

    return $this;
  }

  public function setLang(string $lang): self
  {
    if (empty($lang)) {
      return $this;
    }

    $this->lang = $lang;

    return $this;
  }

  public function getLang(): string
  {
    return $this->lang;
  }

  public function setTitleTextAppended(string $titleTextAppended): self
  {
    $this->titleTextAppended = $titleTextAppended;

    return $this;
  }

  public function getTitleTextAppended(): string
  {
    return $this->titleTextAppended;
  }

  public function setTitleAppendSeparator(string $titleAppendSeparator): self
  {
    $this->titleAppendSeparator = $titleAppendSeparator;

    return $this;
  }

  public function getTitleAppendSeparator(): string
  {
    return $this->titleAppendSeparator;
  }

  public function getJSHeader(): array
  {
    return $this->jsHeader;
  }

  public function addJSHeader(SCRIPT | array $jsscript): self
  {
    if (is_array($jsscript)) {
      foreach ($jsscript as $script) {
        $this->addJSHeader($script);
      }

      return $this;
    }

    $this->jsHeader[] = $jsscript;

    return $this;
  }

  public function getJSBody(): array
  {
    return $this->jsBody;
  }

  public function addJSBody(SCRIPT | array $jsscript): self
  {
    if (is_array($jsscript)) {
      foreach ($jsscript as $script) {
        $this->addJSBody($script);
      }

      return $this;
    }

    $this->jsBody[] = $jsscript;

    return $this;
  }

  public function getCSSHeader(): array
  {
    return $this->cssHeader;
  }

  public function addCSSHeader(LINK | array $cssscript): self
  {
    if (is_array($cssscript)) {
      foreach ($cssscript as $script) {
        $this->addCSSHeader($script);
      }

      return $this;
    }

    $this->cssHeader[] = $cssscript;

    return $this;
  }

  public function getCSSBody(): array
  {
    return $this->cssBody;
  }

  public function addCSSBody(LINK | array $cssscript): self
  {
    if (is_array($cssscript)) {
      foreach ($cssscript as $script) {
        $this->addCSSBody($script);
      }

      return $this;
    }

    $this->cssBody[] = $cssscript;

    return $this;
  }
}
