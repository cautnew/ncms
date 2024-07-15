<?php

namespace Core\DPG;

use HTML\DIV;
use HTML\FA\ICON_HEART;
use HTML\FOOTER;
use HTML\HR;
use HTML\LINK;
use HTML\META;
use HTML\P;
use HTML\SCRIPT;

class SimplePage extends DPG
{
  protected DIV $bodyContainer;
  protected FOOTER $footer;

  public function __construct()
  {
    parent::__construct();
    $this->setTitleTextAppended('NCMS Simple Page');

    $this->getHead()->append(new META('charset', 'utf-8'));
    $this->getHead()->append(new META('viewport', 'width=device-width, initial-scale=1.0'));
    $this->addJSHeader(new SCRIPT('/scripts/jq/jquery-3.7.1.min.js'));
    $this->addJSHeader(new SCRIPT('/shared/js/init.js'));
    $this->addJSBody(new SCRIPT('/scripts/bs/bootstrap-5.3.3.bundle.min.js'));
    $this->addCSSHeader(new LINK("/styles/bs/bs.simplepage.css", "stylesheet"));
    $this->addCSSHeader(new LINK("/styles/fa/fa.ncms.admin.css", "stylesheet"));

    $this->getBody()->append([$this->getBodyContainer(), $this->getFooter()]);

    $this->prepareFooter();
  }

  public function getBodyContainer(): DIV
  {
    if (!isset($this->bodyContainer)) {
      $this->setBodyContainer(new DIV('container pt-3'));
    }

    return $this->bodyContainer;
  }

  private function setBodyContainer(DIV $bodyContainer): self
  {
    $this->bodyContainer = $bodyContainer;

    return $this;
  }

  public function getFooter(): FOOTER
  {
    if (!isset($this->footer)) {
      $this->setFooter(new FOOTER('fixed-bottom mt-3 p-3'));
    }

    return $this->footer;
  }

  private function setFooter(FOOTER $footer): self
  {
    $this->footer = $footer;

    return $this;
  }

  protected function prepareFooter(): self
  {
    $footerLine = new HR('border border-secondary-subtle');
    $footerText = new P('text-center', html: "Made with " . (new ICON_HEART()) . " by NCMS");
    $footerContainer = new DIV('container text-center', appendList: [$footerLine, $footerText]);
    $this->getFooter()->append($footerContainer);

    return $this;
  }
}
