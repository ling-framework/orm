<?php
namespace Ling\Orm\Sqlite3;

// this file may not need
trait ModelTrait {
    public function preInit() {
        $this->orm = new Orm();
    }
}