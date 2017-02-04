#!/bin/bash

clear;

# Start webserver, run Behat tests, stop webserver
php -S localhost:8089 tests/webserver.php &> /dev/null &
WEBSERVER_PROCESS_ID=$!;
./vendor/bin/phpunit --config tests/phpunit.xml;
PHPUNIT_RETURN_CODE=$?
kill -9 $WEBSERVER_PROCESS_ID;

exit $PHPUNIT_RETURN_CODE;
