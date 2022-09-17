<?php

namespace App\Models;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PDOException;

class DbAddController
{

  private $container;

  //  slimに自動で引数が渡される
  public function __construct($constainer)
  {
    $this->container = $constainer;
  }

  public function __invoke(Request $request, Response $response, array $args)
  {
    $data = $request->getParsedBody();
    $group_name = $data["group_name"];
    $title = $data["title"];
    $description = $data["description"];
    $date = $data["date"];

    $sql = "INSERT INTO todo (group_name, title, description, date) VALUES (:group_name, :title, :description, :date)";

    try {
      $db = new Db();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':group_name', $group_name);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':date', $date);

      $result = $stmt->execute();

      $db = null;
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
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
