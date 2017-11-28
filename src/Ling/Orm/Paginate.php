<?php
namespace Ling\Orm;


abstract class Paginate { // provide improved pagination
    // input for pagination
    const DEFAULT_CURRENT_PAGE = 10; // can be overrided
    const DEFAULT_LIST_SIZE = 10;
    const DEFAULT_PAGINATION_SIZE = 5;

    public $currentPage;
    public $listSize;
    public $paginationSize;
    // output
    public $totalCount;
    public $valid; // when current page index is valid, not p < 0 or p > totalCount
    public $list;
    // temporary variables
    public $startAt;
    public $endAt;
    protected $prev;
    protected $next;
    protected $startPage;
    protected $endPage;
    protected $useFirstPage;
    protected $useFirstSkip;
    protected $useLastPage;
    protected $useLastSkip;
    protected $totalPage;

    public function __construct($currentPage, $listSize = 0, $paginationSize = 0){
        $this->currentPage = (!$currentPage) ? self::DEFAULT_CURRENT_PAGE : $currentPage ;
        $this->listSize = $listSize ?: self::DEFAULT_LIST_SIZE;
        $this->paginationSize = $paginationSize ?: self::DEFAULT_PAGINATION_SIZE;
        $this->startAt = ($this->currentPage - 1)*$this->paginationSize;
        $this->endAt = $this->currentPage * $this->paginationSize;
        $this->valid = true;
        $this->totalCount = 0;
        $this->list = array();
    }

    public function setList($list){$this->list = $list;}
    abstract public function setTotalCount($totalCount); // you can set internal values by totalCount. there was html() for template, but removed because we only use json
    public function json() {
        return json_encode($this->plainObject());
    }
    public function plainObject() {
        $obj = array();
        $obj['currentPage'] = $this->currentPage;
        $obj['listSize'] = $this->listSize;
        $obj['paginationSize'] = $this->paginationSize;
        $obj['totalCount'] = $this->totalCount;
        $obj['valid'] = $this->valid;
        $obj['list'] = array();
        foreach ($this->list as $item) {
            $obj['list'][] = $item->plainObject();
        }
        return (object)$obj;
    }
}
