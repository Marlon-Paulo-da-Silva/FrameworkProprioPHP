<?php

require_once(dirname(__DIR__).'/core/Database.php');

class Application
{
  public Database $db;

  public function __construct($rootPath, array $config)
  {
    $this->db =  new Database($config['db']);
  }
}