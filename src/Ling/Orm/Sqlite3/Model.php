<?php
/**
 * Created by IntelliJ IDEA.
 * User: fri13th
 * Date: 2017/11/06
 * Time: 16:27
 */

namespace Ling\Orm\Sqlite3;

class Model extends \Ling\Orm\Common\Model {

    public function __construct()
    {
        $this->orm = new Orm(get_class($this));
    }

}