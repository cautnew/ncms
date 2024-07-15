<?php

namespace Core\NCMS;

use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use HTML\BS\BREADCRUMB_ITEM;
use HTML\BS\CARD;
use HTML\A;
use HTML\H1;
use HTML\LI;
use HTML\P;

class LoginPage extends AdminPage
{
  public function __construct()
  {
    $this->setTitleText('Login');
  }

  public function renderGet(): string
  {
    $cardPrinc = new CARD('card-principal');
    $cardPrinc->getBody()->append(new H1('card-title', html: 'Login'));
    $cardPrinc->getBody()->append(new P(html: 'Welcome to the NCMS. Login below to access the admin panel.'));

    parent::__construct();

    return $this->render();
  }
}
