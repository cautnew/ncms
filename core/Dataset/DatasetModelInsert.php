<?php

namespace Core\Dataset;

use Core\Model\ModelInsert;
use Core\Dataset\DatasetModelTable;

class DatasetModelInsert extends ModelInsert
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(new DatasetModelTable());
    $this->setColumnsAllowedInsert([
      'var_name',
      'var_controller',
      'txt_description',
      'bol_enabled',
      'bol_admin',
      'bol_system'
    ]);
  }
}
