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

## Credentials

For the database server, use username ```root``` and password ```root``` on localhost port 3316.

Note there is intentionally no security on these user credentials. These containers are not designed to be used anywhere
but a local Docker setup.

For the web server, use http://localhost:8900 - this is configured to serve pages from ```/var/www/public``` inside the
container.
