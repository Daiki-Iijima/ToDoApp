<?php

//  App\ : composer.jsonで定義されている
use App\Models\DB;
use App\Models\DbGetController;
use App\Models\DbAddController;
use App\Models\DbUpdateController;
use App\Models\DbDeleteController;

use App\MiddleWares\MyMiddleWare;
use App\MiddleWares\IPShowMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// ミドルウェアの登録
// ルーティングミドルウェアを登録している
$app->addRoutingMiddleware();

// JSON,XML形式のデータのパース処理を受け持つ
$app->addBodyParsingMiddleware();

$app->add(new BasePathMiddleware($app));

// エラー時のハンドリングをslimにやってもらう
// 表示されるエラーは以下のようになる
// 第1引数 : エラーの詳細を画面に表示
// 第2引数 : サーバー内にエラーの詳細を出力
// 第3引数 : 例外の投げ直しの場合、もとの例外の内容もログに表示
$app->addErrorMiddleware(true,true,true);

// 自作
// $app->add(new MyMiddleWare("A"));
// $app->add(new MyMiddleWare("B"));
// $app->add(new MyMiddleWare("C"));
// $app->add(new IPShowMiddleware());

$app->get('/', function(Request $request, Response $response){

  echo "hello";

  return $response;

});

// ルーティングの登録
// GETメソッドでのアクセスの場合
$app->get('/todo/all', DbGetController::class);

// POST処理のリクエストの場合
$app->post('/todo/add', DbAddController::class);

//  更新
$app->put('/todo/update/{id}',DbUpdateController::class);

//  消去
$app->delete('/todo/delete/{id}',DbDeleteController::class);

$app->run();
