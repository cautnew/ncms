<?php

$filename = "base_apar_01.csv";

$datInicial = new DateTime('2015-01-01');
$datFinal = new DateTime('2020-01-31');

$specialWeekDays = require_once('teste_create_database_specialweekdays.php');
$specialDays = require_once('teste_create_database_specialdays.php');
$products = require_once('teste_create_database_products.php');
$stores = require_once('teste_create_database_stores.php');

define('MAXIDPRODUCT', count($products) - 1);
define('MAXIDSTORE', count($stores) - 1);

$turn = true;
$samecustomer = false;
$idStore = rand(0, MAXIDSTORE);
$customerId = 1;

$columns = ["date", "weekday", "store", "in_mall", "customer", "product", "quantity", "price"];
$dataContent = "\"" . implode("\";\"", $columns) . "\"\n";

for ($dat = $datInicial; $dat <= $datFinal; $dat->modify('+1 day')) {
  $weightDay = $specialDays[$dat->format('Y-m-d')]['weight'] ?? 1;
  if ($weightDay == 0) {
    continue;
  }
  $weekday = (int)$dat->format('w');
  $specialWeekDay = $specialWeekDays[$weekday];
  $weekdayWeight = $specialWeekDay['weight'];

  $quantity = (int)(rand($specialWeekDay['minsale'], $specialWeekDay['maxsale']) * $weightDay);

  for ($numSell = 0; $numSell < $quantity; $numSell++) {
    $idStore = ($samecustomer) ? $idStore : rand(0, MAXIDSTORE);
    $store = $stores[$idStore];
    if ($store['isonstreet'] && $weekday === 0) {
      $idStore += 1;
      $store = $stores[$idStore];
    }

    $idProduct = $turn ? rand($store['minidproduct'] ?? 0, $store['maxidproduct']) : 0;
    if ($idProduct > 0) {
      $idProduct = (int)($idProduct * $store['weightproductexpensive'] ?? 1);
      if ($idProduct > $store['maxidproduct']) {
        $idProduct = $turn ? rand($store['minidproduct'] ?? 0, $store['maxidproduct']) : 0;
      }
    }
    $product = $products[$idProduct];

    // $dataContent .= "\"{$dat->format('Y-m-d')}\";\"{$weekday}\";\"{$store['name']}\";\"" . $store['isonstreet'] ? 'N' : 'Y' . "\";\"\";\"{$product['name']}\";\"1\";\"{$product['price']}\"\n";
    $dataContent .= "\"{$dat->format('Y-m-d')}\";\"{$weekday}\";\"{$store['name']}\";\"" . ($store['isonstreet'] ? 'N' : 'Y') . "\";\"customer-{$customerId}\";\"{$product['name']}\";\"1\";\"{$product['price']}\"\n";
    $turn = !$turn;
    $samecustomer = (bool)rand(0, 1);
    if (!$samecustomer) {
      $customerId += 1;
    }
  }
}

file_put_contents($filename, $dataContent);
