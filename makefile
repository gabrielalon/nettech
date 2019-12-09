.PHONY: start
start: ## spin up environment
		docker-compose up -d --remove-orphans

.PHONY: stop
stop: ## stop environment
		docker-compose stop

.PHONY: rebuild
rebuild: erase build start db ## clean current environment, recreate dependencies and spin up again

.PHONY: erase
erase: ## stop and delete containers, clean volumes.
		docker-compose stop
		docker-compose rm -v -f

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		docker-compose build
		docker-compose up -d --remove-orphans
		docker exec -it nettech-php bash -lc 'COMPOSER_MEMORY_LIMIT=-1 composer install'

.PHONY: composer-update
composer-update: ## Update project dependencies
		docker exec -it nettech-php bash -lc 'COMPOSER_MEMORY_LIMIT=-1 composer update'

.PHONY: tests
tests: ## execute project unit tests
		docker-compose exec php bash -lc "./bin/phpunit $(conf)"

.PHONY: style
style: ## executes php analizers
		docker-compose run --rm php bash -lc './vendor/bin/phpstan analyse -l 6 -c phpstan.neon src tests'

.PHONY: cs
cs: ## executes php cs fixer
		docker-compose run --rm php bash -lc './vendor/bin/php-cs-fixer --no-interaction --diff -v fix'

.PHONY: cs-check
cs-check: ## executes php cs fixer in dry run mode
		docker-compose run --rm php bash -lc './vendor/bin/php-cs-fixer --no-interaction --dry-run --diff -v fix'

.PHONY: db
db: ## recreate database
		docker-compose exec php bash -lc './bin/console doctrine:database:drop --force'
		docker-compose exec php bash -lc './bin/console doctrine:database:create'
		docker-compose exec php bash -lc './bin/console doctrine:schema:update --force'
		docker-compose exec php bash -lc './bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration'

.PHONY: schema-validate
schema-validate: ## validate database schema
		docker-compose exec php bash -lc './bin/console doctrine:schema:validate'

.PHONY: bash
bash: ## gets inside a container, use 's' variable to select a service. make s=php bash
		docker-compose exec $(s) bash -l

.PHONY: logs
logs: ## look for 's' service logs, make s=php logs
		docker-compose logs -f $(s)

.PHONY: help
help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
