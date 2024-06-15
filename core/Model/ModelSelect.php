<?php

namespace Core\Model;

use Core\Conn\DB;
use Core\Support\Logger;
use QB\SELECT;
use QB\CONDITION as COND;
use \Exception;
use \PDO;
use stdClass;

class ModelSelect
{
  private ModelTable $modelTable;
  private SELECT $querySelect;
  protected int $fetchMode = PDO::FETCH_DEFAULT;

  /**
   * Array with the selected data from the fetch operation.
   * @var array $selectedData
   */
  private array $selectedData = [];

  private array $selectedColumns = [];

  private int $page = 0;
  private int $rowsLimit = 0;
  private int $offset = 0;

  private int $currentIndex = 0;
  private int $maxIndex = 0;

  /**
   * Associative array with the columns of the table.
   * key = column name
   * value = column type
   * @var array $columns
   */
  private array $columns = [];

  /**
   * Associative array with the columns to the columns alias of the table.
   * key = column name
   * value = alias
   * @var array $columnsAlias
   */
  private array $columnsAlias = [];

  /**
   * Matrix with the relationships of the columns of the table.
   * key = column name
   * value = [
   *   'type': 'left' | 'inner',
   *   'table': string | ModelCRUD,
   *   'alias': string (optional, ignored if table is ModelCRUD),
   *   'condition': string (ignored if table is ModelCRUD)
   * ]
   * @var array $columnsRelationships
   */
  private array $columnsRelationships = [];

  public function __construct(ModelTable $modelTable)
  {
    $this->setModelTable($modelTable);
  }

  public function __get(string $key)
  {
    return $this->get($key);
  }

  public function get(string $key)
  {
    return $this->getCurrentData()->$key;
  }

  public function getCurrentData()
  {
    return $this->selectedData[$this->getCurrentIndex()];
  }

  public function getCurrentIndex(): int
  {
    return $this->currentIndex ?? 0;
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

  public function getConn(): PDO
  {
    return DB::getConn();
  }

  public function getFetchMode(): int
  {
    if (!isset($this->fetchMode)) {
      $this->setFechMode(PDO::FETCH_DEFAULT);
    }

    return $this->fetchMode;
  }

  public function setFechMode(int $fetchMode): self
  {
    $this->fetchMode = $fetchMode;

    return $this;
  }

  /**
   * Get the limit of rows to be selected.
   */
  public function getRowsLimit(): ?int
  {
    if (empty($this->rowsLimit)) {
      return null;
    }

    return $this->rowsLimit;
  }

  /**
   * Set the limit of rows to be selected.
   */
  public function setRowsLimit(int $rowsLimit): self
  {
    $this->rowsLimit = $rowsLimit;

    return $this;
  }

  public function getOffset(): ?int
  {
    if (empty($this->offset)) {
      return null;
    }

    return $this->offset;
  }

  public function setOffset(int $offset): self
  {
    $this->offset = $offset;

    return $this;
  }

  public function getQuerySelect(): SELECT
  {
    if (!isset($this->querySelect)) {
      $tableName = $this->getModelTable()->getSchema() . "." . $this->getModelTable()->getTableName();
      $this->setQuerySelect(new SELECT($tableName, $this->getModelTable()->getCommonAlias()));
    }

    return $this->querySelect;
  }

  public function setQuerySelect(SELECT $querySelect): self
  {
    $this->querySelect = $querySelect;

    return $this;
  }

  /**
   * Adds a LEFT JOIN to the query according to the column in this current
   * table referenced by the primary key in the passed model.
   * @param string $column
   * @param ModelCRUD $model
   */
  private function addJoinFromModel(string $column, ModelTable $model): void
  {
    $columnLocal = "{$this->getTableAlias()}.{$column}";
    $columnReference = "{$model->getTableAlias()}.{$model->getPrimaryKey()}";
    $condition = (new COND($columnLocal))->equals($columnReference);

    $this->getQuerySelect()
      ->join('LEFT', $model->getTableName(), $this->getTableAlias(), $condition);
  }

  private function prepareColumnsQuerySelect(): void
  {
    $this->getQuerySelect()->setColumns(array_values($this->columns));

    if (!empty($this->columnsAlias)) {
      $this->getQuerySelect()->setColumnsAliases($this->columnsAlias);
    }
  }

  private function prepareJoinsQuerySelect(): void
  {
    foreach ($this->columnsRelationships as $column => $relationship) {
      if ($relationship instanceof ModelTable) {
        $this->addJoinFromModel($column, $relationship);
        continue;
      }

      $this->getQuerySelect()
        ->join($relationship['type'], $relationship['table'], $relationship['alias'], $relationship['condition']);
    }
  }

  /**
   * Prepare the conditions of the query select.
   * Add here all the conditions that should always be applied to the select query.
   * For exemple, conditions for valid values, or only for one type of user, etc.
   * @return void
   */
  protected function prepareConditionsQuerySelect(): void
  {
    $this->getQuerySelect()->where((new COND('1'))->equals('1'));
  }

  protected function prepareQuerySelect(): void
  {
    if ($this->getRowsLimit() != null) {
      $this->getQuerySelect()->limit($this->getRowsLimit());
    }

    if ($this->getOffset() != null) {
      $this->getQuerySelect()->offset($this->getOffset());
    }

    $this->prepareColumnsQuerySelect();
    $this->prepareJoinsQuerySelect();
    $this->prepareConditionsQuerySelect();
  }

  /**
   * Set the next point to the next row of the selected data.
   * It's possible to iterate in $this until the end of the selected data
   * @return self|null
   */
  public function next(): ?self
  {
    $this->currentIndex++;

    if ($this->currentIndex >= $this->numSelectedRows()) {
      $this->currentIndex = 0;
      return null;
    }

    return $this;
  }

  /**
   * Set the next point to the previous row of the selected data.
   * It's possible to iterate in $this until the beginning of the selected data
   * @return self|null
   */
  public function prev(): ?self
  {
    $this->currentIndex--;

    if ($this->currentIndex < 0) {
      $this->currentIndex = 0;
      return null;
    }

    return $this;
  }

  /**
   * Returns the count of selected rows.
   * @return int
   */
  public function numSelectedRows(): int
  {
    return $this->maxIndex ?? 0;
  }

  protected function clearSelectedData(): self
  {
    $this->selectedData = [];
    $this->currentIndex = 0;
    $this->maxIndex = 0;
    $this->offset = 0;
    $this->page = 0;

    return $this;
  }

  /**
   * Loads the data into the model from an array.
   * @param array $data
   * Each index is a row of the table formatted as an associative array
   * as described in the array $this->columns.
   * @return self
   */
  public function loadData(array $data): self
  {
    $this->clearSelectedData();

    if (empty($data)) {
      return $this;
    }

    $this->selectedData = $data;
    $this->selectedColumns = array_keys(json_decode(json_encode($this->selectedData[0]), true));
    $this->currentIndex = 0;
    $this->maxIndex = count($this->selectedData);

    return $this;
  }

  public function getData(): stdClass
  {
    return $this->getCurrentData();
  }

  public function selectById(string $id): self
  {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND($this->getModelTable()->getPrimaryKey()))->equals("'$id'"));

    return $this;
  }

  public function select(): self
  {
    if (!isset($this->querySelect)) {
      $this->prepareQuerySelect();
    }

    $stm = $this->getConn()->query($this->querySelect);

    try {
      $stm->execute();
    } catch (Exception $e) {
      $this->clearSelectedData();
      Logger::regException($e);
      return $this;
    }

    $this->loadData($stm->fetchAll($this->getFetchMode()));

    return $this;
  }
}
