<?php

namespace Core\NCMS;

use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use HTML\BS\BREADCRUMB_ITEM;
use HTML\A;
use HTML\DIV;
use HTML\FA\ICON_USERS;
use HTML\FA\ICON_NEWSPAPER;
use HTML\P;

class HomeNCMS extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/home.chtml';

  public function __construct()
  {
    $this->setTitleText('NCMS');
  }

  public function renderGet(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE);
    }

    $this->setBodyTitleText('NCMS');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('Site', false, '/'),
      new BREADCRUMB_ITEM('NCMS', true),
    ]);

    $this->getCardPrinc()->getBody()->append(new P(html: 'Welcome to the NCMS. Here you can edit the site content.'));
    $this->getCardPrinc()->getBody()->append(new DIV('nav justify-content-center', append: [
      new A("/ncms/user", class: 'btn btn-primary me-2', append: [new ICON_USERS(), new P('fs-5 mb-0', html: 'User')]),
      new A("/ncms/content", class: 'btn btn-primary me-2', append: [new ICON_NEWSPAPER(), new P('fs-5 mb-0', html: 'Content')]),
      new A('/ncms/admin/config/db', class: 'btn btn-success', append: [new ICON_NEWSPAPER(), new P('fs-5 mb-0', html: 'DB Connection Settings')])
    ]));

    parent::__construct();

    return $this->render(self::PATH_CACHE);
  }
}
