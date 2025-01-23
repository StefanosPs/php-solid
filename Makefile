PROJECT_NAME = "wather_client"
# Executables (local)
DOCKER_COMP = docker compose --project-name $(PROJECT_NAME)

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec app

# Executables
PHP      = $(PHP_CONT) php
COMPOSER =  $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console
PHP_CS_FIXER_CMD=$(COMPOSER) run cs:check

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down logs bash dev-init cs-check tests test-unit test-integration


## —— 🎵 🐳 The Symfony-docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[//a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
docker/build: ## Builds the Docker images
	@$(DOCKER_COMP) build
docker/up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up -d

docker/start: docker/build docker/up ## Build and start the containers

docker/down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

docker/logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

docker/bash: ## Connect to the server container
	@$(PHP_CONT) bash

## —— Tools  ————————————————————————————————————————————————————————————————————
tools/cs-check:  ## run cs fixer check (PHP Coding Standards Fixer)
	$(PHP_CS_FIXER_CMD)

tools/cs-fix:  ## run cs fixer (PHP Coding Standards Fixer)
	$(COMPOSER) run cs:fix

tools/stan:  ## run cs fixer (PHP Coding Standards Fixer)
	$(COMPOSER) run stan

## —— Custom command  ———————————————————————————————————————————————————————————

custom/run:
	$(SYMFONY) app:weather-info Cholargos
