<?php
namespace Ling\Orm\Common;

abstract class Paginate { // provide improved pagination
    // input for pagination
    public $currentPage;
    public $listSize;
    public $paginationSize;
    public $criteria;
    // output
    public $totalCount;
    public $searchable;
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
    public function __construct($currentPage, $keyword = NULL, $criteria = NULL, $listSize = 0, $paginationSize = 0){
        $this->keyword = $keyword; // legacy
        $this->criteria = $criteria; // legacy
        $this->currentPage = (!$currentPage) ? PAGINATE_DEFAULT_CURRENT_PAGE : $currentPage ;
        $this->listSize = $listSize ? $listSize : PAGINATE_DEFAULT_LIST_SIZE;
        $this->paginationSize = $paginationSize ? $paginationSize : PAGINATE_DEFAULT_PAGINATION_SIZE;
        $this->startAt = ($this->currentPage - 1)*$this->paginationSize;
        $this->endAt = $this->currentPage * $this->paginationSize;
        $this->totalCount = 0;
        $this->list = array();
    }
    public function setList($list){$this->list = $list;}
    abstract public function setTotalCount($totalCount);
    public function json() {
        return json_encode($this->plainObject());
    }
    public function plainObject() {
        $obj = array();
        $obj["currentPage"] = $this->currentPage;
        $obj["listSize"] = $this->listSize;
        $obj["paginationSize"] = $this->paginationSize;
        $obj["criteria"] = $this->criteria;
        $obj["totalCount"] = $this->totalCount;
        $obj["searchable"] = $this->searchable;
        $obj["list"] = array();
        foreach ($this->list as $item) {
            array_push($obj["list"], $item->plainObject());
        }
        return (object)$obj;
    }
}
