<?php

namespace Core\Model\Exception;

use \Exception;

class TableDoesntExist extends Exception
{
  public function __construct()
  {
    parent::__construct("Table does not exist.");
  }
}
