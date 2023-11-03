#!/bin/bash

ENVIRONMENT=test
ENVIRONMENT_SYMFONY=test

PHP_BIN=php

# Cache clear
$PHP_BIN bin/console cache:clear --env=$ENVIRONMENT_SYMFONY

# Update database
$PHP_BIN bin/console doctrine:schema:update --complete --force --env=$ENVIRONMENT_SYMFONY

# Init data
# $PHP_BIN bin/console app:init --env=$ENVIRONMENT_SYMFONY