<?php

require_once(dirname(__DIR__).'/core/Application.php');
require_once(dirname(__DIR__).'/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

class m0001_initial {
  public function up()
  {
    $config = [
      'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
      ]
    ]; 

    echo __DIR__.'/core/Application.php';

    $app = new Application(__DIR__, $config);
    $db = $app->db;

    $SQL = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            status TINYINT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
          ) ENGINE=INNODB;";

    $db->pdo->exec($SQL);
    echo "migration executed";

  }

  public function down()
  {
    echo "Down migration".PHP_EOL;
  }

}

$instance = new m0001_initial();
$instance->up();