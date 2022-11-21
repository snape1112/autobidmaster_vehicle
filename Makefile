DOCKER=docker
DOCKER_COMPOSE?=docker-compose
RUN=$(DOCKER_COMPOSE) run --rm app
EXEC?=$(DOCKER_COMPOSE) exec app
COMPOSER=$(EXEC) composer
CONSOLE=php bin/console

.DEFAULT_GOAL := help
.PHONY: help start stop reset

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


##
## Project setup
##---------------------------------------------------------------------------

start: build up db build-assets perm                                                                   ## Install and start the project

stop:                                                                                              ## Remove docker containers
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) rm -v --force

reset: stop start

clear: perm                                                                                        ## Remove all the cache, the logs, the sessions and the built assets
	-$(EXEC) rm -rf var/cache/*
	-$(EXEC) rm -rf supervisord.log supervisord.pid npm-debug.log .tmp
	rm -rf var/log/*
	rm var/.php_cs.cache

clean: clear                                                                                       ## Clear and remove dependencies
	rm -rf vendor node_modules

cc:                                                                                                ## Clear the cache in dev env
	$(EXEC) $(CONSOLE) cache:clear --no-warmup
	$(EXEC) $(CONSOLE) cache:warmup

tty:                                                                                               ## Run app container in interactive mode
	$(RUN) /bin/bash

##
## Database
##---------------------------------------------------------------------------
wait-for-db:
	$(EXEC) php -r "set_time_limit(60);for(;;){if(@fsockopen('db',3306)){break;}echo \"Waiting for MySQL\n\";sleep(1);}"

db: vendor wait-for-db                                                                             ## Reset the database and load fixtures
	$(EXEC) $(CONSOLE) doctrine:migrations:migrate -n

db-diff: vendor wait-for-db                                                                            ## Generate a migration by comparing your current database to your mapping information
	$(EXEC) $(CONSOLE) doctrine:migration:diff

db-migrate: vendor wait-for-db                                                                         ## Migrate database schema to the latest available version
	$(EXEC) $(CONSOLE) doctrine:migration:migrate -n

db-rollback: vendor wait-for-db                                                                        ## Rollback the latest executed migration
	$(EXEC) $(CONSOLE) doctrine:migration:migrate prev -n

##
## Assets
##---------------------------------------------------------------------------
build-assets: node_modules                                                                             ## Compile Js Assets
	$(EXEC) yarn build

watch: node_modules                                                                                ## Watch the assets and build their development version on change
	$(EXEC) yarn watch

##
## Dependencies
##---------------------------------------------------------------------------
deps: vendor build-assets

# Internal rules
build:
	$(DOCKER_COMPOSE) pull --parallel --ignore-pull-failures
	$(DOCKER_COMPOSE) build --force-rm --pull

up:
	$(DOCKER_COMPOSE) up -d --remove-orphans

perm:
	$(EXEC) chmod -R 777 var
	$(EXEC) chown -R www-data:root var

# Rules from files
vendor: composer.lock
	$(COMPOSER) install -n

composer.lock: composer.json
	@echo composer.lock is not up to date.

node_modules: yarn.lock
	$(EXEC) yarn install

yarn.lock: package.json
	@echo yarn.lock is not up to date.
