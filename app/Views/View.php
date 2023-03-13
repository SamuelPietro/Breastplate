<?php

namespace App\Views;

use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use function ob_get_clean;

/**
 * Class View
 *
 * Class responsible for loading and rendering templates.
 */
class View
{
    private FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
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
        $content = '';
        $headerPath = VIEWS_PATH . '/templates/header.php';

        if (!file_exists($headerPath)) {
            error_log(gettext('Header not found: ') . $headerPath);
        }

        ob_start();
        include_once $headerPath;
        $content .= ob_get_clean();

        foreach ($templateNames as $templateName) {
            $templatePath = VIEWS_PATH . '/' . $templateName . '.php';
            if (!file_exists($templatePath)) {
                error_log(gettext('Template not found: ') . $templatePath);
            }

            $cacheKey = md5($templateName . serialize($request));
            $cachedContent = $this->cache->getItem($cacheKey);
            if (!$cachedContent->isHit()) {
                extract($request);
                ob_start();
                include_once $templatePath;
                $cachedContent->set(ob_get_clean());
                $this->cache->save($cachedContent);
            }

            $content .= $cachedContent->get();
        }

        $footerPath = VIEWS_PATH . '/templates/footer.php';
        if (!file_exists($footerPath)) {
            error_log(gettext('Footer not found: ') . $footerPath);
        }

        ob_start();
        include_once $footerPath;
        $content .= ob_get_clean();

        return $content;
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
            error_log(gettext('An error occurred: ') . $e->getMessage());
        }
    }
}
