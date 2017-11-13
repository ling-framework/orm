<?php
/**
 * Created by IntelliJ IDEA.
 * User: fri13th
 * Date: 2017/11/06
 * Time: 11:17
 */

namespace Ling\Orm\Common;

class Model {
    public $rowNumber; // for pagination
    public $totalCount; // for pagination

    /** @var $orm Orm */
    protected $orm;

    public function init() {}

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
    public function where(string $column, $comparator = null, $value = null) {
        $this->orm->where($column, $comparator, $value);
        return $this;
    }
    public function whereRaw($raw, $value = null) {
        $this->orm->whereRaw($raw, $value);
        return $this;
    }
    public function whereIn($column, array $items) {
        $this->orm->whereIn($column, $items);
        return $this;
    }
    public function whereBetween($column, array $range){
        $this->orm->whereBetween($column, $range);
        return $this;
    }
    public function whereSearch($columns, $keyword){
        $this->orm->whereSearch($columns, $keyword);
        return $this;
    }
    public function whereWrap() {
        $this->orm->whereWrap();
        return $this;
    }
    public function whereWrapEnd() {
        $this->orm->whereWrapEnd();
        return $this;
    }
    public function whereOr() { // replace AND to OR, OR must not appear in the first place
        $this->orm->whereOr();
        return $this;
    }
    public function whereNot() {
        $this->orm->whereNot();
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
    public function orderBy($column, $order = 'DESC') {
        $this->orm->orderBy($column, $order);
        return $this;
    }
    public function groupBy($column, $having = null) {
        $this->orm->groupBy($column, $having);
        return $this;
    }
    public function limit($start, $length) {
        $this->orm->limit($start, $length);
        return $this;
    }
    public function select() {
        return $this->orm->select();
    }
    public function selectAll() {
        return $this->orm->selectAll();
    }
    public function selectObjects() {
        return $this->orm->selectObjects();
    }
    public function selectCount() {
        return $this->orm->selectCount();
    }
    public function selectChunk(int $count, callable $func) {
        return $this->orm->selectChunk($count, $func);
    }
    public function save() {
        $this->orm->save();
    }
    public function increment($column, $num = null){
        $this->orm->increment($column, $num);
    }
    public function delete() {
        $this->orm->delete();
    }
    public function reuse() {
        $this->orm->reuse();
        return $this;
    }



    // public function copy($obj){
    //     foreach ($this->orm->prefixedColumns as $key => $val) {
    //         if (property_exists($obj, $key)) $this->$key = $obj->$key;
    //     }
    // }
    /**
     * @param $join BaseJoin
     * @return BaseModel
     */
    // public function join(BaseJoin $join){
    //     $this->orm->join($join);
    //     return $this;
    // }

}
