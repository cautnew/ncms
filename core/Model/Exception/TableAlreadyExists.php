<?php

namespace Core\Model\Exception;

use \Exception;

class TableAlreadyExists extends Exception
{
  public function __construct($table)
  {
    parent::__construct("Table '{$table}' already exists.");
  }
}
