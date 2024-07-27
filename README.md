# TODO App

Yet another Todo app. This application allows users to manage their tasks efficiently. It includes features like adding, completing, and deleting tasks. Additionally, it integrates with Telegram to manage tasks via a bot.

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/ShonazarovUz/todo_list.git
    cd todo-app
    ```

2. **Install dependencies using Composer:**

    ```bash
    composer install
    ```

3. **Set up the database:**

    - Create a MySQL database named `todo_app`.
    - Import the `dump.sql` file to set up the tables and initial data:

      ```bash
      mysql -u root -p todo_app < dump.sql
      ```

4. **Configure the database connection in `src/DB.php`:**

    ```php
    <?php
    // src/DB.php

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'todo_app');

    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    ?>
    ```

## Usage

1. **Web Interface:**

    Start a local PHP server:

    ```bash
    php -S localhost:8080
    ```
