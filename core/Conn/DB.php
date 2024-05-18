<?php

namespace Core\Conn;

use \PDO;
use \PDOException;
use \Exception;
use \DateTime;
use Boot\Constants\DirConstant as DC;
use Core\Support\Logger;

class DB {
  private static PDO | null $con = null;

  private const CONN_OPTIONS = [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL
  ];

  private static int $currentConId = 0;

  private const DB_CONID_LOCAL = 1;
  private const DB_CONID_UMBLER = 2;
  private const DB_PRINCIPAL_CONID = self::DB_CONID_LOCAL;

  private const PATH_CRED_LOCALHOST = DC::PSUPPORT . '/creddblocalhost.cdb';
  private const PATH_CRED_UMBLER = DC::PSUPPORT . '/credumbler.cdb';

  private static function getCredentials(int $conId): ?array {
    $jsonCred = function ($path): ?array {
      $fileContent = file_get_contents($path);
      $fileContentDecoded = base64_decode($fileContent, true);
      $json = json_decode($fileContentDecoded, true);
      return $json;
    };

    switch ($conId) {
      case self::DB_CONID_LOCAL:
        return $jsonCred(self::PATH_CRED_LOCALHOST);
      case self::DB_CONID_UMBLER:
        return $jsonCred(self::PATH_CRED_UMBLER);
    }

    return null;
  }

  private static function createDSN(array $cred): string {
    $dsn = "mysql:charset=utf8;host={$cred['host']};dbname={$cred['dbname']};port={$cred['port']}";
    return $dsn;
  }

  public function __invoke() {
    return self::getConn();
  }

  public function __destruct() {
    self::resetConnection();
  }

  public static function resetConnection(): void {
    self::$con = null;
    self::$currentConId = 0;
  }

  public static function getConn(int $conId = self::DB_PRINCIPAL_CONID): PDO {
    if ($conId == self::$currentConId) {
      return self::$con;
    }

    $cred = self::getCredentials($conId);
    $dsn = self::createDSN($cred);

    try {
      self::$con = new PDO($dsn, $cred['us'], $cred['pw'], self::CONN_OPTIONS);
    } catch (PDOException $e) {
      $txtErro = "Não foi possível conectar ao banco ({$e->getMessage()} - {$dsn})";
      Logger::reg($txtErro);
      throw new Exception($txtErro);
    }

    return self::$con;
  }
}
