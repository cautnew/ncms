<?php

namespace Boot;

use AutoloadListDirectories;
use Boot\Constants\Constant as C;
use Boot\Constants\DirConstant as DC;
use Boot\Constants\DatesConstant as DatesC;
use Core\Support\Session;
use Core\Route\Request;
use \Exception;

class Autoload
{
  private static bool $isjsonresponse = false;
  private static bool $istxtresponse = false;
  private static bool $iscsvresponse = false;

  private static Request $request;
  private static Session $session;

  private static array $globalDirectories = [
    DC::PCORE,
    DC::PROOT,
    DC::PGLOBALS,
    DC::PGLOBALVARS
  ];

  private static array $defaultDirectories = [
    'Boot\\' => DC::PBOOT,
    'Boot\\Constants\\' => DC::PBOOT . '/constants',
    'Boot\\Exceptions\\' => DC::PBOOT . '/exceptions',
    'Boot\\Helper\\' => DC::PBOOT . '/helper',
    'Cautnew\\QB\\' => DC::PGLOBALS . '/cautnew/qb/src',
    'Cautnew\\HTML\\' => DC::PGLOBALS . '/cautnew/html/src',
    'Cautnew\\HTML\\BS\\' => DC::PGLOBALS . '/cautnew/html/src/BS',
    'Core\\' => DC::PCORE,
    'Core\\BarTop\\' => DC::PCORE . '/BarTop',
    'Core\\Conn\\' => DC::PCORE . '/Conn',
    'Core\\Conn\\Exceptions\\' => DC::PCORE . '/Conn/exceptions',
    'Core\\Clock\\' => DC::PCORE . '/Clock',
    'Core\\Clock\\Exceptions\\' => DC::PCORE . '/Clock/exceptions',
    'Core\\Dim\\' => DC::PCORE . '/Dim',
    'Core\\DPG\\' => DC::PCORE . '/DPG',
    'Core\\Empresa\\' => DC::PCORE . '/Empresa',
    'Core\\Route\\' => DC::PCORE . '/Route',
    'Core\\Route\\Exceptions\\' => DC::PCORE . '/Route/exceptions',
    'Core\\SideMenu\\' => DC::PCORE . '/SideMenu',
    'Core\\Support\\' => DC::PCORE . '/Support',
    'Core\\UserInfo\\' => DC::PCORE . '/UserInfo',
    'Core\\UserInfo\\Exceptions\\' => DC::PCORE . '/UserInfo/exceptions',
    'League\\Plates\\' => DC::PGLOBALS . '/league/plates/src',
    'MatthiasMullie\\PathConverter\\' => DC::PGLOBALS . '/matthiasmullie/path-converter/src',
    'MatthiasMullie\\Minify\\' => DC::PGLOBALS . '/matthiasmullie/minify/src',
    'PHPMailer\\PHPMailer\\' => DC::PGLOBALS . '/phpmailer/phpmailer/src',
    'Source\\' => DC::PSOURCE,
    'thiagoalessio\\TesseractOCR\\' => DC::PGLOBALS . '/thiagoalessio/tesseract_ocr/src'
  ];

  private static string $ext = 'php';

  public static function register(): void
  {
    spl_autoload_register(['Boot\autoload', 'setAutoloadRegister']);
    set_exception_handler(['Boot\autoload', 'exceptionHandler']);
    //set_error_handler(['Boot\autoload', 'exceptionHandler']);
  }

  public static function setTimeLimit(int $time): void
  {
    set_time_limit($time);
  }

  public static function setToUnlimitTime(): void
  {
    self::setTimeLimit(0);
  }

  public static function setToUnlimitedTime(): void
  {
    self::setTimeLimit(0);
  }

  public static function setToNoTimeLimit(): void
  {
    self::setTimeLimit(0);
  }

