<?php

namespace Source;

use Boot\Constants\DirConstant as DC;
use Core\DPG\SimplePage;
use HTML\DIV;
use HTML\A;
use HTML\H1;
use HTML\SCRIPT;

class HomePage extends SimplePage
{
  protected const PATH_CACHE = DC::PCACHED . '/source/homepage.chtml';

  public function __construct()
  {
    $this->setTitleText('Home Page');
    $this->addJSBody(new SCRIPT('/scripts/bs/bs.helper.admin.js'));
  }

  public function renderGet(): string
  {
    if ($this->isCached(self::PATH_CACHE)) {
      return $this->getCached(self::PATH_CACHE);
    }

    $this->getBodyContainer()->append(new H1(html: 'Welcome to the Home Page'));
    $this->getBodyContainer()->append(new A('/ncms', 'Admin Page', 'btn btn-primary',));

    parent::__construct();

    return $this->render(self::PATH_CACHE);
  }
}
