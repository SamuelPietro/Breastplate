<?php

namespace Breastplate\App\Middlewares;

use Breastplate\Src\Core\WebHelper;

/**
 * The authentication middleware class.
 */
class AuthenticationMiddleware
{
    /**
     * @var WebHelper The web helper object.
     */
    private WebHelper $webHelper;

    /**
     * AuthenticationMiddleware constructor.
     *
     * @param WebHelper $webHelper The web helper object.
     */
    public function __construct(WebHelper $webHelper)
    {
        $this->webHelper = $webHelper;
    }

    /**
     * Handle an incoming request.
     *
     * @return void The response.
     */
    public function handle(): void
    {
        if ($this->webHelper->getSession('usr_id') === null) {
            $this->webHelper->redirect('/auth/login');
        }
    }
}