  private static function splitNamespaceAndClassName($class): array
  {
    $split = explode("\\", $class);
    $classname = end($split);

    $namespace = '';
    $limitNames = count($split) - 1;
    for ($i = 0; $i < $limitNames; $i++) {
      $namespace = $namespace . $split[$i] . "\\";
    }

    return [$namespace, $classname];
  }

  private static function addRequiredFile($class): bool
  {
    list($namespace, $classname) = self::splitNamespaceAndClassName($class);

    if (array_key_exists($namespace, self::$defaultDirectories)) {
      $dir = self::$defaultDirectories[$namespace];
      $file = str_replace('\\', DIRECTORY_SEPARATOR, "$dir/$classname") . '.' . self::$ext;

      if (file_exists($file)) {
        require_once $file;
        return true;
      }
    }

    return false;
  }

  public static function setAutoloadRegister($class): void
  {
    if (self::addRequiredFile($class)) {
      return;
    }

    foreach (autoload::$globalDirectories as $dir) {
      $file = str_replace('\\', DIRECTORY_SEPARATOR, "$dir/$class") . '.' . self::$ext;

      if (file_exists($file)) {
        require_once $file;
        return;
      }
    }

    echo "Class \"$class\" not found.";

    die();
  }

  public static function getExceptionTraceAsString($e)
  {
    if (gettype($e) != 'object') {
      return;
    }

    try {
      $file = $e->getFile();
    } catch (Exception $e) {
      $file = __FILE__;
    }

    try {
      $line = $e->getLine();
    } catch (Exception $e) {
      $line = '0';
    }

    $err = sprintf(
      "#%s %s(%s): [%s] [%s] %s\n",
      'main',
      $file,
      $line,
      get_class($e),
      $e->getCode(),
      $e->getMessage()
    );

    if (empty($e->getTrace())) {
      return $err;
    }

    $count = 0;
    foreach ($e->getTrace() as $frame) {
      $args = "";

      if (isset($frame['args'])) {
        $args = array();
        foreach ($frame['args'] as $arg) {
          if (is_string($arg)) {
            $args[] = "'$arg'";
          } elseif (is_array($arg)) {
            $args[] = "Array";
          } elseif (is_null($arg)) {
            $args[] = 'NULL';
          } elseif (is_bool($arg)) {
            $args[] = ($arg) ? "true" : "false";
          } elseif (is_object($arg)) {
            $args[] = get_class($arg);
          } elseif (is_resource($arg)) {
            $args[] = get_resource_type($arg);
          } else {
            $args[] = $arg;
          }
        }
        $args = join(", ", $args);
      }

      $err .= sprintf(
        "#%s %s(%s): %s%s%s(%s)\n",
        $count,
        $frame['file'] ?? '',
        $frame['line'] ?? '',
        $frame['class'] ?? '',
        $frame['type'] ?? '',
        $frame['function'],
        $args
      );

      $count++;
    }

    $err = rtrim($err, "\n");

    return "<pre>\n$err\n</pre>";
  }

  public static function exceptionHandler($e): void
  {
    echo self::getExceptionTraceAsString($e);
  }

  public static function getErrorTraceAsString($e, $txt, $file, $line, $args): string
  {
    return '';
  }

  public static function errorExceptionHandler($e, $txt, $file, $line, $args)
  {
    self::getErrorTraceAsString($e, $txt, $file, $line, $args);
  }

  public static function writeFileInfo(): void
  {
    global $cwd;
    $pm = new \DateTime('now');

    echo "Timestamp: " . $pm->format(DatesC::FORMAT_DATETIME_PHP_UNI) . "\n";
    echo "CWD: {$cwd()}\n";
    echo "Document Root: {$_SERVER['DOCUMENT_ROOT']}\n";
    echo "Filename: {$_SERVER['PHP_SELF']}\n";

    if (!empty($_SERVER['SERVER_NAME'])) {
      echo "Server Name: {$_SERVER['SERVER_NAME']}";
    }
  }

