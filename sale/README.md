# Sale Microservice

Sale microservice exposes REST API to handle the sale entities.

## Available endpoints

1. GET sales <br/><br/> This endpoint is externally available through URL rewrite for given seller ID and internally available to other microservices.


2. GET sales/{year} <br/><br/> This endpoint is both externally and internally available.


3. POST sales <br/><br/> This endpoint is internally available only to other microservices (for instance cli-worker for importing data).