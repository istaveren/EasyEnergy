<?php

declare(strict_types=1);

namespace Eone;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Read prices from EasyEnergy.com
 */
class EasyEnergyPrice
{
    public const PRICES_URL = "https://mijn.easyenergy.com/nl/api/tariff/getapxtariffs";
    public const DATE_START_FILTER = "startTimestamp";
    public const DATE_END_FITLER = "endTimestamp";
    public const FORMAT_ISO = "c";

    private HttpClientInterface $httpClient;
    private $prices = [];
    private $errorBody = '';

    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Read the prices for a given day.
     *
     * @param \DateTimeImmutable $startDate Defaults to now
     * @param \DateTimeImmutable $endDate Defaults to tomorrow
     * @return bool
     */
    public function read(\DateTimeImmutable $startDate = new \DateTimeImmutable(), ?\DateTimeImmutable $endDate = null): bool
    {
        $success = false;
        $options = ['query' => [self::DATE_START_FILTER => $startDate->format(self::FORMAT_ISO),
                self::DATE_END_FITLER => ($endDate === null ? $startDate->modify("+1 day") : $endDate)->format(self::FORMAT_ISO)]
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
    public function getPrices(): array
    {
        return $this->prices;
    }

    public function getErrorMessage(): string
    {
        return $this->errorBody;
    }
}
