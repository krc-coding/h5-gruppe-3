## **Dev setup guide**
### **Requirements**
- php 8.3.x NTS (Not Thread Safe)
- Composer
- MariaDB

## **Setup**
1. **Git clone**
    - Clone the project down on your machine

2. **Install/update composer in project**
    - Using a terminal to navigate to the project and go into /StoreDataCollection 
    - Run: `composer install`

3. **Env**
    - In terminal run both: 
        * `cp .env.example .env`
        * `php artisan key:generate`
    - Update your DB settings to fit your database

4. **Database**
    - Create a new database with the same name as you used in your env
    - In the terminal run: `php artisan migrate`

5. **Run degub**
    - Run: `php artisan serve` 

## Debugging
### Frontend
You can debug by going to localhost:8000

### Backend
You need to send http request.
We recommend using postman, as we have an collection in the files called: H5.postman_collection.json

## Integration testing
Backend as an integration test setuped.

* __This is not necessary__. In testing we can use another db if we don't want to use our live:
    * In terminal run: `cp .env.testing.example .env.testing`
    * Update the db settings and copy APP_KEY from .env
    * Create the db on your db
    * Run: `php artisan migrate --env=testing`

* To try it run: `php artisan test`
