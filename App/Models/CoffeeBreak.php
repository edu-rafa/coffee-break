<?php

namespace App\Models;

use Core\Model;
class CoffeeBreak extends Model {
    public function addCoffee($data) {
        date_default_timezone_set('America/Sao_Paulo');

        $now       = new \DateTime();
        $now       = $now->format('Y-m-d H:i:s');
        $userId    = $data['data']['iduser'];
        $idCoffee  = $data['data']['idCoffee'] ?? 1;
        $coffeeQty = $data['data']['coffee_qty'];
        $logDate   = $data['data']['log_date']?? null;

        if (empty($data['data']['log_date'])) {
            $logDate = $now;
        } else {
            if ($this->validateDateFormat($data['data']['log_date'])) {
                $logDate = $now;
            }
        }

        try {
            $query = "INSERT INTO coffee_users (iduser, id_coffee, coffee_qty, log_date)
                VALUES (:iduser, :id_coffee, :coffee_qty, :log_date)";

            $pdo = $this->db->prepare($query);
            $pdo->bindParam(':iduser', $userId);
            $pdo->bindParam(':id_coffee', $idCoffee);
            $pdo->bindParam(':coffee_qty', $coffeeQty);
            $pdo->bindParam(':log_date', $logDate);
        
            $pdo->execute();
            $drinkCounter = $this->drinkCounter(['iduser' => $userId]);
            $data['data']['drinkCounter'] = $drinkCounter['drinkCounter'];
            return $data;
        } catch (\PDOException $e) {
            $data['code'] = 500;
            $data['data'] = "Error: " . $e->getMessage();
            return $data;
        }
    }

    public function validateDateFormat($date) {
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $date);

        if ($dateTime !== false) {
            return true;
        } else {
            http_response_code(400);
            echo "Invalid date format: $date";
            exit();
        }
        
    }

    public function drinkCounter($data) {
        $pdo = $this->db->prepare('SELECT ct.type, sum(cu.coffee_qty) as coffee_qty FROM coffee_users cu
            JOIN coffee_type ct ON cu.id_coffee = ct.id_coffee
            WHERE cu.iduser = :iduser
            GROUP BY cu.id_coffee');

        $pdo->bindParam(':iduser', $data['iduser']);
        $pdo->execute();

        $result = $pdo->fetchAll(\PDO::FETCH_ASSOC);
        $data['drinkCounter'] = 0;
        foreach ($result as $r) {
            $data[$r['type']] = $r['coffee_qty'];
            $data['drinkCounter'] += $r['coffee_qty'];
        }

        return $data;
    }

    public function totalizerDrinksById($iduser) {
        $query = "SELECT cu.log_date, ct.type, SUM(cu.coffee_qty) AS total
            FROM coffee_users cu
            JOIN coffee_type ct ON cu.id_coffee = ct.id_coffee
            WHERE cu.iduser = :iduser GROUP BY cu.log_date, cu.id_coffee";

        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':iduser', $iduser);
        $pdo->execute();
        $result = $pdo->fetchAll(\PDO::FETCH_ASSOC);
        $data   = [];

        if (!empty($result)) {
            foreach ($result as $item) {
                $data   = $item['log_date'];
                $coffee = $item['type'];
                $total  = $item['total'];
        
                if (!isset($newArray[$data]['all'])) {
                    $newArray[$data]['all'] = 0;
                }
    
                $newArray[$data]['all'] += (int)$total;
    
                if (!isset($newArray[$data][$coffee])) {
                    $newArray[$data][$coffee] = 0;
                }
    
                $newArray[$data][$coffee] += (int)$total;
            }

            return $newArray;
        }


    }

    public function totalizerDrinks() {
        $query = "SELECT cu.iduser, cu.log_date, ct.type, SUM(cu.coffee_qty) AS total
            FROM coffee_users cu
            JOIN coffee_type ct ON cu.id_coffee = ct.id_coffee
            GROUP BY cu.iduser, cu.log_date, cu.id_coffee";

        $pdo = $this->db->prepare($query);
        $pdo->execute();
        $result = $pdo->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $newArray = array();

            foreach ($result as $item) {
                $log_date    = $item['log_date'];
                $iduser      = $item['iduser'];
                $coffee_type = $item['type'];
                $total       = intval($item['total']);
            
                if (!isset($newArray[$log_date])) {
                    $newArray[$log_date] = [];
                }
            
                if (!isset($newArray[$log_date][$iduser])) {
                    $newArray[$log_date][$iduser] = ['all' => 0];
                }
            
                if (!isset($newArray[$log_date][$iduser][$coffee_type])) {
                    $newArray[$log_date][$iduser][$coffee_type] = 0;
                }
            
                $newArray[$log_date][$iduser][$coffee_type] += $total;
                $newArray[$log_date][$iduser]['all']        += $total;
            }

            return $newArray;
        }
    }

    public function ranking($data) {
        $str_date = $data['str_date'];
        $end_date = $data['end_date'];

        $query = "SELECT u.name, SUM(cu.coffee_qty) AS qty FROM coffee_users cu
            JOIN users u on cu.iduser = u.iduser 
            WHERE cu.log_date BETWEEN :str_date AND :end_date
            GROUP BY cu.iduser ORDER BY qty DESC LIMIT 10";

        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':str_date', $str_date);
        $pdo->bindParam(':end_date', $end_date);
        $pdo->execute();

        return $pdo->fetchAll(\PDO::FETCH_ASSOC);
    }
}
