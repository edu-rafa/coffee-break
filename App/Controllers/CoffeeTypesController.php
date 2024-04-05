<?php

namespace App\Controllers;

require_once '..\App\Models\CoffeeTypes.php';

use App\Models\CoffeeTypes;

class CoffeeTypesController
{
    private $coffeeTypes;

    public function __construct() {
        $this->coffeeTypes = new CoffeeTypes();
    }

    public function getAllTypes() {
        $result = $this->coffeeTypes->getAllTypes();
        echo json_encode($result, JSON_PRETTY_PRINT);
        http_response_code(200);
        exit();
    }
}