<?php

namespace Core\Model\Exception;

use \Exception;

class NotAllowedColumnToUpdateException extends Exception
{
  public function __construct($column)
  {
    parent::__construct("Column '{$column}' is not allowed to update.");
  }
}
