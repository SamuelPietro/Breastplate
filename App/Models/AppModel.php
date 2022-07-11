<?php

declare(strict_types=1);

namespace App\Models;

use Core\Dao;

class AppModel extends Dao
{
    private string $table;

    public function __construct($table)
    {
        $this->table = $table;
        parent::__construct($this->table);
    }
}
