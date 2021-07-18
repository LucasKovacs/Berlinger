# Berlinger
 
## Start the project
- Run on your terminal `docker-compose up` where the file docker-compose.yml is located
- Run `docker exec -it laravel bash` to access the container
- Run `composer install` on `/var/www/html`
- Once all the services are up, app, mysql and phpmyadmin on your browser run `http://localhost/public/`
- Open your /etc/hosts file and add the following line `127.0.0.1 mysql`, this is optional depending on your setup, on my computer is needed.

## Avaiable endpoints
- (POST) `/api/pictures/upload`
  - Payload: `file`
- (GET) `/api/pictures`
- (GET) `/api/pictures/{picture_id}`

## AWS Environment for testing and examples

### Upload csv
http://berlinger-env.eba-pcdzqm3a.us-west-2.elasticbeanstalk.com/api/pictures/upload

### Get all pictures with their exif
http://berlinger-env.eba-pcdzqm3a.us-west-2.elasticbeanstalk.com/api/pictures

### Get a single picture
http://berlinger-env.eba-pcdzqm3a.us-west-2.elasticbeanstalk.com/api/pictures/1

## How did I get there? (Workflow)
- Setup docker, laravel using composer, .env file
- Read a few times the assignment and made notes of the requirements and key points from it.
- Research for laravel libraries that would do all the CSV handling and image handling.
- DB
  - Planned the database structure based on the CSV file.
  - Worked with migrations.
- Coding
  - Based on the requirements I plan the minimum endpoints that I will require.
  - Setup everything and then started working on the logic and interconnection with the libraries [Laravel Excel](https://laravel-excel.com/) and [Intervention Image](http://image.intervention.io/).
- Tests
  - Setup some basic unit testing.
  - Postman.
- Deployment
  - Researched and used AWS CodeCommit, CodePipeline, EB and RDS.

## Challenges
- Docker for some reason from time to time the whole environment stall and had to reset it.
- Worked with two new libraries, "Laravel Excel" and "Intervention Image" which I had to learn and understand how they work. That took most of my time, and had to solve different issues that I encounter along the way. Previous knowledge on this two libs might have made everything faster.
- Setup a pipeline on AWS to automate the deployment flow.
