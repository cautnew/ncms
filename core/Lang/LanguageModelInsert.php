<?php

namespace Core\Lang;

use Core\Model\ModelInsert;

class LanguageModelInsert extends ModelInsert
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(new LanguageModelTable());
    $this->setPermitedColumns([
      'var_lang', 'var_name'
    ]);
  }
}
