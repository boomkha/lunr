<?php

/**
 * This file contains the MySQLDMLQueryBuilderTest class.
 *
 * PHP Version 5.3
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @author     Felipe Martinez <felipe@m2mobi.com>
 * @copyright  2012-2013, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\DataAccess\Tests;

use Lunr\DataAccess\MySQLDMLQueryBuilder;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the MySQLDMLQueryBuilder class.
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @covers     Lunr\DataAccess\MySQLDMLQueryBuilder
 */
abstract class MySQLDMLQueryBuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * Instance of the MySQLQueryBuilder class.
     * @var MySQLDMLQueryBuilder
     */
    protected $builder;

    /**
     * Reflection instance of the MySQLDMLQueryBuilder class.
     * @var ReflectionClass
     */
    protected $builder_reflection;

    /**
     * Mock instance of the MySQLConnection class.
     * @var MySQLConnection
     */
    protected $db;

    /**
     * TestCase Constructor.
     */
    public function setUp()
    {
        $this->db = $this->getMockBuilder('Lunr\DataAccess\MySQLConnection')
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->builder = new MySQLDMLQueryBuilder($this->db);

        $this->builder_reflection = new ReflectionClass('Lunr\DataAccess\MySQLDMLQueryBuilder');
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown()
    {
        unset($this->builder);
        unset($this->builder_reflection);
        unset($this->db);
    }

    /**
     * Unit Test Data Provider for Select modes handling duplicate result entries.
     *
     * @return array $modes Array of select modes
     */
    public function selectModesDuplicatesProvider()
    {
        $modes   = array();
        $modes[] = array('ALL');
        $modes[] = array('DISTINCT');
        $modes[] = array('DISTINCTROW');

        return $modes;
    }

    /**
     * Unit Test Data Provider for Select modes handling the sql query cache.
     *
     * @return array $modes Array of select modes
     */
    public function selectModesCacheProvider()
    {
        $modes   = array();
        $modes[] = array('SQL_CACHE');
        $modes[] = array('SQL_NO_CACHE');

        return $modes;
    }

    /**
     * Unit Test Data Provider for standard Select modes.
     *
     * @return array $modes Array of select modes
     */
    public function selectModesStandardProvider()
    {
        $modes   = array();
        $modes[] = array('HIGH_PRIORITY');
        $modes[] = array('STRAIGHT_JOIN');
        $modes[] = array('SQL_BIG_RESULT');
        $modes[] = array('SQL_SMALL_RESULT');
        $modes[] = array('SQL_BUFFER_RESULT');
        $modes[] = array('SQL_CALC_FOUND_ROWS');

        return $modes;
    }

    /**
     * Unit Test Data Provider for standard Select modes.
     *
     * @return array $modes Array of select modes
     */
    public function updateModesStandardProvider()
    {
        $modes   = array();
        $modes[] = array('LOW_PRIORITY');
        $modes[] = array('IGNORE');

        return $modes;
    }

    /**
     * Unit Test Data Provider for standard lock modes.
     *
     * @return array $modes Array of lock modes
     */
    public function lockModesStandardProvider()
    {
        $modes   = array();
        $modes[] = array('FOR UPDATE');
        $modes[] = array('LOCK IN SHARE MODE');

        return $modes;
    }

    /**
     * Unit Test Data Provider for Delete modes.
     *
     * @return array $modes Array of delete modes
     */
    public function deleteModesStandardProvider()
    {
        $modes   = array();
        $modes[] = array('LOW_PRIORITY');
        $modes[] = array('QUICK');
        $modes[] = array('IGNORE');

        return $modes;
    }

    /**
     * Unit Test Data Provider for Delete modes uppercasing.
     *
     * @return array $expectedmodes Array of delete modes and their expected result
     */
    public function expectedDeleteModesProvider()
    {
        $expectedmodes   = array();
        $expectedmodes[] = array('low_priority', 'LOW_PRIORITY');
        $expectedmodes[] = array('QuIcK', 'QUICK');
        $expectedmodes[] = array('IGNORE', 'IGNORE');

        return $expectedmodes;
    }

    /**
     * Unit Test Data Provider for Insert modes.
     *
     * @return array $modes Array of Insert modes
     */
    public function insertModesStandardProvider()
    {
        $modes   = array();
        $modes[] = array('LOW_PRIORITY');
        $modes[] = array('DELAYED');
        $modes[] = array('HIGH_PRIORITY');
        $modes[] = array('IGNORE');

        return $modes;
    }

    /**
     * Unit Test Data Provider for Insert modes uppercasing.
     *
     * @return array $expectedmodes Array of insert modes and their expected result
     */
    public function expectedInsertModesProvider()
    {
        $expectedmodes   = array();
        $expectedmodes[] = array('low_priority', 'LOW_PRIORITY');
        $expectedmodes[] = array('DeLayeD', 'DELAYED');
        $expectedmodes[] = array('IGNORE', 'IGNORE');

        return $expectedmodes;
    }

    /**
     * Unit Test Data Provider for invalid indices.
     *
     * @return array $indices Array of invalid indices
     */
    public function invalidIndicesProvider()
    {
        $indices   = array();
        $indices[] = array(NULL);
        $indices[] = array(FALSE);
        $indices[] = array('string');
        $indices[] = array(new \stdClass());
        $indices[] = array(array());

        return $indices;
    }

    /**
     * Unit Test Data Provider for valid Index Keywords.
     *
     * @return array $keywords Array of valid index keywords.
     */
    public function validIndexKeywordProvider()
    {
        $keywords   = array();
        $keywords[] = array('USE');
        $keywords[] = array('IGNORE');
        $keywords[] = array('FORCE');

        return $keywords;
    }

    /**
     * Unit Test Data Provider for valid Index use definitions.
     *
     * @return array $for Array of valid index use definitions.
     */
    public function validIndexForProvider()
    {
        $for   = array();
        $for[] = array('JOIN');
        $for[] = array('ORDER BY');
        $for[] = array('GROUP BY');
        $for[] = array('');

        return $for;
    }

}

?>
