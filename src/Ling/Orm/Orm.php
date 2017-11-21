<?php
namespace Ling\Orm;

use function \Ling\config;

// base is mysql
class Orm {

    public $tableName;
    public $pk;
    public $columns = array();
    public $createdAtColumn;
    public $updatedAtColumn;
    public $customColumns = array(); // custom columns i.e. max(seq)


    /** @var $pdo \PDO */
    private $pdo;
    /** @var $statement \PDOStatement */
    private $statement;

    private $model;
    private $className;
    private $paramSuffix;
    private $prefixedColumns;
    private $vars;
    /** @var $joins Join[] */
    private $joins;

    // we may need partial update columns
    // we may need partial select columns
    // we may support encrypted columns or hashed columns or private columns
    // partial vs encrypted, which one is good?
    // i think encrypted is a good way. some customer want all the user data be encrypted
    // also some hashed data will not need to read from db,
    // just compare.. no select result, but update is available, and where is available
    // how about private data? we want to hide some fields and read only when private mode.

    // we have to support private columns and encrypted columns -> this will be the end of support

    /** @var  $opOr bool */
    private $opOr;
    /** @var  $opNot bool */
    private $opNot;
    /** @var  $noOp bool */
    private $noOp; // for the first time or after '('

    protected $now = 'NOW()'; // current time
    const CONFIG_KEY = 'orm.pdo';

    public function __construct()
    {
        $this->pdo = config(self::CONFIG_KEY);
    }

    public function init(&$model) {
        $this->model = $model;
        $this->className = get_class($model);
        $this->opOr = false;
        $this->opNot = false;
        $this->noOp = true;
        $this->prefixedColumns = array();
        $this->joins = array();

        $this->paramSuffix = 0;
        foreach($this->columns as $key => $val) {
            $this->prefixedColumns[$key] = 'a.' . $val;
        }
        $this->initVars();

    }

