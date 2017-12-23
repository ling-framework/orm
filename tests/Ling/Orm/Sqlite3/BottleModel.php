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
    public $seq;
    public $name;
    public $liquorType;
    public $caskType;
    public $year;
    public $age;
    public $strength;
    public $volume;
    public $brandSeq;
    public $bottlerBrandSeq;
    public $importerSeq;
    public $createdAt;
    public $updatedAt;

    // join brand
    public $bSeq;
    public $distillerySeq;
    public $brandName;

    // join distillery
    public $cSeq;
    public $distilleryName;
    public $country;
    public $region;

    // join bottlerBrand
    public $dSeq;
    public $bottlerSeq;
    public $bottlerBrandName;

    // join bottler
    public $eSeq;
    public $bottlerName;
    public $bottlerCountry;

    // join importer
    public $fSeq;
    public $importerName;
    public $importerCountry;


    public function init() {
        $this->orm->tableName = 'bottle';
        $this->orm->pk = 'seq';
        $this->orm->columns = [
            'seq' => 'seq',
            'name' => 'name',
            'liquorType' => 'liquor_type',
            'caskType' => 'cask_type',
            'year' => 'year',
            'age' => 'age',
            'strength' => 'strength',
            'volume' => 'volume',
            'brandSeq' => 'brand_seq',
            'bottlerBrandSeq' => 'bottler_brand_seq',
            'importerSeq' => 'importer_seq',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at',
        ];
        $this->orm->createdAtColumn = 'createdAt';
        $this->orm->updatedAtColumn = 'updatedAt';

        // create join and add it

        $brandJoin = new Join('', 'b', 'brand',
            ['bSeq' => 'seq', 'distillerySeq' => 'distillery_seq', 'brandName' => 'name'], // no same name allowed
            ['bSeq', 'brandSeq']);
        $this->join($brandJoin);

        $distilleryJoin = new Join('', 'c', 'distillery',
            ['cSeq' => 'seq', 'distilleryName' => 'name', 'country' => 'country', 'region' => 'region'],
            ['cSeq', 'distillerySeq']);
        $this->join($distilleryJoin);

        $bottlerBrandJoin = new Join('LEFT', 'd', 'bottler_brand',
            ['dSeq' => 'seq', 'bottlerSeq' => 'bottler_seq', 'bottlerBrandName' => 'name'],
            ['dSeq', 'bottlerBrandSeq']);
        $this->join($bottlerBrandJoin);

        $bottlerJoin = new Join('LEFT', 'e', 'bottler',
            ['eSeq' => 'seq', 'bottlerName' => 'name', 'bottlerCountry' => 'country'],
            ['eSeq', 'bottlerSeq']);
        $this->join($bottlerJoin);

        $importerJoin = new Join('LEFT', 'f', 'importer',
            ['fSeq' => 'seq', 'importerName' => 'name', 'importerCountry' => 'country'],
            ['fSeq', 'importerSeq']);
        $this->join($importerJoin);

    }

}