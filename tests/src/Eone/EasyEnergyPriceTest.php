<?php

namespace Test\Eone;

use Eone\EasyEnergyPrice;

class EasyEnergyPriceTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var EasyEnergyPrice
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->object = new EasyEnergyPrice();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void {
        
    }

    /**
     * @covers Eone\EasyEnergyPrice::read
     * @todo   Implement testRead().
     */
    public function testRead() {
        $this->assertEquals('', $this->object->Read());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers Eone\EasyEnergyPrice::store
     * @todo   Implement testStore().
     */
    public function testStore() {
        $this->assertEquals('', $this->object->Store());
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

}
