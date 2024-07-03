<?php

namespace Core\Model;

use Core\Model\ModelTableInformation;
use \Exception;
use \PDO;
use Core\Conn\DB;
use Core\Conn\Exception\TableAlreadyExists;
use Core\Model\Exception\TableDoesntExist;
use Core\Support\Logger;
use PDOException;
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
  private string $commonAlias;
  private array $columnsDefinitions = [];
  private array $tableTriggers = [];

  public function __construct(string $schema, string $tableName, ?string $commonAlias = null)
  {
    $this->setSchema($schema);
    $this->setTableName($tableName);
    $this->setCommonAlias($commonAlias);
    $this->prepareDefaultTriggers();
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

  public function getCommonAlias(): string
  {
    if (!isset($this->commonAlias)) {
      return $this->getTableName();
    }

    return $this->commonAlias;
  }

  public function setCommonAlias(string $commonAlias): self
  {
    $this->commonAlias = $commonAlias;
    return $this;
  }

  public function getConn(): PDO
  {
    return DB::getConn();
  }

  public function getPrimaryKey(): string
  {
    foreach ($this->columnsDefinitions as $columnName => $columnDefinition) {
      if (isset($columnDefinition['is_primary_key']) && $columnDefinition['is_primary_key']) {
        return $columnName;
      }
    }

    return 'var_cid';
  }

  public function getColumnTableReference(string $columnName): ?ModelTable
  {
    if (!isset($this->columnsDefinitions[$columnName]['table_reference'])) {
      return null;
    }

    return $this->columnsDefinitions[$columnName]['table_reference'];
  }

  public function getColumnsTableReference(): array
  {
    $references = [];
    foreach ($this->getColumnsDefinitions() as $columnName => $columnDefinition) {
      if (isset($columnDefinition['table_reference'])) {
        $references[$columnName] = $columnDefinition['table_reference'];
      }
    }

    return $references;
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
  public function getColumnsDefinitions(): array
  {
    return $this->columnsDefinitions;
  }

  public function setTableTriggers(array $triggers): self
  {
    foreach ($triggers as $triggerName => $definition) {
      $this->setTableTrigger($triggerName, $definition);
    }

    return $this;
  }

  public function setTableTrigger(string $triggerName, ?CREATE_TRIGGER $definition): self
  {
    if ($definition === null) {
      unset($this->tableTriggers[$triggerName]);

      return $this;
    }

    $this->tableTriggers[$triggerName] = $definition;

    return $this;
  }

  public function addTableTrigger(string $triggerName, CREATE_TRIGGER $definition): self
  {
    return $this->setTableTrigger($triggerName, $definition);
  }

  /**
   * Add a foreign key to this table.
   * @param string $column
   * The column that will be the foreign key.
   * @param ModelTable|string $table_source
   * The table that will be the source of the foreign key. Prioritize the ModelTable instance.
   * @param string $column_source
   * The column that will be the source of the foreign key.
   */
  public function addForeignKey(string $column, ModelTable | string $table_source, string $column_source): self
  {
    if ($table_source instanceof ModelTable) {
      $table_source = $table_source->getTableName();
    }

    $fkName = "fk_{$this->getTableName()}_{$column}_{$table_source}_{$column_source}";
    $this->getCreateTableQuery()->addForeignKey($fkName, $column, $table_source, $column_source);

    return $this;
  }

  public function tableExists(): bool
  {
    /*try {
      $tableInformation = new ModelTableInformation();
      return $tableInformation->tableExists($this->getSchema(), $this->getTableName());
    } catch (Exception $e) {
      Logger::regException($e);
      throw new Exception("[{$e->getCode()}] Error on checking if table exists | " . $e->getMessage());
    }*/
    return false;
  }

  public function addColumn(string $columnName, array $definition): self
  {
    $this->columnsDefinitions[$columnName] = $definition;
    return $this;
  }

  public function removeColumn(string $columnName): self
  {
    DB::exec("ALTER TABLE {$this->getSchema()}.{$this->getTableName()} DROP COLUMN {$columnName}");
    return $this;
  }

  public function drop(bool $force = false): self
  {
    if (!$this->tableExists() && !$force) {
      throw new TableDoesntExist();
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
      throw new TableAlreadyExists();
    }

    $this->getCreateTableQuery()->setDefinitions($this->columnsDefinitions);

    try {
      $stm = DB::exec($this->getCreateTableQuery());
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
    try {
      $this->drop(true);
    } catch (TableDoesntExist $e) {
      Logger::regException($e, "Table doesn't exists. Proceeding to create it.");
    } catch (Exception $e) {
      Logger::regException($e);
      throw new Exception("[{$e->getCode()}] Error on recreate table | " . $e->getMessage());
    }

    $this->create(true);

    return $this;
  }

  public function prepareDefaultTriggers(): self
  {
    $this->setTableTriggers([
      "{$this->getTableName()}_before_insert" => new CREATE_TRIGGER($this->getTableName(), "{$this->getTableName()}_before_insert", 'BEFORE', 'INSERT', <<<SQL
      BEGIN
        SET NEW.dtm_created = NOW();
        SET NEW.var_user_created = CURRENT_USER();
        SET NEW.dtm_updated = NULL;
        SET NEW.var_user_updated = NULL;
        SET NEW.dtm_expired = NULL;
        SET NEW.var_user_expired = NULL;
      END
      SQL),
      "{$this->getTableName()}_before_update" => new CREATE_TRIGGER($this->getTableName(), "{$this->getTableName()}_before_update", 'BEFORE', 'UPDATE', <<<SQL
      BEGIN
        SET NEW.dtm_created = OLD.dtm_created;
        SET NEW.var_user_created = OLD.var_user_created;
        SET NEW.dtm_updated = NOW();
        SET NEW.var_user_updated = CURRENT_USER();
      END
      SQL)
    ]);

    return $this;
  }

  public function createTriggers(): self
  {
    if (empty($this->tableTriggers)) {
      $this->prepareDefaultTriggers();
    }

    foreach ($this->tableTriggers as $triggerName => $trigger) {
      try {
        DB::exec($trigger);
      } catch (PDOException $e) {
        Logger::regException($e);
        throw new Exception("[{$e->getCode()}] Error on create trigger \"$triggerName\" | " . $e->getMessage());
      }
    }

    return $this;
  }
}
