---
layout: default
title: REST API
nav_order: 1
permalink: /rest-api
---

# REST API

The REST API of this project is developed in the `app/API` namespace. All major versions have its own working directory where all the implementations can be found.

Routes are located in the `routes/api` directory.

## OpenAPI

All versions of the REST API must be documented in our OpenAPI description files. They are located in the `openapi` directory. Every change to the API should be reflected in the OpenAPI file. A pull request can be declined of it's not following this rule.
