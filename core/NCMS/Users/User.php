<?php

namespace Core\NCMS\Users;

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

class User extends AdminPage
{
  public function __construct()
  {
    parent::__construct();
    $this->setTitleText('Users');
    $this->addJSBody(new SCRIPT('/core/NCMS/Admin/DB/configdb.js'));
  }

  public function renderList(): string
  {
    $breadcrumb = new NAV(append: new OL('breadcrumb', appendList: [
      new LI('breadcrumb-item', append: new A('/ncms', 'NCMS')),
      new LI('breadcrumb-item active', append: 'Users'),
    ]));

    $tableList = new TABLE('table table-striped table-hover');
    $tableList->append([
      new THEAD('table-dark', append: new TR(appendList: [
        new TH('text-center', html: 'ID'),
        new TH('text-center', html: 'Username'),
        new TH('text-center', html: 'Email'),
        new TH('text-center', html: 'Active'),
        new TH('text-center', html: 'Actions'),
      ])),
      new TBODY(appendList: [
        new TR(appendList: [
          new TD('text-center', html: 'glksdjfgt23k4jtbn2k34jbtk'),
          new TD('text-center', html: 'admin.jose'),
          new TD('text-center', html: 'jose@ncms.com'),
          new TD('text-center', html: 'Yes'),
          new TD('text-center', html: 'Edit')
        ]),
        new TR(appendList: [
          new TD('text-center', html: 'k2jb34kjb2k34jjb46k2j34b6'),
          new TD('text-center', html: 'admin.maria'),
          new TD('text-center', html: 'maria@ncms.com'),
          new TD('text-center', html: 'Yes'),
          new TD('text-center', html: 'Edit')
        ])
      ])
    ]);

    $cardPrinc = new CARD('card-principal');
    $cardPrinc->getBody()->append(new H1('card-title', html: 'Users'));
    $cardPrinc->getBody()->append(new P(html: 'Here you can check all users registered in the system.'));
    $cardPrinc->getBody()->append($tableList);

    $this->getBodyContainer()->append([
      $breadcrumb,
      $cardPrinc
    ]);

    return $this->render();
  }
}
