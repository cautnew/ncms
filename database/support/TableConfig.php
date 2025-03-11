<?php

namespace Database\Support;

class TableConfig
{
  public static function getTableConfigs(string $config): array
  {
    $configs = [
      'triggers' => [
        'before_insert' => [
          'SET new.created_at = now();',
          'SET new.updated_at = null;'
        ],
        'before_update' => [
          'SET new.id = old.id;',
          'SET new.created_at = old.created_at;',
          'SET new.updated_at = now();'
        ]
      ]
    ];

    return $configs[$config];
  }

  public static function getFunctionConfigBeforeInsert(string $tableName, array $moreInstructions = []): string
  {
    $configs = self::getTableConfigs('triggers');
    $beforeInsert = $configs['before_insert'];

    return implode("\n", [
      "CREATE TRIGGER tg_{$tableName}_before_insert BEFORE INSERT on `{$tableName}` FOR EACH ROW BEGIN",
      ...$beforeInsert,
      ...$moreInstructions ?? [],
      "END;"
    ]);
  }

  public static function getFunctionConfigBeforeUpdate(string $tableName, array $moreInstructions = []): string
  {
    $configs = self::getTableConfigs('triggers');
    $beforeUpdate = $configs['before_update'];

    return implode("\n", [
      "CREATE TRIGGER tg_{$tableName}_before_udpate BEFORE UPDATE on `{$tableName}` FOR EACH ROW BEGIN",
      ...$beforeUpdate,
      ...$moreInstructions ?? [],
      "END;"
    ]);
  }
}
