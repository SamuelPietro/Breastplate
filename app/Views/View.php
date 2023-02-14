<?php

namespace App\Views;

use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

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
        $this->cache = new FilesystemAdapter('', 3600);
    }

    /**
     * Loads one or more templates and returns their content.
     *
     * @param string[] $templateNames Names of the template files to load.
     * @param array $request Array containing the variables that will be extracted and made available to the templates.
     * @return string The content of the loaded templates.
     * @throws Exception|InvalidArgumentException if any of the template files do not exist.
     */
    public function load(array $templateNames, array $request = []): string
    {
        $key = md5(serialize($templateNames) . serialize($request));
        $item = $this->cache->getItem($key);
        if (!$item->isHit()) {
            $headerPath = VIEWS_PATH . '/templates/header.php';
            if (!file_exists($headerPath)) {
                throw new Exception(sprintf('Header not found: %s', $headerPath));
            }
            ob_start();
            include $headerPath;
            $content = ob_get_clean();

            foreach ($templateNames as $templateName) {
                $templatePath = VIEWS_PATH . '/' . $templateName . '.php';
                if (!file_exists($templatePath)) {
                    throw new Exception(sprintf('Template not found: %s', $templatePath));
                }

                extract($request);
                ob_start();
                include $templatePath;
                $content .= ob_get_clean();
            }

            $footerPath = VIEWS_PATH . '/templates/footer.php';
            if (!file_exists($footerPath)) {
                throw new Exception(sprintf('Footer not found: %s', $footerPath));
            }
            ob_start();
            include $footerPath;
            $content .= ob_get_clean();

            $item->set($content);
            $this->cache->save($item);
        }

        return $item->get();
    }

    /**
     * Renders one or more templates and displays their content.
     *
     * @param string[] $templateNames Names of the template files that will be rendered.
     * @param array $data Array containing the variables that will be made available to the templates.
     * @throws Exception|InvalidArgumentException If an error occurs during execution.
     */
    public function render(array $templateNames, array $data = []): void
    {
        try {
            $content = $this->load($templateNames, $data);
            echo $content;
        } catch (Exception $e) {
            echo sprintf('An error occurred: %s', $e->getMessage());
        }
    }
}
