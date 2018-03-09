<?php
declare(strict_types=1);

namespace Ling\Orm\Sqlite3;
// distillery brand one to many model

class DistilleryBrandModel extends Model {
    public $id;
    public $name;
    public $country;
    public $region;
    public $createdAt;
    public $updatedAt;

    /** @var $brands BrandModel[] */
    public $brands;

    public function init() {
        $this->orm->tableName = 'distillery';
        $this->orm->pk = 'id';
        $this->orm->columns = [
            'id' => 'id',
            'name' => 'name',
            'country' => 'country',
            'region' => 'region',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at'
        ];
        $this->orm->createdAtColumn = 'createdAt';
        $this->orm->updatedAtColumn = 'updatedAt';

        $brandRelation = new Relation();
        $this->relation($brandRelation);
    }


}