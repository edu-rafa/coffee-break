<?php
namespace Public;

require_once __DIR__ . '/../autoload.php';

use App\Routing\ApiRouter;

$method  = $_SERVER['REQUEST_METHOD'];
$path    = $_SERVER['REQUEST_URI'];
$putData = file_get_contents("php://input");
$putData = json_decode($putData, true);
$data    = $putData;
$token   = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

if (strpos($token, 'Bearer ') === 0) {
    $token = substr($token, 7);
}

$data['token'] = $token;
$router = new ApiRouter();
$router->routeRequest($method, $path, $data);
