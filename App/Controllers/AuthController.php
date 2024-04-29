<?php
namespace App\Controllers;

use App\Models\Users;

use App\Models\CoffeeBreak;

class AuthController
{
    private $users;
    private $coffeeBreak;

    public function __construct() {
        $this->users = new Users();
        $this->coffeeBreak = new CoffeeBreak();
    }

    public function authenticateByTokenEndpoint($token) {
        if (isset($token)) {
            $result = $this->users->authenticateToken($token);

            if ($result === false) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid Token'], JSON_PRETTY_PRINT);
                exit();
            } else {
                return true;
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Token not provided'], JSON_PRETTY_PRINT);
            exit();
        }
    }

    public function authenticateByLoginEndpoint($email, $password) {
        $result = $this->users->authenticate($email);

        if ($result === false) {
            http_response_code(404);
            echo json_encode(['error' => 'User does not exist'], JSON_PRETTY_PRINT);
            exit();
        } elseif ($password == $result['password']) {
            $result = $this->coffeeBreak->drinkCounter($result);

            unset($result['password']);
            http_response_code(200);
            echo json_encode($result, JSON_PRETTY_PRINT);
            exit();
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid password'], JSON_PRETTY_PRINT);
            exit();
        }
    }
}
