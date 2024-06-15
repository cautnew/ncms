<?php

namespace Core\Model;

use Core\Conn\DB;
use Core\Model\Exception\NotAllowedColumnToInsertException;
use Core\Model\Exception\TableDoesntExist;
use Core\Support\Logger;
use QB\INSERT;
use \PDO;
use \Exception;

class ModelInsert
{
  private ModelTable $modelTable;
  private INSERT $queryInsert;

  private array $dataToInsert = [];
  private array $insertingData = [];
  private array $preparedDataToInsert = [];

  private array $columnsAllowedInsert = [];

  public const PDO_TABLE_DOESNT_EXISTS = '42S02';
  public const PDO_DUPLICATE_ENTRY = '23000';

  public function __construct(ModelTable $modelTable)
  {
    $this->setModelTable($modelTable);
  }

  public function __set(string $key, $value)
  {
    if (array_search($key, array_values($this->columnsAllowedInsert)) === false) {
      throw new NotAllowedColumnToInsertException($key);
    }

    $this->insertingData[$key] = $value;
  }

  public function __get(string $key)
  {
    if (array_search($key, array_values($this->columnsAllowedInsert)) === false) {
      throw new NotAllowedColumnToInsertException($key);
    }

    return $this->insertingData[$key] ?? null;
  }

  public function getConn(): PDO
  {
    return DB::getConn();
  }

  public function getModelTable(): ModelTable
  {
    return $this->modelTable;
  }

  public function setModelTable(ModelTable $modelTable): self
  {
    $this->modelTable = $modelTable;

    return $this;
  }

  public function getSchema(): string
  {
    return $this->getModelTable()->getSchema();
  }

  public function getTableName(): string
  {
    return $this->getModelTable()->getTableName();
  }

  public function getTable(): string
  {
    return $this->getTableName();
  }

  public function getTableAlias(): string
  {
    return $this->getModelTable()->getCommonAlias();
  }

  public function getQueryInsert(): INSERT
  {
    if (!isset($this->queryInsert)) {
      $this->queryInsert = new INSERT($this->getTableName());
      $this->getQueryInsert()->setIndAssoc(true);
    }

    return $this->queryInsert;
  }

  public function setPermitedColumns(array $columns): self
  {
    $this->columnsAllowedInsert = $columns;

    return $this;
  }

  public function isPendingData(): bool
  {
    return !empty($this->insertingData);
  }

  public function getPreparedDataToInsert(): array
  {
    return $this->preparedDataToInsert;
  }

  /**
   * Adds the pending data to the prepared data to insert.
   * Utilize method insert to adjust the data before inserting.
   * @return self
   */
  protected function preparePendingData(): self
  {
    if (!$this->isPendingData()) {
      return $this;
    }

    $this->preparedDataToInsert[] = $this->insertingData;
    $this->insertingData = [];

    return $this;
  }

  protected function adjustRowFields(array $row): array
  {
    $row[$this->getModelTable()->getPrimaryKey()] = $this->generateId(40);
    return $row;
  }

  public function generateId(int $len): string
  {
    return substr(uniqid(md5(rand())), 0, $len);
  }

  /**
   * Loads data into the model from an array.
   * $data must be an array of associative arrays.
   * Each index is a row. Each row is an associative array.
   * @param array $data
   */
  public function loadData(array $data): self
  {
    foreach ($data as $row) {
      $this->insert($row);
    }

    return $this;
  }

  public function prepareQueryInsert(): self
  {
    $this->insert();

    if (empty($this->preparedDataToInsert)) {
      return $this;
    }

    if (!isset($this->queryInsert)) {
      $this->queryInsert = new INSERT($this->getTableName());
      $this->getQueryInsert()->setIndAssoc(true);
      $this->getQueryInsert()->setColumns(array_keys($this->preparedDataToInsert[0]));
    }

    $this->getQueryInsert()->clearRows();
    $this->dataToInsert = [];
    $rows = [];

    foreach ($this->preparedDataToInsert as $key => $row) {
      foreach ($this->getQueryInsert()->getColumns() as $column) {
        $idKey = ":{$column}_{$key}";
        $this->dataToInsert[$idKey] = $row[$column] ?? '';
        $rows[$key][$column] = $idKey;
      }
    }

    $this->getQueryInsert()->addRows($rows);

    return $this;
  }

  public function insert(?array $data = null): self
  {
    if (empty($this->insertingData) && empty($data)) {
      return $this;
    }

    if ($data === null) {
      $this->insertingData = $this->adjustRowFields($this->insertingData);
      return $this->preparePendingData();
    }

    $data = $this->adjustRowFields($data);
    $this->preparedDataToInsert[] = $data;

    return $this;
  }

  public function commit(): self
  {
    $this->prepareQueryInsert();

    $stm = $this->getConn()->prepare($this->getQueryInsert());

    try {
      $stm->execute($this->dataToInsert);
    } catch (Exception $e) {
      Logger::regException($e);

      if ($e->getCode() == self::PDO_DUPLICATE_ENTRY) {
        throw new Exception("{$e->getCode()} Error on insert data | " . $e->getMessage());
      }

      if ($e->getCode() == self::PDO_TABLE_DOESNT_EXISTS) {
        $this->getModelTable()->create();
        $this->getModelTable()->createTriggers();
        return $this->commit();
      }

      throw new Exception("{$e->getCode()} Error on insert data | " . $e->getMessage());
    }

    $this->preparedDataToInsert = [];
    return $this;
  }
}
