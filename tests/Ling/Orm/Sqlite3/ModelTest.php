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
use function Ling\config as config;

include 'bootstrap.php';



class ModelTest extends TestCase
{

    public $dao;


    public function setUp() {
        $pdo = new \PDO('sqlite:whisky.sqlite3');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        config(['orm.pdo' => $pdo]);

        $this->dao = new Model();

        foreach (glob(__DIR__ . '/fixture/create/*.sql') as $filename) {
            $sql = file_get_contents($filename);
            $this->dao->exec($sql);
        }
        foreach (glob(__DIR__ . '/fixture/insert/*.sql') as $filename) {
            //error_log($filename);
            $sql = file_get_contents($filename);
            $this->dao->exec($sql);
        }

    }

    public function tearDown()
    {
        unlink('whisky.sqlite3');
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

    public function testSelectAllDistillery() {
        // select where shop is 1 or 3 and stock is more than 2 and price is over 3
        // stock > 3 and (shop_seq = 1 or shop_seq = 3) and price > 10000
        // ((stock = 3 and stock = 10) or (shop_seq = 1 or shop_seq = 3)) and price > 10000

        $dao = new DistilleryModel();
        $distilleries = $dao->where('seq', '>', 2)->selectAll();
        $this->assertEquals($distilleries[0]->name, 'yamazaki');
    }


    public function testWrapOrNotStock() {
        $dao = new StockModel();
        $stocks = $dao->where('stock', '>', 3)
            ->opOr()->wrap()->

    }


    // we need partial select and partial update, i.e. password field. we don't need to get password at all. password must be hashed in proper way.

    // we need to test join

    // and set files
}