<?php

namespace App\Models;

use PDO;
use Src\Core\WebHelper;
use Src\Database\Connection;


class AppModel extends Connection
{
    private const TABLE = 'users';
    private PDO $db;
    private WebHelper $webHelper;

    public function __construct()
    {
        parent::__construct();
        $this->db = Connection::getInstance();
        $this->webHelper = new WebHelper();
    }
}
