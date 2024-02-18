<?php

namespace pFrame\App\Middlewares;

use pFrame\Src\Core\WebHelper;

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
     * @return void
     */
    public function handle(): void
    {
        if ($this->webHelper->getSession('usr_id') === null) {
            $this->webHelper->redirect('/login');
        }
    }
}