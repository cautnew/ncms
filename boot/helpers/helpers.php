<?php

require_once __DIR__ . '/helpers_arrays.php';
require_once __DIR__ . '/helpers_dates.php';
require_once __DIR__ . '/helpers_pdos.php';
require_once __DIR__ . '/helpers_strings.php';
require_once __DIR__ . '/helpers_urls.php';

function session(): Core\Support\Session
{
  return new Core\Support\Session();
}

function request(): Core\Route\Request
{
  return new Core\Route\Request();
}

$const = function ($nome)
{
  return $nome;
};

$cwd = function(): string
{
  return getcwd();
};

function scriptstring($path): string
{
  ob_start();
  include $path;
  return ob_get_clean();
}

function redirect($location, $args=[]): void
{
  if(!empty($args)) {
    $indArgs = strpos($location, '?');
    if ($indArgs === false) {
      $location .= "?";
    } else {
      $location = rtrim($location, '&') . '&';
    }
    $location .= http_build_query($args);
  }

  header("Location:{$location}");

  die();
}

function codTimeStamp(): string
{
  return date(FORMAT_CODTIMESTAMP);
}

function encrypt_args(array $args): string
{
  $strArgs = http_build_query($args);
  $strArgs = base64_encode($strArgs);

  return $strArgs;
}

function decrypt_args(string $strArgs): array
{
  $args = [];
  $strArgs = base64_decode($strArgs);
  parse_str($strArgs, $args);

  return $args;
}

function prep_update_sets($listValues): string
{
  $r = '';
  foreach ($listValues as $k => $v) {
    $r .= "`{$k}`='{$v}',";
  }
  $r = rtrim($r, ',');

  return $r;
}

function is_email(string $email): bool
{
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_pw(string $password): bool
{
  if (password_get_info($password)['algo']) {
    return true;
  }

  return (mb_strlen($password) >= PW_MIN_LEN && mb_strlen($password) <= PW_MAX_LEN ? true : false);
}

function pw(string $password): string
{
  return password_hash($password, PW_DEFAULT_ALGO, PW_OPTION);
}

function pw_verify(string $password, string $hash): bool
{
  return password_verify($password, $hash);
}

function pw_rehash(string $hash): bool
{
  return password_needs_rehash($hash, PW_DEFAULT_ALGO, PW_OPTION);
}

function csrf_input(): string
{
  session()->csrf();
  $tkn = (session()->csrf_token ?? '');
  return "<input type=\"hidden\" name=\"csrf\" value=\"{$tkn}\"/>";
}

function csrf_verify($request): bool
{
  if (empty(session()->csrf_token) || empty($request['csrf']) || $request['csrf'] != session()->csrf_token) {
    return false;
  }

  return true;
}

function isInteger($input): bool
{
  return (ctype_digit(strval($input)));
}

function ifnull($varcheck, $varifnull)
{
  return ($varcheck === null) ? $varifnull : $varcheck;
}

function split_data_from_base64_uri($uri): array {
  list($file_type, $data) = explode(";base64,", $uri);
  list($file_type, $ext) = explode("/", $file_type);

  return [$file_type, $ext, $data];
}
