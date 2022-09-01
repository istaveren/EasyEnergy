# Easy energy prices

This project pulls the prices from <https://www.easyenergy.com/nl/energietarieven> for a given date and stores them in the database.

So that the prices can be used to decide when to start or stop your electricity appliances.


## Use the program

It uses Symfony console application to get a nice CLI.

You can see all options by running

    ./prices -h

## Running the test

To run PHPunit use

    ./vendor/bin/phpunit --colors=auto --testdox tests

## With Docker

To run the applicaton in Docker. Assume you have docker installed and docker-compose.

```bash
docker compose up -d
docker compose exec app ./vendor/bin/phpunit
docker compose exec app ./prices easyEnergy:read
```

## Create table

Table needed in MySql 

```sql
SET NAMES utf8mb4;

DROP TABLE IF EXISTS `price_per_hour`;
CREATE TABLE `price_per_hour` (
  `time` datetime NOT NULL,
  `price` decimal(7,5) NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```
