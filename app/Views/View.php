<?php

namespace App\Views;

class View
{
    private mixed $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function render(): void
    {
        extract($this->data);
        include 'templates/header.php';
        include 'templates/footer.php';
    }
}
