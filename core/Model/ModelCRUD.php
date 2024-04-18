<?php

namespace Core\Model;

use \PDO;
use Core\Conn\DB;
use Core\Support\Logger;
use Core\Model\Exception\NotAllowedColumnToInsertException;
use Cautnew\QB\CONDITION as COND;
use Cautnew\QB\SELECT;
use Cautnew\QB\INSERT;
use Cautnew\QB\UPDATE;
use Cautnew\QB\DELETE;
use \Exception;

/**
 * Class to manage CRUD operations on the database
 */
class ModelCRUD {
  private bool $indAllowDelete = true;
  private bool $indAllowInsert = true;
  private bool $indAllowSelect = true;
  private bool $indAllowUpdate = true;

  private bool $insertingMode = false;

  /**
   * Table name.
   * @var string $table
   */
  private string $table;

  /**
   * Table name alias.
   * @var string $aliasTableName
   */
  private string $aliasTableName;

  /**
   * Primary key of the table.
   * value = column name
   * @var string $primaryKey
   */
  private string $primaryKey;

  /**
   * Associative array with the columns of the table.
   * key = column name
   * value = column type
   * @var array $columns
   */
  private array $columns = [];

  private array $columnsSelect = [];
  private array $selectedColumns = [];

  /**
   * Associative array with the alias for the columns of the table.
   * key = alias
   * value = column name
   * @var array $aliasColumns
   */
  private array $aliasColumns = [];

  /**
   * Associative array with the columns to the columns alias of the table.
   * key = column name
   * value = alias
   * @var array $columnsAlias
   */
  private array $columnsAlias = [];

  private array $columnsAllowInsert = [];
  private array $columnsAllowUpdate = [];

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

  /**
   * Array with the selected data from the fetch operation.
   * @var array $selectedData
   */
  private array $selectedData = [];

  private array $insertingData = [];
  private array $preparedDataToInsert = [];

  private int $fetchMode = PDO::FETCH_DEFAULT;

  private int $page = 0;
  private int $rowsLimit = 0;
  private int $offset = 0;

  private int $currentIndex = 0;
  private int $maxIndex = 0;

  private bool $commited = false;
  private bool $limitedSelect = true;

  private DELETE $queryDelete;
  private INSERT $queryInsert;
  private SELECT $querySelect;
  private UPDATE $queryUpdate;

  public function __construct(string $table, string $aliasTableName) {
    $this->setTableName($table, $aliasTableName);
  }

  public function __get(string $key) {
    if ($this->isInsertingMode()) {
      if (array_search($key, array_keys($this->columnsAllowInsert)) === false) {
        throw new Exception\NotAllowedColumnToInsertException($key);
      }

      return $this->insertingData[$key];
    }

    return $this->selectedData[$this->currentIndex]->$key;
  }

  public function __set(string $key, $value) {
    if ($this->isInsertingMode()) {
      if (array_search($key, array_keys($this->columnsAllowInsert)) === false) {
        throw new NotAllowedColumnToInsertException($key);
      }

      $this->insertingData[$key] = $value;
      return;
    }

    $this->selectedData[$this->currentIndex]->$key = $value;
  }

  public function getConn(): PDO {
    return DB::getConn();
  }

  public function getTableName(): string {
    return $this->table;
  }

  public function getTableAlias(): string {
    return $this->aliasTableName;
  }

  public function setTableName(string $table, ?string $tableAlias=null): self {
    $this->table = $table;

    if (!empty($tableAlias)) {
      $this->setTableAlias($tableAlias);
    }

    return $this;
  }

  public function setTableAlias(string $tableAlias): self {
    $this->aliasTableName = $tableAlias;

    return $this;
  }

  public function setPrimaryKey(string $primaryKey): self {
    $this->primaryKey = $primaryKey;

    return $this;
  }

  public function getPrimaryKey(): ?string {
    return $this->primaryKey;
  }

  /**
   * Set the array of columns of the table.
   * key = column name
   * value = column type
   */
  public function setColumns(array $columns): self {
    $this->columns = $columns;

    return $this;
  }

  public function getSelectedColumns(): array {
    return $this->selectedColumns;
  }

  /**
   * Set the array of aliases for the columns of the table.
   * key = alias
   * value = column name
   * @param array $columnsAlias
   */
  public function setColumnsAlias(array $columnsAlias): self {
    $this->aliasColumns = $columnsAlias;
    $this->columnsAlias = array_flip($columnsAlias);

    return $this;
  }

