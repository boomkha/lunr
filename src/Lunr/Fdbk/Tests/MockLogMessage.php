<?php

/**
 * This file contains a MockLogMessage class.
 *
 * PHP Version 5.3
 *
 * @category   Libraries
 * @package    Fdbk
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @copyright  2012-2013, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Fdbk\Tests;

/**
 * This class contains a mock for a simple log message class.
 *
 * @category   Libraries
 * @package    Fdbk
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 */
class MockLogMessage
{

    /**
     * Return a simple test string.
     *
     * @return String $return simple test string
     */
    public function __toString()
    {
        return 'Foo';
    }

}

?>
