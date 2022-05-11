<p align="center"><img src="/art/project-logo.svg" alt="Logo Core Application" height="120"></p>

<p align="center">
<a href="https://github.com/KA-Huis/space-management/actions"><img src="https://github.com/KA-Huis/space-management/actions/workflows/run-tests.yml/badge.svg?branch=acceptance" alt="Integration Tests Status"></a>
</p>

<p align="center">Core web application of youth building KA-Huis in Posterholt, The Netherlands.</p>

## Introduction

This repository contains the core web application of the KA-Huis. A youth building located in Posterholt, The Netherlands fully run by volunteers. This application serves as an internal tool to improve processes and realtime availability of information. It's build in PHP with the popular Laravel framework. This application is responsible for multiple services. It contains at this moment the web application interface and a generic purpose REST API (used for example by the mobile application [Repair Tool](https://github.com/KA-Huis/repair-tool)). Both reuse the same core and domain logic. 

### Technical overview

<img src="/art/system-infrastructure-overview.png" alt="System Infrastructure Overview" height="280">

## Local Installation

### Docker

This project contains a preconfigured Docker setup to easily get this project locally running. Although it's heavily advised to use this Docker setup, so it matches production as much as possible it's not required. We do however not accept code changes to support other setups. 

#### Services

* `php`: Core application
* `nginx`: NGINX server
* `mariadb`: MySQL database
  * Database: `sm`
  * Root password: `root`
  * Username: `sm`
  * User password: `sm`
  * Exposed port: `34030`
* `redis`: Cache data store
* `mailhog`: Local e-mail testing tool to catch sent e-mails
  * URL: `https://mailhog.space-management.localhost`

### Traefik - reverse proxy

In order to easily redirect local requests to the localhost domain of this project, we use Traefik a reverse proxy. Make sure it's running. Follow the installation steps at [KA-Huis/traefik-gateway](https://github.com/KA-Huis/traefik-gateway).

### Setup script

All the installation steps are added to the `./setup.sh` script to automate the process. This makes sure every necessary step is executed in the right order. Shell scripts can be run on macOS and Linux. In case you're using windows, you could use WSL (Windows Subsystem for Linux).

Run the `./setup.sh` script every time you want to set up the project.

### Running commands in the environment

In order to run commands, you will have to SSH into the `php` service container:

```shell
docker-compose exec php sh
```

After that you can for example run Laravel artisan commands. For example `php artisan list`.

### Seeded data

### User accounts

A small list of user accounts are already automatically created by the database seeder which you can use to easily get access to the admin portal.

| First Name | Last Name | E-mailaddress | Password | Role |
|------------|-----------|---------------|----------|------|
| Cyril | de Wit | 453717@student.fontys.nl| Welkom0! | To be created |
| Abas | Sharif | 472244@student.fontys.nl| Welkom0! | To be created |
| Joey | Vonck | 454988@student.fontys.nl| Welkom0! | To be created |
| Mitch | Kessels | 453258@student.fontys.nl| Welkom0! | To be created |
| Enno | Overbeeken | enno@kahuis.nl| Welkom0! | To be created |

## Branching model

* **Branch `production`:** This branch will be used to build an artefact that can be deployed to the production environment.
* **Branch `acceptance`:** This branch will be used to build an artefact that can be deployed to the acceptance environment.

The acceptance branch should always be production ready. That means it should not be used to test code on the environment. When the product owner approves a ticket after testing it on the acceptance environment, we will be able to create a release branch and merge it to production.

Branch naming convention when working on tickets:

* **feature/<short-feature-description>:** When the ticket is about introducing a new feature. Include a GitHub issue reference when possible.
* **bugfix/<short-bugfix-description>:** When the code change fixes a bug.
* **hotfix/<short-hotfix-description>:** When something is broken on production and should immediately be fixed. This can go straight to the `production` branch.

All work branches branch off from the `producion` branch to make branches independent of each other, meaning they will not block each other. Working branches can also branch of from other ticket branches, when they dependent on each other.

## Credits

* **Cyril de Wit** - _Creator_ - [cyrildewit](https://github.com/cyrildewit)
* **Jasper Stolwijk** - _Creator_ - [Hoopless](https://github.com/Hoopless)
* **Mitch Kessels** - _Creator_ - [MiKessels](https://github.com/MiKessels)
* **Joey Vonck** - _Creator_ - [joeyvonck](https://github.com/joeyvonck)

See also the list of [contributors](https://github.com/KA-Huis/space-management/graphs/contributors) who participated in this project.

## Changelog

Please see [CHANGELOG](CHANGELOG-2.0.md) for more information on what has changed recently.

## License

Copyright (c) KA-Huis. All rights reserved.

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
