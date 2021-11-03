# Space Management

Spaces management tool built for KA-Huis.

## Overview

The application is built with the Laravel framework.

## Setup

### Requirements

This application requires **PHP 8.0+**.

### Installation

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

* **Mitch Kessels** - _Creator_ - [MiKessels](https://github.com/MiKessels)
* **Abas Sharif** - _Creator_ - [abassharif](https://github.com/abassharif)
* **Joey Vonck** - _Creator_ - [joeyvonck](https://github.com/joeyvonck)
* **Cyril de Wit** - _Creator_ - [cyrildewit](https://github.com/cyrildewit)

See also the list of [contributors](https://github.com/cyrildewit/eloquent-viewable/graphs/contributors) who participated in this project.

## Changelog

Please see [CHANGELOG](CHANGELOG-2.0.md) for more information on what has changed recently.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
