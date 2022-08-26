<?php

declare(strict_types=1);

namespace Eone;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Can read data from EasyEnergy.com and can store it in the DB.
 */
class EasyEnergyPrice {

    const PRICES_URL = "https://mijn.easyenergy.com/nl/api/tariff/getapxtariffs";
    const DATE_START_FILTER = "startTimestamp";
    const DATE_END_FITLER = "endTimestamp";
    
    private HttpClientInterface $httpClient;
    private $prices = [];
    
    public function __construct(HttpClientInterface $client) {
        $this->httpClient = $client;
    }

    public function read(\DateTime $date = new \DateTime()): bool {
        return false;
    }
    
    public function store(): bool {
        return false;
    }
    
    public function getPrices(): \ArrayAccess {
        return $this->prices;
    }
}