    protected function initVars() {
        $this->noOp = true;
        $this->opOr = false;
        $this->opNot = false;
        $this->vars = array(
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

    public function customColumn(string $column) {
        $this->customColumns[] = $column;
    }

    public function where(string $column, $comparator = null, $value = null)
    {
        $operator = $this->operator();
        $this->paramSuffix++;
        $valueKey = $column . '___' . $this->paramSuffix;
        $this->vars['wheres'][] = $operator . $this->getPrefixedColumn($column) . ' ' . $comparator . ' :' . $valueKey;
        $this->vars['params'][$valueKey] = $value;
    }


    public function customWhere($where, array $values = null) // replace ? to value
    {
        $operator = $this->operator();
        foreach($values as $value) {
            $this->paramSuffix++;
            $valueKey = 'custom___' . $this->paramSuffix;
            $this->vars['params'][$valueKey] = $value;
            $where = preg_replace('/\?/', $valueKey, $where, 1);
        }
        $this->vars['wheres'][] =  $operator . $where;
    }

    public function in($column, array $items)
    {
        $operator = $this->operator();
        $valueKeys = array();
        foreach($items as $item) {
            $this->paramSuffix++;
            $valueKey = $column . '___' . $this->paramSuffix;
            $valueKeys[] = $valueKey;
            $this->vars['params'][$valueKey] = $item;
        }
        $this->vars['wheres'][] =  $operator . $this->getPrefixedColumn($column) . ' IN (' . implode(', ', $valueKeys) . ')';
    }

    public function between($column, $start, $end)
    {
        $operator = $this->operator();
        $this->paramSuffix++;
        $valueStartKey = $column . '___' . $this->paramSuffix;
        $this->paramSuffix++;
        $valueEndKey = $column . '___' . $this->paramSuffix;

        $this->vars['wheres'][] = $operator . $this->getPrefixedColumn($column) . ' BETWEEN  :' . $valueStartKey . ' AND :' . $valueEndKey;
        $this->vars['params'][$valueStartKey] = $start;
        $this->vars['params'][$valueEndKey] = $end;

    }

    public function search(array $columns, $keyword)
    {
        $operator = $this->operator();
        $likes = array();
        foreach($columns as $column) {
            $this->paramSuffix++;
            $valueKey = 'keyword___' . $this->paramSuffix;
            $likes[] = $this->getPrefixedColumn($column) . " LIKE CONCAT('%', :" . $valueKey . ", '%')";
            $this->vars['params'][$valueKey] = $keyword;
        }
        $this->vars['wheres'][] =  $operator . ' (' . implode(' OR ', $likes) . ')';

    }

    public function eq($column, $value)
    {
        $this->where($column, '=', $value);
    }

    public function neq($column, $value)
    {
        $this->where($column, '!=', $value);
    }


    public function wrap()
    {
        $this->vars['wheres'][] = $this->operator() . ' (';
        $this->noOp = true;
    }
    public function wrapEnd()
    {
        $this->vars['wheres'][] = ')';
    }

    public function opOr()
    {
        $this->opOr = true;
    }

    public function opNot()
    {
        $this->opNot = true;
    }

    public function isNull($column)
    {
        $this->vars['wheres'][] = ' AND ' . $this->prefixedColumns[$column] . ' IS NULL ';
    }

    public function isNotNull($column)
    {
        $this->vars['wheres'][] = ' AND ' . $this->prefixedColumns[$column] . ' IS NOT NULL ';
    }

    public function orderBy($column, $order = 'DESC')
    {
        $this->vars['orderBys'][] = array($this->getPrefixedColumn($column), $order);
    }

    public function groupBy($column, $having = null)
    {
        $sql = $this->getPrefixedColumn($column);
        if ($having) {
            $sql .= ' HAVING ' . $having;
        }
        $this->vars['groupBys'][] = $sql;
    }

    public function limit($start, $length)
    {
        $this->vars['limit'] = array($start, $length);
    }

    public function select()
    {
        $sql = $this->generateSelectSql();
        $results = $this->fetch($sql, $this->vars['params']);        // reset variables for next use
        $this->initVars();
        return $results;
    }

    public function selectAll()
    {
        $sql = $this->generateSelectSql();
        $results = $this->fetch($sql, $this->vars['params'], true);        // reset variables for next use
        $this->initVars();
        return $results;
    }

    public function selectObjects() : array
    {
//        $results = $this->selectAll();
//        $plainObjects = array();
//        foreach ($results as $obj) {
////            $plainObjects[] = $obj->plainObject(); // may we need it? because model is just a plain object
//        }
//        return $plainObjects;
        return array();
    }

    public function selectCount()
    {
        $sqlFroms = sqlFroms($this->tableName, $this->joins, $this->prefixedColumns);
        $sqlWhere = sqlWhere($this->vars['wheres']);
        $sql = 'SELECT count(*) AS totalCount  FROM ' . $sqlFroms . $sqlWhere;
        return $this->fetch($sql, $this->vars['params'])->totalCount;

    }

    public function selectChunk(int $count, callable $func)
    {
        $totalCount = $this->selectCount();
        for ($i = 0; $count*$i < $totalCount; $i++) {
            $this->limit($i*$count, $count);
            $sql = $this->generateSelectSql();
            $models = $this->fetch($sql, $this->vars['params'], true);
            $func($models, $i);
        }
    }

    public function save()
    {
        $columns = array();
        $values = array();
        $sets = array();
        $params = array();

        if ($this->pk && $this->model->{$this->pk}) { // update
            foreach ($this->columns as $column => $original_column) {
                if ($column === $this->pk || $column === $this->createdAtColumn) {
                    continue;
                }
                if ($column === $this->updatedAtColumn) {
                    $sets[] = $original_column . '=' . $this->now;
                } else {
                    $sets[] = $original_column . '=:' . $column;
                    $params[$column] = $this->model->{$column};
                }
            }
            $params[$this->pk] = $this->model->{$this->pk};
            $sql = 'UPDATE ' . $this->tableName . ' SET ' . implode(', ', $sets) . ' WHERE ' . $this->columns[$this->pk] . '=:' . $this->pk;
            $this->exec($sql, $params);
            if ($this->updatedAtColumn) {
                $fetched = $this->fetch('SELECT ' . $this->columns[$this->updatedAtColumn] . ' FROM ' . $this->tableName . ' WHERE '. $this->columns[$this->pk] . ' = ' .  $this->model->{$this->pk}, [] );
                $this->model->{$this->updatedAtColumn} = $fetched->{$this->columns[$this->updatedAtColumn]};
            }

        } else {
            foreach ($this->columns as $column => $original_column) {
                if ($column === $this->createdAtColumn || $column === $this->updatedAtColumn) {
                    $values[] = $this->now;
                } else if ($column === $this->pk || $this->model->{$column} === null) {
                    continue;
                } else {
                    $values[] = ':' . $column;
                    $params[$column] = $this->model->{$column};
                }
                $columns[] = $original_column;

            }
            $sql = 'INSERT INTO ' . $this->tableName . ' (' . implode(', ', $columns). ') VALUES (' . implode(', ', $values). ')';
            #error_log($sql);
            #error_log(join(", ", $params));
            // we need some error handler here
            $this->exec($sql, $params);
            $this->model->{$this->pk} = $this->lastInsertId();
            if ($this->createdAtColumn) {
                $fetched = $this->fetch('SELECT ' . $this->columns[$this->createdAtColumn] . ' FROM ' . $this->tableName . ' WHERE '. $this->columns[$this->pk] . ' = ' .  $this->model->{$this->pk}, [] );
                $this->model->{$this->createdAtColumn} = $fetched->{$this->columns[$this->createdAtColumn]};
                if ($this->updatedAtColumn) {
                    $this->model->{$this->updatedAtColumn} = $this->model->{$this->createdAtColumn};
                }
            }
        }
    }

    public function increment($column, $num = 1)
    {
        $original_column = $this->columns[$column];
        $sql = 'UPDATE ' . $this->tableName . ' SET ' . $original_column . ' = ' . $original_column . ' + ' . $num . ' WHERE ' . $this->columns[$this->pk] . '=:' . $this->pk;
        $this->exec($sql, []);
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE ' . $this->columns[$this->pk] . ' = :' . $this->pk;
        $this->exec($sql, [$this->pk => $this->{$this->pk}]);
    }

    public function join(Join $join) {
        foreach ($join->columns as $key => $val) {
            $this->prefixedColumns[$key] = $join->prefix . '.' . $val;
        }
        $this->joins[] = $join;
    }


    private function operator() : string
    {
        if ($this->noOp) {
            $this->noOp = false;
            $operator = '';
        } else {
            if ($this->opOr) {
                $operator = ' OR ';
                $this->opOr = false;
            } else {
                $operator = ' AND ';
            }
        }
        if ($this->opNot) {
            $operator .= ' NOT ';
            $this->opNot = false;
        }
        return $operator;
    }

    private function getPrefixedColumn($column) {
        return $this->prefixedColumns[$column] ?: $column;
    }

    private function generateSelectSql() : string
    {
        $sqlColumns = sqlColumns($this->customColumns, $this->prefixedColumns);
        $sqlFroms = sqlFroms($this->tableName, $this->joins, $this->prefixedColumns);
        $sqlWhere = sqlWhere($this->vars['wheres']);
        $sqlGroupBy = sqlGroupBy($this->vars['groupBys']);
        $sqlOrderBy = sqlOrderBy($this->vars['orderBys']);
        $sqlLimit = sqlLimit($this->vars['limit']);
        $sql = 'SELECT ' . $sqlColumns . ' FROM ' . $sqlFroms . $sqlWhere . $sqlGroupBy . $sqlOrderBy . $sqlLimit;
        error_log($sql);
        return $sql;
    }

    public function paginate(Paginate $paginate) : Paginate
    {
        $this->limit($paginate->startAt, $paginate->paginationSize);
        $totalCount = $this->selectCount();
        $paginate->setTotalCount($totalCount);
        $sql = $this->generateSelectSql();
        $paginate->setList($this->fetch($sql, $this->vars['params'], true));
        return $paginate;
    }

    public function plainObject()
    {
        $obj = array();
//        $columns = $this->prefixedColumns;
//        foreach ($columns as $key => $value) {
//            if (!in_array($key, $this->orm->jsonExcludes)) $obj[$key] = $this->$key;
//        }
        return (object)$obj;
    }

}


function sqlColumns(array $customColumns, array $prefixedColumns) {
    $columns = array();
    foreach ($customColumns as $column) {
        $columns[] = $column;
    }
    foreach ($prefixedColumns as $key => $val) {
        $columns[] = $val . ' AS ' . $key;
    }
    return implode(', ', $columns);

}


function sqlFroms($tableName, array $joins, $prefixedColumns) : string
{
    $sqlFroms = array($tableName . ' as a ');
    if (count($joins) > 0) {
        /** @var Join $join */
        foreach($joins as $join) {
            $ons = array();
            foreach ($join->conditions as $cond) {
                $ons[] = $prefixedColumns[$cond[0]] . ' ' . $cond[1] . ' ' . $prefixedColumns[$cond[2]];
            }
            $sqlFroms[] = $join->joinType . ' JOIN ' . $join->tableName . ' AS ' . $join->prefix . ' ON ' . implode(' AND ', $ons);
        }
    }
    return implode(' ', $sqlFroms);

}

function sqlGroupBy(array $groupBys) : string
{
    $sql = '';
    if (count($groupBys) > 0) { // group by doesn't require prefix
        $sql = ' GROUP BY ' . implode(', ', $groupBys);
    }
    return $sql;
}

function sqlOrderBy(array $orderBys) : string
{
    $sql = '';
    if (count($orderBys) > 0) { // order by doesn't require prefix
        $orders = array();
        foreach ($orderBys as $orderBy) {
            $orders[] = implode(' ', $orderBy);
        }
        $sql = ' ORDER BY ' . implode(', ', $orders);
    }
    return $sql;
}

function sqlWhere($wheres) : string
{
    $sql = '';
    if (count($wheres) > 0) {
        $sql = ' WHERE ' . implode(' ', $wheres);
    }
    return $sql;
}

function sqlLimit($limits) : string
{
    $sql = '';
    if (count($limits) > 0) { // only for sql server 2012+
        $sql = ' LIMIT ' . $limits[0] . ', ' . $limits[1];
    }
    return $sql;
}

//
///* we may not need interface. every orm has almost same part, we just change the current time or some methods IN or BETWEEN */
//interface Orm {
//
////    /** @var  $pdo \PDO */
////    public $pdo;
////    /** @var  $statement \PDOStatement */
////    public $statement;
//
//
//    //public function init(PDO $pdo, $pk = "seq", $created_at = null, $updated_at = null);
//
//    //public function __construct(string $className);
//
//
//    // column function
////    public function max($column = null); // default pk
////    public function min($column = null); // default pk
////    public function avg($column);
////    public function sum($column);
////    public function ifNull($column, $default);
//
//    public function init(&$model);
//
//    // PDO function wrapper
//    public function fetch(string $sql, array $params, bool $isAll = null);
//    public function fetchArray(string $sql, array $params);
//    public function exec(string $sql, array $params) : bool;
//    public function lastInsertId() : int;
//    public function rowCount() : int;
//    public function debugDumpParams() : bool;
//    public function beginTransaction() : bool;
//    public function commit() : bool;
//    public function rollBack() : bool;
//    public function errorInfo() : array;
//    public function errorCode();
//
//    public function where(string $column, $comparator = null, $value = null);
//    public function whereRaw($raw, array $value = null);
//    public function in($column, array $items);
//    public function between($column, $start, $end);
//    public function search(array $columns, $keyword); // like search
//
//    public function eq($column, $value);
//    public function neq($column, $value);
//    public function isNull($column);
//    public function isNotNull($column);
//
//    public function wrap();
//    public function wrapEnd();
//    public function opOr(); // replace AND to OR, OR must not appear in the first place
//    public function opNot(); // add Not
//
//    public function orderBy($column, $order = 'DESC');
//    public function groupBy($column, $having = null);
//    public function limit($start, $length);
//
//    public function select();
//    public function selectAll();
//    public function selectObjects();
//    public function selectCount(); // don't reset
//    public function selectChunk(int $count, callable $func);
//
//    public function save(); // insert or update
//    public function increment($column, $num = 1); // partially update
//    public function delete();
//
//    public function join(Join $join);
//    public function paginate(Paginate $paginate);
//    public function plainObject();
//
//}