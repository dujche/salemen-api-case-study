# Seller Microservice

Seller microservice exposes REST API to handle the seller entities.

## Available endpoints

1. GET sellers <br/><br/> This endpoint is internally available only to other microservices.


2. GET sellers/{id} <br/><br/> This endpoint is both externally and internally available.


3. POST sellers <br/><br/> This endpoint is internally available only to other microservices (for instance cli-worker for importing data).