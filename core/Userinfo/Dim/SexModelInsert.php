<?php

namespace Core\Userinfo\Dim;

use Core\Model\ModelInsert;

class SexModelInsert extends ModelInsert
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(new SexModelTable());
    $this->setPermitedColumns([
      'var_name', 'var_abrev', 'chr_cod', 'var_lang'
    ]);
  }
}
