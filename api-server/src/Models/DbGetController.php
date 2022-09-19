<?php

namespace App\Models;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PDOException;
use \PDO;

class DbGetController
{

  private $container;

  //  slimに自動で引数が渡される
  public function __construct($constainer)
  {
    $this->container = $constainer;
  }

  public function __invoke(Request $request, Response $response, array $args)
  {
    try{
      $sql = "SELECT * FROM todo";

      $db = new Db();
      $conn = $db->connect();
      $stmt = $conn->query($sql);
      $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;

      //  JSON_UNESCAPED_UNICODE : これを入れないと、JsonがUnicodeとして変換される
      $response->getBody()->write(json_encode($customers,JSON_UNESCAPED_UNICODE));
      return $response;
    }catch(PDOException $e){
      $error = array(
        "message" => $e->getMessage()
      );

      $response->getBody()->write(json_encode($error,JSON_UNESCAPED_UNICODE));
      return $response
        ->withHeader('content-type','application/json')
        ->withStatus(500);
    }
  }
}
