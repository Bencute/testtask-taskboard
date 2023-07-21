<?php

namespace System\Db;

use PDO;
use System\Kernel\Config;

class Connect extends PDO
{
    public function __construct()
    {
        $config = Config::get()['db'];
        parent::__construct($config['dsn'], $config['user'], $config['password']);
    }
}