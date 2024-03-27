# Breastplate - Framework for Rapid PHP Development

[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)
![](https://img.shields.io/github/release/SamuelPietro/Breastplate)
![](https://img.shields.io/github/issues/SamuelPietro/Breastplate)
[![Maintainability](https://api.codeclimate.com/v1/badges/9c8c322765b71a7eb7e4/maintainability)](https://codeclimate.com/github/SamuelPietro/Breastplate/maintainability)

Breastplate is a rapid PHP development framework based on best practices, SOLID principles and the MVC pattern. Unlike conventional frameworks, Breastplate emphasizes simplicity and adherence to standards, allowing developers to accelerate development without relying on extensive configurations or unnecessary abstractions, and most importantly, without the developer losing their autonomy and freedom.
## Resources
Quick Start: Begin development promptly without the burden of initial configurations.

Best Practices Based: Adheres to industry best practices, including SOLID principles, ensuring code quality and maintainability.

MVC Pattern: Embraces the Model-View-Controller pattern for structured code organization, promoting modularity and ease of maintenance.

Freedom and Autonomy: Provides flexibility to modify the core, preventing projects from being indefinitely bound to the framework. Enjoy autonomy in shaping your project's architecture.

## Installation

To install Breastplate, clone the GitHub repository and follow these setup instructions:

```bash
git clone https://github.com/SamuelPietro/Breastplate.git
cd Breastplate
composer install
php -S localhost:8000 -t public/
```

Optionally, you can import the SQL tables located in `/resources/sql`. Make sure that the necessary tables exist for the application to function correctly.

## Environmental variables

Update the environment variables in `config/Config.php` based on your environment settings.

Access the application in your browser using [http://localhost:8000](http://localhost:8000).

## Third-party libraries

Breastplate uses the following third-party libraries via Composer:

- [filp/whoops](https://github.com/filp/whoops) -> For error handling and debugging
- [symfony/dotenv](https://github.com/symfony/dotenv) -> To manage environment variables
- [symfony/cache](https://github.com/symfony/cache) -> For template caching
- [league/plates](https://github.com/thephpleague/plates) -> For model rendering

These libraries are automatically installed during the Breastplate configuration process.

## Usage

With Breastplate, you can quickly develop PHP applications following the MVC pattern. Organize your views, controllers and models in a clean and maintainable way.

See the examples provided in the `app` directory for guidance on MVC development using Breastplate.

## Improvements

For a list of all changes, see the [changelog](https://github.com/SamuelPietro/Breastplate/commits/master).

## Documentation

Detailed Breastplate documentation is available in the `docs` directory. Covers usage of the framework, features, and contribution guidelines.

## Authors

- [Samuel Pietro](https://www.github.com/samuelpietro)

## Contributions

Contributions are encouraged! Report bugs, suggest features, or propose improvements by opening an issue on GitHub. Feel free to create new branches with additional features.

New collaborators are welcome. Explore open questions to get started.

## Opinion

Share your comments at samuel@pietro.dev.br. We value your opinion and strive to improve Breastplate based on community insights.