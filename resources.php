<?php

/**
 * Resources
 * Configurations for routes and their corresponding resources
 * @file resources.php
 */

return [
  'GET' => [
    '/' => [
      'path' => '/ncms/admin/page-type.php',
      'method' => 'GET'
    ],
    '/ncms/admin' => [
      'path' => '/ncms/admin/admin.php',
      'method' => 'GET'
    ],
    '/ncms/admin/config/db' => [
      'path' => '/ncms/admin/db/config-db.php',
      'method' => 'GET'
    ],
    '/ncms/admin/{qt}' => [
      'path' => '/ncms/admin/admin.php',
      'method' => 'GET'
    ]
  ]
];
