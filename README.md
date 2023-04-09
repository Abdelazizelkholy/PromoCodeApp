# Laravel App

## This is a Laravel application that provides 

promo code app where admins can create promo codes through an api the app consists of 2 endpoints

1- first end point :
is an endpoint for admins to create new promo codes

2- secand end point :
is an endpoint for users to check the validity of a promo code

## Below are the instructions on how to use the app and run tests.

# Prerequisites
Before running the Laravel app, make sure you have the following software installed on your machine:

Docker
Docker Compose

# Setup
1-Clone the repository to your local machine:
git clone https://github.com/your-username/laravel-app.git

2-Change directory to the cloned repository:
cd laravel-app

3-Build the Docker containers using Docker Compose:
docker-compose build

4-Start the Docker containers:
docker-compose up -d

5-Install the Laravel dependencies:
docker-compose exec app composer install

6-Generate the Laravel application key:
docker-compose exec app php artisan key:generate

7-Migrate the database
docker-compose exec app php artisan migrate

# Usage
To use the Laravel app, you can access it via a web browser at http://localhost:8080 or using a tool like Postman for API endpoints.

# Running Tests
To run the tests for the Laravel app, you can use the following 
# command:
docker-compose exec app php artisan test
