<?php

require_once(dirname(__DIR__).'/core/Application.php');
require_once(dirname(__DIR__).'/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();



class m0003_add_password_column {
  public function up()
  {

    $config = [
      'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
      ]
    ]; 
    
    
    $app = new Application(__DIR__, $config);
    $db = $app->db;

    $db->pdo->exec("ALTER TABLE users ADD COLUMN password VARCHAR(512) NOT NULL");

  }

  public function down()
  {
    $config = [
      'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
      ]
    ]; 
    
    
    $app = new Application(__DIR__, $config);
    $db = $app->db;

    $db->pdo->exec("ALTER TABLE users DROP COLUMN password;");
  }

}

