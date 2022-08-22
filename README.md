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

