## Getting started

### Launch the starter project

*(Assuming you've [installed Laravel](https://laravel.com/docs/7.x/installation))*

Fork this repository, then clone your fork, and run this in your newly created directory:

```
composer install
```

Next you need to make a copy of the `.env.example` file and rename it to `.env` inside your project root.

For production ready, please change the value for:
```
APP_DEBUG=false
API_DEBUG=false
```

Please setup the database connection as needed:
```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Run the following command to generate your app key:
```
$ php artisan key:generate
```

Run the following command to migrate and seed the tables:
```
$ php artisan migrate --seed
```

Then start your server:
```
$ php artisan serve
```

Your project is now up and running!

### Configure the starter project

This project contains task scheduler and Queues, so please follow this guide to setup the those configurations:

#### Starting Scheduler
When using the scheduler, you only need to add the following Cron entry to your server.
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
This Cron will call the Laravel command scheduler every minute.

*For the full documentation about Starting Scheduler, please refers to [Laravel Task Scheduling](https://laravel.com/docs/7.x/scheduling#introduction)*

#### Starting Queues
Queues allow you to defer the processing of a time consuming task, such as sending an email, until a later time. Deferring these time consuming tasks drastically speeds up web requests to your application.

Please learn more about Queues, please review the [full Laravel queue documentation](https://laravel.com/docs/7.x/queues)

Run the following command to run the worker with specific connection to database:
```
$ php artisan queue:work database --tries=3 --timeout=300
```

To keep the `queue:work` process running permanently in the background, you should use a process monitor such as [Supervisor](https://laravel.com/docs/7.x/queues#supervisor-configuration) to ensure that the queue worker does not stop running.

You may have to restart your server.

## Deploying on Docker

An easy way to deploy the application is to use [Docker](https://www.docker.com/). This project comes with the configuration to Docker. Just follow these few simple steps to deploy it:

Run the following command into the root directory:
```
$ docker-compose up --build -d
```

Run the following command to enter the container:
```
docker exec -it mamikos.lv.engine /bin/sh
```

After the please follow the instructions at [Launch the starter project](#launch-the-starter-project)

You don't have to worry about [Configuring the starter project](#configuring-the-starter-project), because the Docker configuration already have those configurations.