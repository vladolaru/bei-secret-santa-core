# bei_secret_santa_core

The Secret Santa Core exercise in our Backend Internship program.

## Rules

- each one will only work in their respective directory;
- you should push code changes as often as possible (at least twice a day);
- you already have a pseudo-testing code in `index.php` in your respective directory;

## Unit Tests

We use PHPUnit tests. See here for more details: https://phpunit.de/getting-started/phpunit-7.html

In the `tests` directory, run from the terminal `./vendor/bin/phpunit --testsuite angel` to run the tests for Angel.

## Coding Standards Checks

We use PHPCS for checking coding standards. See here for more details: https://github.com/squizlabs/PHP_CodeSniffer

There is no need to install anything as it is already installed in the `wpcs` directory. We will use the WordPress Coding Standards taken from here: https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards

To run the coding standards checks against the `.php` files in the `angel` directory run this in the terminal `./wpcs/vendor/bin/phpcs angel/*`.
