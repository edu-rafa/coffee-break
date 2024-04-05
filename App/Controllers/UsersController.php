<?php

namespace App\Controllers;

require_once '..\App\Models\CoffeeBreak.php';

require_once '..\App\Models\Users.php';

use App\Models\Users;

use App\Models\CoffeeBreak;

class UsersController
{
    private $users;
    private $coffeeBreak;

    public function __construct() {
        $this->users = new Users();
        $this->coffeeBreak = new CoffeeBreak();
    }

    public function createUser($data) {
        $data['token'] = $this->generateToken();
        $result        = $this->users->createUser($data);

        http_response_code($result['code']);
        echo json_encode($result, JSON_PRETTY_PRINT);
        exit();
    }

    public function addCoffee($data) {
        $user = $this->users->getUserById($data['iduser']);

        if ($user['code'] === 200) {
            $user['data'] = array_merge($data, $user['data']);
            $user = $this->coffeeBreak->addCoffee($user);

            unset($user['data']['coffee_qty']);
            unset($user['data']['token']);
            unset($user['data']['iduser']);
            unset($user['data']['email']);
            unset($user['data']['password']);
        }

        http_response_code($user['code']);
        echo json_encode($user['data'], JSON_PRETTY_PRINT);
    }

    public function getUserById($iduser) {
        $result = $this->users->getUserById($iduser);
        $users  = $result['data'];
        $code   = $result['code'];

        if ($code == 200) {
            $users = $this->coffeeBreak->drinkCounter($users);
        }

        echo json_encode($users, JSON_PRETTY_PRINT);
        http_response_code($code);
        exit();
    }

    public function getAllUsers($data) {
        if (!isset($data['perPage']) || !isset($data['page'])) {
            $data['perPage'] = 10;
            $data['page']    = 1;
        }

        $result = $this->users->getAllUsers($data);

        if (!empty($result)) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        }

        http_response_code(200);
        exit();
    }

    public function editUser($data) {
        $result = $this->users->editUser($data);

        http_response_code($result['code']);
        echo json_encode($result['data'], JSON_PRETTY_PRINT);

    }

    public function deleteUser($data) {
        $user  = $this->users->getUserById($data['iduser']);
        $users = $user['data'];
        $code  = $user['code'];

        $this->users->deleteUser($data);

        echo json_encode($users, JSON_PRETTY_PRINT);
        http_response_code($code);
        exit();
    }

    public function generateToken($length = 32) {
        $randomBytes = random_bytes($length);
        return bin2hex($randomBytes);
    }

    public function totalizerDrinksById($iduser) {
        $result = $this->coffeeBreak->totalizerDrinksById($iduser);

        if (!empty($result)) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        }
        
        http_response_code(200);
    }

    public function totalizerDrinks() {
        $result = $this->coffeeBreak->totalizerDrinks();

        if (!empty($result)) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        }
        
        http_response_code(200);
    }

    public function ranking($data) {
        $result = $this->coffeeBreak->ranking($data);

        if (!empty($result)) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        }
        
        http_response_code(200);
    }
}
