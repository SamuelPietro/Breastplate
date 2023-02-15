<?php

namespace App\Models;

use PDO;
use Src\Core\WebHelper;
use Src\Database\Connection;

/**
 * Base classes for application models.
 */
class AppModel extends Connection
{
    private const TABLE = 'users';
    private PDO $db;
    private WebHelper $webHelper;

    /**
     * Creates a new instance of the model.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = Connection::getInstance();
        $this->webHelper = new WebHelper();
    }
}
