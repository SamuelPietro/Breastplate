<?php

namespace App\Controllers;

/**
 * Class AppController
 *
 * @package App\Controllers
 *
 * This class is used to manage index of application.
 */
class AppController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): void
    {
        /**
         * Renders the view for the home page.
         */
        include VIEWS_PATH . 'templates/header.php';
        include VIEWS_PATH . 'app.php';
        include VIEWS_PATH . 'templates/footer.php';
    }
}
