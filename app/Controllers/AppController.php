<?php

namespace App\Controllers;

class AppController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): void
    {
        include VIEWS_PATH . 'templates/header.php';
        include VIEWS_PATH . 'app.php';
        include VIEWS_PATH . 'templates/footer.php';
    }
}
