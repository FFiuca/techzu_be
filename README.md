# Requirements :
- PHP 8.2 - 8.3 -> requirement of laravel 11
- MariaDB 10.3+ or  MySQL 5.7+

# How to Run Locally :
1. clone this repo into your local `git clone https://github.com/FFiuca/techzu_be.git`
2. after clone, go to project folder
3. prepare your db and config db connection in .env file
current setting :
`DB_HOST=127.0.0.1; DB_PORT=3302; DB_DATABASE=techzu_test; DB_USERNAME=root ; DB_PASSWORD=admin;`
4. `composer install`
5. `php artisan migrate --seed`
6. `php artisan serve` -> will run at http://localhost:8000
7. open new terminal with previous terminal still running then run `php artisan schedule:work`

# Overview
1. demo video : [here](https://drive.google.com/file/d/1kC78l9ytgKdyq6k_EKa10gNLOZMZJMhj/view?usp=drive_link)
2. db schema : [here](https://drive.google.com/file/d/1_ALIjBagK6tdvgTREnG_7Qq0ouDbsS7c/view?usp=sharing)
