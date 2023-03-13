<?php

namespace Src\Database;

use PDO;

interface ConnectionInterface
{
    public function connect(): PDO;
    public function disconnect(): void;
}
