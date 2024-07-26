<?php

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