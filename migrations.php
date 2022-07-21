<?php

// use core\Application;
require_once(dirname(__FILE__) . '/core/Application.php');
require_once __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__.'/FrameworkProprioPHP'));
$dotenv->load();


$config = [
    'db' => [
      'dsn' => $_ENV['DB_DSN'],
      'user' => $_ENV['DB_USER'],
      'password' => $_ENV['DB_PASSWORD']
    ]
  ];

$app = new Application(__DIR__, $config);

$app->db->applyMigrations();