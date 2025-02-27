<?php

declare(strict_types=1);

use GuzzleHttp\Client;

class Bot
{
    private  string $TOKEN;
    public  string $API   = "https://api.telegram.org/bot";
    public Client $http;
    private PDO   $pdo;

    public function __construct($token)
    {
        $this -> TOKEN = $token;
        $this->http = new Client(['base_uri' => $this->API.$this->TOKEN.'/']);
        $this->pdo  = DB::connect();
    }

    public function handleStartCommand(int $chatId): void
    {
        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text'    => 'Welcome to The Best TODO App ever in entire Universe!',
            ]
        ]);
    }

    public function handleAddCommand(int $chatId): void
    {
        $status = 'add';
        $query  = "INSERT INTO users (chat_id, status, created_at)
                VALUES (:chat_id, :status, NOW())
                ON DUPLICATE KEY UPDATE status = :status, created_at = NOW()";
        $stmt   = $this->pdo->prepare($query);
        $stmt->bindParam(':chat_id', $chatId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text'    => 'Please, enter your text',
            ]
        ]);
    }

    public function addTask(int $chatId, string $text): void
    {

        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE chat_id = :chat_id LIMIT 1");
        $stmt->execute(['chat_id' => $chatId]);
        $userId = $stmt->fetchObject()->id;
        $task = new Task();
        $task->add($text, $userId);
        $status = null;
        $stmt   = $this->pdo->prepare("UPDATE users SET status=:status WHERE chat_id = :chatId");
        $stmt->bindParam(':chatId', $chatId);
        $stmt->bindParam(':status', $status, PDO::PARAM_NULL);
        $stmt->execute();

        $this->http->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text'    => 'Task added successfully',
            ]
        ]);
    }

    public function getAllTasks(int $chatId): void
    {
        $query = "SELECT * FROM todos WHERE user_id = (SELECT id FROM users WHERE chat_id = :chatId LIMIT 1)";
        $stmt  = $this->pdo->prepare($query);
        $stmt->bindParam(':chatId', $chatId);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = $this->prepareTasks($tasks);

        $text = $this->prepareTexts($tasks);
        $buttons = $this->prepareButtons($tasks);
        if (!empty($text)) {
            $this->http->post('sendMessage', [
                'form_params' => [
                    'chat_id'      => $chatId,
                    'text'         => $text,
                    'reply_markup' => $buttons
                ]
            ]);
        } else {
            $this->http->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text'    => 'No tasks found.',
                ]
            ]);
        }
    }

    private function prepareTasks(array $tasks): array
    {
        $result = [];
        foreach ($tasks as $task) {
            $result[] = [
                'task_id' => $task['id'],
                'text'    => $task['text'],
                'status'  => $task['status']
            ];
        }

        return $result;
    }

    private function prepareTexts(array $tasks): string
    {
        $text    = '';
        $counter = 1;
        for ($task = 0; $task < count($tasks); $task++) {
            $status = $tasks[$task]['status'] === 0 ? '🟩' : '✅';
            $text   .= $status." ".($counter + $task).". {$tasks[$task]['text']}\n";
        }

        return $text;
    }

    private function prepareButtons(array $tasks): string
    {
        $buttons = ['inline_keyboard' => []];
        foreach ($tasks as $index => $task) {
            $buttons['inline_keyboard'][] = [
                ['text' => (string)$index, 'callback_data' => (string)$task['task_id']],
                ['text' => 'Delete', 'callback_data' => 'delete_'.$task['task_id']]
            ];
        }

        return json_encode($buttons);
    }

    public function handleInlineButton(int $chatId, string $data): void
    {
        if (strpos($data, 'delete_') === 0) {
            $taskId = (int) str_replace('delete_', '', $data);
            $this->deleteTask($taskId);

            $this->http->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text'    => 'Task deleted successfully',
                ]
            ]);
        } else {
            $task = new Task();
            $taskId = (int)$data;

            $currentTask = $task->getTask($taskId);

            if ($currentTask->status === 0) {
                $task->complete($taskId);
                $text = 'Task completed';
            } else {
                $task->uncompleted($taskId);
                $text = 'Task uncompleted';
            }

            $this->http->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text'    => $text,
                ]
            ]);
        }

        $this->getAllTasks($chatId);
    }

    public function deleteTask(int $taskId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = :taskId");
        $stmt->bindParam(':taskId', $taskId);
        $stmt->execute();
    }
}
