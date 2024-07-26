<?php

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