<?php

namespace Ling\Orm;

function pdo($dsn) {
    $pdo = new \PDO('mysql:' . $dsn);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
}