<?php

namespace App\Controller;

use App\Database;
use App\Repository\NoteRepository;
use App\Service\AuthService;
use App\Service\LayoutService;

class HomeController
{
    private NoteRepository $noteRepository;

    public function __construct()
    {
        $db = new Database();
        $this->noteRepository = new NoteRepository($db);
    }

    /**
     * [Renders the home page with notes if the user is logged in.]
     *
     * @return void
     * 
     */
    public function index(): void
    {
        $notes = [];
        $isLoggedIn = AuthService::checkIfLogging();
        if ($isLoggedIn) {
            $notes = $this->noteRepository->findByUserId($_SESSION['user']['id']);
        }

        $content = __DIR__ . '/../Resources/views/home.php';
        require_once LayoutService::renderLayout();
    }
}