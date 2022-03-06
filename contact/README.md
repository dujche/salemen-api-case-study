# Contact Microservice

Contact microservice exposes REST API to handle the contact entities.

## Available endpoints

1. GET contacts <br/><br/> This endpoint is externally available through URL rewrite for given seller ID and internally available to other microservices.

2. POST contacts <br/><br/> This endpoint is internally available only to other microservices (for instance cli-worker for importing data).