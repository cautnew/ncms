<?php

namespace Core\NCMS;

use \Exception;
use \PDO;
use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\BS\CARD;
use HTML\BS\FORM_CHECK_SWITCH;
use HTML\BS\FORM_SELECT;
use HTML\BS\ROW;
use HTML\A;
use HTML\BUTTON;
use HTML\DIV;
use HTML\FORM;
use HTML\H1;
use HTML\INPUT_NUMBER;
use HTML\INPUT_TEXT;
use HTML\INPUT_PASSWORD;
use HTML\LABEL;
use HTML\LI;
use HTML\NAV;
use HTML\OL;
use HTML\OPTION;
use HTML\P;
use HTML\SCRIPT;
use HTML\SPAN;
use HTML\TABLE;
use HTML\TBODY;
use HTML\TD;
use HTML\THEAD;
use HTML\TH;
use HTML\TR;

class HomeNCMS extends AdminPage
{
  public function __construct()
  {
    parent::__construct();
    $this->setTitleText('NCMS');
    $this->addJSBody(new SCRIPT('/core/NCMS/Admin/DB/configdb.js'));
  }

  public function renderGet(): string
  {
    $breadcrumb = new NAV(append: new OL('breadcrumb', appendList: [
      new LI('breadcrumb-item', append: new A('/', 'Site')),
      new LI('breadcrumb-item active', append: 'NCMS'),
    ]));

    $cardPrinc = new CARD('card-principal');
    $cardPrinc->getBody()->append(new H1('card-title', html: 'NCMS'));
    $cardPrinc->getBody()->append(new P(html: 'Welcome to the NCMS. Here you can edit the site content.'));

    $this->getBodyContainer()->append([
      $breadcrumb,
      $cardPrinc
    ]);

    return $this->render();
  }
}
