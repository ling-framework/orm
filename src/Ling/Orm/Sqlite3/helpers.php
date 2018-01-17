<?php

namespace Ling\Orm\Sqlite3;
/**
 * helper function
 * only basic support
 * if there are more attributes to set, please set later in init script
 *
 * @param $filepath
 * @return \PDO
 */
function pdo($filepath) {
    $pdo = new \PDO('sqlite:' . $filepath);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
}
