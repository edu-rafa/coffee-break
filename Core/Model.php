<?php

namespace Core;

require_once '..\Core\Database.php';

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
