#!/usr/bin/env bash

# Try to install composer dev dependencies
composer install --no-interaction --no-scripts --no-plugins

# If that failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi

# Run the feature tests
vendor/bin/behat --stop-on-failure --strict

# If they failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi
