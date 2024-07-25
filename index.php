<?php

require_once "vendor/autoload.php";
require 'functions.php';

date_default_timezone_set('Asia/Tashkent');

$update = json_decode(file_get_contents('php://input'));
$task = new Task();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode('/', $uri); // Corrected variable name

if (array_search('api', $path)) {
    if ($path === '/add') {
        $userId = isset($update->userId) && is_int($update->userId) ? $update->userId : 0; 
        $task->add($update->text, $userId);
    }

    if (isset($_GET['delete'])) {
        $task->delete($_GET['delete']);
    }
    
    if (isset($_GET['complete'])) {
        $task->complete($_GET['complete']);
    }

    if (isset($_GET['uncomplete'])) {
        $task->uncomplete($_GET['uncomplete']);
    }

    if (isset($update) && isset($update->update_id)) {
        require 'bot/bot.php';
        return;
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
        $userId = isset($_POST['userId']) && is_int($_POST['userId']) ? (int)$_POST['userId'] : 0; 
        $task->add($_POST['text'], $userId);
    }
    if (isset($_GET['complete'])) {
        $task->complete($_GET['complete']);
    }
    if (isset($_GET['uncompleted'])) {
        $task->uncompleted($_GET['uncompleted']);
    }
    if (isset($_GET['delete'])) {
        $task->delete($_GET['delete']);
    }
}
