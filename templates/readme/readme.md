Task
----

The goal of the task was to create crawler that fetches galleries from website: http://www.watchthedeer.com/photos.
I am not sure whether you have noticed but this website contains not only image galleries but also video galleries.
Due to lack of time I have decided to skip video galleries, because getting to them would require method that covers POST
requests and following redirects. I thought that you were interested more in my knowledge of Symfony framework or DDD / CQRS 
than in my ability to write spaghetti code..
If I am wrong please let me know and I will improve this solution.

Code
----
To prepare this task I have used Symfony 5.1 and my own solution for CQRS + Event Sourcing.
For my previous project I have written package: https://packagist.org/packages/n3ttech/messeging for handling CQRS and ES.
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

To run code locally you need to install:
* doctrine
* doctrine-compose

Running project
---------------

After that in main directory of project run: ```make build```. 
I have provided `.makefile` with some predefined commands that should be usefull in further work.
Command ```make db``` will create database and will run all migrations.
Command ```make tests``` will run all tests.

There is also file `shle.sh` that after execution will open php container in which you can run `bin/console` commands.