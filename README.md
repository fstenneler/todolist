# ToDo List
## Application edited by ToDo & Co

Online version [here](http://todolist.orlinstreet.rocks).

ToDo List is an application that manages tasks for a group of users.

## Certifications

### Symfony Insights
[![SymfonyInsight](https://insight.symfony.com/projects/a0f0055c-9247-4db5-8c55-11a5fd3badaa/big.svg)](https://insight.symfony.com/projects/a0f0055c-9247-4db5-8c55-11a5fd3badaa)

### Codacy
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/18cb4ae222854aad885a3e44d49ac8c9)](https://www.codacy.com/manual/fstenneler/bilemo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=fstenneler/bilemo&amp;utm_campaign=Badge_Grade)

Installing the app
------------------

### Download the repository

#### Either from the url
[Download repository using the web URL](https://github.com/fstenneler/todolist/archive/master.zip)

#### Or from Git
```bash
$ git clone https://github.com/fstenneler/bilemo.git
```    

### Download and install Composer
[Download Composer](https://getcomposer.org/download/)

### Update dependencies

#### In command line from the project directory
```bash
$ composer update
```

### Setup the .env file (at the root of the project) with your own parameters
    # .env

    DATABASE_URL=mysql://user:password@hostName:port/bilemo

#### Set your own app passphrase
    # .env

    JWT_PASSPHRASE=yourpassphrase

### Create database and load data
In command line from the project directory

#### Create database
```bash
$ php bin/console doctrine:database:create
```

#### Create tables and relations
```bash
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

#### Load initial data
```bash
$ php bin/console doctrine:fixtures:load
```

### Deploy the app

#### Change the APP_ENV and APP_DEBUG values in the .env file
    # .env
    
    APP_ENV=prod
    APP_DEBUG=0

#### Empty cache
```bash
$ php bin/console cache:clear
```

#### Upload all project files on your server

#### In case or errors, run the debug mode in the .env file
    # .env

    APP_DEBUG=1

### Starting with the app

#### Run tests
```bash
$ php bin/phpunit
```

#### Connect to the app
Log into app with this credentials :

    Username : admin
    Password : admin
    
Please edit this user after your first connection with a new password.

#### Edit user accounts
All created demo users have their password set to "user".
Erase these fake accounts and create your own accounts by using user management.

Contributing
------------

Before contributing to ToDo List, please take a look at the [CONTRIBUTING.md](CONTRIBUTING.md) document.