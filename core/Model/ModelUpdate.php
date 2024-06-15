<?php

namespace Core\Model;

use Core\Conn\DB;
use Core\Model\Exception\NotAllowedColumnToUpdateException;
use Core\Support\Logger;
use QB\UPDATE;
use \PDO;
use \Exception;

class ModelUpdate
{
  private ModelTable $modelTable;
  private UPDATE $queryUpdate;

  private array $updatingData = [];
  private array $preparedDataToUpdate = [];

  private array $columnsAllowedUpdate = [];

  public const PDO_TABLE_DOESNT_EXISTS = 1051;

  public function __construct(ModelTable $modelTable)
  {
    $this->setModelTable($modelTable);
  }

  public function __set(string $key, $value)
  {
    if (array_search($key, array_values($this->columnsAllowedUpdate)) === false) {
      throw new NotAllowedColumnToUpdateException($key);
    }

    $this->updatingData[$key] = $value;
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

  public function getQueryUpdate(): UPDATE
  {
    return $this->queryUpdate;
  }

  public function getColumnsAllowedUpdate(): array
  {
    return $this->columnsAllowedUpdate;
  }

  public function isPendingData(): bool
  {
    return !empty($this->updatingData);
  }

  public function getPreparedDataToInsert(): array
  {
    return $this->preparedDataToUpdate;
  }

  public function preparePendingData(): self
  {
    $this->preparedDataToUpdate[] = $this->updatingData;
    $this->updatingData = [];

    return $this;
  }

  public function update(): self
  {
    $this->preparedDataToUpdate[] = $this->getCurrentData();

    return $this;
  }

  public function commit(): self
  {
    $this->preparePendingData();

    if (empty($this->preparedDataToUpdate)) {
      return $this;
    }

    $setList = [];
    foreach ($this->getColumnsAllowedUpdate() as $column => $type) {
      $setList[$column] = ":{$column}";
    }

    if (!isset($this->queryUpdate)) {
      $this->queryUpdate = new UPDATE($this->getTableName(), $this->getTableAlias());
      $this->queryUpdate->limit(1);
      $this->queryUpdate->addSetList($setList);
      $this->queryUpdate->addCondition("{$this->getModelTable()->getPrimaryKey()}=:{$this->getModelTable()->getPrimaryKey()}");
    }

    foreach ($this->preparedDataToUpdate as $row) {
      $data = [];
      foreach ($row as $column => $value) {
        if (in_array($column, array_keys($setList)) || $column == $this->getModelTable()->getPrimaryKey()) {
          $data[":{$column}"] = $value;
        }
      }

      $stm = $this->getConn()->prepare($this->queryUpdate);

      try {
        $stm->execute($data);
      } catch (Exception $e) {
        Logger::regException($e);
        throw new Exception("{$e->getCode()} Error on update data | " . $e->getMessage());
      }
    }

    $this->preparedDataToUpdate = [];

    return $this;
  }

  public function save(): self
  {
    $this->update();
    $this->commit();

    return $this;
  }
}
