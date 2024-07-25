<?php

class Router
{
    public $updates;

    public function __construct()
    {
        $this -> updates = json_decode(file_get_contents("php://input"));
    }

    public function isApiCall()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = explode('/', $uri);
        return array_search('api', $path);
    }

    public function getResourceId(): float|false|int|string
    {
        $uri = pare_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = explode('/', $url);
        $resourceId = $path[count($path)-1];
        return is_numeric($resourceId)?$resourceId:false;
    }

    public function isTelegramUpdate(): bool
    {
        if(isset($this->updates?->update_id)){
            return true;
        }
    }
}