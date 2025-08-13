<?php

namespace App\Service;



class Routing
{
    /**
     * [Starts a session if not already started.]
     *
     * @return void
     * 
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * [Renders the current file name without extension.]
     *
     * @return string
     * 
     */
    public static function getFileName(): string
    {
        $url = $_SERVER['PHP_SELF'];
        $file = basename($url);

        return pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * [Renders the specified controller method and returns its output.]
     *
     * @param object $controller
     * @param string $method
     * 
     * @return void
     * 
     */
    public static function route(object $controller, string $method): void
    {
        self::startSession();
        echo $controller->$method();
    }
}
