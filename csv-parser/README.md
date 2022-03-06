# (CSV) Parser Microservice

(CSV) Parser microservice exposes REST API to load (import) the csv files with data. It also contains the import cli-worker, which asynchronously parses the uploaded files, and post the data to appropriate microservice's API.

## Available endpoints

1. POST load <br/><br/> This endpoint is externally available to load the csv files with data.

## Uploading csv files from command line

There are a few example csv files in the [example](https://github.com/dujche/salesmen-api-case-study/tree/main/csv-parser/example) folder of this microservice.

To upload such a file from the host machine using curl, execute following request

```shell
 curl -X POST \
 'http://localhost:8000/load' \
 -H 'content-type: application/x-www-form-urlencoded' \
 --data-binary '@/path/to/file/filename.csv'
 ```