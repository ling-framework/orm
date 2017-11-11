<?php
namespace Ling\Orm\Sqlite3;

use Ling\Orm\Common\Join;
use function \Ling\config as config;

class Orm implements \Ling\Orm\Common\Orm {

    public $tableName;
    public $pk;
    public $columns = array();
    public $createdAtField = '';
    public $updatedAtField = '';
    public $updateFields = array();


    /** @var $pdo \PDO */
    protected $pdo;
    /** @var $statement \PDOStatement */
    protected $statement;

    protected $className;
    protected $paramSuffix;
    protected $prefixedColumns = array();
    protected $vars = array();
    /** @var Join[]  */
    protected $joins = array();

    public function __construct(string $className)
    {
        $this->className = $className;
        $this->pdo = config('orm.pdo');
    }

    protected function initVars() {
        $this->vars = array(
            'fields' => array(), // custom fields (max(*) as maxVal, )
            'wheres' => array(),
            'orderBys' => array(),
            'groupBys' => array(),
            'limit' => array(),
            'params' => array(),
            'joins' => array(),
        );
    }


    // PDO functions
    public function fetch(string $sql, array $params, bool $isAll = null)
    {
        $this->statement = $this->pdo->prepare($sql);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->statement->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->className);
        $this->statement->execute($params);
        $rows = $isAll ?  $this->statement->fetchAll() : $this->statement->fetch();
        $this->statement->closeCursor();
        return $rows;
    }

    public function fetchArray(string $sql, array $params)
    {
        $this->statement = $this->pdo->prepare($sql);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->statement->setFetchMode(\PDO::FETCH_ASSOC);
        $this->statement->execute($params);
        $rows = $this->statement->fetchAll();
        $this->statement->closeCursor();
        return $rows;
    }

    public function exec(string $sql, array $params) : bool
    {
        $this->statement = $this->pdo->prepare($sql);
        return $this->statement->execute($params);
    }

    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    public function debugDumpParams(): bool
    {
        return $this->statement->debugDumpParams();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    public function errorInfo(): array
    {
        return $this->pdo->errorInfo();
    }

    public function errorCode()
    {
        $errorCode = $this->pdo->errorCode();
        if (!$errorCode) {
            $errorCode = $this->statement->errorCode();
        }
        return $errorCode ;
    }

    public function where(string $column, $comparator = null, $value = null)
    {
        $this->paramSuffix++;
        $valueKey = $column . "___" . $this->paramSuffix;
        if ($comparator === "IN" || $comparator === "IS") {
            $this->vars["wheres"][] = " AND " . getPrefixedColumn($this->prefixedColumns, $column) . " " . $comparator . " " . getPrefixedColumn($this->prefixedColumns, $value);
        }
        else {
            $this->vars["wheres"][] = " AND " . getPrefixedColumn($this->prefixedColumns, $column) . " " . $comparator . " :" . $valueKey;
            $this->vars["params"][$valueKey] = $value; // we must use pdo for preventing sql injection
        }
    }


    public function whereRaw($raw, $value = null)
    {

    }

    public function whereIn($column, array $items)
    {
        // TODO: Implement whereIn() method.
    }

    public function whereBetween($column, array $range)
    {
        // TODO: Implement whereBetween() method.
    }

    public function whereSearch($columns, $keyword)
    {
        // TODO: Implement whereSearch() method.
    }

    public function whereWrap($func)
    {
        // TODO: Implement whereWrap() method.
    }

    public function whereOr()
    {
        // TODO: Implement whereOr() method.
    }

    public function whereNot()
    {
        // TODO: Implement whereNot() method.
    }

    public function isNull($column)
    {
        // TODO: Implement isNull() method.
    }

    public function isNotNull($column)
    {
        // TODO: Implement isNotNull() method.
    }

    public function orderBy($column, $order = 'DESC')
    {
        // TODO: Implement orderBy() method.
    }

    public function groupBy($column, $having = null)
    {
        // TODO: Implement groupBy() method.
    }

    public function limit($start, $length)
    {
        // TODO: Implement limit() method.
    }

    public function select()
    {
        // TODO: Implement select() method.
    }

    public function selectAll()
    {
        // TODO: Implement selectAll() method.
    }

    public function selectObjects()
    {
        // TODO: Implement selectObjects() method.
    }

    public function selectCount()
    {
        // TODO: Implement selectCount() method.
    }

    public function selectChunk(int $count, callable $func)
    {
        // TODO: Implement selectChunk() method.
    }

    public function save($model)
    {
        $columns = array();
        $values = array();
        $sets = array();
        $params = array();

        if ($this->pk && $model->{$this->pk}) { // update
            foreach ($this->columns as $column => $original_column) {
                if ($column === $this->pk || $column === $this->createdAtField) {
                    continue;
                }
                if ($column === $this->updatedAtField) {
                    $sets[] = $original_column . "=DateTime('now')";
                } else {
                    $sets[] = $original_column . '=:' . $column;
                    $params[$column] = $model->{$column};
                }
            }
            $params[$this->pk] = $model->{$this->pk};
            $sql = 'UPDATE ' . $this->tableName . ' SET ' . implode(', ', $sets) . ' WHERE ' . $this->columns[$this->pk] . '=:' . $this->pk;
            $this->exec($sql, $params);
            if ($this->updatedAtField) {
                $fetched = $this->fetch('SELECT ' . $this->columns[$this->updatedAtField] . ' FROM ' . $this->tableName . ' WHERE '. $this->columns[$this->pk] . ' = ' .  $model->{$this->pk}, [] );
                $model->{$this->updatedAtField} = $fetched->{$this->columns[$this->updatedAtField]};
            }

        } else {
            foreach ($this->columns as $column => $original_column) {
                if ($column === $this->createdAtField || $column === $this->updatedAtField) {
                    $values[] = "DateTime('now')";
                } else if ($column === $this->pk || $model->{$column} === null) {
                    continue;
                } else {
                    $values[] = ':' . $column;
                    $params[$column] = $model->{$column};
                }
                $columns[] = $original_column;

            }
            $sql = 'INSERT INTO ' . $this->tableName . ' (' . implode(', ', $columns). ') VALUES (' . implode(', ', $values). ')';
            #error_log($sql);
            #error_log(join(", ", $params));
            // we need some error handler here
            $this->exec($sql, $params);
            $model->{$this->pk} = $this->lastInsertId();
            if ($this->createdAtField) {
                $fetched = $this->fetch('SELECT ' . $this->columns[$this->createdAtField] . ' FROM ' . $this->tableName . ' WHERE '. $this->columns[$this->pk] . ' = ' .  $model->{$this->pk}, [] );
                $model->{$this->createdAtField} = $fetched->{$this->columns[$this->createdAtField]};
                if ($this->updatedAtField) {
                    $model->{$this->updatedAtField} = $model->{$this->createdAtField};
                }
            }
        }
    }

    public function increment($column, $num = null)
    {
        // TODO: Implement increment() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function reuse()
    {
        // TODO: Implement reuse() method.
    }




}

function getPrefixedColumn($prefixedColumns, $column) {
    return $prefixedColumns[$column] ? $prefixedColumns[$column] : $column;
}


function sqlFrom() {

}

function sqlColumns() {

}

function sqlGroupBy() {

}

function sqlOrderBy() {

}

function sqlWhere() {

}

function sqlLimit() {

}

function generateSelectSql() {

}

function paginate() {

}