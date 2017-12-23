<?php
/**
 * Created by IntelliJ IDEA.
 * User: fri13th
 * Date: 2017/11/06
 * Time: 11:17
 */

namespace Ling\Orm;

class Model {
    public $rowNumber; // for pagination
    public $totalCount; // for pagination

    /** @var $orm Orm */
    protected $orm;

    public function __construct()
    {
        $this->preInit();
        $this->init();
        $this->postInit();
    }

    public function preInit() {
        $this->orm = new Orm();
    }
    public function postInit() {
        $this->orm->init(\get_class($this));
    }

    public function init() {} // must be overrided

    // interface for separating model to orm
    public function fetch(string $sql, array $params, bool $isAll = null) {
        return $this->orm->fetch($sql, $params, $isAll);
    }
    public function fetchAll(string $sql, array $params) {
        return $this->orm->fetch($sql, $params, true);
    }
    public function fetchArray(string $sql, array $params) {
        return $this->orm->fetchArray($sql, $params);
    }
    public function exec(string $sql, array $params = []) : bool {
        return $this->orm->exec($sql, $params);
    }
    public function lastInsertId() : int {
        return $this->orm->lastInsertId();
    }
    public function rowCount() : int {
        return $this->orm->rowCount();
    }
    public function debugDumpParams() : bool {
        return $this->orm->debugDumpParams();
    }
    public function beginTransaction() : bool {
        return $this->orm->beginTransaction();
    }
    public function commit() : bool {
        return $this->orm->commit();
    }
    public function rollBack() : bool {
        return $this->orm->rollBack();
    }
    public function errorInfo() : array {
        return $this->orm->errorInfo();
    }
    public function errorCode() {
        return $this->orm->errorCode();
    }
    public function customColumn(string $column) {
        $this->orm->customColumn($column);
        return $this;
    }
    public function where(string $column, $comparator = null, $value = null) {
        $this->orm->where($column, $comparator, $value);
        return $this;
    }
    public function customWhere($where, array $value = null) {
        $this->orm->customWhere($where, $value);
        return $this;
    }
    public function in($column, array $items) {
        $this->orm->in($column, $items);
        return $this;
    }
    public function between($column, $start, $end){
        $this->orm->between($column, $start, $end);
        return $this;
    }
    public function search(array $columns, $keyword){
        $this->orm->search($columns, $keyword);
        return $this;
    }

    public function eq($column, $value) {
        $this->orm->eq($column, $value);
        return $this;
    }
    public function neq($column, $value) {
        $this->orm->eq($column, $value);
        return $this;
    }
    public function isNull($column) {
        $this->orm->isNull($column);
        return $this;
    }
    public function isNotNull($column) {
        $this->orm->isNotNull($column);
        return $this;
    }
    public function wrap() {
        $this->orm->wrap();
        return $this;
    }
    public function wrapEnd() {
        $this->orm->wrapEnd();
        return $this;
    }
    public function opOr() { // replace AND to OR, OR must not appear in the first place
        $this->orm->opOr();
        return $this;
    }
    public function opNot() {
        $this->orm->opNot();
        return $this;
    }
    public function orderBy($column, $order = 'DESC') {
        $this->orm->orderBy($column, $order);
        return $this;
    }
    public function groupBy($column, $having = null) {
        $this->orm->groupBy($column, $having);
        return $this;
    }
    public function limit($start, $length = null) {
        if (!$length) {
            $length = $start;
            $start = 0;
        }
        $this->orm->limit($start, $length);
        return $this;
    }
    public function select() {
        return $this->orm->select();
    }
    public function selectAll() {
        return $this->orm->selectAll();
    }
    public function selectObjects() : array
    {
        return $this->orm->selectObjects();
    }
    public function selectCount() {
        return $this->orm->selectCount();
    }
    public function selectChunk(int $count, callable $func) {
        $this->orm->selectChunk($count, $func);
    }
    public function save() {
        $this->orm->save($this);
    }
    public function increment($column, $num = 1){
        $this->orm->increment($this, $column, $num);
    }
    public function delete() {
        $this->orm->delete();
    }

    /**
     * @param $join Join
     * @return Model
     */
    public function join(Join $join) : Model
    {
        $this->orm->join($join);
        return $this;
    }

    public function paginate(Paginate $paginate) : Paginate {
        return $this->orm->paginate($paginate);
    }

    public function plainObject() : array
    {
        return array();
    }


    // public function copy($obj){
    //     foreach ($this->orm->prefixedColumns as $key => $val) {
    //         if (property_exists($obj, $key)) $this->$key = $obj->$key;
    //     }
    // }

}
