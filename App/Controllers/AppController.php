<?php

namespace breastplate\App\Controllers;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Psr\Cache\InvalidArgumentException;
use breastplate\Src\Core\View;
use breastplate\Src\Core\WebHelper;

/**
 * Class AppController
 *
 * This class is used to manage application operations.
 */
class AppController
{

    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * @var View The view instance.
     */
    private View $view;

    /**
     * @var AuthController The authentication controller instance.
     */
    private AuthController $authController;

    /**
     * @var WebHelper The web helper instance.
     */
    private WebHelper $webHelper;

    /**
     * AppController constructor.
     *
     * @param Container $container The dependency injection container.
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->authController = $this->container->get(AuthController::class);
        $this->view = $container->get(View::class);
        $this->webHelper = $this->container->get(WebHelper::class);
    }

    /**
     * Renders the application home page.
     *
     * @return void
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function index(): void
    {
        echo $this->view->render('app');
    }

    /**
     * Renders the not found page.
     *
     * @return void
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function notFound(): void
    {
        echo $this->view->render('error/404');
    }
}
