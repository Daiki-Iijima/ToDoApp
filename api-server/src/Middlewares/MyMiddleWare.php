<?php

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MyMiddleWare implements MiddlewareInterface{

  private $msg;

  public function __construct(string $msg)
  {
    $this->msg = $msg;
  }

  public function process(Request $request,RequestHandlerInterface $handler): Response{
    echo "befor:($this->msg)";
    $response = $handler->handle($request);
    echo "after:($this->msg)";
    return $response;
  }
}
