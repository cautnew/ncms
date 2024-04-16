<?php

function getConn(string $connName): PDO
{
  return (new Conn\DB())->getConn($connName);
}

function tableExists(Conn\DB $con, string $tableName): bool
{
  return $con->tableExists($tableName);
}

function insertResultForCsvInFile($res, $filename, $fieldsSeparatedBy = ',', $linesTerminatedBy = "\n", $enclosedBy = '', $indUtf8 = false)
{
  $r = $res->fetch(PDO::FETCH_ASSOC);

  $glueJoin = $enclosedBy . $fieldsSeparatedBy . $enclosedBy;
  $endLine = $enclosedBy . $linesTerminatedBy;

  $result = $enclosedBy . join($glueJoin, array_keys($r)) . $endLine;
  $result .= $enclosedBy . join($glueJoin, $r) . $endLine;

  if (empty($result)) {
    return 'Não há informações.';
  }

  $f = fopen($filename, 'w');

  if ($indUtf8) {
    fwrite($f, utf8_decode($result));
    while ($r = $res->fetch(PDO::FETCH_ASSOC)) {
      $join = join($glueJoin, $r);
      $str = "{$enclosedBy}{$join}{$endLine}";
      $result = utf8_decode($str);
      fwrite($f, $result);
    }
  } else {
    fwrite($f, $result);
    while ($r = $res->fetch(PDO::FETCH_ASSOC)) {
      $join = join($glueJoin, $r);
      $result = "{$enclosedBy}{$join}{$endLine}";
      fwrite($f, $result);
    }
  }

  fclose($f);
}

function _strResultForCsv($res, $fieldsSeparatedBy = ',', $linesTerminatedBy = "\n", $enclosedBy = '', $indUtf8 = false)
{
  $r = $res->fetch(PDO::FETCH_ASSOC);

  if (empty($r)) {
    return 'Não há informações.';
  }

  $glueJoin = $enclosedBy . $fieldsSeparatedBy . $enclosedBy;
  $endLine = $enclosedBy . $linesTerminatedBy;

  $result = $enclosedBy . join($glueJoin, array_keys($r)) . $endLine;
  $result .= $enclosedBy . join($glueJoin, $r) . $endLine;

  if (empty($result)) {
    return 'Não há informações.';
  }

  while ($r = $res->fetch(PDO::FETCH_ASSOC)) {
    $join = join($glueJoin, $r);
    $result .= "{$enclosedBy}{$join}{$endLine}";
  }

  return ($indUtf8) ? utf8_decode($result) : $result;
}

function strResultForCsv($res, $fieldsSeparatedBy = ',', $linesTerminatedBy = "\n", $enclosedBy = '')
{
  return _strResultForCsv($res, $fieldsSeparatedBy, $linesTerminatedBy, $enclosedBy, true);
}

function strResultForCsvNoUtf8($res, $fieldsSeparatedBy = ',', $linesTerminatedBy = "\n", $enclosedBy = '')
{
  return _strResultForCsv($res, $fieldsSeparatedBy, $linesTerminatedBy, $enclosedBy, false);
}

function echoResultForCsv($res, $fieldsSeparatedBy = ',', $linesTerminatedBy = "\n", $enclosedBy = '')
{
  echo strResultForCsv($res, $fieldsSeparatedBy, $linesTerminatedBy, $enclosedBy);
}

function echoResultForCsvNoUtf8($res, $fieldsSeparatedBy = ',', $linesTerminatedBy = "\n", $enclosedBy = '')
{
  echo strResultForCsvNoUtf8($res, $fieldsSeparatedBy, $linesTerminatedBy, $enclosedBy);
}

function insUniqueDimRow($con, $tabDest, $colDest, $tabOrig, $colOrig): int
{
  $ins = "INSERT INTO {$tabDest} ({$colDest}, `DAT_CRIACAO`)";

  $selOrig = "SELECT DISTINCT {$colOrig} B FROM {$tabOrig} WHERE {$colOrig} IS NOT NULL";

  $sel = <<<SQL
  SELECT B, NOW() FROM ({$selOrig}) B
  LEFT JOIN {$tabDest} A ON B.B=A.{$colDest}
  WHERE A.{$colDest} IS NULL
  SQL;

  $query = "{$ins} {$sel};";

  try {
    $res = $con->getConn()->prepare($query);
    $res->execute();
  } catch (\Exception $e) {
    throw new \Exception("Não foi possível inserir os indices novos. {$e->getMessage()}");
  }

  return $res->rowCount();
}

function insUniqueDimVal($con, $tabDest, $colDest, $val): int
{
  try {
    $selOrig = "SELECT COUNT(*) QTD_VAL FROM {$tabDest} WHERE {$colDest}='{$val}';";
    $res = $con->getConn()->prepare($selOrig, 'QTD_VAL');
    $res->execute();
    $qtd = $res->fetch()->QTD_VAL;
  } catch (\Exception $e) {
    throw new \Exception("Não foi possível verificar a quantidade de registros. {$e->getMessage()}");
  }

  if ($qtd > 0) {
    $ins = <<<SQL
    INSERT INTO $tabDest ($colDest, DAT_CRIACAO)
    VALUES('$val', NOW());
    SQL;

    try {
      $con = $con->getConn();
      $res = $con->prepare($ins);
      $res->execute();
    } catch (\Exception $e) {
      throw new \Exception("Não foi possível inserir os indices novos. {$e->getMessage()}");
    }
  }

  return $con->lastInsertId();
}

function timeSleepRetryRunQueryTimeout(): int
{
  return random_int(MIN_SLEEP_RETRY_RUN_QUERY_TIMEOUT, MAX_SLEEP_RETRY_RUN_QUERY_TIMEOUT);
}

function timeSleepRetryRunQueryPllSignl(): int
{
  return random_int(MIN_SLEEP_RETRY_RUN_QUERY_PLLSIGN, MAX_SLEEP_RETRY_RUN_QUERY_PLLSIGN);
}
