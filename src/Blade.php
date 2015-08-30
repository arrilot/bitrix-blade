<?php

namespace Arrilot\BitrixBlade;

use Illuminate\Container\Container;
use Philo\Blade\Blade as BaseBlade;

class Blade extends BaseBlade
{
    /**
     * Constructor.
     *
     * @param array  $viewPaths
     * @param string $cachePath
     */
    public function __construct($viewPaths = [], $cachePath)
    {
        parent::__construct($viewPaths, $cachePath);

        Container::setInstance($this->container);
    }

    /**
     * Register the view finder implementation.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $me = $this;
        $this->container->singleton('view.finder', function ($app) use ($me) {
            $paths = $me->viewPaths;

            return new ViewFinder($app['files'], $paths);
        });
    }
}
