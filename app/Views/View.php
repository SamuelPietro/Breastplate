<?php

namespace App\Views;

use Exception;

class View
{
    /**
     * @throws Exception
     */
    public function load($templateName, $request = []): bool|string
    {
        $templatePath = VIEWS_PATH . "/$templateName.php";
        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: $templatePath");
        }
        extract($request);
        
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * @throws Exception
     */
    public function render($templateName, $data = []): void
    {
        $content = $this->load($templateName, $data);
        echo $content;
    }
}
