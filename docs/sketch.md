### Project structure

+ "app": It is the main folder for the application.
    + "Controllers": Stores all controllers, which are responsible for handling user requests and managing interaction with the model.
    + "Models": Stores all models, which are responsible for managing data and performing tasks related to the database.
    + "Views": Stores all views, which are responsible for displaying data to the user.
        + "templates": Stores the application's layout files.
+ "docs": Stores the project documentation. This includes manuals, user guides, architectural diagrams, release notes, and other types of project-related documentation.
+ "public": It is the public folder, accessible to the end user. Stores CSS files, images and JavaScript that are accessible via browser.
    + "index.php": It is the main entry point for the application.
+ "src": Contains the source code of the application.
    + "Core": Contains the core of the application, which is responsible for managing the business logic and other important components.
        + "bootstrap.php": Contains code that initializes and configures the application.
        + "Autoloader.php": Makes the class autoloader.
        + "helpers.php": Contain useful helper functions that can be used throughout the application, such as functions for parsing strings, formatting dates, etc.
        + "Router.php": Class that implements routing functionality, such as adding routes, matching routes to specific URLs, and redirecting the user to the appropriate controller.
        + "routes.php": This file is responsible for defining the application's routes. It contains calls to the methods of the Router class that add routes to the routing system.
    + "Database": Contains database-related code, including table creation scripts and SQL queries.
        + "Connection.php" : Connects to the database using information from the .env file.
        + "Dao.php": It is responsible for providing an abstraction layer to access the data stored in the database.
+ "tests": Stores automated tests for the application.
+ "vendor": Stores external dependencies installed with Composer.
+ "composer.json": Contains information about application dependencies and other Composer settings.
+ "composer.lock": Stores information about exact versions of installed dependencies.
+ ".env": Store sensitive and personal data for project execution



### Composer.json
+ "name": Specifies the package name, in the format "user/package+name".
+ "description": Provides a brief description of the project.
+ "type": Specifies the type of project, in this case "project".
+ "license": Specifies the project's license, in this case "GPL +3.0 +or +later".
+ "autoload": Specifies the Composer autoload settings for the project, using the default PSR +4.
+ "authors": Lists the authors of the project, including name and e-mail.
+ "minimum +stability": Specifies the minimum acceptable stability for project dependencies. In this case it is "dev".
+ "require": Lists the project's dependencies, including the minimum PHP version and the PDO and JSON extensions.
+ "require-dev": Lists project dependencies during development.


### Pattern of new commits
In order to standardize and scale code versioning, now all commits will be preceded by prefixes, which are

+ feat: A new feature that was added to the application
+ fix: The resolution of a bug
+ style: Feature and update related to styling 
+ refactor: Refactoring a specific section of the source code
+ test: Everything related to testing
+ docs: Everything related to documentation
+ chore: Regular code maintenance

