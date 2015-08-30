<?php

namespace Arrilot\BitrixBlade;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;

class Blade
{
    /**
     * Array of view base directories.
     *
     * @var array
     */
    protected $viewPaths;

    /**
     * Local path to blade cache storage.
     *
     * @var string
     */
    protected $cachePath;

    /**
     * Service container instance.
     *
     * @var Illuminate\Container\Container
     */
    protected $container;

    /**
     * View factory instance.
     *
     * @var Illuminate\View\Factory
     */
    protected $viewFactory;

    /**
     * Constructor.
     *
     * @param array     $viewPaths
     * @param string    $cachePath
     * @param Container $container
     */
    public function __construct($viewPaths = [], $cachePath, $container)
    {
        $this->viewPaths = $viewPaths;
        $this->cachePath = $cachePath;
        $this->container = $container;

        $this->registerFilesystem();
        $this->registerEvents();
        $this->registerEngineResolver();
        $this->registerViewFinder();
        $this->registerFactory();
    }

    /**
     * Getter for view factory.
     *
     * @return Factory
     */
    public function view()
    {
        return $this->viewFactory;
    }

    /**
     * Register filesystem in container.
     *
     * @return void
     */
    public function registerFilesystem()
    {
        $this->container->singleton('files', function () {
            return new Filesystem;
        });
    }

    /**
     * Register events in container.
     *
     * @return void
     */
    public function registerEvents()
    {
        $this->container->singleton('events', function () {
            return new Dispatcher;
        });
    }

    /**
     * Register the engine resolver instance.
     *
     * @return void
     */
    public function registerEngineResolver()
    {
        $me = $this;

        $this->container->singleton('view.engine.resolver', function ($app) use ($me) {
            $resolver = new EngineResolver;

            $me->registerPhpEngine($resolver);
            $me->registerBladeEngine($resolver);

            return $resolver;
        });
    }

    /**
     * Register the PHP engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver $resolver
     *
     * @return void
     */
    public function registerPhpEngine($resolver)
    {
        $resolver->register('php', function () {
            return new PhpEngine;
        });
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver $resolver
     *
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        $me = $this;
        $app = $this->container;

        $this->container->singleton('blade.compiler', function ($app) use ($me) {
            $cache = $me->cachePath;

            return new BladeCompiler($app['files'], $cache);
        });

        $resolver->register('blade', function () use ($app) {
            return new CompilerEngine($app['blade.compiler'], $app['files']);
        });
    }

    /**
     * Register the view factory.
     */
    public function registerFactory()
    {
        $resolver = $this->container['view.engine.resolver'];

        $finder = $this->container['view.finder'];

        $factory = new Factory($resolver, $finder, $this->container['events']);
        $factory->setContainer($this->container);

        $this->viewFactory = $factory;
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
