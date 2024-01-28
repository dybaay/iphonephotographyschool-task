# iPhonePhotographySchool Task
-The application emphasizes flexibility, with data retrieval from the database instead of hard-coding.
-The code has been refactored to improve testability.

## Setup Instructions

Follow these steps to set up the application after cloning:

# Install dependencies using Composer
composer install

# Generate a new application key
php artisan key:generate

# Copy the .env.example file to .env
cp .env.example .env

# Create a database and update the .env file with appropriate credentials
# Run migrations and seed the database
php artisan migrate --seed

## Running Tests
# To run tests for the application, use the following command:
php artisan test
