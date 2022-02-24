<?php

/**
 * @file
 */

$databases['default']['default'] = [
  'database' => 'prueba',
  'username' => 'drupal',
  'password' => 'drupal',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  // 'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
  // 'collation' => 'utf8mb4_general_ci',
];

$settings['hash_salt'] = 'bUmc1PV9VzwAXban_VVRtSKKwOBp7QNugOCWzsUrRgyy7Zl7D2dJm-J8Wb5FWOUrDKH7zepC2A';
$config_directories['sync'] = '../config/sync';
$settings['install_profile'] = 'standard';
$settings['file_private_path'] = 'sites/default/files/private';

/**
 * Enable local development services.
 */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/services.local.yml';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['trusted_host_patterns'] = [
  '^prueba\.local\.com$',
  // '^transacciones\.local\.com$',
  // 'tigo.localhost',
];
