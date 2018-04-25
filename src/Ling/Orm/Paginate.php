<?php
namespace Ling\Orm;


class Paginate { // provide improved pagination
    // input for pagination
    const DEFAULT_CURRENT_PAGE = 1; // can be overrided
    const DEFAULT_PAGINATION_SIZE = 10;
    const DEFAULT_LIST_SIZE = 10;

    public $currentPage;
    public $paginationSize;
    public $listSize; // this is for server-side pagination template, not important in these days
    public $keyword; // this must be set after initialize
    public $criteria; // this must be set after initialize

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

    public function __construct($currentPage, int $paginationSize = 0, int $listSize = 0){
        $this->currentPage = (!$currentPage) ? self::DEFAULT_CURRENT_PAGE : $currentPage ;
        $this->paginationSize = $paginationSize ?: self::DEFAULT_PAGINATION_SIZE;
        $this->listSize = $listSize ?: self::DEFAULT_LIST_SIZE;
        $this->validIndex = true;
        $this->totalCount = 0;
        $this->list = array();

        $this->startAt = ($this->currentPage - 1)*$this->paginationSize;
        $this->endAt = $this->currentPage * $this->paginationSize;
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
