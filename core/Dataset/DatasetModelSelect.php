<?php

namespace Core\Dataset;

use Core\Model\ModelSelect;
use Core\Dataset\DatasetModelTable;

class DatasetModelSelect extends ModelSelect
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(new DatasetModelTable());
  }
}
