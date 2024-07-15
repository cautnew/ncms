<?php

namespace Core\Dataset;

use Core\Model\ModelUpdate;
use Core\Dataset\DatasetModelTable;

class DatasetModelUpdate extends ModelUpdate
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(new DatasetModelTable());
    $this->setColumnsAllowedUpdate([
      'var_name',
      'var_controller',
      'txt_description',
      'bol_enabled',
      'bol_admin',
      'bol_system'
    ]);
  }
}
