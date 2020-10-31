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

# REST API application
When make the request into API, please make sure the header for `Accept` and `Content-Type` should in `application/vnd.api+json`, or your request will be thrown to Bad Request.

## Register user as owner
**Request:**
```json
POST /api/auth/register/owner HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

body: {
    "name": "john",
    "email": "johndoe@example.com",
    "password": "readyrockandroll!",
    "password_confirmation": "readyrockandroll!"
}
```
**Successful Response:**
```json
HTTP/1.1 201 Created

{
   "data": {
     "token": "1|9lUw4F6EGfeKhMG7jlMvoXr6cTkNdhIxnU3mn0AX"
   }
}
```

## Register user as user general
**Request:**
```json
POST /api/auth/register/tenant HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

body: {
    "name": "john",
    "email": "johndoe@example.com",
    "password": "readyrockandroll!",
    "password_confirmation": "readyrockandroll!",
    "type": "user-general"
}
```
**Successful Response:**
```json
HTTP/1.1 201 Created

{
   "data": {
     "token": "2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM"
   }
}
```

## Register user as user premium
**Request:**
```json
POST /api/auth/register/tenant HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

body: {
    "name": "john",
    "email": "johndoe@example.com",
    "password": "readyrockandroll!",
    "password_confirmation": "readyrockandroll!",
    "type": "user-premium"
}
```
**Successful Response:**
```json
HTTP/1.1 201 Created

{
   "data": {
     "token": "2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM"
   }
}
```

## Get the token user as owner
**Request:**
```json
POST /api/auth/token/owner HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

data: {
    "email": "johndoe@example.com",
    "password": "readyrockandroll!",
}
```
**Successful Response:**
```json
HTTP/1.1 201 Created

{
   "data": {
     "token": "2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM"
   }
}
```

## Get the token user as user general or premium
**Request:**
```json
POST /api/auth/token/tenant HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

data: {
    "email": "johndoe@example.com",
    "password": "readyrockandroll!",
}
```
**Successful Response:**
```json
HTTP/1.1 201 Created

{
   "data": {
     "token": "2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM"
   }
}
```

## Search the rooms

**Parameters:**
| Name             | Value            | Default       | Format        |
| ---------------- | ---------------  | ------------- | ------------- |
| sort             | [asc, desc]      | asc           |               |
| filter[name]     |                  |               |               |
| filter[location] |                  |               |               |
| filter[price]    |                  |               | [min]~[max]   |

**Request:**
```json
GET /api/explore HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Authorization: Bearer 2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM

params: {
    "sort": "asc",
    "filter[price]": "0~1000000"
}
```
**Successful Response:**
```json
HTTP/1.1 200 OK

{
   "data": {
     "token": "2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM"
   }
}
```

## Add room for owner
**Request:**
```json
POST /api/rooms HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Authorization: Bearer 2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM

data: {
  "title": "This is the second room",
  "total": 1,
  "description": "Lorem ipsum dolor sit amet",
  "price": 1500000,
  "location": 2
}
```
**Successful Response:**
```json
HTTP/1.1 201 Created

{
  "data": {
    "id": 5,
    "type": "Room",
    "attributes": {
      "description": "Lorem ipsum dolor sit amet",
      "lat": null,
      "lng": null,
      "price": 1500000,
      "title": "This is the second room",
      "createdAt": "2020-10-31T11:05:56.000000Z",
      "updatedAt": "2020-10-31T11:05:56.000000Z"
    },
    "links": {
      "self": "http://localhost/api/rooms/5"
    },
    "relationships": {
      "location": {
        "id": 2,
        "type": "Location",
        "attributes": {
          "description": null,
          "title": "Kota Yogyakarta"
        }
      }
    }
  }
}
```

## Get rooms for owner
**Request:**
```json
GET /api/rooms HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Authorization: Bearer 2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM
```
**Successful Response:**
```json
HTTP/1.1 200 OK

{
  "data": [{
    "id": 5,
    "type": "Room",
    "attributes": {
      "description": "Lorem ipsum dolor sit amet",
      "lat": null,
      "lng": null,
      "price": 1500000,
      "title": "This is the second room",
      "createdAt": "2020-10-31T11:05:56.000000Z",
      "updatedAt": "2020-10-31T11:05:56.000000Z"
    },
    "links": {
      "self": "http://localhost/api/rooms/5"
    },
    "relationships": {
      "location": {
        "id": 2,
        "type": "Location",
        "attributes": {
          "description": null,
          "title": "Kota Yogyakarta"
        }
      }
    }
  }, {
    ...
  }]
}
```

## Get detail room
**Request:**
```json
GET /api/rooms/5 HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Authorization: Bearer 2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM
```
**Successful Response:**
```json
HTTP/1.1 200 OK

{
  "data": {
    "id": 5,
    "type": "Room",
    "attributes": {
      "description": "Lorem ipsum dolor sit amet",
      "lat": null,
      "lng": null,
      "price": 1500000,
      "title": "This is the second room",
      "createdAt": "2020-10-31T11:05:56.000000Z",
      "updatedAt": "2020-10-31T11:05:56.000000Z"
    },
    "links": {
      "self": "http://localhost/api/rooms/5"
    },
    "relationships": {
      "location": {
        "id": 2,
        "type": "Location",
        "attributes": {
          "description": null,
          "title": "Kota Yogyakarta"
        }
      }
    }
  }
}
```

## Delete the room
**Request:**
```json
DELETE /api/rooms/5 HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Authorization: Bearer 2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM
```
**Successful Response:**
```json
HTTP/1.1 200 OK

{
  "data": {
    "delete": true
  }
}
```

## Update the room
**Request:**
```json
PUT /api/rooms/5 HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Authorization: Bearer 2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM

data: {
  "title": "This is the fifth room",
  "total": 5,
}
```
**Successful Response:**
```json
HTTP/1.1 200 OK

{
  "data": {
    "id": 5,
    "type": "Room",
    "attributes": {
      "description": "Lorem ipsum dolor sit amet",
      "lat": null,
      "lng": null,
      "price": 1500000,
      "title": "This is the fifth room",
      "createdAt": "2020-10-31T11:05:56.000000Z",
      "updatedAt": "2020-10-31T12:05:56.000000Z"
    },
    "links": {
      "self": "http://localhost/api/rooms/5"
    },
    "relationships": {
      "location": {
        "id": 2,
        "type": "Location",
        "attributes": {
          "description": null,
          "title": "Kota Yogyakarta"
        }
      }
    }
  }
}
```

## Check room availability
**Request:**
```json
GET /api/rooms/5/availability HTTP/1.1
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Authorization: Bearer 2|XK8DENUdD3fRqsA00Z3Ol9AQ6YPeR5XoKgU86RLM
```
**Successful Response:**
```json
HTTP/1.1 200 OK

{
  "data": {
    "availability": true
  }
}
```