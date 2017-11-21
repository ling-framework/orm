<?php
namespace Ling\Orm\Sqlite3;

class Orm extends \Ling\Orm\Orm {
    protected /** @noinspection ClassOverridesFieldOfSuperClassInspection */ $now = "DateTime('now')";
    const CONFIG_KEY = 'orm.pdo.sqlite3';
}


