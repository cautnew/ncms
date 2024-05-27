<?php

namespace Core\Model\Finance;

use QB\CONDITION as COND;
use Core\Model\ModelCRUD;
use Boot\Constants\Constant as CNT;
use QB\CREATE_TRIGGER;

class ModelDespesa extends ModelCrud
{
  protected string $version = '0.0.1';

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME . '.fp_despesa', 'desp');

    $this->setColumnsDefinitions([
      'cod_despesa' => [
        'type' => 'varchar',
        'length' => '40',
        'is_primary_key' => true
      ],
      'cod_user' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => false
      ],
      'dat_created' => [
        'type' => 'datetime',
        'is_null' => false
      ],
      'dat_updated' => [
        'type' => 'datetime',
        'is_null' => true
      ],
      'dat_expired' => [
        'type' => 'datetime',
        'is_null' => true
      ],
      'cod_user_created' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => false
      ],
      'cod_user_updated' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => true
      ],
      'cod_user_expired' => [
        'type' => 'varchar',
        'length' => '40',
        'is_null' => true
      ]
    ]);

    $this->setTableTriggers([
      'fp_despesa_before_insert' => new CREATE_TRIGGER($this->getTableName(), 'fp_despesa_before_insert', 'BEFORE', 'INSERT', <<<SQL
      BEGIN
        SET NEW.dat_created = NOW();
        SET NEW.cod_user_created = CURRENT_USER();
        SET NEW.dat_updated = NULL;
        SET NEW.cod_user_updated = NULL;
        SET NEW.dat_expired = NULL;
        SET NEW.cod_user_expired = NULL;
      END
      SQL),
      'fp_despesa_before_update' => new CREATE_TRIGGER($this->getTableName(), 'fp_despesa_before_update', 'BEFORE', 'UPDATE', <<<SQL
      BEGIN
        SET NEW.dat_created = OLD.dat_created;
        SET NEW.cod_user_created = OLD.cod_user_created;
        SET NEW.dat_updated = NOW();
        SET NEW.cod_user_updated = CURRENT_USER();
      END
      SQL)
    ]);

    $this->setPrimaryKey('cod_despesa');

    $this->addColumnRelationship('cod_lancamento', [
      'type' => 'left',
      'table' => 'fp_lancamento',
      'alias' => 'lanc',
      'condition' => (new COND('desp.cod_lancamento'))->equals('lanc.cod_lancamento')
    ]);

    $this->setRowsLimit(1);
  }

  protected function prepareConditionsQuerySelect(): void
  {
    $this->getQuerySelect()->where((new COND('lanc.dat_expiracao'))->isnull());
  }

  public function getLancamentoByUsername(string $cod_user): self
  {
    $this->getQuerySelect()->where((new COND('lanc.cod_user'))->equals($cod_user));

    return $this;
  }
}
