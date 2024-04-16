<?php

namespace Boot\Helper;

use Boot\Constants\Constant;
use Boot\Constants\DatesConstant;
use DateInterval;
use DateTime;

/**
 * Full static class with methods for helping with dates.
 */
class DateHelper
{
  /**
   * @param int $month
   * @param int $type Optional. Default is 0.
   */
  public static function monthName(int $month, int $type = 0): ?string
  {
    if ($month > 0 && $month <= 12) {
      $indMonth = $month - 1;
    } else {
      return null;
    }

    if ($type == 1) {
      return DatesConstant::NOME_MES_ABREV[$indMonth];
    } elseif ($type == 0) {
      return DatesConstant::NOME_MES[$indMonth];
    }

    return null;
  }

  /**
   * @param int $day
   * @param int $cond Optional. Default is 0.
   */
  public static function weekDayName(int $day = 0, int $cond = 0): ?string
  {
    if ($day < 0 || $day > 7) {
      return null;
    }

    switch ($cond) {
      case 0:
        return DatesConstant::NOME_DIASEMANA[$day];
      case 1:
        return DatesConstant::NOME_DIASEMANA_ABREV[$day];
      case 2:
        return DatesConstant::LETRA_DIASEMANA[$day];
    }

    return null;
  }

  public static function getWeekDayName(int $diasemana = 0, int $cond = 0)
  {
    return self::weekDayName($diasemana, $cond);
  }

  public static function getIntervalInDays(int $days = 1): ?DateInterval
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

  /**
   * Check if the given date in $datComparison is newer than the given
   * date in $datReference.
   * @param DateTime $datReference
   * @param DateTime $datComparison
   * @return boolean
   * <p>Returns true if $datComparison is newer than $datReference</p>
   * <p>Example:</p>
   * <p>In case of the following values are</p>
   * $datComparison = new DateTime('2021-02-10 09:30:19');<br>
   * $datReference = new DateTime('2021-02-10 09:29:39');<br>
   * returns true, because $datComparison contains a date newer than $datReference<br><br>
   * 
   * <p>In case of the following values are</p>
   * $datComparison = new DateTime('2021-02-10 09:29:39');<br>
   * $datReference = new DateTime('2021-02-10 09:29:39');<br>
   * returns false, because $datComparison is equals to $datReference
   */
  public static function isNewer(DateTime $datReference, DateTime $datComparison): bool
  {
    return ($datReference < $datComparison);
  }

  /**
   * Check if the given date in $datComparison is newer than the given
   * date in $datReference.
   * @param DateTime $datReference
   * @param DateTime $datComparison
   * @return boolean
   * Returns true if $datComparison is newer than $datReference
   * Example:
   * In case of the following values are
   * $datComparison = new DateTime('2021-02-10 09:30:19');
   * $datReference = new DateTime('2021-02-10 09:29:39');
   * returns true, because $datComparison contains a date newer than $datReference
   * 
   * In case of the following values are
   * $datComparison = new DateTime('2021-02-10 09:29:39');
   * $datReference = new DateTime('2021-02-10 09:29:39');
   * returns true, because $datComparison is equals to $datReference
   */
  public static function isNewerOrEquals(DateTime $datReference, DateTime $datComparison): bool
  {
    return ($datReference <= $datComparison);
  }

  /**
   * Check if the given date in $datComparison is older than the given
   * date in $datReference.
   * @param DateTime $datReference
   * @param DateTime $datComparison
   * @return boolean
   * Returns true if $datComparison is older than $datReference
   * Example:
   * In case of the following values are
   * $datComparison = new DateTime('2021-02-10 09:30:19');
   * $datReference = new DateTime('2021-02-10 09:29:39');
   * returns false, because $datComparison contains a date older than $datReference
   * 
   * In case of the following values are
   * $datComparison = new DateTime('2021-02-10 09:29:39');
   * $datReference = new DateTime('2021-02-10 09:29:39');
   * returns false, because $datComparison is equals to $datReference
   */
  public static function isOlder(DateTime $datReference, DateTime $datComparison): bool
  {
    //return ($datReference > $datComparison);
    return !self::isNewerOrEquals($datReference, $datComparison);
  }

  /**
   * Check if the given date in $datComparison is older than the given
   * date in $datReference.
   * @param DateTime $datReference
   * @param DateTime $datComparison
   * @return boolean
   * Returns true if $datComparison is older than $datReference
   * Example:
   * In case of the following values are
   * $datComparison = new DateTime('2021-02-10 09:30:19');
   * $datReference = new DateTime('2021-02-10 09:29:39');
   * returns false, because $datComparison contains a date older than $datReference
   * 
   * In case of the following values are
   * $datComparison = new DateTime('2021-02-10 09:29:39');
   * $datReference = new DateTime('2021-02-10 09:29:39');
   * returns true, because $datComparison is equals to $datReference
   */
  public static function isOlderOrEquals(DateTime $datReference, DateTime $datComparison): bool
  {
    //return ($datReference >= $datComparison);
    return !self::isNewer($datReference, $datComparison);
  }

  public static function getTimestampToString(?DateTime $moment = null): string
  {
    if ($moment === null) {
      $moment = new DateTime('now');
    }

    return $moment->format(DatesConstant::FORMAT_DATETIME_PHP_UNI);
  }

  public static function getTimestampToStringJS(?DateTime $moment = null): string
  {
    if ($moment === null) {
      $moment = new DateTime('now');
    }

    return $moment->format(DatesConstant::FORMAT_DATETIME_PHP_UNI_JS);
  }

  public static function getIntervalInMinutes(DateInterval $interval): int
  {
    $minutes = (int) $interval->i;
    $minutes += (int) $interval->h * Constant::MINUTES_IN_HOUR;
    $minutes += (int) $interval->d * Constant::MINUTES_IN_DAY;
    $minutes += (int) $interval->m * Constant::MINUTES_IN_MONTH;
    $minutes += (int) $interval->y * Constant::MINUTES_IN_YEAR;

    return $minutes;
  }

  public static function getTexto(string $txt): string
  {
    return "vai {$txt}";
  }
}
