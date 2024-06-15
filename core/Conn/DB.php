<?php

namespace Core\Conn;

use Core\Conn\Exception\QueryError;
use \PDO;
use \PDOStatement;
use \PDOException;
use \Exception;
use \DateTime;
use Boot\Constants\DirConstant as DC;
use Core\Support\Logger;

class DB
{
  private static PDO | null $con = null;

  private const CONN_OPTIONS = [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL
  ];

  private static int $currentConId = 0;

  public const PDO_DATABASE_DONT_EXISTS = 1049;
  private const DB_CONID_LOCAL = 1;
  private const DB_CONID_UMBLER = 2;
  private const DB_PRINCIPAL_CONID = self::DB_CONID_LOCAL;

  private const KEY_CRED_LOCALHOST = 'local';
  private const KEY_CRED_UMBLER = 'umbler';
  private const PATH_CRED_JSON = DC::PSUPPORT . '/conf.jdb';

  private static function getCredentials(int $conId): ?array
  {
    $jsonCred = function ($key): ?array {
      $fileContent = file_get_contents(self::PATH_CRED_JSON);
      $fileContentDecoded = base64_decode($fileContent, true);
      $json = json_decode($fileContentDecoded, true);
      return $json[$key];
    };

    switch ($conId) {
      case self::DB_CONID_LOCAL:
        return $jsonCred(self::KEY_CRED_LOCALHOST);
      case self::DB_CONID_UMBLER:
        return $jsonCred(self::KEY_CRED_UMBLER);
    }

    return null;
  }

  private static function createDSN(array $cred, bool $noDBName = false): string
  {
    if ($noDBName) {
      $dsn = "mysql:charset=utf8;host={$cred['host']};port={$cred['port']}";
    } else {
      $dsn = "mysql:charset=utf8;host={$cred['host']};dbname={$cred['dbname']};port={$cred['port']}";
    }

    return $dsn;
  }

  public function __invoke()
  {
    return self::getConn();
  }

  public function __destruct()
  {
    self::resetConnection();
  }

  public static function resetConnection(): void
  {
    self::$con = null;
    self::$currentConId = 0;
  }

  public static function getConn(int $conId = self::DB_PRINCIPAL_CONID): PDO
  {
    if ($conId == self::$currentConId && self::$con !== null) {
      return self::$con;
    }

    if (self::$con !== null) {
      return self::$con;
    }

    $cred = self::getCredentials($conId);
    $dsn = self::createDSN($cred);

    try {
      self::$con = new PDO($dsn, $cred['us'], $cred['pw'], self::CONN_OPTIONS);
    } catch (PDOException $e) {
      $txtErro = "Não foi possível conectar ao banco ({$e->getMessage()} - {$dsn})";
      Logger::reg($txtErro);

      if ($e->getCode() == self::PDO_DATABASE_DONT_EXISTS) {
        $dsn = self::createDSN($cred, true);
        self::$con = new PDO($dsn, $cred['us'], $cred['pw'], self::CONN_OPTIONS);
        self::$con->exec("CREATE DATABASE {$cred['dbname']}");
        Logger::reg("Banco de dados criado com sucesso ({$cred['dbname']})");
        return self::getConn($conId);
      }

      throw new Exception($txtErro);
    }

    self::$currentConId = $conId;

    return self::$con;
  }

  /**
   * Execute a SQL query. Prefer to run line by line.
   * It's going to return the state for the complete query.
   * @param string $sql
   * The SQL query.
   * @param array $params
   * The parameters for the query.
   */
  public static function exec(string $sql, array $params = []): PDOStatement|bool|null
  {
    if (empty($sql)) {
      return null;
    }

    if (empty($params)) {
      $params = [];
    }

    $sql = trim($sql);
    $stmt = self::getConn()->prepare($sql);

    try {
      $stmt->execute($params);
    } catch (PDOException $e) {
      Logger::regException($e);
      throw new QueryError($e);
    }

    return $stmt;
  }
}
