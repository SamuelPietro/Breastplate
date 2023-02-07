# pFrame - Quick Start Framework for Free Development
[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)
![](https://img.shields.io/github/tag/SamuelPietro/pframe)
![](https://img.shields.io/github/release/SamuelPietro/pframe)
![](https://img.shields.io/github/issues/SamuelPietro/pframe)


pFrame is a quickstart framework for PHP development based on best practices and the MVC pattern. With it, you can create PHP applications using pure language code, without having to worry about initial configurations and other unnecessary aspects.
## Installation
To install pFrame, simply clone the Github repository and follow the setup instructions.

     git clone https://github.com/SamuelPietro/pframe.git
     # In your terminal run
     composer install
     php -S localhost:8000 -t public/

## Environment variables

To run this project, you may need to update some environment variables in your .env based on your development environment

Now just access the application in your browser using http://localhost:8000

##
pFrame uses the following third-party libraries through composer:

- filp/whoops (For code debugging)
- symfony/dotenv
- symfony/cache

These libraries are necessary for the framework to work and will be installed automatically during the pFrame installation process.

## Usage
By using pFrame, you can quickly and neatly create PHP applications following the MVC pattern. This means you can separate your views, controllers and models in a clean and maintainable way.


## Improvements

We refactored the entire core of the application so that good practices and clean code are faithfully followed.

## Documentation
The pFrame documentation is still being developed and is available in the doc's directory. In it, you will find detailed information about how to use the framework, its functionalities and how to contribute to the project.

## Authors

- [@samuelpietro](https://www.github.com/samuelpietro)

## Contributions
All contributions are welcome! If you find bugs, would like to suggest new features, or have any other ideas to improve pFrame, just open an issue on GitHub.


## feedback

If you have any feedback, please let us know at samuel@pietro.dev.br
