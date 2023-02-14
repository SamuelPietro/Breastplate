<?php

namespace App\Views;

use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class View
 *
 * Class responsible for loading and rendering templates.
 */
class View
{
    private FilesystemAdapter $cache;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    /**
     * Loads a template and returns its content.
     *
     * @param string $templateName Name of the template file to load.
     * @param array $request Array containing the variables that will be extracted and made available to the template.
     * @return bool|string The content of the loaded template.
     * @throws Exception|InvalidArgumentException if the template file does not exist.
     */
    public function load(string $templateName, array $request = []): bool|string
    {
        $key = md5($templateName . serialize($request));
        $item = $this->cache->getItem($key);
        if (!$item->isHit()) {
            $templatePath = VIEWS_PATH . "/$templateName.php";
            if (!file_exists($templatePath)) {
                throw new Exception("Template not found: $templatePath");
            }

            extract($request);
            ob_start();
            include $templatePath;
            $item->set(ob_get_clean());
            $this->cache->save($item);
        }

        return $item->get();
    }

    /**
     * Renders a template and displays its content.
     *
     * @param string $templateName Name of the template file that will be rendered.
     * @param array $data Array containing the variables that will be made available to the template.
     * @throws Exception|InvalidArgumentException If an error occurs during execution.
     */
    public function render(string $templateName, array $data = []): void
    {
        try {
            $content = $this->load($templateName, $data);
            echo $content;
        } catch (Exception $e) {
            // Handle the error as appropriate for your application.
            // For example, you might display a custom error message or log the error.
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
