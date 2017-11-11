<?php
declare(strict_types=1);

namespace Ling\Orm\Sqlite3;


class DistilleryModel extends Model {
    public $seq;
    public $name;
    public $country;
    public $region;
    public $createdAt;
    public $updatedAt;

    public function init(array $updateFields = null) {
        $this->orm->tableName = 'distillery';
        $this->orm->pk = 'seq';
        $this->orm->columns = [
            'seq' => 'seq',
            'name' => 'name',
            'country' => 'country',
            'region' => 'region',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at'
        ];
        $this->orm->createdAtField = 'createdAt';
        $this->orm->updatedAtField = 'updatedAt';
        $this->orm->updateFields = $updateFields; // this means nothing in this case, we need some password and ssn number field
    }

}