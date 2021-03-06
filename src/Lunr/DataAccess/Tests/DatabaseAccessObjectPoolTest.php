<?php

/**
 * This file contains the DatabaseAccessObjectPoolTest class.
 *
 * PHP Version 5.3
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2012-2013, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\DataAccess\Tests;

use Lunr\DataAccess\DatabaseAccessObject;

/**
 * This class contains the tests for the DatabaseAccessObject class.
 *
 * Base tests for the case where there is a DatabaseConnectionPool.
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @covers     Lunr\DataAccess\DatabaseAccessObject
 */
class DatabaseAccessObjectPoolTest extends DatabaseAccessObjectTest
{

    /**
     * Testcase Constructor.
     */
    public function setUp()
    {
        $this->setUpPool();
    }

    /**
     * Test that DatabaseConnection class is passed by reference.
     */
    public function testDatabaseConnectionIsPassedByReference()
    {
        $property = $this->reflection_dao->getProperty('db');
        $property->setAccessible(TRUE);

        $this->assertSame($this->db, $property->getValue($this->dao));
    }

    /**
     * Test that DatabaseConnectionPool is passed by reference.
     */
    public function testDatabaseConnectionPoolIsPassedByReference()
    {
        $property = $this->reflection_dao->getProperty('pool');
        $property->setAccessible(TRUE);

        $this->assertSame($this->pool, $property->getValue($this->dao));
    }

}

?>
