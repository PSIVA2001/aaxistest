# aaxis api test

PROJECT SETUP

Welcome to the AAXIS Test project!

## Requirements

- PHP Version: 8.1
- Symfony Version: 6.3

## Getting Started

Follow these steps to get the project up and running on your local machine.

### Clone the Repository

git clone https://github.com/PSIVA2001/aaxistest.git

after this step please configure .env and .env.test database username and password

## Install Dependencies
Use Composer to install the required dependencies.

composer install

## Run Migration
symfony bin/console make:migration

symfony bin/console doctrine:migrations:migrate

## Load Fixtures Data

php bin/console doctrine:fixtures:load

## Generate the SSL keys

php bin/console lexik:jwt:generate-keypair

## Start the Symfony Server
Launch the Symfony server to run the application.

symfony server:start

## Running PHPUnit Tests
PHPUnit is used for testing the application. Run the following command to execute the tests.

./vendor/bin/phpunit

## API Token Generation

To generate a token, use the following `curl` command:

```bash
curl -X POST -H "Content-Type: application/json" https://localhost:8000/api/login_check -d '{"username":"admin","password":"12345"}' -k
```

The generated token should be passed in the API header as follows:

Authorization: Bearer {token}

# Sample API Documentation

## Endpoints

### Create Record
- **URL**: `https://localhost:8000/api/records/create`
- **Method**: `POST`

### Update Records
- **URL**: `https://localhost:8000/api/records/update`
- **Method**: `PUT`

### List Records
- **URL**: `https://localhost:8000/api/records/list`
- **Method**: `GET`

## Example JSON Payload

```json
[
    {
        "sku": "SKU15432523",
        "product_name": "Test Product",
        "description": "This is a test product."
    },
    {
        "sku": "SKU154524",
        "product_name": "Test Product",
        "description": "This is a test product."
    }
]





