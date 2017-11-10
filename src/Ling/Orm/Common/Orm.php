<?php
namespace Ling\Orm\Common;

interface Orm {

//    /** @var  $pdo \PDO */
//    public $pdo;
//    /** @var  $statement \PDOStatement */
//    public $statement;


    //public function init(PDO $pdo, $pk = "seq", $created_at = null, $updated_at = null);

    //public function __construct(string $className);


    // column function
    // public function max($column = null); // default pk
    // public function min($column = null); // default pk
    // public function avg($column);
    // public function sum($column);
    // public function ifNull($column, $default);

    // PDO function wrapper
    public function fetch(string $sql, array $params, bool $isAll = null);
    public function fetchArray(string $sql, array $params);
    public function exec(string $sql, array $params) : bool;
    public function lastInsertId() : int;
    public function rowCount() : int;
    public function debugDumpParams() : bool;
    public function beginTransaction() : bool;
    public function commit() : bool;
    public function rollBack() : bool;
    public function errorInfo() : array;
    public function errorCode();

    public function where(string $column, $comparator = null, $value = null);
    public function whereRaw($raw, $value = null);
    public function whereIn($column, array $items);
    public function whereBetween($column, array $range);
    public function whereSearch($columns, $keyword); // like search

    public function whereWrap($func);
    public function whereOr(); // replace AND to OR, OR must not appear in the first place
    public function whereNot();

//    public function eq($column, $value);
//    public function neq($column, $value);
    public function isNull($column);
    public function isNotNull($column);


    public function orderBy($column, $order = 'DESC');
    public function groupBy($column, $having = null);
    public function limit($start, $length);

    public function select();
    public function selectAll();
    public function selectObjects();
    public function selectCount(); // don't reset
    public function selectChunk(int $count, callable $func);

    public function save(); // insert or update
    public function increment($column, $num = null); // partially update
    public function delete();
    public function reuse(); // use condition again


    // public function paginate(BasePaginate $paginate);
    // public function plainObject();

}