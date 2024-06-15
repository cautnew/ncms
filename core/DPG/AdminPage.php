<?php

namespace Core\DPG;

use Boot\Constants\DirConstant as DC;
use HTML\A;
use HTML\BS\CARD;
use HTML\DIV;
use HTML\FA\ICON_HEART;
use HTML\FOOTER;
use HTML\H1;
use HTML\HR;
use HTML\LI;
use HTML\LINK;
use HTML\META;
use HTML\NAV;
use HTML\OL;
use HTML\P;
use HTML\SCRIPT;
use HTML\TAG;

class AdminPage extends DPG
{
  protected DIV $bodyContainer;
  protected FOOTER $footer;
  protected NAV $mainBreadCrumb;
  protected OL $mainBreadCrumbList;
  protected CARD $cardPrinc;

  public function __construct()
  {
    parent::__construct();
    $this->setTitleTextAppended('Admin | NCMS');

    $this->getHead()->append([
      new META('charset', 'utf-8'),
      new META('viewport', 'width=device-width, initial-scale=1.0'),
      new LINK(DC::PRIMGS . '/sys/logo.jpeg', 'icon')
    ]);
    $this->addJSHeader(new SCRIPT('/scripts/jq/jquery-3.7.1.min.js'));
    $this->addJSBody([
      new SCRIPT('/scripts/bs/bootstrap-5.3.3.bundle.min.js'),
      new SCRIPT('/scripts/bs/bs.helper.admin.js'),
      new SCRIPT('/shared/js/AdminPage.footer.friendtext.js')
    ]);
    $this->addCSSHeader([
      new LINK("/styles/bs/bs.ncms.admin.css", "stylesheet"),
      new LINK("/styles/fa/fa.ncms.admin.css", "stylesheet")
    ]);

    $this->getBody()->append([
      new DIV('toast-container position-fixed top-0 end-0 p-3'),
      new DIV('main-wrapper', append: $this->getBodyContainer())
    ]);

    $this->getBodyContainer()->append([
      $this->getMainBreadCrumb(),
      $this->getCardPrinc()
    ]);

    $footerLine = new HR('border border-secondary-subtle');
    $footerText = new P('text-center', append: ["Made with ", new ICON_HEART(), " by ", new A('https://www.ncms.com.br/', 'NCMS')]);
    $footerFriendText = new P('text-center text-muted', 'ncms-friend-text', html: '...');
    $footerContainer = new DIV('container text-center', appendList: [$footerLine, $footerText, $footerFriendText]);
    $this->footer = new FOOTER('fixed-bottom mt-3 p-3', append: $footerContainer);
    $this->getBody()->append($this->footer);
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

  public function getMainBreadCrumb(): NAV
  {
    if (!isset($this->mainBreadCrumb)) {
      $this->setMainBreadCrumb(new NAV('main-breadcrumb', append: $this->getMainBreadCrumbList()));
    }

    return $this->mainBreadCrumb;
  }

  public function setMainBreadCrumb(NAV $mainBreadCrumb): self
  {
    $this->mainBreadCrumb = $mainBreadCrumb;

    return $this;
  }

  public function getMainBreadCrumbList(): OL
  {
    if (!isset($this->mainBreadCrumbList)) {
      $this->setMainBreadCrumbList(new OL('breadcrumb'));
    }

    return $this->mainBreadCrumbList;
  }

  public function setMainBreadCrumbList(OL $mainBreadCrumbList): self
  {
    $this->mainBreadCrumbList = $mainBreadCrumbList;

    return $this;
  }

  public function getCardPrinc(): CARD
  {
    if (!isset($this->cardPrinc)) {
      $this->setCardPrinc(new CARD('card-principal'));
    }

    return $this->cardPrinc;
  }

  public function setCardPrinc(CARD $cardPrinc): self
  {
    $this->cardPrinc = $cardPrinc;

    return $this;
  }

  public function setBodyTitle(TAG $title): self
  {
    $this->getCardPrinc()->getBody()->append($title);

    return $this;
  }

  public function setBodyTitleText(string $title): self
  {
    $this->setBodyTitle(new H1('card-title', html: $title));

    return $this;
  }
}
