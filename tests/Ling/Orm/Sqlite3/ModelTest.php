<?php
/**
 * Created by IntelliJ IDEA.
 * User: fri13th
 * Date: 2017/11/06
 * Time: 20:59
 */
declare(strict_types=1);

namespace Ling\Orm\Sqlite3;

use PHPUnit\Framework\TestCase;
use function Ling\config;

include 'bootstrap.php';



class ModelTest extends TestCase
{

    public function setUp() {
        config([Orm::CONFIG_KEY => pdo(':memory:')]);

        $dao = new Model();

        foreach (glob(__DIR__ . '/fixture/create/*.sql') as $filename) {
            $sql = file_get_contents($filename);
            $dao->exec($sql);
        }
        foreach (glob(__DIR__ . '/fixture/insert/*.sql') as $filename) {
            //error_log($filename);
            $sql = file_get_contents($filename);
            $dao->exec($sql);
        }
    }

    public function testInsertUpdateDistillery() {
        $model = new DistilleryModel();

        $model->name = 'laphroaig';
        $model->country = 'uk';
        $model->region = 'islay';
        $model->save();

        $this->assertEquals($model->seq, 9);
        $this->assertEquals($model->name, 'laphroaig');
        $this->assertNotNull($model->createdAt);
        $this->assertNotNull($model->updatedAt);
        $this->assertEquals($model->createdAt, $model->updatedAt);

        $model->name = 'mortlach';
        $model->save();

        $this->assertEquals($model->seq, 9);
        $this->assertEquals($model->name, 'mortlach');
        //$hash = password_hash('hello', PASSWORD_DEFAULT);
    }

    public function testSelectDistillery() {
        // select where shop is 1 or 3 and stock is more than 2 and price is over 3
        // stock > 3 and (shop_seq = 1 or shop_seq = 3) and price > 10000
        // ((stock = 3 and stock = 10) or (shop_seq = 1 or shop_seq = 3)) and price > 10000

        $dao = new DistilleryModel();
        $distillery = $dao->where('name', '=', 'laphroaig')->select();
        $this->assertEquals($distillery->name, 'laphroaig');
    }

    public function testSelectAllLimitDistillery() {
        $dao = new DistilleryModel();
        $distilleries = $dao->where('seq', '>', 2)->selectAll();
        $this->assertEquals($distilleries[0]->name, 'yamazaki');

        $distilleries = $dao->where('seq', '>', 2)->limit(5)->selectAll();
        $this->assertCount(5, $distilleries);

        $distilleries = $dao->where('seq', '>', 2)->limit(1, 2)->selectAll();
        $this->assertCount(2, $distilleries);

    }


    public function testWrapOrNotStock() {
        $dao = new StockModel();

        // stock > 3 or (shop_seq = 1 or shop_seq = 3) and price > 10000
        $stocks = $dao->where('stock', '>', 3)
            ->opOr()->wrap()->eq('shopSeq', 1)->opOr()->eq('shopSeq', 3)->wrapEnd()
            ->where('price', '>', 10000)->selectAll();
        $this->assertCount(40, $stocks);

        // stock > 3 and (shop_seq = 1 or shop_seq = 3) and price > 10000
        $stocks = $dao->where('stock', '>', 3)
            ->wrap()->eq('shopSeq', 1)->opOr()->eq('shopSeq', 3)->wrapEnd()
            ->where('price', '>', 10000)->selectAll();
        $this->assertCount(11, $stocks);

        // ((stock = 3 or stock = 10) and (shop_seq = 1 or shop_seq = 3)) and price > 10000
        $stocks = $dao->wrap()->wrap()->eq('stock', 3)->opOr()->eq('stock', 10)->wrapEnd()
            ->wrap()->eq('shopSeq', 1)->opOr()->eq('shopSeq', 3)->wrapEnd()->wrapEnd()
            ->where('price', '>', 10000)->selectAll();
        $this->assertCount(8, $stocks);
    }

    public function testInBetweenSearch() {
        $dao = new StockModel();
        $stocks = $dao->in('stock', [1, 3])->selectAll();
        $this->assertCount(11, $stocks);

        $stocks = $dao->between('stock', 1, 3)->selectAll();
        $this->assertCount(12, $stocks);

        $stocks = $dao->search(['bottleSeq', 'stock'], '3')->selectAll();
        $this->assertCount(16, $stocks);
    }

    public function testCustomWhere() {
        $dao = new DistilleryModel();
        $distilleries = $dao->customWhere('region ISNULL')->selectAll();
        $this->assertCount(2, $distilleries);

        $distilleries = $dao->customWhere('NOT name = ? AND NOT name = ?', ['kavalan', 'caroni'])->selectAll();
        $this->assertCount(6, $distilleries);
    }

    public function testIncrement() {
        $dao = new StockModel();
        $stock = $dao->eq('seq', 1)->select();
        $this->assertEquals(10, $stock->stock);

        $stock->increment('stock');

        $stock = $dao->eq('seq', 1)->select();
        $this->assertEquals(11, $stock->stock);
    }

    public function testSelectChunk() {
        $dao = new StockModel();
        $dao->where('stock', '>', 10)->selectChunk(5, function(array $stocks, $index) {
            if ($index === 3) {
                $this->assertCount(3, $stocks);
            } else {
                $this->assertCount(5, $stocks);
            }
        });

    }

    public function testJoin() {
        $dao = new BrandModel();
        $brands = $dao->selectAll();
        $this->assertCount(15, $brands);

        // we need more complicated join issue

        $dao = new BottleModel();
        $bottles = $dao->selectAll();
        $this->assertCount(33, $bottles);
    }

    public function testPaginate() {
        $dao = new BottleModel();
        $paginate = new BottlePaginate(1);
        $paginate = $dao->orderBy('seq')->paginate($paginate);
        $this->assertEquals(33, $paginate->totalCount);
        $this->assertCount(5, $paginate->list);
    }

    public function testOneToMany() {
        // there must be a sub model which is connected to current model
        // parent model has to get one field for array
        // if there's matched model, we have to set when it goes.
        // use join

    }

    public function testManyToMany() {

        // We have to set two model as join
    }

}