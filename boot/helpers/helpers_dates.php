<?php

function monthName(int $month, int $type = 0): null | string
{
  if ($month > 0 && $month <= 12) {
    $indMonth = $month - 1;

    if ($type == 1) {
      return NOME_MES_ABREV[$indMonth];
    } elseif ($type == 0) {
      return NOME_MES[$indMonth];
    }
  }

  return null;
}

function deparaMes(int $mes, int $cond = 0): null | string
{
  return monthName($mes, $cond);
}

function weekDayName(int $diasemana = 0, int $cond = 0): null | string
{
  switch ($cond) {
    case 0:
      return NOME_DIASEMANA[$diasemana];
    case 1:
      return NOME_DIASEMANA_ABREV[$diasemana];
    case 2:
      return LETRA_DIASEMANA[$diasemana];
  }

  return null;
}

function deparaDiaSemana(int $diasemana = 0, int $cond = 0)
{
  return weekDayName($diasemana, $cond);
}

function convertDateDDMMYYYY(string $strDate, string $sep = '/'): DateTime
{
  $elmtDat = explode($sep, $strDate);
  $strDatUni = "{$elmtDat[2]}-{$elmtDat[1]}-{$elmtDat[0]}";
  $dat = new DateTime($strDatUni);

  return $dat;
}

function getIntervalDay(int $days = 1): ?DateInterval
{
  if ($days < 1) {
    return null;
  }

  return new DateInterval("P{$days}D");
}

function getIntervalMonth(int $qtdMonts = 1): ?DateInterval
{
  if ($qtdMonts < 1) {
    return null;
  }

  return new DateInterval("P{$qtdMonts}M");
}

function isNewer(DateTime $datReferencia, DateTime $datComparacao): bool
{
  return ($datReferencia->diff($datComparacao)->invert === 0);
}

function isOlder(DateTime $datReferencia, DateTime $datComparacao): bool
{
  return !isNewer($datReferencia, $datComparacao);
}

function timestampToString(): string
{
  $now = new DateTime('now');
  return $now->format(FORMAT_DATETIME_PHP_UNI);
}

function timestampToStringJS(): string
{
  $now = new DateTime('now');
  return $now->format(FORMAT_DATETIME_PHP_UNI_JS);
}

function getIntervalInMinutes(DateInterval $interval): int
{
  $minutes = $interval->i;
  $minutes += $interval->h * MINUTES_IN_HOUR;
  $minutes += $interval->d * MINUTES_IN_DAY;
  $minutes += $interval->m * MINUTES_IN_MONTH;
  $minutes += $interval->y * MINUTES_IN_YEAR;

  return $minutes;
}
