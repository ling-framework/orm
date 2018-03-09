<?php
/**
 * Created by IntelliJ IDEA.
 * User: fri13th
 * Date: 2017/12/14
 * Time: 10:43
 */

declare(strict_types=1);

namespace Ling\Orm\Sqlite3;
use Ling\Orm\Join;

class BrandModel extends Model {
    public $id;
    public $distilleryId;
    public $name;
    public $createdAt;
    public $updatedAt;

    // join columns
    public $bId;
    public $distilleryName;
    public $country;
    public $region;


    public function init() {
        $this->orm->tableName = 'brand';
        $this->orm->pk = 'id';
        $this->orm->columns = [
            'id' => 'id',
            'distilleryId' => 'distillery_id',
            'name' => 'name',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
        ];
        $this->orm->createdAtColumn = 'createdAt';
        $this->orm->updatedAtColumn = 'updatedAt';

        // create join and add it

        $distilleryJoin = new Join('', 'b', 'distillery',
            ['bId' => 'id', 'distilleryName' => 'name', 'country' => 'country', 'region' => 'region'], // no same name allowed
            ['bId', 'distilleryId']);
        $this->join($distilleryJoin);
    }

}