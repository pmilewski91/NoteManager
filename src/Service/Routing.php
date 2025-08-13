<?php

namespace App\Service;



class Routing
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getFileName(): string
    {
        $url = $_SERVER['PHP_SELF'];
        $file = basename($url);

        return pathinfo($file, PATHINFO_FILENAME);
    }
    
    public static function route(object $controller, string $method): void
    {
        self::startSession();
        echo $controller->$method();
    }
}
