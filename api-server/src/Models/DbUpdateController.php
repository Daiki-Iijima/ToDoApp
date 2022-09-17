<?php

namespace App\Models;

use App\Models\DB;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use PDOException;
use \PDO;

class DbUpdateController{

  private $container;

  public function __construct($container)
  {
    $this->container = $container;
  }

  //  パラメーター : id=xxx
  //  body : name,email.phone
  public function __invoke(Request $request, Response $response, array $args){
    //  リクエストに含まれているはずのidの値を取得
    //  ルートバインディング時に[customers-data/update/{id}]として設定している
    $id = $request->getAttribute('id');
    //  Bodyをパース
    $data = $request->getParsedBody();
    //  パースしたデータから値を取得
    $group_name = $data["group_name"];
    $title = $data["title"];
    $description = $data["description"];
    $date = $data["date"];

    $sql = "UPDATE todo SET
      group_name = :group_name,
      title = :title,
      description = :description,
      date = :date
      WHERE id = $id";

    try{
      //  DBと接続
      $db = new DB();
      $conn = $db->connect();

      //  SQL文のパラメーター部分に値をバインド
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':group_name', $group_name);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':date', $date);

      //  実行 & 実行結果の格納
      $result = $stmt->execute();

      //  接続を消去
      $db = null;

      //  ここまで来たら成功しているのでクライアントに成功表示
      echo "Update successful!";

      //  成功を知らせるためにBodyに結果を正式に記述
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
