
# Set up the default (i.e. - first) make entry.
test:
	docker-compose run --rm php bash -c "./run-tests.sh"

bash:
	docker-compose run --rm php bash

behat:
	docker-compose run --rm php bash -c "vendor/bin/behat --stop-on-failure"

behatappend:
	docker-compose run --rm php bash -c "vendor/bin/behat --append-snippets"

clean:
	docker-compose kill
	docker-compose rm -f

composer:
	docker-compose run --rm php bash -c "composer install --no-scripts --no-plugins"

# Example: `make composerrequire NAME=monolog/monolog`
composerrequire:
	docker-compose run --rm php bash -c "composer require $(NAME) --no-scripts --no-plugins"

composerupdate:
	docker-compose run --rm php bash -c "composer update --no-scripts"

ps:
	docker-compose ps
