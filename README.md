Yet another Todo app. This application allows users to manage their tasks efficiently. It includes features like adding, completing, and deleting tasks. Additionally, it integrates with Telegram to manage tasks via a bot.

Requirements
PHP 8.3 or higher
Composer
MySQL
Installation
Clone the repository:

git clone https://github.com/Nurullayev2004/API.git
cd todo-app
Install dependencies using Composer:

composer install
Set up the database:

Create a MySQL database named TODO_LIST.
Import the dump.sql file to set up the tables and initial data:
mysqldump -u root -p TODO_LIST < dump.sql
Configure the database connection in src/DB.php:

<?php
// src/DB.php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', '');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
Usage
Web Interface
Start a local PHP server:

php -S localhost:8080
