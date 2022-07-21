<?php

require_once(dirname(__DIR__).'/core/Application.php');

class Database 
{
  public \PDO $pdo;
  static $ROOT_DIR;

  public function __construct(array $config)
  {
    $dsn = $config['dsn'] ?? '';
    $user = $config['user'] ?? '';
    $password = $config['password'] ?? '';

    $this->pdo = new \PDO($dsn, $user, $password);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  public function applyMigrations(){
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();

    $newMigrations = [];


    $files = scandir(dirname(__DIR__).'/migrations');
    $toApplyMigrations = array_diff($files, $appliedMigrations);

    foreach($toApplyMigrations as $migration){
      if($migration === '.' or $migration === '..'){
        continue;
      }

      require_once(dirname(__DIR__)."\migrations\\".$migration);
      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className();
      echo PHP_EOL."Applying Migration $migration".PHP_EOL;
      $instance->up();
      echo "Applied Migration $migration".PHP_EOL.PHP_EOL;
      $newMigrations[] = $migration;
      // echo '<pre>';
      // var_dump($className);
      // echo '</pre>';

    }

    if(!empty($newMigrations)){
      $this->saveMigrations($newMigrations);
    } else {
      echo 'All migrations are applied';
    }

    // echo '<pre>';
    // var_dump($toApplyMigrations);
    // echo '</pre>';
    // exit;
  }

  public function createMigrationsTable()
  {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
      id INT AUTO_INCREMENT PRIMARY KEY,
      migration VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;");
  }

  public function getAppliedMigrations()
  {
    $statement = $this->pdo->prepare("SELECT migration FROM migrations");
    $statement->execute();

    return $statement->fetchAll(\PDO::FETCH_COLUMN);
  }

  public function saveMigrations(array $migrations){
    
    $str = implode(",", array_map(fn($m) => "('$m')", $migrations));

    $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES 
      $str
    ");

    $statement->execute();

  }
}