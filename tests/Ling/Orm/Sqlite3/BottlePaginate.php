<?php

declare(strict_types=1);

namespace Ling\Orm\Sqlite3;
use Ling\Orm\Paginate;

class BottlePaginate extends Paginate {

    public function setTotalCount($totalCount) {
        $this->pagesPerPagination = 5;

        $this->totalCount = $totalCount;

        if ($this->page < 1 || $this->totalCount < 1 || $this->totalCount < (($this->page - 1)*$this->rowsPerPage)) { // we don't need to search
            $this->isValidPage = false;
            return;
        }
        $this->isValidPage = true;
        $this->totalPage = (int)($this->totalCount/$this->rowsPerPage) + (($this->totalCount % $this->rowsPerPage === 0) ? 0 : 1);

        if ($this->endAt > $this->totalCount) {
            $this->endAt = $this->totalCount;
        }

        $this->prev = $this->page - 1;
        $this->next = $this->page + 1;

        $this->startPage = (($this->page - $this->pagesPerPagination) < 1) ? 1 : ($this->page - $this->pagesPerPagination);
        if (($this->totalPage > ($this->pagesPerPagination*2 + 3)) && ($this->startPage > $this->totalPage - $this->pagesPerPagination*2 - 1)) {
            $this->startPage = $this->totalPage - $this->pagesPerPagination*2 - 1;
        }
        $this->endPage = (($this->page + $this->pagesPerPagination) > $this->totalPage) ? $this->totalPage : ($this->page + $this->pagesPerPagination);
        if ($this->totalPage > ($this->pagesPerPagination*2 + 3) && $this->endPage < ($this->pagesPerPagination*2 + 1)) {
            $this->endPage = $this->pagesPerPagination*2 + 1;
        }
        if ($this->next > $this->endPage) {
            $this->next = null;
        }

        $this->useFirstPage = ($this->startPage > 1);
        $this->useFirstSkip = ($this->startPage > 2);
        $this->useLastPage = (($this->totalPage - $this->endPage) > 0 && $this->totalPage > $this->startPage);
        $this->useLastSkip = (($this->totalPage - $this->endPage) > 1 && $this->totalPage > $this->startPage + 1);
    }

    public function html($condition) {
        $html = '';
        if (!$this->isValidPage) {
            return $html;
        }
        if ($this->prev) {
            $html .= "<a class='pagination-prev' href='?" . $condition . '&pno=' . $this->prev . "'>&lt; Prev<span class='page-next'></span></a>";
        }
        for ($i = $this->startPage; $i <= $this->endPage; $i++) {
            if ($this->page === $i) {
                $html .= "<span class='current'>" . $i . '</span>';
            } else {
                $html .= "<a class='inactive' href='?" . $condition . '&pno=' . $i . "'>" . $i . '</a>';
            }
        }
        if ($this->next) {
            $html .= "<a class='pagination-next' href='?" . $condition . '&pno=' . $this->next . "'>Next &gt;<span class='page-next'></span></a>";
        }
        return $html;
    }
}