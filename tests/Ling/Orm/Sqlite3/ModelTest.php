<?php
/**
 * Created by IntelliJ IDEA.
 * User: fri13th
 * Date: 2017/11/06
 * Time: 20:59
 */

namespace Ling\Orm\Sqlite3;

use PHPUnit\Framework\TestCase;
use function Ling\config as config;

class ModelTest extends TestCase
{

    public $dao;

    public function setUp() {
        config(['orm.pdo' => new \PDO('sqlite::memory:')]);
    }

    public function sqlCreateProvider() {
        return [
            ['CREATE TABLE distillery (seq integer PRIMARY KEY, name text NOT NULL, country text NOT NULL, region text, created_at text NOT NULL, updated_at text NOT NULL)']
        ];
    }
    /**
     * @dataProvider sqlCreateProvider
     */
    public function testCreateTable($sql) // may we need some providers for many tables
    {
        $this->dao = new Model();
        $result = $this->dao->exec($sql, array());
        $rows = $this->dao->fetchArray('PRAGMA table_info(distillery)', []);
        $this->assertCount(6, $rows);
        $this->assertTrue($result);
    }

    /**
     * @depends testCreateTable
     */
    public function testInsertTable() {
        //

    }

    // we need to test join

    // and set files
}