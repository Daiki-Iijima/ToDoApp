<?php

namespace App\Models;

use \PDO;

class DB{
  //  ここに入っている値は一例
  private $host = 'localhost';
  private $user = 'root';
  private $pass = 'root';

  //  データベース名
  private $dbname = 'todo_db';

  private $charset = 'utf8';

  public function connect(){
    $conn_str = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset";
    $conn = new PDO($conn_str,$this->user,$this->pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    return $conn;
  }
}
