<?php

namespace Core\Conn\Exception;

use \Exception;

class QueryError extends Exception
{
  public function __construct(Exception $previous)
  {
    parent::__construct("Query Error during execution.", $previous->getCode(), $previous);
  }
}
