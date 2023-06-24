# pFrame - Quick Start Framework for Free Development

[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)
![](https://img.shields.io/github/release/SamuelPietro/pframe)
![](https://img.shields.io/github/issues/SamuelPietro/pframe)
[![Maintainability](https://api.codeclimate.com/v1/badges/9c8c322765b71a7eb7e4/maintainability)](https://codeclimate.com/github/SamuelPietro/pframe/maintainability)

pFrame is a quickstart framework for PHP development based on best practices and the MVC pattern. With it, you can
create PHP applications using pure language code, without having to worry about initial configurations and other
unnecessary aspects.

## Installation

To install pFrame, simply clone the GitHub repository and follow the setup instructions.

     git clone https://github.com/SamuelPietro/pframe.git
     # In your terminal run
     composer install
     php -S localhost -t public/

## Environment variables

To run this project, you may need to update some environment variables in your .env based on your development
environment

Now just access the application in your browser using http://localhost:8000

## Third-party libraries

pFrame uses the following third-party libraries through composer:

- [filp/whoops](https://github.com/filp/whoops) -> For code debugging
- [symfony/dotenv](https://github.com/symfony/dotenv) -> For environment variables
- [symfony/cache](https://github.com/symfony/cache) -> For template caching
- [league/plates](https://github.com/thephpleague/plates) -> For template Engine

These libraries are necessary for the framework to work and will be installed automatically during the pFrame
installation process.

## Usage

Using pFrame, you can quickly and neatly create PHP applications following the MVC pattern. That means you can
separate your views, controllers, and models cleanly and maintainably.

We have within the app directory examples of how to develop with MVC using pFrame.

## Improvements

Check [changelog](https://github.com/SamuelPietro/pframe/commits/master) for a list of all changes.

## Documentation

The pFrame documentation is still being developed and is available in the doc's directory. In it, you will find detailed
information about how to use the framework, its functionalities and how to contribute to the project.

## Authors

- [Samuel Pietro](https://www.github.com/samuelpietro)

## Contributions


All contributions are welcome! If you find bugs, would like to suggest new features or have other ideas for improvement
Frame, just open an issue on GitHub.

We also appreciate new branches with fixes and new features.

## Feedback

If you have any feedback, please let us know at samuel@pietro.dev.br
