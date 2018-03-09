<?php

declare(strict_types=1);

namespace Ling\Orm\Sqlite3;

class StockModel extends Model {
    public $id;
    public $bottleId;
    public $shopId;
    public $stock;
    public $price;
    public $createdAt;
    public $updatedAt;

    public function init() {
        $this->orm->tableName = 'stock';
        $this->orm->pk = 'id';
        $this->orm->columns = [
            'id' => 'id',
            'bottleId' => 'bottle_id',
            'shopId' => 'shop_id',
            'stock' => 'stock',
            'price' => 'price',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at'
        ];
        $this->orm->createdAtColumn = 'createdAt';
        $this->orm->updatedAtColumn = 'updatedAt';
    }

}