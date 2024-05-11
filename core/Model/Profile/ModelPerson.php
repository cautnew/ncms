<?php

namespace Core\Model\Profile;

use Cautnew\QB\CONDITION as COND;
use Core\Model\ModelCRUD;
use Boot\Constants\Constant as CNT;
use Core\Model\Dim\ModelGender;

class ModelPerson extends ModelCrud
{
  protected string $version = '0.0.1';

  private ModelGender $modelGender;

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME . '.dim_person', 'pess');
    $this->setColumns([
      'pess.cod_person' => 'string',
      'pess.dsc_name' => 'string',
      'pess.dsc_middle_name' => 'string',
      'pess.dsc_last_name' => 'string',
      'pess.dsc_full_name' => 'string',
      'pess.dat_birthdate' => 'date',
      'pess.num_cpf' => 'string',
      'pess.num_pis' => 'string',
      'pess.cod_sexo' => 'int',
      'sexo.dsc_sexo' => 'string',
      'sexo.cod_sexo_abrev' => 'string',
      'pess.cod_gender' => 'varchar',
      'gndr.dsc_genero' => 'string',
      'gndr.sgl_genero' => 'string',
      'gndr.dsc_explicacao_genero' => 'string',
      'pess.dat_created' => 'date',
      'pess.dat_updated' => 'date',
      'pess.dat_expired' => 'date',
      'pess.cod_user_created' => 'string',
      'pess.cod_user_updated' => 'string',
      'pess.cod_user_expired' => 'string'
    ]);

    $this->setColumnsAllowInsert([
      'cod_person' => 'string',
      'dsc_name' => 'string',
      'dsc_middle_name' => 'string',
      'dsc_last_name' => 'string',
      'dsc_full_name' => 'string',
      'dat_birthdate' => 'date',
      'num_cpf' => 'string',
      'num_pis' => 'string',
      'cod_sexo' => 'int',
      'cod_genero' => 'int',
      'cod_user_created' => 'string'
    ]);

    $this->setColumnsAllowUpdate([
      'dsc_name' => 'string',
      'dsc_middle_name' => 'string',
      'dsc_last_name' => 'string',
      'dsc_full_name' => 'string',
      'dat_birthdate' => 'date',
      'num_cpf' => 'string',
      'num_pis' => 'string',
      'cod_sexo' => 'int',
      'cod_gender' => 'int',
      'cod_user_updated' => 'string',
      'cod_user_expired' => 'string'
    ]);

    $this->setPrimaryKey('cod_person');

    $this->addColumnRelationship('cod_sexo', [
      'type' => 'left',
      'table' => 'dim_sexo',
      'alias' => 'sexo',
      'condition' => (new COND('pess.cod_sexo'))->equals('sexo.cod_sexo')
    ]);

    $this->addColumnRelationship('cod_gender', [
      'type' => 'left',
      'table' => 'dim_gender',
      'alias' => 'gndr',
      'condition' => (new COND('pess.cod_gender'))->equals('gndr.cod_gender')
    ]);

    $this->setRowsLimit(1);
  }

  protected function prepareConditionsQuerySelect(): void
  {
    $this->getQuerySelect()->where((new COND('pess.dat_expiracao'))->isnull());
  }
}
