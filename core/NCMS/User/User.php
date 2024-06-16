<?php

namespace Core\NCMS\User;

use \Exception;
use \PDO;
use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\Route\Request;
use Core\Route\Response;
use HTML\BS\CARD;
use HTML\BS\BREADCRUMB_ITEM;
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
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/user/user.chtml';

  public function __construct()
  {
    $this->setTitleText('Users');
  }

  public function renderList(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE);
    }

    $this->setBodyTitleText('NCMS');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('User', true)
    ]);

    $tableList = new TABLE('table table-striped table-hover', 'table-users-list');
    $tableList->append([
      new THEAD('table-dark', append: new TR(appendList: [
        new TH('text-center', html: 'ID'),
        new TH('text-center', html: 'Username'),
        new TH('text-center', html: 'Email'),
        new TH('text-center', html: 'Active'),
        new TH('text-center', html: 'Actions'),
      ])),
      new TBODY()
    ]);

    $this->getCardPrinc()->getBody()->append(new P(html: 'Here you can check all users registered in the system.'));
    $this->getCardPrinc()->getBody()->append($tableList);

    parent::__construct();

    $this->addJSBody(new SCRIPT('/core/NCMS/User/loaduserlist.js'));

    return $this->render(self::PATH_CACHE);
  }
}
