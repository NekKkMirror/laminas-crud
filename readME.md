# Task Service API

A modern PHP-based RESTful API built with the Laminas Framework, Doctrine ORM, and PostgreSQL. This project provides a robust task management service with OpenAPI (Swagger) documentation, automated migrations, and comprehensive testing.

## Table of Contents

- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [Available Scripts](#available-scripts)
- [API Documentation](#api-documentation)
- [Directory Structure](#directory-structure)

## Features

- RESTful API for task management
- Laminas MVC framework for modular architecture
- Doctrine ORM for database interactions
- PostgreSQL as the database
- OpenAPI/Swagger documentation
- Automated database migrations with Doctrine Migrations
- Unit and integration testing with PHPUnit
- Code style enforcement with PHP_CodeSniffer (PSR-12)
- Static analysis with PHPStan
- Dockerized development and testing environments

## Prerequisites

- **Docker** and **Docker Compose** for containerized environments
- **PHP 8.1+** (if running natively)
- **Composer** for dependency management
- **PostgreSQL** (managed via Docker in this setup)
- A `.env` file with required environment variables

## Installation

1. **Clone the repository**:
   ```bash
   git clone <github.com/NekKkMirror/laminas-crud.git>
   cd <laminas-crud-[env]>
   ```

2. **Create a .env file**:
   ```bash
    touch .env
   ```

3. **Copy the environment file**:
   ```bash
    cp .env.[testing | development] .env
   ```

4. **Install dependencies**:
   ```bash
   composer install
   ```

5. **Set up Docker (for development or testing):**:
   ```bash
   docker-compose -f docker-compose.[testing | development].yml up -d
   ```

6. **Watch logs**:
   ```bash
   docker compose -f docker-compose.[testing | development].yml logs --tail 500 -f
   ```

7. **Stop compose**:
   ```bash
   docker compose -f docker-compose.[testing | development].yml down
   ```

## Running the Application

### Development Environment

Start the development environment with Docker Compose:
   ```bash
      docker-compose -f docker-compose.development.yml up -d
   ```
The application will be available at http://localhost:${APP_PORT} (default: http://localhost:8080).

### API Documentation

Generate Swagger documentation:
   ```bash
      composer docs:gen
   ```
Access the Swagger JSON at http://localhost:${APP_PORT}/swagger (default: http://localhost:8080/swagger).


## Available Scripts

#### The following Composer scripts are available (defined in composer.json):

1. **Start the development server**:
   ```bash
   composer serve 
   ```

2. **Generate a new migration**:
   ```bash
    composer migrations:generate
   ```

3. **Check code style (PSR-12)**:
   ```bash
   composer cs-check
   ```

4. **Fix code style issues**:
   ```bash
   composer cs-fix
   ```

5. **Run static analysis**:
   ```bash
   composer static-analysis
   ```

6. **Generate Swagger documentation**:
   ```bash
   composer docs:gen
   ```

## Directory Structure

#### Below is the recommended directory structure for the project:

```text
├── bootstrap/                  # Bootstrap scripts (e.g., testing.php)
├── config/                     # Configuration files
│   ├── autoload/               # Laminas autoload configurations
│   │   └── swagger.global.php  # Swagger configuration
│   └── module.config.php       # Module-specific configuration
├── data/                       # Runtime data (writable by the application)
├── local/                      # Local scripts and initialization files
│   └── init/                   # Database initialization scripts
│       ├── local-init.sql.gz   # Development DB initialization
│       └── testing-init.sql.gz # Testing DB initialization
├── module/                     # Laminas modules
│   ├── Api/                    # API module
│   │   ├── src/                # Source code
│   │   │   ├── Controller/     # API controllers
│   │   │   ├── Dto/            # Data Transfer Objects
│   │   │   └── ...             # Other API-related classes
│   │   └── test/               # API tests
│   └── Db/                     # Database module
│       ├── config/             # Database configurations
│       │   └── doctrine_migrations.php
│       └── src/                # Database-related classes
├── public/                     # Publicly accessible files
│   ├── swagger/                # Swagger documentation (css, js, dist, json) files
│   └── index.php               # Application entry point
├── vendor/                     # Composer dependencies
├── .env                        # Environment variables
├── .env.example                # Example environment file
├── composer.json               # Composer configuration
├── Dockerfile                  # Docker configuration
├── docker-compose.development.yml # Development Docker Compose
├── docker-compose.testing.yml  # Testing Docker Compose
├── migrations-db.php           # Doctrine migrations database config
├── phpunit.xml                 # PHPUnit configuration
├── swagger.php                 # Swagger documentation generator
└── README.md                   # Project documentation
```


