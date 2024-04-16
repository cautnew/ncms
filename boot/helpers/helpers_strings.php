<?php

function str_translate_special_chars(string $str): string
{
  $str = strtr($str, utf8_decode(STRING_SPECIALCHARS), STRING_SPECIALCHARS_SANITIZED);
  $str = trim($str);

  return $str;
}

function str_sanity_space(string $str): string
{
  $str = preg_replace('/\s\s+/', ' ', $str);
  $str = trim($str);

  return $str;
}

function str_sanity_dash(string $str, string $replacement = '-'): string
{
  $str = preg_replace('/\s+/', $replacement, $str);
  $str = trim($str);

  return $str;
}

function str_slug(string $string): string
{
  $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $string = utf8_decode($string);
  $string = str_translate_special_chars($string);
  $string = str_replace(' ', '-', $string);
  $string = preg_replace('/--+/', '-', $string);

  return $string;
}

function str_var_name(string $string): string
{
  $string = str_slug($string);
  $string = str_replace('-', '_', $string);

  return $string;
}

function str_studly_case(string $string): string
{
  $string = str_slug($string);
  $string = str_replace("-", " ", $string);
  $string = mb_convert_case($string, MB_CASE_TITLE);
  $string = str_replace(" ", "", $string);

  return $string;
}

function str_sanitize_for_query(?string $string): string
{
	if (empty($string)) {
		return '';
	}

	$string = str_replace("\n", "\\n", $string);
  $string = addslashes($string);

  return $string;
}

function str_camel_case(string $string): string
{
  return lcfirst(str_studly_case($string));
}

function str_between(string $str, string $strBeg, string $strEnd, int $offset = 0): ?string
{
  $posBeg = strpos($str, $strBeg, $offset);

  if ($posBeg === false) {
    return '';
  }

  $ptBeg = $posBeg + strlen($strBeg);
  $ptEnd = strpos($str, $strEnd, $ptBeg);

  if ($ptEnd === false) {
    return '';
  }

  $length = $ptEnd - $ptBeg;

  return substr($str, $ptBeg, $length);
}
