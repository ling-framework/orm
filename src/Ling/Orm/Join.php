<?php
namespace Ling\Orm;
class Join {
    public $prefix = ''; // from b...
    public $tableName = ''; // table or inner select
    public $joinType = ''; // EMPTY, LEFT, RIGHT, INNER
    public $columns = array();
    public $conditions = array();
    public $prefixedColumns = array();
    public function __construct($joinType, $prefix, $table, $columns, $onEqConditions = null) {
        $this->joinType = $joinType;
        $this->prefix = $prefix;
        $this->tableName = $table;
        $this->columns = $columns;
        foreach($this->columns as $key => $val) {
            $this->prefixedColumns[$key] = $this->prefix . '.' . $val;
        }
        if ($onEqConditions) {
            $this->onEq($onEqConditions[0], $onEqConditions[1]);
        }
    }
    public function onEq($cond1, $cond2) {
        $this->on($cond1, '=', $cond2);
        return $this;
    }
    public function on($cond1, $comparator,  $cond2) {
        $this->conditions[] = array($cond1, $comparator, $cond2);
        return $this;
    }
}
