<?php

namespace App\Controller;

use App\Database;
use App\Repository\NoteRepository;
use App\Service\AuthService;

class HomeController
{
    private $noteRepository;

    public function __construct()
    {
        $db = new Database();
        $this->noteRepository = new NoteRepository($db);
    }

    public function index(): void
    {
        $notes = [];
        $isLoggedIn = AuthService::checkIfLogging();
        if ($isLoggedIn) {
            $notes = $this->noteRepository->findByUserId($_SESSION['user']['id']);
        }

        $content = __DIR__ . '/../Resources/views/home.php';
        require_once __DIR__ . '/../Resources/layouts/default.php';
    }
}