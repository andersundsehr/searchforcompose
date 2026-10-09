# Search for compose

## General informations

This image is used in the local development environment. It provides a service that is used as standard in the Nginx
proxy.

If you call a URL where no service is stored, this service is used.

It searches through all available containers and finds the matching container that contains the domain.
The container can be started at the click of a button.

## How to use it

If your project was only stopped and not fully shut down, you can easily restart it by simply entering the project's URL
into your browser. This service will pop up, and with just one click on the start button, all containers
associated with the project will be launched. This eliminates the need to manually start the containers through the
console, saving you time and effort.

## PHP runtime

Production and development containers use PHP 8.5 by default. To build the
previous PHP 8.3 runtime locally, use `PHP_VERSION=8.3 docker compose build`.
For the production Dockerfile, use `docker build --build-arg PHP_VERSION=8.3 .`.
Both PHP 8.3 and PHP 8.5 syntax checks run in CI, alongside the container build.

### Where you can find it too

* github.com/andersundsehr
* hub.docker.com

Made by me, Itay ≽^•⩊•^≼