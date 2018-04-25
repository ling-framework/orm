<?php
namespace Ling\Orm;


class Paginate { // provide improved pagination
    // input for pagination
    const DEFAULT_PAGE = 1; // can be overrided
    const DEFAULT_ROWS_PER_PAGE = 10;
    const DEFAULT_PAGES_PER_PAGINATION = 10;

    public $page;
    public $rowsPerPage;
    public $pagesPerPagination; // this is for server-side pagination template, not important in these days
    public $keyword; // this must be set after initialize
    public $criteria; // this must be set after initialize

    // output
    public $totalCount;
    public $items;
    protected $isValidPage; // if page is valid, page must not p < 0 or p > totalCount
    public $startAt;
    protected $endAt;
    // temporary variables
    protected $prev;
    protected $next;
    protected $startPage;
    protected $endPage;
    protected $useFirstPage;
    protected $useFirstSkip;
    protected $useLastPage;
    protected $useLastSkip;
    protected $totalPage;

    public function __construct($page, int $rowsPerPage = 0){
        $this->page = (!$page) ? self::DEFAULT_PAGE : $page ;
        $this->rowsPerPage = $rowsPerPage ?: self::DEFAULT_ROWS_PER_PAGE;
        $this->pagesPerPagination = self::DEFAULT_PAGES_PER_PAGINATION;
        $this->isValidPage = ($page > 0);
        $this->totalCount = 0;
        $this->items = array();

        $this->startAt = ($this->page - 1)*$this->rowsPerPage ;
        $this->endAt = $this->page * $this->rowsPerPage ;
    }

    public function setItems($items){$this->items = $items;}
    public function setTotalCount($totalCount) { // you can set internal values by totalCount. there was html() for template, but removed because we only use json
       $this->totalCount = $totalCount;
    }

    public function json() {
        return json_encode($this->plainObject());
    }
    public function plainObject() {
        $obj = array();
        $obj['page'] = $this->page;
        $obj['rowsPerPage'] = $this->rowsPerPage;
        $obj['pagesPerPagination'] = $this->pagesPerPagination;
        $obj['totalCount'] = $this->totalCount;
        $obj['items'] = array();
        foreach ($this->items as $item) {
            $obj['items'][] = $item->plainObject();
        }
        return (object)$obj;
    }
}
