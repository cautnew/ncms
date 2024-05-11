<?php

namespace Core\Support;

use Boot\Constants\DirConstant as DC;
use Boot\Constants\DatesConstant as DTC;
use Core\Model\Support\ModelParam;
use DateTime;
use Exception;

class Log
{
  protected Session $ses;

  private ModelParam $modelParam;

  private string $cod;
  private string $prefix;

  private bool $indShowLog;

  private const TYPE_ERROR = 1;
  private const TYPE_WARNING = 2;
  private const TYPE_LOG = 3;
  private const TYPE_INFO = 4;
  private const TYPE_DANGER = 5;
  private const TYPE_SUCCESS = 6;

  public function __construct()
  {
    $this->setIndShowLog(false);
    $this->modelParam = new ModelParam();
  }

  public function setSession(Session $ses): self
  {
    $this->ses = $ses;

    return $this;
  }

  public function getSession(): Session
  {
    if (!isset($this->ses)) {
      $this->ses = new Session();
    }

    return $this->ses;
  }

  public function setCod(string $cod = 'UNKNOWN'): self
  {
    if (empty($cod)) {
      $this->cod = 'UNKNOWN';
    } else {
      $this->cod = $cod;
    }

    return $this;
  }

  public function setCodLog(string $cod): self
  {
    return $this->setCod($cod);
  }

  public function getCod(): string
  {
    if (!isset($this->cod)) {
      $this->setCod();
    }

    return $this->cod;
  }

  public function setPrefix(string $prefix): self
  {
    $this->prefix = $prefix;

    return $this;
  }

  public function getPrefix(): ?string
  {
    if (!isset($this->prefix)) {
      return null;
    }

    return $this->prefix;
  }

  public function setIndShowLog(bool $indShowLog = false): self
  {
    $this->indShowLog = $indShowLog;

    return $this;
  }

  public function getIndShowLog(): bool
  {
    if (!isset($this->indShowLog)) {
      $this->setIndShowLog();
    }

    return $this->indShowLog;
  }

  public function cathAllErrors(): Log
  {
    set_error_handler([$this, 'handlerError']);
    set_exception_handler([$this, 'handlerException']);

    return $this;
  }

  protected function getCodUser(): string
  {
    if (!$this->getSession()->has('COD_USUARIO')) {
      return '0';
    }

    return $this->getSession()->COD_USUARIO;
  }

  private function prepareTxt(string $txt): string
  {
    $txt = filter_var($txt, FILTER_SANITIZE_SPECIAL_CHARS | FILTER_SANITIZE_ADD_SLASHES);

    if (!empty($this->getPrefix())) {
      $txt = "({$this->getPrefix()}) - $txt";
    }

    return $txt;
  }

  private function genFilename(): string
  {
    $instant = new DateTime('now');
    $dateStr = $instant->format(DTC::FORMAT_CODTIMESTAMP);
    $hash = md5($dateStr);
    return "/logerror_{$dateStr}_{$hash}.txt";
  }

  protected final function log(int $codTipo, string $txt): Log
  {
    $instant = new DateTime('now');
    $txt = $this->prepareTxt($txt);
    $txtErro = "{$instant->format(DTC::FORMAT_DATETIME_PHP_UNI)} - [{$this->getCod()}] {$txt}";

    if ($this->indShowLog) {
      echo $txtErro . "\n";
    }

    try {
      file_put_contents(DC::PLOGS . $this->genFilename(), $txtErro);
    } catch (Exception $e) {
      file_put_contents(DC::PLOGS . $this->genFilename(), $txtErro . "\n{$e->getMessage()}");
    }

    return $this;
  }

  public function regException(Exception $e, string|null $desc = null): self
  {
    $this->regError($e->getMessage());
    $this->regError($e->getCode());

    if (!empty($desc)) {
      return $this->regError($desc);
    }

    return $this;
  }

  public function regError(string|Exception $txt): Log
  {
    return $this->log(self::TYPE_ERROR, $txt);
  }

  public function error(string|Exception $txt): Log
  {
    return $this->regError($txt);
  }

  public function regErro(string|Exception $txt): Log
  {
    return $this->regError($txt);
  }

  public function erro(string|Exception $txt): Log
  {
    return $this->regError($txt);
  }

  public function regWarning(string|Exception $txt): Log
  {
    return $this->log(self::TYPE_WARNING, $txt);
  }

  public function warning(string|Exception $txt): Log
  {
    return $this->regWarning($txt);
  }

  public function regAviso(string|Exception $txt): Log
  {
    return $this->regWarning($txt);
  }

  public function aviso(string|Exception $txt): Log
  {
    return $this->regWarning($txt);
  }

  public function regLog(string|Exception $txt): Log
  {
    return $this->log(self::TYPE_LOG, $txt);
  }

  public function reg(string|Exception $txt): Log
  {
    return $this->regLog($txt);
  }

  public function regInfo(string|Exception $txt): Log
  {
    return $this->log(self::TYPE_INFO, $txt);
  }

  public function info(string|Exception $txt): Log
  {
    return $this->regInfo($txt);
  }

  public function regDanger(string|Exception $txt): Log
  {
    return $this->log(self::TYPE_DANGER, $txt);
  }

  public function regSuccess(string|Exception $txt): Log
  {
    return $this->log(self::TYPE_SUCCESS, $txt);
  }

  public function success(string|Exception $txt): Log
  {
    return $this->regSuccess($txt);
  }

  public function handlerException($e)
  {
    $this->regException($e);

    die();
  }

  public function handlerError(Exception $e)
  {
    $this->regException($e);

    die();
  }
}
