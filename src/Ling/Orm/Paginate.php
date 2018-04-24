<?php
namespace Ling\Orm;


class Paginate { // provide improved pagination
    // input for pagination
    const DEFAULT_CURRENT_PAGE = 10; // can be overrided
    const DEFAULT_LIST_SIZE = 10;
    const DEFAULT_PAGINATION_SIZE = 5;

    public $currentPage;
    public $listSize;
    public $paginationSize;
    public $keyword;
    public $criteria;

    // output
    public $totalCount;
    public $validIndex; // when current page index is valid, not p < 0 or p > totalCount
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

    public function __construct($currentPage, $keyword = NULL, $criteria = NULL, int $listSize = 0, int $paginationSize = 0){
        $this->currentPage = (!$currentPage) ? self::DEFAULT_CURRENT_PAGE : $currentPage ;
        $this->listSize = $listSize ?: self::DEFAULT_LIST_SIZE;
        $this->paginationSize = $paginationSize ?: self::DEFAULT_PAGINATION_SIZE;
        $this->startAt = ($this->currentPage - 1)*$this->paginationSize;
        $this->endAt = $this->currentPage * $this->paginationSize;
        $this->validIndex = true;
        $this->totalCount = 0;
        $this->list = array();
    }

    public function setList($list){$this->list = $list;}
    public function setTotalCount($totalCount) { // you can set internal values by totalCount. there was html() for template, but removed because we only use json
       $this->totalCount = $totalCount;
    }

    public function json() {
        return json_encode($this->plainObject());
    }
    public function plainObject() {
        $obj = array();
        $obj['currentPage'] = $this->currentPage;
        $obj['listSize'] = $this->listSize;
        $obj['paginationSize'] = $this->paginationSize;
        $obj['totalCount'] = $this->totalCount;
        $obj['validIndex'] = $this->validIndex;
        $obj['list'] = array();
        foreach ($this->list as $item) {
            $obj['list'][] = $item->plainObject();
        }
        return (object)$obj;
    }
}