  /**
   * Set the array of columns allowed to insert data in the table.
   * value = column name
   * @param array $columnsAllowInsert
   */
  public function setColumnsAllowInsert(array $columnsAllowInsert): self {
    $this->columnsAllowInsert = $columnsAllowInsert;

    return $this;
  }

  /**
   * Set the array of columns allowed to update data in the table.
   * value = column name
   * @param array $columnsAllowUpdate
   */
  public function setColumnsAllowUpdate(array $columnsAllowUpdate): self {
    $this->columnsAllowUpdate = $columnsAllowUpdate;

    return $this;
  }

  /**
   * Set the limit of rows to be selected.
   */
  public function setRowsLimit(int $rowsLimit): self {
    $this->rowsLimit = $rowsLimit;

    return $this;
  }

  public function getRowsLimit(): ?int {
    if (empty($this->rowsLimit)) {
      return null;
    }

    return $this->rowsLimit;
  }

  public function setOffset(int $offset): self {
    $this->offset = $offset;

    return $this;
  }

  public function getOffset(): ?int {
    if (empty($this->offset)) {
      return null;
    }

    return $this->offset;
  }

  /**
   * Set the next point to the next row of the selected data.
   * It's possible to iterate in $this until the end of the selected data
   * @return self|null
   */
  public function next(): ?self {
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
  public function prev(): ?self {
    $this->currentIndex--;

    if ($this->currentIndex < 0) {
      $this->currentIndex = 0;
      return null;
    }

    return $this;
  }

  public function getPage(): int {
    return $this->page;
  }

  public function setPage(int $page): self {
    $this->page = $page;

    return $this;
  }

  public function nextPage(): ?self {
    $this->setPage($this->getPage() + 1);
    $this->setOffset((int) $this->getRowsLimit() * $this->page);
    $this->select();

    if ($this->numSelectedRows() == 0) {
      $this->page = 0;
      $this->offset = 0;
      return null;
    }

    return $this;
  }

  public function getCurrentIndex(): int {
    return $this->currentIndex ?? 0;
  }

  /**
   * Returns the count of selected rows.
   * @return int|null
   */
  public function numSelectedRows(): int {
    return $this->maxIndex ?? 0;
  }

  public function setFechMode(int $fetchMode): self {
    $this->fetchMode = $fetchMode;

    return $this;
  }

  public function getFetchMode(): int {
    if (!isset($this->fetchMode)) {
      $this->setFechMode(PDO::FETCH_DEFAULT);
    }

    return $this->fetchMode;
  }

  public function setCommited(bool $commited = true): self {
    $this->commited = $commited;

    return $this;
  }

  public function isCommited(): bool {
    return $this->commited;
  }

  public function setLimitedSelect(bool $limitedSelect = true): self {
    $this->limitedSelect = $limitedSelect;

    return $this;
  }

  public function isLimitedSelect(): bool {
    return $this->limitedSelect;
  }

  public function isInsertingMode(): bool {
    return $this->insertingMode;
  }

  /**
   * Set the relationships of the columns of the table.
   * @param array $relationships
   * key = column name
   * value = [
   *   'type': 'left' | 'inner',
   *   'table': string | ModelCRUD,
   *   'alias': string (optional, ignored if table is ModelCRUD),
   *   'condition': string (ignored if table is ModelCRUD)
   * ]
   */
  public function setColumnsRelationships(array $relationships): self {
    $this->columnsRelationships = $relationships;

    return $this;
  }

  /**
   * Add a relationship of the columns of the table.
   * @param string $column
   * @param array $relationship
   * [
   *   'type': 'left' | 'inner',
   *   'table': string | ModelCRUD,
   *   'alias': string (optional, ignored if table is ModelCRUD),
   *   'condition': string (ignored if table is ModelCRUD)
   * ]
   */
  public function addColumnRelationship(string $column, array $relationship): self {
    $this->columnsRelationships[$column] = $relationship;

    return $this;
  }

  public function getQuerySelect(): SELECT {
    if (!isset($this->querySelect)) {
      $this->querySelect = new SELECT($this->getTableName(), $this->getTableAlias());
    }

    return $this->querySelect;
  }

  private function prepareColumnsQuerySelect(): void {
    $this->getQuerySelect()->setColumns(array_keys($this->columns));
    if (!empty($this->columnsAlias)) {
      $this->getQuerySelect()->setColumnsAliases($this->columnsAlias);
    }
  }

  private function addJoinFromModel(ModelCRUD $model, string $column): void {
    //
  }

  private function prepareJoinsQuerySelect(): void {
    foreach ($this->columnsRelationships as $column => $relationship) {
      if ($relationship['table'] instanceof ModelCRUD) {
        $this->addJoinFromModel($relationship, $column);
        continue;
      }

      $this->getQuerySelect()->join($relationship['type'], $relationship['table'], $relationship['alias'], $relationship['condition']);
    }
  }

  /**
   * Prepare the conditions of the query select.
   * Add here all the conditions that should always be applied to the select query.
   * For exemple, if you want valid values, or only for one type of user, etc.
   * @return void
   */
  protected function prepareConditionsQuerySelect(): void {
    $this->getQuerySelect()->where((new COND('1'))->equals('1'));
  }

  protected function prepareQuerySelect(): void {
    if ($this->getrowsLimit() != null) {
      $this->getQuerySelect()->limit($this->getrowsLimit());
    }

    if ($this->getOffset() != null) {
      $this->getQuerySelect()->offset($this->getOffset());
    }

    $this->prepareColumnsQuerySelect();
    $this->prepareJoinsQuerySelect();
    $this->prepareConditionsQuerySelect();
  }

  public function findById(string $id): self {
    $this->prepareQuerySelect();
    $this->getQuerySelect()->getCondition()
      ->and((new COND($this->getPrimaryKey()))->equals("'$id'"));

    return $this;
  }

  protected function clearSelectedData(): self {
    $this->selectedData = [];
    $this->currentIndex = 0;
    $this->maxIndex = 0;
    $this->offset = 0;
    $this->page = 0;

    return $this;
  }

  public function loadData(array $data): self {
    $this->clearSelectedData();
    $this->selectedData = $data;
    $this->selectedColumns = array_keys(json_decode(json_encode($this->selectedData[0]), true));
    $this->currentIndex = 0;
    $this->maxIndex = count($this->selectedData);

    return $this;
  }

  public function select(): self {
    if (!isset($this->querySelect)) {
      $this->prepareQuerySelect();
    }

    $stm = $this->getConn()->query($this->querySelect);

    try {
      $stm->execute();
    } catch (\Exception $e) {
      $this->clearSelectedData();
      Logger::regException($e);
      return $this;
    }

    $this->loadData($stm->fetchAll($this->getFetchMode()));

    return $this;
  }

  public function setInsertingMode(bool $isInsertingMode = true): self {
    $this->insertingMode = $isInsertingMode;

    return $this;
  }

  public function insert(?array $data = null): self {
    if (empty($this->insertingData) && empty($data)) {
      return $this;
    }

    $this->preparedDataToInsert[] = ($data === null) ? $this->insertingData : $data;
    
    $this->insertingData = [];

    return $this;
  }

  public function insertFromCurrentData(): self {
    $this->preparedDataToInsert[] = json_decode(json_encode($this->getCurrentData()), true);

    return $this;
  }

  public function commitInsert(): self {
    if (empty($this->preparedDataToInsert)) {
      return $this;
    }

    $this->queryInsert = new INSERT($this->getTableName());
    $this->queryInsert->setIndAssoc(true);
    $this->queryInsert->setColumns(array_keys($this->preparedDataToInsert[0]));
    $data = [];
    $rows = [];

    foreach($this->preparedDataToInsert as $key => $row) {
      $idKey = ":{$this->getPrimaryKey()}_{$key}";
      $data[$idKey] = uniqid(md5(rand()), true);
      $rows[$key][$this->getPrimaryKey()] = $idKey;
      foreach($this->queryInsert->getColumns() as $column) {
        $idKey = ":{$column}_{$key}";
        $data[$idKey] = $row[$column];
        $rows[$key][$column] = $idKey;
      }
    }

    $this->queryInsert->addRows($rows);
    $stm = $this->getConn()->prepare($this->queryInsert);

    try {
      $stm->execute($data);
    } catch (\Exception $e) {
      Logger::regException($e);
      throw new Exception('Error on insert data | ' . $e->getMessage(), $e->getCode());
    }

    $this->preparedDataToInsert = [];
    return $this;
  }

  public function update(): self {
    return $this;
  }

  public function commitUpdate(): self {
    return $this;
  }

  public function delete(): self {
    return $this;
  }

  public function commitDelete(): self {
    return $this;
  }

  public function commit(): self {
    /**
     * @todo Implement this method with the following order:
     *  1 - Insert
     *  2 - Update
     *  3 - Delete
     */

    $this->commitInsert();
    $this->commitUpdate();
    $this->commitDelete();

    return $this;
  }
}
