# Save remote data
An app to save remote data locally using custom Symfony commands

## Installation

First clone this repository, and install the dependencies.
For demo purpose, `.env.example` already has the required database information that the underlying orm makes use of.
This app uses _users_db_ and _users_db_test_ databases for app functionality and testing respectively.

```bash
git clone https://github.com/junaidbinfarooq/save-remote-data.git
cd save-remote-data
composer install
cp .env.example .env
```

## Database and migrations
Now create the necessary database. The following was tested on MySQL.

### Database and migration setup for app

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Database and migration setup for test env

```bash
APP_ENV=test php bin/console doctrine:database:create
APP_ENV=test php bin/console doctrine:migrations:migrate
```

## Running the commands

### Run the following command to fetch and save remote users
```bash
php bin/console save-remote-users
```

### Run the following commands to fetch and save remote posts
```bash
php bin/console save-remote-posts
php bin/console save-remote-posts 5
```
#### Note: The last command fetches posts for a specific user with id=5.

### Run the following command to run the tests
```bash
php bin/phpunit
```
