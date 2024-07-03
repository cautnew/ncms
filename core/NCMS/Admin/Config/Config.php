<?php

namespace Core\NCMS\Admin\Config;

use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\NCMS\Admin\Admin;
use HTML\BS\BREADCRUMB_ITEM;
use HTML\A;
use HTML\DIV;
use HTML\FA\ICON_USERS;
use HTML\FA\ICON_NEWSPAPER;
use HTML\FA\ICON_TABLE;
use HTML\P;

class Config extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/admin/config/config.chtml';

  public function __construct()
  {
    $this->setTitleText('Config');
    $this->setRoute('/ncms/admin/config');
    $this->setParent(new Admin);
  }

  public function renderGet(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE);
    }

    $this->setBodyTitleText('Config');

    parent::__construct();

    return $this->render(self::PATH_CACHE);
  }
}
