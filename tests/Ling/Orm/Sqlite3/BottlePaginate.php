<?php

declare(strict_types=1);

namespace Ling\Orm\Sqlite3;
use Ling\Orm\Paginate;

class BottlePaginate extends Paginate {

    public function setTotalCount($totalCount) {
        $this->listSize = 5;

        $this->totalCount = $totalCount;

        if ($this->currentPage < 1 || $this->totalCount < 1 || $this->totalCount < (($this->currentPage - 1)*$this->paginationSize)) { // we don't need to search
            $this->validIndex = false;
            return;
        }
        $this->validIndex = true;
        $this->totalPage = (int)($this->totalCount/$this->paginationSize) + (($this->totalCount % $this->paginationSize === 0) ? 0 : 1);

        if ($this->endAt > $this->totalCount) {
            $this->endAt = $this->totalCount;
        }

        $this->prev = $this->currentPage - 1;
        $this->next = $this->currentPage + 1;

        $this->startPage = (($this->currentPage - $this->listSize) < 1) ? 1 : ($this->currentPage - $this->listSize);
        if (($this->totalPage > ($this->listSize*2 + 3)) && ($this->startPage > $this->totalPage - $this->listSize*2 - 1)) {
            $this->startPage = $this->totalPage - $this->listSize*2 - 1;
        }
        $this->endPage = (($this->currentPage + $this->listSize) > $this->totalPage) ? $this->totalPage : ($this->currentPage + $this->listSize);
        if ($this->totalPage > ($this->listSize*2 + 3) && $this->endPage < ($this->listSize*2 + 1)) {
            $this->endPage = $this->listSize*2 + 1;
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
        if (!$this->validIndex) {
            return $html;
        }
        if ($this->prev) {
            $html .= "<a class='pagination-prev' href='?" . $condition . '&pno=' . $this->prev . "'>&lt; Prev<span class='page-next'></span></a>";
        }
        for ($i = $this->startPage; $i <= $this->endPage; $i++) {
            if ($this->currentPage === $i) {
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