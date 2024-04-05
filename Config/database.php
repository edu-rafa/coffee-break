<?php

return [
    'dsn'      => 'mysql:host=localhost;dbname=db_coffee_break',
    'username' => 'root',
    'password' => '',
    'options'  => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
