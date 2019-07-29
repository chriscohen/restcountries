# RestCountries API integration

## Prerequisites

* Docker
* docker-compose

## Starting the containers

Change to the root directory (where this file is located) and run:

```
docker-compose up -d
```

The first time this is run, the containers will be built from scratch, which might take a few minutes.

This process will:

* Build a web container and a database container.
* Run MySQL on the database container, using local (non-standard) port 3316.
* Install the database and set up its tables.
* Run Apache2 in the database container, using http://localhost:8900.
* Install composer packages in the container.
* Map the container's ```/var/www``` to your local machine at the root of the project (where this file is located).
* Set up a Docker network on ```172.27.1.*``` and assign static Docker IP addresses to both containers.

## Viewing the site

Please visit http://localhost:8900 in your browser.

If, although unlikely, your local port 8900 is already in use when attempting to start the containers, they will fail to
start.

In this situation, please alter the port in **docker-compose.yml** to a port of your choosing.

## Credentials

You will only need these credentials if you are interested to see how the code works!

For the database server, use username ```root``` and password ```root``` on localhost port 3316.

Note there is intentionally no security on these user credentials. These containers are not designed to be used anywhere
but a local Docker setup.

For the web server, use http://localhost:8900 - this is configured to serve pages from ```/var/www/public``` inside the
container.

## Chosen technology

The site runs:

* Apache 2.4
* PHP 7.3
* MySQL 5.7

[Bootstrap](https://getbootstrap.com/) has been used to provide a very quick and easy way to display an HTML page in a
non-default look.

[Typeahead](https://twitter.github.io/typeahead.js/) is used to provide the jQuery functionality necessary to power a
text-based autocomplete search.

The [PHP Rest Countries](https://packagist.org/packages/namnv609/php-rest-countries) package from Packagist is used to
easily integrate with the API.

## Methodology

The code complies with PSR-2 standards, and is using PHP **strict_types**.

Code utilises the PSR-4 autoloader provided by Composer in order to properly load the required classes.

Where functionality is not obviously described by the code, documentation is provided in the code itself.

## Hints for comprehensive testing

If you access **prefetch.php** in your browser, you can see the entire JSON for all known countries from the database.

By accessing **remote.php?query=WORD**, and replacing WORD with a query of your choice, you can simulate a query to the
API and see what results are returned in JSON format.

## Known limitations

As with everything Docker-based, it runs quite slowly, and takes some time between the page loading and the autocomplete
functionality becoming fully available, especially where results are required from the API.

The API does not provide a way to specify multiple search criteria in one request. Since the requirements state that a
country should be searched on a number of criteria, this involves many API calls.

Typeahead does not always function well with small numbers of characters. It appears to function better with 4 or more
characters. The requirements state that it should be possible to search by 2-letter country code, but there are so many
possible results that this requirement has been left out, because it is simply confusing for the user.

## Suggested improvements

The entire list of countries can be retrieved and stored in the database before anyone uses it! Although a "cache
locally only when requested" approach is useful for large datasets, there are only a few hundred countries, and
retrieving them all at once has huge benefits:

* No need to write "if not in database then..." code
* Much faster response by relying on a local database than an API
* Overall reduction in complexity

The requirements specify that only one calling code is considered, but countries may have multiple calling codes. Most
notably, this results in the wrong single calling code being displayed for United States of America, where most people
associate it with being "+1".

The autocomplete suggestions only show the name of the country, but this might lead to user confusion if the user has
typed "London" because "Great Britain" is suggested. It would be better to fully customise the entries in the
suggestions list, but I feel this is beyond the scope of the requirements.
