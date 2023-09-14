<?php

namespace Src\Core;

use DI\Container;
use League\Plates\Engine;
use Psr\Cache\InvalidArgumentException;
use Src\Extensions\Base64Extension;
use Src\Extensions\FormatTextExtension;
use Src\Extensions\FormatTimestampExtension;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class View
 *
 * Class responsible for loading and rendering templates.
 */
class View
{

    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * @var Engine
     */
    private Engine $plates;

    /**
     * @var Csrf
     */
    private Csrf $csrf;

    /**
     * @var WebHelper
     */
    private WebHelper $webHelper;

    /**
     * @var FilesystemAdapter
     */
    private FilesystemAdapter $cache;

    /**
     * @var string|false
     */
    private string|bool $templatesDir;

    /**
     * View constructor.
     *
     * @param Container $container The dependency injection container.
     */
    public function __construct(Container $container)
    {
        $this->plates = new Engine(VIEWS_PATH);
        $this->container = $container;
        $this->csrf = $this->container->get(Csrf::class);
        $this->plates->addFolder('default', __DIR__ . '/');
        $this->plates->addData([
            'timestamp' => time(),
            'csrf' => ['generate' => $this->csrf->generate()],
            'config' => $this->container->get(AppConfig::class)
        ]);
        $this->plates->loadExtension(new FormatTextExtension());
        $this->plates->loadExtension(new FormatTimestampExtension());
        $this->plates->loadExtension(new Base64Extension());

        $this->webHelper = $this->container->get(WebHelper::class);
        $this->cache = new FilesystemAdapter();
        $this->templatesDir = realpath(__DIR__ . '/../Views');
    }

    /**
     * Render the specified template with the provided data.
     *
     * @param string $templateName The name of the template file.
     * @param array $data The data to pass to the template.
     * @return string The rendered template as a string.
     * @throws InvalidArgumentException
     */
    public function render(string $templateName, array $data = []): string
    {
        $templateFile = $this->templatesDir . '/' . $templateName;
        $dataHash = md5(serialize($this->plates->getData()));
        $cacheKey = 'template_' . md5($templateFile . (file_exists($templateFile) ? filemtime($templateFile) : '') . $dataHash);
        $cachedResult = $this->cache->getItem($cacheKey);

        if (!$cachedResult->isHit()) {
            $redirectData = $this->webHelper->getSession('redirect_data');
            if ($redirectData !== null) {
                $data = array_merge($data, $redirectData['value']);
            }

            $result = $this->plates->render($templateName, $data);
            $cachedResult->set($result);
            $this->cache->save($cachedResult);
        }

        return $cachedResult->get();
    }
}
