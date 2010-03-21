<?php
/**
 * Phergie
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://phergie.org/license
 *
 * @category  Phergie
 * @package   Phergie
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2010 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie
 */

require_once(dirname(__FILE__) . '/TestCase.php');

/**
 * Unit test suite for Pherge_Plugin_Ping.
 *
 * @category Phergie
 * @package  Phergie_Tests
 * @author   Phergie Development Team <team@phergie.org>
 * @license  http://phergie.org/license New BSD License
 * @link     http://pear.phergie.org/package/Phergie
 */
class Phergie_Plugin_PingTest extends Phergie_Plugin_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->setPlugin(new Phergie_Plugin_Ping);
    }


    /**
     * Tests the onConnect hook
     */
    public function testOnConnect()
    {
        $time = time();
        // We need to make sure time() is going to be creater next time it is called
        sleep(1);
        $this->plugin->onConnect();
        $this->assertNull($this->plugin->getLastPing(), 
                          'onConnect should set last ping to null');
        $this->assertGreaterThan($time,
                                 $this->plugin->getLastEvent(),
                                 'onConnect should update lastEvent with the ' .
                                 'current timestamp');
    }

    /**
     * @todo Implement testPreEvent().
     */
    public function testPreEvent()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testOnPingResponse().
     */
    public function testOnPingResponse()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testOnTick().
     */
    public function testOnTick()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>
