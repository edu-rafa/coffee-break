<?php
namespace App\Routing;

require_once '..\App\Controllers\AuthController.php';
require_once '..\App\Controllers\UsersController.php';
require_once '..\App\Controllers\CoffeeTypesController.php';

use App\Controllers\AuthController;
use App\Controllers\UsersController;
use App\Controllers\CoffeeTypesController;

class ApiRouter
{
    private $authController;
    private $usersController;
    private $coffeeTypesController;

    public function __construct() {
        $this->authController        = new AuthController();
        $this->usersController       = new UsersController();
        $this->coffeeTypesController = new CoffeeTypesController();
    }

    public function routeRequest($method, $path, $data = []) {
        $path = str_replace('/coffeeBreak', '', $path);
        $path = array_values(array_filter(explode('/', $path)));

        if ($path[0] === 'users') {
            $this->handleUsersEndpoint($method, $data, $path);

        } elseif ($path[0] === 'login') {
            $this->handleLoginEndpoint($data);

        } elseif ($path[0] === 'totalizer') {
            $this->handleTotalizerEndpoint($path);

        } elseif ($path[0] === 'ranking') {
            $this->handleRankingEndpoint($data);

        } elseif ($path[0] === 'CoffeeTypes') {
            $this->handleCoffeeTypesEndpoint();

        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found'], JSON_PRETTY_PRINT);
        exit();
        }

    }

    private function authenticateRequest($data) {
        return $this->authController->authenticateByTokenEndpoint($data);
    }

    private function authenticateRequestLogin($email, $pass) {
        return $this->authController->authenticateByLoginEndpoint($email, $pass);
    }

    private function handleUsersEndpoint($method, $data, $path) {
        switch ($method) {
            case 'GET':
                if (isset($data['token'])) {
                    $data['iduser'] = $path[1] ?? null;
                    $this->authenticateRequest($data['token']);

                    if (!isset($path[1])) {
                        $this->usersController->getAllUsers($data);
                    } elseif (is_numeric($path[1])) {
                        $this->usersController->getUserById($data['iduser']);
                    }
                }

                break;
            case 'POST':
                if (!isset($path[1])) {
                    $this->usersController->createUser($data);
                } elseif (is_numeric($path[1]) && isset($data['token'])) {
                    $data['iduser'] = $path[1];
                    $return = $this->authenticateRequest($data['token']);

                    if ($return) {
                        $this->usersController->addCoffee($data);
                    }
                }

                break;
            case 'PUT':
                if (isset($path[1]) && is_numeric($path[1]) && isset($data['token'])) {
                    $data['iduser'] = $path[1];
                    $this->authenticateRequest($data['token']);
                    $this->usersController->editUser($data);
                }
                break;
            case 'DELETE':
                if (isset($path[1]) && is_numeric($path[1]) && isset($data['token'])) {
                    $data['iduser'] = $path[1];
                    $this->authenticateRequest($data['token']);
                    $this->usersController->deleteUser($data);
                }

                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method Not Allowed'], JSON_PRETTY_PRINT);
                exit();
        }
    }

    private function handleLoginEndpoint($data) {
        if (isset($data['email']) && isset($data['password'])) {
            $email = $data['email'];
            $pass  = md5($data['password']);

            $this->authenticateRequestLogin($email, $pass);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'One or more required parameters are missing.'], JSON_PRETTY_PRINT);
            exit();
        }
    }

    private function handleTotalizerEndpoint($path) {
        $iduser = $path[1] ?? null;

        if (isset($path[1])) {
            $this->usersController->totalizerDrinksById($iduser);
        } else {
            $this->usersController->totalizerDrinks($iduser);
        }
    }

    private function handleRankingEndpoint($data) {
        $this->usersController->ranking($data);
    }

    private function handleCoffeeTypesEndpoint() {
        $this->coffeeTypesController->getAllTypes();
    }
}
