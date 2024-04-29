<?php

namespace App\Models;

use Core\Model;
class Users extends Model {
    public function getAllUsers($data) {
        $perPage = empty($data['perPage']) ? 10 :$data['perPage'];
        $page    = empty($data['page']) ? 1 : $data['page'];
        $page    = ($page - 1) * $perPage;
        $pdo     = $this->db->query("SELECT iduser, name, email FROM users LIMIT $perPage OFFSET $page");
        $pdo->execute();

        return $pdo->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function authenticate($email) {
        $pdo = $this->db->prepare('SELECT iduser, email, name, password, token FROM users
            WHERE email = :email');
        $pdo->bindParam(':email', $email);
        $pdo->execute();

        return $pdo->fetch(\PDO::FETCH_ASSOC);
    }

    public function createUser($data) {

        if (!isset($data['name']) &&!isset($data['password']) && !isset($data['email'])) {
            $result['message'] = 'Name, email, and password are mandatory fields.';
            $result['code']    = 400;
            return $result;
        }

        $username       = $data['name'];
        $hashedPassword = md5($data['password']);
        $email          = $data['email'];
        $token          = $data['token'];

        try {
            $query = "INSERT INTO users (name, password, email, token) 
                VALUES (:username, :password, :email, :token)";

            $pdo   = $this->db->prepare($query);

            $pdo->bindParam(':username', $username);
            $pdo->bindParam(':password', $hashedPassword);
            $pdo->bindParam(':email', $email);
            $pdo->bindParam(':token', $token);
            $pdo->execute();

            $result['message'] = 'User registration successful!';
            $result['code']    = 200;
        } catch (\PDOException $e) {
            $result['message'] = 'User already exists';
            $result['code']    = 400;
        }

        return $result;
    }

    public function getUserById($idUser) {
        $pdo = $this->db->prepare('SELECT * FROM users WHERE iduser = :iduser');
        $pdo->bindParam(':iduser', $idUser);
        $pdo->execute();

        $return = $pdo->fetch(\PDO::FETCH_ASSOC);

        unset($return['password']);
        unset($return['token']);

        if (!empty($return)) {
            $result['data'] = $return;
            $result['code'] = 200;
        } else {
            $result['data'] = 'Error: User not found';
            $result['code'] = 404;
        }

        return $result;
    }

    public function authenticateToken($token) {
        $sql = 'SELECT iduser FROM users WHERE token = :token';
        $pdo = $this->db->prepare($sql);

        $pdo->bindParam(':token', $token);
        $pdo->execute();

        return $pdo->fetch(\PDO::FETCH_ASSOC);
    }

    public function editUser($data) {
        $idUser = $data['iduser'];
        $name   = $data['name'] ?? null;
        $pass   = $data['password'] ?? null;

        if (!empty($name) || !empty($pass)) {
            $parametros = array();
            $sql        = 'UPDATE users SET ';

            if (!empty($name)) {
                $sql .= 'name = :name, ';
                $parametros[':name'] = $name;
            }

            if (!empty($pass)) {
                $sql .= 'password = :password, ';
                $parametros[':password'] = md5($pass);
            }
        
            $sql = rtrim($sql, ', ');
        
            $sql .= ' WHERE iduser = :iduser';
            $parametros[':iduser'] = $idUser;

            try {
                $pdo = $this->db->prepare($sql);
                $pdo->execute($parametros);
        
                if ($pdo->rowCount() > 0) {
                    return $this->getUserById($idUser);
                } else {
                    $result['code'] = 404;
                    $result['data'] = ["Error" => 'User not found'];
                    return $result;
                }
            } catch (\PDOException $e) {
                $result['code'] = 500;
                $result['data'] = ["Error" => 'Ops!'];
                return $result;
            }
        }
    }

    public function deleteUser($data) {
        $query = "DELETE FROM users WHERE iduser = :iduser";
        $pdo = $this->db->prepare($query);
        $pdo->bindParam(':iduser', $data['iduser']);
        $pdo->execute();
    
        return true;
    }
}
