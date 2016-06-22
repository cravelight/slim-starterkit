# Automated Tests

To run automated test we are using the following frameworks:

- [PHPUnit](https://phpunit.de/) (testing framework)
- [PHP dotenv](https://github.com/vlucas/phpdotenv) (setting environment variables)

**Note:**
Tests inside the `/tests/web` directory are designed to be run against a web server.
In testing environments this may be [PHP's built in web server](http://php.net/manual/en/features.commandline.webserver.php).
In your development environment it will most likely be against localhost.
Typically this url is configured in your `.env` file.


## PHPStorm Configuration

To configure PHPStorm:

- Open **Preferences** and navigate to **Languages and Frameworks** &rarr; **PHP** &rarr; **PHPUnit**
- Under **PHPUnit library**, select **Use custom autoloader**
- Enter **Path to script** as `[path/to/project/vendor/autoload.php]`
- In the **Test Runner** section, select **Default bootstrap file** and enter the `[path/to/project/tests/bootstrap.php]`
- **Apply** your changes and exit **Preferences**


## Command Line

When running from the command line use the following:

` php [path/to/phpunit] --bootstrap [path/to/tests/bootstrap.php] --no-configuration [path/to/tests]`

For additional information see: [PHPUnit command line options](https://phpunit.de/manual/current/en/textui.html)


