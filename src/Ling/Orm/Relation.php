<?php

namespace Ling\Orm;

class Relation {
    public $relatedTable = '';
    public $conditions = array();

    public function __construct() {
    }

    public function condition (array $onConditions) {
    }
}