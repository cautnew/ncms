<?php

namespace Core\NCMS\Admin;

use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use HTML\BS\BREADCRUMB_ITEM;
use HTML\A;
use HTML\DIV;
use HTML\FA\ICON_USERS;
use HTML\FA\ICON_NEWSPAPER;
use HTML\FA\ICON_TABLE;
use HTML\P;

class Admin extends AdminPage
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

    $this->setBodyTitleText('Admin');

    $this->getMainBreadCrumbList()->append([
      new BREADCRUMB_ITEM('Site', false, '/'),
      new BREADCRUMB_ITEM('NCMS', false, '/ncms'),
      new BREADCRUMB_ITEM('Admin', true),
    ]);

    parent::__construct();

    return $this->render(self::PATH_CACHE);
  }
}
