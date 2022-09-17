<?php

namespace App\Models;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PDOException;
use \PDO;

class DbDeleteController{

  private $container;

  public function __construct($constainer)
  {
    $this->container = $constainer;
  }

  public function __invoke(Request $request, Response $response, array $args)
  {
    $id = $args["id"];

    $sql = "DELETE FROM todo WHERE id = $id";

    try{
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);

      //  実行
      $result = $stmt->execute();

      $db = null;

      echo "消去完了";

      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);

    }catch(PDOException $e){
      $error = array(
        "message" => $e->getMessage()
      );

      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
  }
}
