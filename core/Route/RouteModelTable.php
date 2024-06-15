<?php

namespace Core\Route;

use Core\Model\ModelTable;

class RouteModelTable extends ModelTable
{
  public function __construct()
  {
    $this->setColumnsDefinitions([
      "var_cid" => [
        "type" => "varchar",
        "length" => 40,
        "is_null" => false,
        "is_primary_key" => true,
      ],
      "var_path" => [
        "type" => "varchar",
        "length" => 255,
        "is_null" => false,
      ],
      'dtm_created' => [
        'type' => 'datetime',
        'default' => 'null',
        'is_null' => true
      ],
      'dtm_updated' => [
        'type' => 'datetime',
        'default' => 'null',
        'is_null' => true
      ],
      'dtm_expired' => [
        'type' => 'datetime',
        'default' => 'null',
        'is_null' => true
      ],
      'var_user_created' => [
        'type' => 'varchar',
        'length' => 40,
      ],
      'var_user_updated' => [
        'type' => 'varchar',
        'length' => 40,
      ],
      'var_user_expired' => [
        'type' => 'varchar',
        'length' => 40,
      ]
    ]);

    $this->prepareDefaultTriggers();
  }
}
