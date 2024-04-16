<?php

namespace Core\Support;

use Core\Conn\ModelCRUD;
use Exception;

class Param extends ModelCRUD
{
  protected bool $indCanSelect = true;
  protected bool $indCanInsert = false;
  protected bool $indCanUpdate = true;
  protected bool $indCanDelete = false;

	protected string $table = 'syslefe.aux_param';
	protected string | null $colId = 'cod_param';

  protected array $columnsPermittedSelect = [
    'COD_PARAM' => 'COD_PARAM',
    'COD_TIPO_PARAM' => 'COD_TIPO_PARAM',
    'COD_GRUPO_PARAM' => 'COD_GRUPO_PARAM',
    'ALIAS_PARAM' => 'ALIAS_PARAM',
    'DSC_PARAM' => 'DSC_PARAM',
    'VAL' => 'IFNULL(`VAL_PARAM`, `VAL_PARAM_LONG`)'
  ];
  protected array $columnsUpdatable = [
    'COD_TIPO_PARAM' => ':COD_TIPO_PARAM',
    'COD_GRUPO_PARAM' => ':COD_GRUPO_PARAM',
    'DSC_PARAM' => ':DSC_PARAM',
    'VAL_PARAM' => ':VAL_PARAM',
    'VAL_PARAM_LONG' => ':VAL_PARAM_LONG'
  ];
  protected array $columnsProtected = ['cod_param', 'dat_criacao', 'dat_alteracao'];
  protected array $defaultSelectParams = [];
  protected array $defaultUpdateParams = [];

	private const MAX_LEN_COL_VARCHAR = 300;

	public function __get($param)
	{
		return $this->get($param);
	}

	public function __set($param, $value): void
	{
		$this->set($param, $value);
	}

	public function __isset($param): bool
	{
		return $this->has($param);
	}

  protected function setPersistentUpdateConditions(): void
  {
    $this->getQueryUpdate()->clearConditions();
  }

  protected function findById($id, array $columns = null): self
  {
    $id = (int) $id;

    $this->setPersistentSelectConditions();
    $this->getQuerySelect()->addConditionAnd([$this->getColId() . '=:cod_id']);

    return $this->find(['cod_id' => $id], $columns);
  }

  protected function findByAlias(string $id, array $columns = null): self
  {
    $this->setPersistentSelectConditions();
    $this->getQuerySelect()->addConditionAnd(['alias_param=:alias_param']);

    return $this->find(['alias_param' => $id], $columns);
  }

  protected function setPersistentSelectConditions(): void
  {
    $this->getQuerySelect()->clearConditions();
  }

	public function has(string | int $param): bool
	{
		if (empty($param)) {
			throw new Exception('Não foram passados parâmetros suficientes.');
		}

    if (gettype($param) === 'integer') {
      $this->findById($param);
    } else {
      $this->findByAlias($param);
    }

    return ($this->count() > 0);
	}

	public function get($param)
	{
		if (!$this->has($param)) {
			return null;
		}

    $columns = ['VAL' => 'IFNULL(`VAL_PARAM`, `VAL_PARAM_LONG`)'];

    if (gettype($param) === 'integer') {
      $this->findById($param, $columns);
    } else {
      $this->findByAlias($param, $columns);
    }

		return $this->fetch()->getCurrentData()->VAL;
	}

	public function set(string $param, string $value): bool
	{
		if (!$this->has($param)) {
			throw new Exception('Parâmetro inexistente.');
		}

    $this->findByAlias($param)->fetch();

		try {
			if (strlen($value) > self::MAX_LEN_COL_VARCHAR) {
        $this->getCurrentData()->VAL_PARAM = null;
        $this->getCurrentData()->VAL_PARAM_LONG = $value;
			} else {
        $this->getCurrentData()->VAL_PARAM = $value;
        $this->getCurrentData()->VAL_PARAM_LONG = null;
      }

      $this->save();
		} catch (Exception $e) {
			return false;
		}

		return true;
	}

	private function add(string $param, int $num = 1): int | null
	{
		if (!$this->has($param)) {
			throw new Exception('Parâmetro inexistente.');
		}

    $this->setPersistentUpdateConditions();
    $this->getQueryUpdate()->addConditionAnd([$this->getColId() . '=:COD_PARAM']);
    $this->paramsUpdate = [
      ':COD_PARAM' => $this->getCurrentData()->COD_PARAM
    ];

    $this->update(['VAL_PARAM' => "(VAL_PARAM+({$num}))"]);

		return (int) $this->get($param);
	}

	public function increase(string $param): int | null
	{
		return $this->add($param);
	}

	public function decrease(string $param): int | null
	{
		return $this->add($param, -1);
	}

	public function inc(string $param): int | null
	{
		return $this->increase($param);
	}

	public function dec(string $param): int | null
	{
		return $this->decrease($param);
	}

	public function is(string $param): bool
	{
		return ($this->get($param) == 1);
	}
}
