<?php

namespace Boot\Constants;

readonly class DatesConstant
{
  public const FORMAT_CODTIMESTAMP = 'YmdHis';
  public const FORMAT_YEAR_PHP = 'Y';
  public const FORMAT_ANO_PHP = self::FORMAT_YEAR_PHP;
  public const FORMAT_MONTH_PHP = 'm';
  public const FORMAT_MES_PHP = self::FORMAT_MONTH_PHP;
  public const FORMAT_MONTH_NZ_PHP = 'n';
  public const FORMAT_MES_NZ_PHP = self::FORMAT_MONTH_NZ_PHP;
  public const FORMAT_DAY_NZ_PHP = 'j';
  public const FORMAT_DIA_NZ_PHP = self::FORMAT_DAY_NZ_PHP;
  public const FORMAT_DAY_PHP = 'd';
  public const FORMAT_DIA_PHP = self::FORMAT_DAY_PHP;
  public const FORMAT_WEEK_PHP = 'w';
  public const FORMAT_SMN_PHP = self::FORMAT_WEEK_PHP;
  public const FORMAT_LASTMONTHDAY_PHP = 't';
  public const FORMAT_ULTDIA_PHP = self::FORMAT_LASTMONTHDAY_PHP;
  public const FORMAT_DAT_PHP_ANOMES = 'Ym';
  public const FORMAT_DAT_PHP_ANOMESDIA = 'Ymd';
  public const FORMAT_DAT_PHP_ANOMESDIA_LAST = 'Ymt';
  public const FORMAT_DAT_PHP_ANOMES_UNI = 'Y-m';
  public const FORMAT_DAT_PHP_MESANO = 'mY';
  public const FORMAT_DAT_PHP_MES_ANO = 'm/Y';
  public const FORMAT_DAT_PHP = 'Y-n-j';
  public const FORMAT_DAT_PHP_UNI = 'Y-m-d';
  public const FORMAT_DAT_PHP_BR = 'd/m/Y';
  public const FORMAT_DAT_PHP_FIRST_DAY_BR = '01/m/Y';
  public const FORMAT_DAT_PHP_LAST_DAY_BR = 't/m/Y';
  public const FORMAT_DAT_PHP_FIRST_DAY = 'Y-m-01';
  public const FORMAT_DAT_PHP_LAST_DAY = 'Y-m-t';
  public const FORMAT_DATETIME_PHP_UNI = 'Y-m-d H:i:s';
  public const FORMAT_DATETIME_PHP_UNI_JS = 'Y-m-d\TH:i:s';
  public const FORMAT_DATETIME_PHP_BR = 'd/m/Y H:i:s';
  public const FORMAT_TIME_PHP_UNI = 'H:i:s';
  public const FORMAT_TIME_PHP_BR = self::FORMAT_TIME_PHP_UNI;
  public const FORMAT_INTERVAL_PHP_UNI = '%H:%I:%S';
  public const FORMAT_DAT_MYSQL_UNI = '%Y-%m-%d';
  public const FORMAT_DAT_MYSQL_BR = '%d/%m/%Y';

  public const NOME_MES = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
  public const NOME_MES_ABREV = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

  public const NOME_DIASEMANA = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
  public const NOME_DIASEMANA_ABREV = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
  public const LETRA_DIASEMANA = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];
}
