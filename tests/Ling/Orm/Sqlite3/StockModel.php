<?php

declare(strict_types=1);

namespace Ling\Orm\Sqlite3;

class StockModel extends Model {
    public $seq;
    public $bottleSeq;
    public $shopSeq;
    public $stock;
    public $price;
    public $createdAt;
    public $updatedAt;

    public function init() {
        $this->orm->tableName = 'stock';
        $this->orm->pk = 'seq';
        $this->orm->columns = [
            'seq' => 'seq',
            'bottleSeq' => 'bottle_seq',
            'shopSeq' => 'shop_seq',
            'stock' => 'stock',
            'price' => 'price',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at'
        ];
        $this->orm->createdAtField = 'createdAt';
        $this->orm->updatedAtField = 'updatedAt';
    }

}