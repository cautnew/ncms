<?php

namespace Core\NCMS\Content;

use Boot\Constants\DirConstant as DC;
use Core\DPG\AdminPage;
use Core\NCMS\HomeNCMS;
use HTML\BS\BREADCRUMB_ITEM;
use HTML\A;
use HTML\DIV;
use HTML\FA\ICON_USERS;
use HTML\FA\ICON_NEWSPAPER;
use HTML\FA\ICON_TABLE;
use HTML\P;

class Content extends AdminPage
{
  protected const PATH_CACHE = DC::PCACHED . '/ncms/content.chtml';

  public function __construct()
  {
    $this->setTitleText('Content');
    $this->setRoute('/ncms/content');
    $this->setParent(new HomeNCMS);
  }

  public function renderGet(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE);
    }

    $this->setBodyTitleText('Content');

    parent::__construct();

    return $this->render(self::PATH_CACHE);
  }
}
