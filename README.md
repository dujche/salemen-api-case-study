# Salesmen API case study

This repository contains demonstration of a [Salesmen API case study](https://github.com/AnwaltdeRepo/developer-test). 

Project contain 4 microservices - **Seller**, **Contact**, **Sale** and **Parser**. All 4 microservices expose REST API + Parser microservice contains **cli-worker** that polls the system for new data files every 5 seconds.

This cli-worker can be easily replaced with message broker, like RabbitMQ in one of the future versions, if needed.

All the implementations are based on [Laminas Mezzio](https://docs.mezzio.dev/).

## Requirements

*   Docker on the host machine

## Initial setup

1. Run docker-compose in the root folder:

    ```shell
    docker-compose build
    docker-compose up
    ```
   
2. (Optional) To run the unit test suites for each of the microservices, go with the following procedure:

   ```shell
    docker-compose -f docker-compose-dev.yml build
    docker-compose -f docker-compose-dev.yml up
    docker exec -it {micro-service-docker-container-name} bash
    vendor/bin/phpunit (on the container)
    ```

## Configuration

Application configuration can be modified in the [{micro-service}/config/autoload/local.php] files, as well in the [docker-compose.yml] file.

## Available endpoints

Please check the OpenAPI specification and static html documentation in the [docs](https://github.com/dujche/salesmen-api-case-study/blob/main/docs) folder.

## Uploading csv files from command line

There are a few example csv files in the [example](https://github.com/dujche/salesmen-api-case-study/tree/main/csv-parser/example) folder of the Parser microservice.

To upload such a file from the host machine using curl, execute following request

```shell
 curl -X POST \
 'http://localhost:8000/load' \
 -H 'content-type: application/x-www-form-urlencoded' \
 --data-binary '@/path/to/file/filename.csv'
 ```