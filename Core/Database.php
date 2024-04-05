<?php

namespace Core;

class Database
{
    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            $config = require '..\Config\database.php';
            self::$instance = new \PDO(
                $config['dsn'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        }

        return self::$instance;
    }
}
