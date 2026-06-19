<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Pdo\Connection;

use PDO;

interface PdoConnectionInterface
{
    public function getPDO(): PDO;
}
