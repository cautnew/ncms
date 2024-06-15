<?php

use Core\Lang\LanguageModelInsert;
use Core\Userinfo\Dim\SexModelInsert;

require_once __DIR__ . '/../autoload.php';

$languageModelInsert = new LanguageModelInsert();

$languageModelInsert->var_lang = 'PT-BR';
$languageModelInsert->var_name = 'Portuguese (Brazil)';
$languageModelInsert->insert();
$languageModelInsert->var_lang = 'PT';
$languageModelInsert->var_name = 'Portuguese';
$languageModelInsert->insert();
$languageModelInsert->var_lang = 'EN';
$languageModelInsert->var_name = 'English';
$languageModelInsert->insert();
$languageModelInsert->var_lang = 'ES';
$languageModelInsert->var_name = 'Spanish';
$languageModelInsert->commit();

$sexModelInsert = new SexModelInsert();
$sexModelInsert->var_name = 'Feminino';
$sexModelInsert->var_abrev = 'Femi';
$sexModelInsert->chr_cod = 'F';
$sexModelInsert->var_lang = 'PT-BR';
$sexModelInsert->insert();
$sexModelInsert->var_name = 'Masculino';
$sexModelInsert->var_abrev = 'Masc';
$sexModelInsert->chr_cod = 'M';
$sexModelInsert->var_lang = 'PT-BR';
$sexModelInsert->insert();
$sexModelInsert->var_name = 'Não declarar';
$sexModelInsert->var_abrev = 'NDEC';
$sexModelInsert->chr_cod = 'N';
$sexModelInsert->var_lang = 'PT-BR';
$sexModelInsert->insert();
$sexModelInsert->var_name = 'Outro';
$sexModelInsert->var_abrev = 'OUTR';
$sexModelInsert->chr_cod = 'O';
$sexModelInsert->var_lang = 'PT-BR';
$sexModelInsert->commit();