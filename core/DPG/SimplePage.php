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
  protected DIV $container;
  protected FOOTER $footer;

  public function __construct()
  {
    parent::__construct();
    $this->setTitleTextAppended('NCMS Simple Page');

    $this->getHead()->append(new META('charset', 'utf-8'));
    $this->getHead()->append(new META('viewport', 'width=device-width, initial-scale=1.0'));
    $this->addJSHeader(new SCRIPT('/scripts/jq/jquery-3.7.1.min.js'));
    $this->addJSBody(new SCRIPT('/scripts/bs/bootstrap-5.3.3.bundle.min.js'));
    $this->addCSSHeader(new LINK("/styles/bs/bs.simplepage.css", "stylesheet"));
    $this->addCSSHeader(new LINK("/styles/fa/fa.ncms.admin.css", "stylesheet"));

    $this->container = new DIV('container pt-3');
    $this->getBody()->append($this->container);

    $footerLine = new HR('border border-secondary-subtle');
    $footerText = new P('text-center', html: "Made with " . (new ICON_HEART()) . " by NCMS");
    $footerContainer = new DIV('container text-center', appendList: [$footerLine, $footerText]);
    $this->footer = new FOOTER('fixed-bottom mt-3 p-3', append: $footerContainer);
    $this->getBody()->append($this->footer);
  }
}
