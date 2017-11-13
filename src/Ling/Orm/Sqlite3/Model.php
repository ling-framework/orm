<?php
/**
 * Created by IntelliJ IDEA.
 * User: fri13th
 * Date: 2017/11/06
 * Time: 16:27
 */

namespace Ling\Orm\Sqlite3;

class Model extends \Ling\Orm\Common\Model {

    /** @var Orm Orm */
    protected $orm;

    public function __construct()
    {
        $this->orm = new Orm();
        $this->init();
        $this->orm->init($this);
    }

}