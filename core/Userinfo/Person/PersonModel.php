<?php

namespace Core\Userinfo\Person;

use QB\CONDITION as COND;
use Core\Model\ModelCRUD;
use Boot\Constants\Constant as CNT;
use Core\Userinfo\Dim\ModelGender;

class ModelPerson extends ModelCRUD
{
  protected string $version = '0.0.1';

  private ModelGender $modelGender;

  public function __construct()
  {
    parent::__construct(CNT::DB_NAME . '.dim_pessoa', 'pess');
    $this->setColumns([
      'pess.cod_pessoa' => 'string',
      'pess.dsc_nome' => 'string',
      'pess.dsc_segundonome' => 'string',
      'pess.dsc_sobrenome' => 'string',
      'pess.dsc_nomecompleto' => 'string',
      'pess.dat_nascimento' => 'date',
      'pess.num_cpf' => 'string',
      'pess.num_pis' => 'string',
      'pess.cod_sexo' => 'int',
      'sexo.dsc_sexo' => 'string',
      'sexo.cod_sexo_abrev' => 'string',
      'pess.cod_genero' => 'int',
      'gndr.dsc_genero' => 'string',
      'gndr.sgl_genero' => 'string',
      'gndr.dsc_explicacao_genero' => 'string',
      'pess.dat_criacao' => 'date',
      'pess.dat_alteracao' => 'date',
      'pess.dat_expiracao' => 'date',
      'pess.cod_usuario_criacao' => 'string',
      'pess.cod_usuario_alteracao' => 'string',
      'pess.cod_usuario_expiracao' => 'string'
    ]);

    $this->setColumnsAllowInsert([
      'cod_pessoa' => 'string',
      'dsc_nome' => 'string',
      'dsc_segundonome' => 'string',
      'dsc_sobrenome' => 'string',
      'dsc_nomecompleto' => 'string',
      'dat_nascimento' => 'date',
      'num_cpf' => 'string',
      'num_pis' => 'string',
      'cod_sexo' => 'int',
      'cod_genero' => 'int',
      'cod_usuario_criacao' => 'string'
    ]);

    $this->setColumnsAllowUpdate([
      'dsc_nome' => 'string',
      'dsc_segundonome' => 'string',
      'dsc_sobrenome' => 'string',
      'dsc_nomecompleto' => 'string',
      'dat_nascimento' => 'date',
      'num_cpf' => 'string',
      'num_pis' => 'string',
      'cod_sexo' => 'int',
      'cod_genero' => 'int',
      'cod_usuario_alteracao' => 'string',
      'cod_usuario_expiracao' => 'string'
    ]);

    $this->setPrimaryKey('cod_pessoa');

    $this->addColumnRelationship('cod_sexo', [
      'type' => 'left',
      'table' => 'dim_sexo',
      'alias' => 'sexo',
      'condition' => (new COND('pess.cod_sexo'))->equals('sexo.cod_sexo')
    ]);

    $this->addColumnRelationship('cod_genero', [
      'type' => 'left',
      'table' => 'dim_genero',
      'alias' => 'gndr',
      'condition' => (new COND('pess.cod_genero'))->equals('gndr.cod_genero')
    ]);

    $this->setRowsLimit(1);
  }

  protected function prepareConditionsQuerySelect(): void
  {
    $this->getQuerySelect()->where((new COND('pess.dat_expiracao'))->isnull());
  }
}
