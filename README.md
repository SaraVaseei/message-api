
## About Message API
This message API, is a simple implementation of authenticated message CRUD REST API.

## Installation
Follow according to following steps in order to run the application on your machine:   
- [Download composer](https://getcomposer.org/download)
- Rename .env.example file to .env inside your project root and fill the database information. (windows wont let you do it, so you have to open your console cd your project root directory and run mv .env.example .env )
- Open the console and cd project root directory
- php >= 8.0.0 is required
- Run composer install or php composer.phar install
- Run ./vendor/bin/sail up
- Open another terminal and run ./vendor/bin/sail artisan key:generate
- Run ./vendor/bin/sail artisan migrate
- Run ./vendor/bin/sail artisan l5-swagger:generate
- Open [Swagger documentation](http://localhost:8087/api/documentation) in a browser to check the application 
- Feel free to call all the endpoints via postman but don't forget to send your bearer token in the header 😁
