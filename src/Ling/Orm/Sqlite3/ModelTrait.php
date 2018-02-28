<?php
namespace Ling\Orm\Sqlite3;

trait ModelTrait {
    public function preInit() {
        $this->orm = new Orm();
    }
}