<?php

namespace Core\Conn\Exception;

use \Exception;

class TableAlreadyExists extends Exception
{
  public function __construct()
  {
    parent::__construct("Table already exists.");
  }
}
