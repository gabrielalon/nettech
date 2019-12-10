Please visit [demo](http://157.230.110.191/) to check this project.

Task
----

The goal of the task was to create crawler that fetches galleries from website: [watchthedeer](http://www.watchthedeer.com/photos).
I am not sure whether you have noticed but this website contains not only image galleries but also video galleries.
Due to lack of time I have decided to skip video galleries, because getting to them would require method that covers POST
requests and following redirects. I thought that you were interested more in my knowledge of Symfony framework or DDD / CQRS 
than in my ability to write spaghetti code..
If I am wrong please let me know and I will improve this solution.

Code
----
To prepare this task I have used Symfony and my own solution for CQRS + Event Sourcing.
For my previous project I have written package: [n3ttech/messeging](https://packagist.org/packages/n3ttech/messeging) for handling CQRS and ES.
For know its quite simple. I mean when some command is registered in the bus it is run instantly along with corresponding events.
In future I want to extend it for some async queries.
The code itself has following structure:

```
src
└───Application
└───Domain
└───Infrastructure
└───Presentation
```

- **Application** folder contains all definitions of commands and events.
- **Domain** folder contains all definitions of models.
- **Infrastructure** folder is responsible for data storage and access
- **Presentation** folder contains UI

Prerequisite
------------

To run code locally you need to have:
* doctrine
* doctrine-compose

Running project
---------------

For simplicity I have prepared `.makefile` with some predefined commands that should be useful in further work.
There is also file `shle.sh` that after execution will open php container in which you can run `bin/console` commands.
Executing ```make help``` will give you list of available commands:
```
start                          spin up environment
stop                           stop environment
rebuild                        clean current environment, recreate dependencies and spin up again
erase                          stop and delete containers, clean volumes.
build                          build environment and initialize composer and project dependencies
composer-install               Install project dependencies
composer-update                Update project dependencies
tests                          execute project unit tests
style                          executes php analizers
cs                             executes php cs fixer
cs-check                       executes php cs fixer in dry run mode
db                             recreate database
schema-validate                validate database schema
bash                           gets inside a container, use 's' variable to select a service. make s=php bash
logs                           look for 's' service logs, make s=php logs
help                           Display this help message
```

For first use please run `make build` and after that `make db`.
In order to run tests execute `make tests`.

Attention! For tests to pass please create user `admin` with the same password.
You can do that by entering php container and executing `bin\console app:create-user admin admin`.

Api
---

* [/api/login_check](/api/login_check)

```
curl --request POST \
  --url http://0.0.0.0/api/login_check \
  --header 'content-type: application/json' \
  --data '{"username":"admin","password":"admin"}'
```
Response:
```json
{
  "token": "TOKEN"
}
```

* [/api/gallery/list/watch-the-deer](/api/gallery/list/watch-the-deer)

```
curl --request GET \
  --url http://0.0.0.0/api/gallery/list/watch-the-deer \
  --header 'authorization: Bearer TOKEN'
```
Response:
```json
{
  "galleries": [
    {
      "uuid": "f4535bd6-0378-4626-a426-5cbd3fcfd1a4",
      "created_at": "2019-12-09 15:02:47",
      "source": "watch-the-deer",
      "name": "Fawn Release from Fence- Texas full video",
      "asset_counter": 0
    },
    ...
    {
      "uuid": "f0161387-b65e-418b-8818-ccd16d7fd723",
      "created_at": "2019-12-09 15:02:39",
      "source": "watch-the-deer",
      "name": "Thanksgiving Virginia Bears Nov 2015 (42 images)",
      "asset_counter": 42
    }
  ],
  "searcher": {
    "page": 1,
    "limit": 5
  }
}
```

* [/api/gallery/assets/{gallery_uuid}](/api/gallery/assets/{gallery_uuid})

```
curl --request GET \
  --url http://0.0.0.0/api/gallery/assets/4ac6629d-60d1-45cc-8ecd-584b1839d887 \
  --header 'authorization: Bearer TOKEN'
```
Response:
```json
{
  "assets": [
    {
      "id": "1674",
      "type": "image",
      "filename": "/looping_images/Abilene%20Sept%202015/droptine3_20150901_230222M.jpg"
    },
    ...
    {
      "id": "1678",
      "type": "image",
      "filename": "/looping_images/Abilene%20Sept%202015/droptine3_20150901_230622M.jpg"
    }
  ],
  "searcher": {
    "page": 1,
    "limit": 5
  }
}
```