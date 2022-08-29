<?php

declare(strict_types=1);

namespace Eone;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Read prices from EasyEnergy.com
 */
class EasyEnergyPrice {

    const PRICES_URL = "https://mijn.easyenergy.com/nl/api/tariff/getapxtariffs";
    const DATE_START_FILTER = "startTimestamp";
    const DATE_END_FITLER = "endTimestamp";
    const FORMAT_ISO = "c";

    private HttpClientInterface $httpClient;
    private $prices = [];
    private $errorBody = '';

    public function __construct(HttpClientInterface $client) {
        $this->httpClient = $client;
    }

    /**
     * Read the prices for a given day.
     * 
     * @param \DateTimeImmutable $date Defaults to now
     * @return bool
     */
    public function read(\DateTimeImmutable $date = new \DateTimeImmutable()): bool {
        $success = false;
        $options = ['query' => [self::DATE_START_FILTER => $date->format(self::FORMAT_ISO),
                self::DATE_END_FITLER => $date->modify("+1 day")->format(self::FORMAT_ISO)]
                ];
        $response = $this->httpClient->request("GET", self::PRICES_URL, $options);
        if ($response->getStatusCode() == 200) {
            // Merge arrays to allow to read multiple days
            $this->prices = array_merge($this->prices, $response->toArray());
            $success = true;
        } else {
            $this->errorBody = "Status code: " . $response->getStatusCode() . ". Error body: " . $response->getContent(false);
        }

        return $success;
    }

    /**
     * Get the prices
     * 
     * @return array
     */
    public function getPrices(): array {
        return $this->prices;
    }

    public function getErrorMessage(): string {
        return $this->errorBody;
    }

}
