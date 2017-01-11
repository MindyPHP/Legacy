<?php

namespace Mindy\Query\Tests;

/**
 * @group db
 * @group pgsql
 */
class MysqlConnectionTest extends ConnectionTest
{
    protected $driverName = 'mysql';

    public function testConnection()
    {
        $this->getDb(true);
    }
}
