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
  public HTML $html;
  private HEAD $head;
  private TITLE $title;
  private BODY $body;

  private string $titleText;

  private string $lang = 'en';
  private string $titleTextAppended = 'NCMS';
  private string $titleAppendSeparator = '|';

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
    } else {
      $this->getTitle()->setHtml(
        implode(' ', [
          $this->getTitleText(),
          $this->getTitleAppendSeparator(),
          $this->getTitleTextAppended()
        ])
      );
    }
  }

  public function render(): string
  {
    $this->prepareTitle();

    $this->getHead()->append($this->getTitle());

    $this->getHtml()->append($this->getHead());
    $this->getHtml()->append($this->getBody());

    return $this->getHtml()->getHtml();
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
}
