<?php

/**
 * This file contains the PHPLoggerTest class.
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

use Lunr\Fdbk\PHPLogger;
use Psr\Log\LogLevel;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * This class contains test methods for the PHPLogger class.
 *
 * @category   Libraries
 * @package    Fdbk
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @covers     Lunr\Fdbk\PHPLogger
 */
class PHPLoggerTest extends PHPUnit_Framework_TestCase
{

    /**
     * Mock-instance of the Request class.
     * @var Request
     */
    private $request;

    /**
     * Instance of the PHPLogger class.
     * @var PHPLogger
     */
    private $logger;

    /**
     * Reflection-instance of the PHPLogger class.
     * @var ReflectionClass
     */
    private $logger_reflection;

    /**
     * Testcase Constructor.
     */
    public function setUp()
    {
        $this->logger_reflection = new ReflectionClass('Lunr\Fdbk\PHPLogger');

        $this->request = $this->getMockBuilder('Lunr\Core\Request')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->logger = new PHPLogger($this->request);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown()
    {
        unset($this->logger_reflection);
        unset($this->logger);
        unset($this->request);
    }

    /**
     * Test that the request class is set correctly.
     */
    public function testRequestSetCorrectly()
    {
        $property = $this->logger_reflection->getProperty('request');
        $property->setAccessible(TRUE);

        $this->assertEquals($this->request, $property->getValue($this->logger));
        $this->assertSame($this->request, $property->getValue($this->logger));
    }

    /**
     * Test that interpolate_message replaces the placeholders as defined in $context.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::interpolate_message
     */
    public function testInterpolateMessageReplacesPlaceholders($message, $context, $expected)
    {
        $method = $this->logger_reflection->getMethod('interpolate_message');
        $method->setAccessible(TRUE);

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals($expected, $msg);
    }

    /**
     * Test that compose_message() without request and file/line metadata present returns the interpolated message.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @depends      testInterpolateMessageReplacesPlaceholders
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::compose_message
     */
    public function testComposeMessageWithoutRequestAndFileInformationReturnsInterpolatedMessage($message, $context, $expected)
    {
        $method = $this->logger_reflection->getMethod('compose_message');
        $method->setAccessible(TRUE);

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals($expected, $msg);
    }

    /**
     * Test message composition when only controller is set.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @depends      testInterpolateMessageReplacesPlaceholders
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::compose_message
     */
    public function testComposeMessageWithControllerAvailable($message, $context, $expected)
    {
        $method = $this->logger_reflection->getMethod('compose_message');
        $method->setAccessible(TRUE);

        $this->request->expects($this->at(0))
                      ->method('__get')
                      ->with($this->equalTo('controller'))
                      ->will($this->returnValue('controller'));
        $this->request->expects($this->at(1))
                      ->method('__get')
                      ->with($this->equalTo('method'))
                      ->will($this->returnValue(NULL));

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals($expected, $msg);
    }

    /**
     * Test message composition when only method is set.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @depends      testInterpolateMessageReplacesPlaceholders
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::compose_message
     */
    public function testComposeMessageWithMethodAvailable($message, $context, $expected)
    {
        $method = $this->logger_reflection->getMethod('compose_message');
        $method->setAccessible(TRUE);

        $this->request->expects($this->once())
                      ->method('__get')
                      ->with($this->equalTo('controller'))
                      ->will($this->returnValue(NULL));

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals($expected, $msg);
    }

    /**
     * Test message composition when controller and method are set.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @depends      testInterpolateMessageReplacesPlaceholders
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::compose_message
     */
    public function testComposeMessageWithControllerAndMethodAvailable($message, $context, $expected)
    {
        $method = $this->logger_reflection->getMethod('compose_message');
        $method->setAccessible(TRUE);

        $this->request->expects($this->exactly(4))
                      ->method('__get')
                      ->will($this->returnArgument(0));

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals('controller/method: ' . $expected, $msg);
    }

    /**
     * Test message composition when only file is set.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @depends      testInterpolateMessageReplacesPlaceholders
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::compose_message
     */
    public function testComposeMessageWithFileAvailable($message, $context, $expected)
    {
        $context['file'] = 'Test.php';

        $method = $this->logger_reflection->getMethod('compose_message');
        $method->setAccessible(TRUE);

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals($expected, $msg);
    }

    /**
     * Test message composition when only line is set.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @depends      testInterpolateMessageReplacesPlaceholders
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::compose_message
     */
    public function testComposeMessageWithLineAvailable($message, $context, $expected)
    {
        $context['line'] = '223';

        $method = $this->logger_reflection->getMethod('compose_message');
        $method->setAccessible(TRUE);

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals($expected, $msg);
    }

    /**
     * Test message composition when file and line are set.
     *
     * @param String $message  The original log message
     * @param array  $context  Log message meta-data
     * @param String $expected Expected log message after replacing
     *
     * @depends      testInterpolateMessageReplacesPlaceholders
     * @dataProvider messageProvider
     * @covers       Lunr\Fdbk\PHPLogger::compose_message
     */
    public function testComposeMessageWithFileAndLineAvailable($message, $context, $expected)
    {
        $context['file'] = 'Test.php';
        $context['line'] = '223';

        $method = $this->logger_reflection->getMethod('compose_message');
        $method->setAccessible(TRUE);

        $msg = $method->invokeArgs($this->logger, array($message, $context));

        $this->assertEquals($expected . ' (Test.php: 223)', $msg);
    }

    /**
     * Test that log() returns a PHP Error.
     *
     * @param String $level              PSR-3 Log Level
     * @param String $expected_exception Expected PHPUnit Exception as string
     *
     * @dataProvider logLevelProvider
     * @covers       Lunr\Fdbk\PHPLogger::log
     */
    public function testLogReturnsError($level, $expected_exception)
    {
        $this->setExpectedException($expected_exception);

        $this->logger->log($level, 'msg');
    }

    /**
     * Test that log() returns a string when an object is passed as message.
     *
     * @expectedException PHPUnit_Framework_Error_Warning
     * @covers            Lunr\Fdbk\PHPLogger::log
     */
    public function testLogReturnsStringWhenObjectPassedAsMessage()
    {
        $object = new MockLogMessage();

        $this->logger->log(LogLevel::WARNING, $object);
    }

    /**
     * Unit test data provider for log messages.
     *
     * @return array $messages Array of log messages
     */
    public function messageProvider()
    {
        $messages   = array();
        $messages[] = array('{test} msg', array('test' => 'value'), 'value msg');
        $messages[] = array('{test} msg, {test1}', array('test' => 'value', 'test1' => 1), 'value msg, 1');

        return $messages;
    }

    /**
     * Unit test data provider for log levels.
     *
     * @return array levels Array of lof levels.
     */
    public function logLevelProvider()
    {
        $levels   = array();
        $levels[] = array(LogLevel::EMERGENCY, 'PHPUnit_Framework_Error');
        $levels[] = array(LogLevel::ALERT, 'PHPUnit_Framework_Error');
        $levels[] = array(LogLevel::CRITICAL, 'PHPUnit_Framework_Error');
        $levels[] = array(LogLevel::ERROR, 'PHPUnit_Framework_Error');
        $levels[] = array(LogLevel::WARNING, 'PHPUnit_Framework_Error_Warning');
        $levels[] = array(LogLevel::NOTICE, 'PHPUnit_Framework_Error_Notice');
        $levels[] = array(LogLevel::INFO, 'PHPUnit_Framework_Error_Notice');
        $levels[] = array(LogLevel::DEBUG, 'PHPUnit_Framework_Error_Notice');
        $levels[] = array('whatever', 'PHPUnit_Framework_Error_Notice');

        return $levels;
    }

}

?>
