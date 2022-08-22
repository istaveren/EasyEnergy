<?php

declare(strict_types=1);

namespace Eone;

/**
 * Can read data from EasyEnergy.com and can store it in the DB.
 */
class EasyEnergyPrice {

    const PRICES_URL = "https://mijn.easyenergy.com/nl/api/tariff/getapxtariffs";
    const DATE_START_FILTER = "startTimestamp";
    const DATE_END_FITLER = "endTimestamp";

    public function read(\DateTime $date = new \DateTime()): bool {
        
    }
    
    public function store(): bool {
        
    }

}
