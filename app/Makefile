#!/bin/bash
SHELL = /bin/bash # Use bash syntax

# ARGUMENT - ENV
ifndef ENV
ENV = dev
else
ENV = $(ENV)
endif

ifeq ($(ENV),dev)
BIN_PHP = php
ENVIRONMENT = develop
else ifeq ($(ENV),staging)
BIN_PHP = php
ENVIRONMENT = staging
else ifeq ($(ENV),preprod)
BIN_PHP = php
ENVIRONMENT = preproduction
else ifeq ($(ENV),prod)
BIN_PHP = php
ENVIRONMENT = production
else
$(error ENV argument is required : dev|staging|preprod|prod)
endif

# VARIABLES
SYMFONY_CONSOLE = $(BIN_PHP) bin/console

ifeq ($(OS), Windows_NT)
	CURRENT_UID = $(cmd id -u)
	CURRENT_GID = $(cmd id -g)
else
	CURRENT_UID = $(shell id -u)
	CURRENT_GID = $(shell id -g)
endif

# EXEC
EXEC_CONTAINER = docker exec -it -u $(CURRENT_UID):$(CURRENT_GID)

# RUN
RUN_APP = docker-compose run --rm -u $(CURRENT_UID):$(CURRENT_GID) www
RUN_NODE = docker-compose run --rm -u $(CURRENT_UID):$(CURRENT_GID) node

# HELP
.DEFAULT_GOAL = help

# Display the list commands whit the command "make"
ifeq ($(OS), Windows_NT)
help:
	@echo "/!\ Make help is disabled on windows /!\ ";
.PHONY: help
else
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
endif

##-----------------------------------------
## QUALITY
##-----------------------------------------

quality: ## Global quality (app)
quality: phpfixer phpstan  lint

lint: ## Run Lint globally (app)
lint:
	$(SYMFONY_CONSOLE) lint:twig templates 
	$(SYMFONY_CONSOLE) lint:yaml config translations 

phpfixer: ## Run PhpCsFixer globally (app)
# phpfixer: .php-cs-fixer-dist.php
	$(BIN_PHP) tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -vvv --diff

phpstan: ## Run PhpStan globally (app)
phpstan: phpstan.dist.neon
	$(BIN_PHP) vendor/bin/phpstan analyse public src tests --level=max -c phpstan.dist.neon --memory-limit=2G

##-----------------------------------------
## DATABASE
##-----------------------------------------

db-load-latest: ## Load latest SQL file - depending env
	$(SYMFONY_CONSOLE) doctrine:database:drop --force
	$(SYMFONY_CONSOLE) doctrine:database:create
	$(SYMFONY_CONSOLE) doctrine:database:import latest.$(ENV).sql

db-with-fixture: 
	$(SYMFONY_CONSOLE) doctrine:fixtures:load

##-----------------------------------------
## .ENV FILES
##-----------------------------------------

generate-env-files: ## Generate env files (from .env.dist)
generate-env-files: generate-env

ifeq ($(OS), Windows_NT)
generate-env: ## Generate .env (windows)
generate-env: .env.dist
	@if exist .env; then \
		echo '.env already exists';\
	else \
		echo cp .env.dist .env; \
		cp .env.dist .env; \
  	fi
else
generate-env: ## Generate .env (unix)
generate-env: .env.dist
	@if [ -f .env ]; then \
		echo '.env already exists';\
	else \
		echo cp .env.dist .env; \
		cp .env.dist .env; \
  	fi
endif

switch-env-develop: ## Generate .env  (.settings/files/.env.develop - no docker)
switch-env-develop: .settings/files/.env.develop
	@ echo cp .settings/files/.env.develop .env; \
	cp .settings/files/.env.develop .env;

switch-env-develop-docker: ## Generate .env (.settings/files/.env.develop.docker - docker)
switch-env-develop-docker: .settings/files/.env.develop.docker
	@ echo cp .settings/files/.env.develop.docker .env; \
	cp .settings/files/.env.develop.docker .env;

##-----------------------------------------
## APPLICATION
##-----------------------------------------

fix-folders-files: ## Fix folders and files
fix-folders-files:
	- bin/shared/fix-folders-files.sh

##-----------------------------------------
## TOOLS
##-----------------------------------------

front-dev-build: ## Build front for dev
	yarn install && yarn encore dev

front-dev-watch: ## Watch front for dev
	yarn && yarn dev --watch

front-production-build: ## Build front for production
	yarn install && yarn encore production

##-----------------------------------------
## COMPOSER
##-----------------------------------------

composer-install: ## Install bundles of symfony whit composer
	@echo "Install bundles of symfony whit compose"
	composer install

composer-update: ## Composer updata
	- composer update