<?php
namespace Ling\Orm\Sqlite3;

trait OrmTrait {
    public function now() {
        return "DateTime('now','localtime')";
    }
}