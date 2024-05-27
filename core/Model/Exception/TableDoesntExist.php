<?php

namespace Core\Model\Exception;

use \Exception;

class TableDoesntExist extends Exception
{
  public function __construct($table)
  {
    parent::__construct("Table '{$table}' does not exist.");
  }
}
