<?php

/**
 * Resources
 * Configurations for routes and their corresponding resources
 * @file resources.php
 */

return [
  'GET' => [
    '/' => [
      'pattern' => '/ncms/admin/page-type.php',
      'callback' => 'GET'
    ],
    '/ncms/admin' => [
      'pattern' => '/ncms/admin/admin.php',
      'callback' => 'GET'
    ],
    '/ncms/admin/config/db' => [
      'pattern' => '/ncms/admin/db/config-db.php',
      'callback' => 'GET'
    ],
    '/ncms/admin/{qt}' => [
      'pattern' => '/ncms/admin/admin.php',
      'callback' => 'GET'
    ]
  ]
];
