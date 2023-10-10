#!/bin/bash

ENVIRONMENT=staging
ENVIRONMENT_SYMFONY=staging

PHP_BIN=/usr/bin/php7.4

# Cache clear
$PHP_BIN bin/console cache:clear --env=$ENVIRONMENT_SYMFONY

# Update database
$PHP_BIN bin/console doctrine:schema:update --complete --force --env=$ENVIRONMENT_SYMFONY

# Init data
# $PHP_BIN bin/console doctrine:fixtures:load --env=$ENVIRONMENT_SYMFONY