<?php

declare(strict_types=1);

namespace App\Models;

use Core\Dao;

class AppModel extends Dao
{
    public function __construct()
    {
        $this->dao = new Dao('clientes');
    }
}
