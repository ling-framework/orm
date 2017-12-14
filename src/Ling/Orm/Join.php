<?php
namespace Ling\Orm;

class Join {
    public $prefix = ''; // from b...
    public $tableName = ''; // table or inner select
    public $joinType = ''; // EMPTY, LEFT, RIGHT, INNER
    public $columns = array();
    public $conditions = array();
    public $prefixedColumns = array();
    public function __construct($joinType, $prefix, $table, $columns, array $onConditions) {
        $this->joinType = $joinType;
        $this->prefix = $prefix;
        $this->tableName = $table;
        $this->columns = $columns;
        foreach($this->columns as $key => $val) {
            $this->prefixedColumns[$key] = $this->prefix . '.' . $val;
        }
        if ($onConditions) {
            $this->on($onConditions);
        }
    }
    public function on(array $onConditions) {

        $count = \count($onConditions);
        if ($count === 1) {
            $this->conditions[] = $onConditions[0];
        } else if ($count === 2) {
            $this->conditions[] = $onConditions[0] . ' = ' . $onConditions[1];
        } else {
            $this->conditions[] = $onConditions[0] . $onConditions[1] . $onConditions[2];
        }

        return $this;
    }
}
