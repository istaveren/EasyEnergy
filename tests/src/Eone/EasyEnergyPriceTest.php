<?php

namespace Test\Eone;

use Eone\EasyEnergyPrice;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EasyEnergyPriceTest extends TestCase {

    /**
     * @var EasyEnergyPrice
     */
    protected $object;
    protected HttpClientInterface $client;
    protected $response;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->response = file_get_contents("tests/fixtures/day.json");
        $mockResponse = new MockResponse($this->response);
        $this->object = new EasyEnergyPrice(new MockHttpClient($mockResponse));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void {
        
    }

    /**
     * @covers Eone\EasyEnergyPrice::read
     * @covers Eone\EasyEnergyPrice::getPrices
     */
    public function testReadAndGetPrices() {
        $this->assertTrue($this->object->Read());
        $this->assertEquals(json_decode($this->response, true), $this->object->getPrices());
    }
}
