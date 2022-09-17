<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IPShowMiddleware implements MiddlewareInterface{

  public function process(Request $request,RequestHandlerInterface $handler): Response{

    $response = $handler->handle($request);

    $ipAddress = $request->getServerParams('REMOTE_ADDR');

    foreach($ipAddress as &$value){
      echo "<br>";
      echo $value;
      echo "<br>";
    }

    return $response;
  }
}
