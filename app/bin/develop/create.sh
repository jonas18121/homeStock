#!/bin/bash

ENVIRONMENT=develop
ENVIRONMENT_SYMFONY=dev

PHP_BIN=php

# Cache clear
$PHP_BIN bin/console cache:clear --env=$ENVIRONMENT_SYMFONY

# Create database
$PHP_BIN bin/console doctrine:database:create --env=$ENVIRONMENT_SYMFONY

# Create $PHP_BIN bin/console doctrine:schema:create --env=$ENVIRONMENT_SYMFONY

$PHP_BIN bin/console doctrine:schema:create --env=$ENVIRONMENT_SYMFONY

# add fixtures
$PHP_BIN bin/console doctrine:fixtures:load --no-interaction  --env=$ENVIRONMENT_SYMFONY

# Init data
# $PHP_BIN bin/console app:init --env=$ENVIRONMENT_SYMFONY