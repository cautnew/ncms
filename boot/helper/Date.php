<?php

namespace Boot\Helper;

use Boot\Constants\Constant;
use Boot\Constants\DatesConstant;
use DateInterval;
use DateTime;

class Date
{
  public static function monthName(int $month, int $type = 0): ?string
  {
    if ($month > 0 && $month <= 12) {
      $indMonth = $month - 1;

      if ($type == 1) {
        return DatesConstant::NOME_MES_ABREV[$indMonth];
      } elseif ($type == 0) {
        return DatesConstant::NOME_MES[$indMonth];
      }
    }

    return null;
  }

  public static function deparaMes(int $mes, int $cond = 0): ?string
  {
    return self::monthName($mes, $cond);
  }

  public static function weekDayName(int $diasemana = 0, int $cond = 0): ?string
  {
    switch ($cond) {
      case 0:
        return DatesConstant::NOME_DIASEMANA[$diasemana];
      case 1:
        return DatesConstant::NOME_DIASEMANA_ABREV[$diasemana];
      case 2:
        return DatesConstant::LETRA_DIASEMANA[$diasemana];
    }

    return null;
  }

  public static function deparaDiaSemana(int $diasemana = 0, int $cond = 0): ?string
  {
    return self::weekDayName($diasemana, $cond);
  }

  public static function getIntervalDay(int $days = 1): ?DateInterval
  {
    if ($days < 1) {
      return null;
    }

    return new DateInterval("P{$days}D");
  }

  public static function getIntervalMonth(int $qtdMonts = 1): ?DateInterval
  {
    if ($qtdMonts < 1) {
      return null;
    }

    return new DateInterval("P{$qtdMonts}M");
  }

  public static function timestampToString(?DateTime $moment = null): string
  {
    if ($moment === null) {
      $moment = new DateTime('now');
    }

    return $moment->format(DatesConstant::FORMAT_DATETIME_PHP_UNI);
  }

  public static function timestampToStringJS(?DateTime $moment = null): string
  {
    if ($moment === null) {
      $moment = new DateTime('now');
    }

    return $moment->format(DatesConstant::FORMAT_DATETIME_PHP_UNI_JS);
  }

  public static function getIntervalInMinutes(DateInterval $interval): int
  {
    $minutes = $interval->i;
    $minutes += $interval->h * Constant::MINUTES_IN_HOUR;
    $minutes += $interval->d * Constant::MINUTES_IN_DAY;
    $minutes += $interval->m * Constant::MINUTES_IN_MONTH;
    $minutes += $interval->y * Constant::MINUTES_IN_YEAR;

    return $minutes;
  }

  public static function getTexto(string $txt): string
  {
    return "vai {$txt}";
  }
}