  public static function writeInfoFile(): void
  {
    self::writeFileInfo();
  }

  protected static function setContentTypeResponse(string $type, string $charset = C::DEFAULT_CHARSET): void
  {
    $header = "Content-Type:{$type}";

    if (!empty($charset)) {
      $header .= ";charset=\"{$charset}\"";
    }

    header($header);
  }

  protected static function setContentLengthResponse(int $bytes): void
  {
    header("Content-Length:{$bytes}");
  }

  public static function getRequest(): Request
  {
    if (!isset(self::$request)) {
      self::$request = new Request();
    }

    return self::$request;
  }

  public static function getSession(): Session
  {
    if (!isset(self::$session)) {
      self::$session = new Session();
    }

    return self::$session;
  }

  public static function isAuthenticated(): bool
  {
    if (empty(self::getRequest()->method())) {
      return true;
    }

    return self::getSession()->has('COD_USUARIO');
  }

  public static function isLoggedIn(): bool
  {
    return self::isAuthenticated();
  }

  public static function isAdmin(): bool
  {
    if (self::isAuthenticated()) {
      return (self::getSession()->COD_NIVEL == C::COD_NIVEL_ADM);
    }

    return false;
  }

  public static function checkAuthenticated(): void
  {
    if (!self::isAuthenticated()) {
      self::setToJsonResponse();
      echo json_encode([
        'erro' => [
          'cod' => '001',
          'txt' => 'Usuário não autenticado'
        ]
      ]);
      die();
    }
  }

  public static function execb(string $cmd): int
  {
    return pclose(popen("start /B $cmd", 'r'));
  }

  public static function redirectTo(string $location, array $args=[]): void
  {
    if (!empty($args)) {
      $indArgs = strpos($location, '?');
      if ($indArgs === false) {
        $location .= "?";
      } else {
        $location = rtrim($location, '&') . '&';
      }
      $location .= http_build_query($args);
    }

    header("Location:$location");

    die();
  }

  public static function setToOctetResponse(string $filename, int $bytes): void
  {
    self::setContentTypeResponse('octet/stream');
    self::setToAttachmentResponse($filename);
    self::setContentLengthResponse($bytes);
    self::$isjsonresponse = false;
    self::$istxtresponse = false;
    self::$iscsvresponse = true;
    self::setToUnlimitedTime();
  }

  public static function setToJsonResponse(string $charset = C::DEFAULT_CHARSET): void
  {
    self::setContentTypeResponse('application/json', $charset);
    self::$isjsonresponse = true;
    self::$istxtresponse = false;
    self::$iscsvresponse = false;
  }

  public static function setToTxtResponse(string $charset = C::DEFAULT_CHARSET): void
  {
    self::setContentTypeResponse('application/text', $charset);
    self::$isjsonresponse = false;
    self::$istxtresponse = true;
    self::$iscsvresponse = false;
    self::setToUnlimitedTime();
  }

  public static function setToCsvResponse(string $charset = C::DEFAULT_CHARSET): void
  {
    self::setContentTypeResponse('application/csv', $charset);
    self::$isjsonresponse = false;
    self::$istxtresponse = false;
    self::$iscsvresponse = true;
    self::setToUnlimitedTime();
  }

  public static function setToAttachmentResponse(string $filename): void
  {
    $header = "Content-Disposition:attachment";

    if (!empty($filename)) {
      $filename = str_replace(' ', '_', $filename);
      $header .= ";filename=\"$filename\"";
    }

    header($header);
    self::setToUnlimitedTime();
  }

  public static function setToDownloadResponse(string $filename): void
  {
    self::setToAttachmentResponse($filename);
  }

  public static function setToDownloadCSVResponse(string $filename, ?string $charset = C::DEFAULT_CHARSET): void
  {
    self::setToCsvResponse($charset);
    self::setToAttachmentResponse($filename);
  }
}
