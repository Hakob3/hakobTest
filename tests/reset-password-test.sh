#!/usr/bin/env bash
export APP_ENV=test
echo $APP_ENV
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console hautelook:fixtures:load -n

php bin/phpunit --testdox --group resetPassword
vendor/bin/codecept run functional LoginCest
#php bin/console doctrine:database:drop --force