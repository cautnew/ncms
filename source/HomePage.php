<?php

namespace Source;

use Core\DPG\SimplePage;
use HTML\DIV;
use HTML\A;
use HTML\H1;
use HTML\SCRIPT;

class HomePage extends SimplePage
{
  public function __construct()
  {
    parent::__construct();
    $this->setTitleText('Home Page');
    $this->addJSBody(new SCRIPT('/scripts/bs/bs.helper.admin.js'));
  }

  public function renderGet(): string
  {
    $this->container->append(new H1(html: 'Welcome to the Home Page'));
    $this->container->append(new A('/ncms/admin/config/db', 'DB Connection Settings', 'btn btn-primary', ));

    return $this->render();
  }
}
