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

class BottleModel extends Model {
    public $id;
    public $name;
    public $liquorType;
    public $caskType;
    public $year;
    public $age;
    public $strength;
    public $volume;
    public $brandId;
    public $bottlerBrandId;
    public $importerId;
    public $createdAt;
    public $updatedAt;

    // join brand
    public $bId;
    public $distilleryId;
    public $brandName;

    // join distillery
    public $cId;
    public $distilleryName;
    public $country;
    public $region;

    // join bottlerBrand
    public $dId;
    public $bottlerId;
    public $bottlerBrandName;

    // join bottler
    public $eId;
    public $bottlerName;
    public $bottlerCountry;

    // join importer
    public $fId;
    public $importerName;
    public $importerCountry;


    public function init() {
        $this->orm->tableName = 'bottle';
        $this->orm->pk = 'id';
        $this->orm->columns = [
            'id' => 'id',
            'name' => 'name',
            'liquorType' => 'liquor_type',
            'caskType' => 'cask_type',
            'year' => 'year',
            'age' => 'age',
            'strength' => 'strength',
            'volume' => 'volume',
            'brandId' => 'brand_id',
            'bottlerBrandId' => 'bottler_brand_id',
            'importerId' => 'importer_id',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
        ];
        $this->orm->createdAtColumn = 'createdAt';
        $this->orm->updatedAtColumn = 'updatedAt';

        // create join and add it

        $brandJoin = new Join('', 'b', 'brand',
            ['bId' => 'id', 'distilleryId' => 'distillery_id', 'brandName' => 'name'], // no same name allowed
            ['bId', 'brandId']);
        $this->join($brandJoin);

        $distilleryJoin = new Join('', 'c', 'distillery',
            ['cId' => 'id', 'distilleryName' => 'name', 'country' => 'country', 'region' => 'region'],
            ['cId', 'distilleryId']);
        $this->join($distilleryJoin);

        $bottlerBrandJoin = new Join('LEFT', 'd', 'bottler_brand',
            ['dId' => 'id', 'bottlerId' => 'bottler_id', 'bottlerBrandName' => 'name'],
            ['dId', 'bottlerBrandId']);
        $this->join($bottlerBrandJoin);

        $bottlerJoin = new Join('LEFT', 'e', 'bottler',
            ['eId' => 'id', 'bottlerName' => 'name', 'bottlerCountry' => 'country'],
            ['eId', 'bottlerId']);
        $this->join($bottlerJoin);

        $importerJoin = new Join('LEFT', 'f', 'importer',
            ['fId' => 'id', 'importerName' => 'name', 'importerCountry' => 'country'],
            ['fId', 'importerId']);
        $this->join($importerJoin);

    }

}