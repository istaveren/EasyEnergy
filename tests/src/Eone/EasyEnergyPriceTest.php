<?php

namespace Eone;

use Eone\EasyEnergyPrice;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EasyEnergyPriceTest extends TestCase
{
    /**
     * @var EasyEnergyPrice
     */
    protected $object;
    protected HttpClientInterface $client;
    protected $response;
    protected MockResponse $mockResponse;

    /**
     * Sets up the fixture
     */
    protected function setUp(): void
    {
        $this->response = file_get_contents("tests/fixtures/day.json");
        $this->mockResponse = new MockResponse($this->response);
        $this->object = new EasyEnergyPrice(new MockHttpClient($this->mockResponse));
    }

    /**
     * @covers Eone\EasyEnergyPrice::read
     * @covers Eone\EasyEnergyPrice::getPrices
     */
    public function testReadAndGetPrices()
    {
        $startDate = new \DateTimeImmutable();
        $this->assertTrue($this->object->Read());
        $this->assertEquals(['startTimestamp' => $startDate->format('c'), 'endTimestamp' => $startDate->modify('+1 day')->format('c')], $this->mockResponse->getRequestOptions()['query']);
        $this->assertEquals(json_decode($this->response, true), $this->object->getPrices());
    }

    public function testReadAndGetPricesWithDates()
    {
        $startDate = new \DateTimeImmutable('-4 weeks');
        $endDate = new \DateTimeImmutable('yesterday');
        $this->assertTrue($this->object->Read($startDate, $endDate));
        $this->assertEquals(['startTimestamp' => $startDate->format('c'), 'endTimestamp' => $endDate->format('c')], $this->mockResponse->getRequestOptions()['query']);
    }

    /**
     * @covers Eone\EasyEnergyPrice::getErrorMessage
     * @covers Eone\EasyEnergyPrice::read
     */
    public function testGetErrorMessage()
    {
        $this->response = "Not found";
        $this->mockResponse = new MockResponse($this->response, ['http_code' => 404]);
        $this->object = new EasyEnergyPrice(new MockHttpClient($this->mockResponse));

        $this->assertFalse($this->object->Read());
        $this->assertEquals('Status code: 404. Error body: '.$this->response, $this->object->getErrorMessage());
    }
}
