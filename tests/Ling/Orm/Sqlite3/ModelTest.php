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
            echo $filename . PHP_EOL;
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

        $model->name = 'mortlach';
        $model->save();

        $this->assertEquals($model->seq, 9);
        $this->assertEquals($model->name, 'mortlach');
    }

    // we need to test join

    // and set files
}