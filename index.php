<?php

require_once "vendor/autoload.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require 'functions.php';
date_default_timezone_set('Asia/Tashkent');

$update = json_decode(file_get_contents('php://input'));
$task = new Task();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode('/', $uri);

if (array_search('api', $path)) {
require 'API.php';
} 
if (isset($update) && isset($update->update_id)) {
    require 'bot/bot.php';
    return;
}
else{
    require "view.php";
}
require "view/home.php";   