<?php

namespace Core\Userinfo\Dim;

use Core\Model\ModelSelect;

class SexModelSelect extends ModelSelect
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(new SexModelTable());
  }
}
