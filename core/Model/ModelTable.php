<?php

namespace Core\Model;

use Core\Model\ModelTableInformation;
use \Exception;
use \PDO;
use Core\Conn\DB;
use Core\Model\Exception\TableAlreadyExists;
use Core\Model\Exception\TableDoesntExist;
use Core\Support\Logger;
use QB\CREATE_TABLE;
use QB\CREATE_TRIGGER;
use QB\DROP_TABLE;

/**
 * ModelTable provides tools to create tables, backup it's data, restore
 * it's data and drop tables.
 * Please make sure to use this class integrated to the ModelCRUD.
 */
class ModelTable
{
  private CREATE_TABLE $createTableQuery;
  private DROP_TABLE $dropTableQuery;
  private string $schema;
  private string $tableName;
  private array $columnsDefinitions = [];
  private array $tableTriggers = [];

  public function __construct(string $schema, string $tableName)
  {
    $this->setSchema($schema);
    $this->setTableName($tableName);
  }

  public function getCreateTableQuery(): CREATE_TABLE
  {
    if (!isset($this->createTableQuery)) {
      $this->setCreateTableQuery(new CREATE_TABLE($this->getSchema(), $this->getTableName()));
    }

    return $this->createTableQuery;
  }

  public function setCreateTableQuery(CREATE_TABLE $createTableQuery): self
  {
    $this->createTableQuery = $createTableQuery;
    $this->createTableQuery->setSchema($this->getSchema());
    $this->createTableQuery->setTableName($this->getTableName());

    return $this;
  }

  public function getSchema(): string
  {
    return $this->schema;
  }

  public function setSchema(string $schema): self
  {
    $this->schema = $schema;
    return $this;
  }

  public function getTableName(): string
  {
    return $this->tableName;
  }

  public function setTableName(string $tableName): self
  {
    $this->tableName = $tableName;
    return $this;
  }

  public function getConn(): PDO
  {
    return DB::getConn();
  }

  /**
   * Set the array of columns of the table and it's definitions.
   * key = column name
   * value = [
   *   "type" => "int" | "decimal" | "double" | "boolean" | "date" | "datetime" | "timestamp" | "time" | "year" | "char" | "varchar" | "text" | "tinytext" | "mediumtext" | "longtext" | "binary" | "varbinary" | "blob" | "tinyblob" | "mediumblob" | "longblob" | "enum" | "set",
   *   "length" => int (Optional. For type varchar, default 255. For type decimal, default is 10),
   *   "comment" => string (Optional),
   *   "default" => string | int (Optional. Default is null),
   *   "is_null" => true | false (Optional. Default is true),
   *   "is_primary_key" => true | false (Optional. Default is false),
   *   "is_auto_increment" => true | false (Optional. If true type must be int. Default is false),
   *   "is_unique" => true | false (Optional. Default is false),
   *   "check" => string (Optional. Default is null)
   * ]
   */
  public function setColumnsDefinitions(array $columns): self
  {
    $this->columnsDefinitions = $columns;

    return $this;
  }

  public function setTableTriggers(array $triggers): self
  {
    $this->tableTriggers = $triggers;

    return $this;
  }

  public function setTableTrigger(string $triggerName, array $definition): self
  {
    $this->tableTriggers[$triggerName] = $definition;

    return $this;
  }

  public function tableExists(): bool
  {
    try {
      $tableInformation = new ModelTableInformation();
      return $tableInformation->tableExists($this->getSchema(), $this->getTableName());
    } catch (Exception $e) {
      Logger::regException($e);
      throw new Exception("[{$e->getCode()}] Error on checking if table exists | " . $e->getMessage());
    }
  }

  public function drop(bool $force = false): self
  {
    if (!$this->tableExists() && !$force) {
      throw new TableDoesntExist("{$this->getSchema()}.{$this->getTableName()}");
    }

    $this->dropTableQuery = new DROP_TABLE($this->getSchema(), $this->getTableName());

    try {
      $stm = $this->getConn()->prepare($this->dropTableQuery);
      $stm->execute();
    } catch (Exception $e) {
      Logger::regException($e);
      throw new Exception("[{$e->getCode()}] Error on drop table | " . $e->getMessage());
    }

    return $this;
  }

  public function dropTable(): self
  {
    return $this->drop();
  }

  public function create(bool $force = false): self
  {
    if ($this->tableExists() && !$force) {
      throw new TableAlreadyExists("{$this->getSchema()}.{$this->getTableName()}");
    }

    $this->createTableQuery = new CREATE_TABLE($this->getSchema(), $this->getTableName());
    $this->createTableQuery->setDefinitions($this->columnsDefinitions);

    try {
      $stm = $this->getConn()->prepare($this->createTableQuery);
      $stm->execute();
    } catch (Exception $e) {
      Logger::regException($e);
      throw new Exception("[{$e->getCode()}] Error on create table | " . $e->getMessage());
    }

    return $this;
  }

  public function createTable(): self
  {
    return $this->create();
  }

  /**
   * Being developed.
   */
  public function backup(): self
  {
    try {
      // $tableInformation->backup($this->getSchema(), $this->getTableName());
    } catch (Exception $e) {
      Logger::regException($e);
      throw new Exception("[{$e->getCode()}] Error on backup table | " . $e->getMessage());
    }

    return $this;
  }

  /**
   * Being developed.
   */
  public function restore(): self
  {
    try {
      // $tableInformation->restore($this->getSchema(), $this->getTableName());
    } catch (Exception $e) {
      Logger::regException($e);
      throw new Exception("[{$e->getCode()}] Error on restore table | " . $e->getMessage());
    }

    return $this;
  }

  public function recreate(): self
  {
    $this->backup();
    $this->drop();
    $this->create();

    return $this;
  }

  public function prepareDefaultTriggers(): self
  {
    $this->setTableTriggers([
      "{$this->getTableName()}_before_insert" => new CREATE_TRIGGER($this->getTableName(), "{$this->getTableName()}_before_insert", 'BEFORE', 'INSERT', <<<SQL
      BEGIN
        SET NEW.dat_created = NOW();
        SET NEW.cod_user_created = CURRENT_USER();
        SET NEW.dat_updated = NULL;
        SET NEW.cod_user_updated = NULL;
        SET NEW.dat_expired = NULL;
        SET NEW.cod_user_expired = NULL;
      END
      SQL),
      "{$this->getTableName()}_before_update" => new CREATE_TRIGGER($this->getTableName(), "{$this->getTableName()}_before_update", 'BEFORE', 'UPDATE', <<<SQL
      BEGIN
        SET NEW.dat_created = OLD.dat_created;
        SET NEW.cod_user_created = OLD.cod_user_created;
        SET NEW.dat_updated = NOW();
        SET NEW.cod_user_updated = CURRENT_USER();
      END
      SQL)
    ]);

    return $this;
  }

  public function createTriggers(): self
  {
    foreach ($this->tableTriggers as $triggerName => $trigger) {
      $stm = $this->getConn()->prepare($trigger);

      try {
        $stm->execute();
        echo $trigger;
      } catch (\Exception $e) {
        Logger::regException($e);
        throw new Exception("[{$e->getCode()}] Error on create trigger \"$triggerName\" | " . $e->getMessage());
      }
    }

    return $this;
  }
}
