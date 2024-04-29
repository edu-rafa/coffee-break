<?php

namespace App\Models;

use Core\Model;
class CoffeeTypes extends Model {

    public function getAllTypes() {
        $pdo = $this->db->query("SELECT * FROM coffee_type");
        $pdo->execute();

        return $pdo->fetchAll(\PDO::FETCH_ASSOC);
    }
}
