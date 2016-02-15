<?php

namespace Arrilot\BitrixBlade;

use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory;

class BladeProvider
{
    /**
     * Path to a folder view common view can be stored.
     *
     * @var string
     */
    protected static $baseViewPath;

    /**
     * Local path to blade cache storage.
     *
     * @var string
     */
    protected static $cachePath;

    /**
     * View factory.
     *
     * @var Factory
     */
    protected static $viewFactory;

    /**
     * Service container factory.
     *
     * @var Container
     */
    protected static $container;

    /**
     * Register blade engine in Bitrix.
     *
     * @param string $baseViewPath
     * @param string $cachePath
     */
    public static function register($baseViewPath = 'local/views', $cachePath = 'local/cache/blade')
    {
        static::$baseViewPath = $_SERVER['DOCUMENT_ROOT'].'/'.$baseViewPath;
        static::$cachePath = $_SERVER['DOCUMENT_ROOT'].'/'.$cachePath;

        static::instantiateServiceContainer();
        static::instantiateViewFactory();

        global $arCustomTemplateEngines;
        $arCustomTemplateEngines['blade'] = [
            'templateExt' => ['blade'],
            'function'    => 'renderBladeTemplate',
        ];
    }

    /**
     * Get view factory.
     *
     * @return Factory
     */
    public static function getViewFactory()
    {
        return static::$viewFactory;
    }

    /**
     * Update paths where blade tries to find additional views.
     *
     * @param string $templateDir
     */
    public static function updateViewPaths($templateDir)
    {
        $newPaths = [
            $_SERVER['DOCUMENT_ROOT'].$templateDir,
            static::$baseViewPath,
        ];

        $finder = Container::getInstance()->make('view.finder');
        $finder->setPaths($newPaths);
    }

    /**
     * Instantiate service container if it's not instantiated yet.
     */
    protected static function instantiateServiceContainer()
    {
        $container = Container::getInstance();

        if (!$container) {
            $container = new Container();
            Container::setInstance($container);
        }

        static::$container = $container;
    }

    /**
     * Instantiate view factory.
     */
    protected static function instantiateViewFactory()
    {
        static::createDirIfNotExist(static::$baseViewPath);
        static::createDirIfNotExist(static::$cachePath);

        $viewPaths = [
            static::$baseViewPath,
        ];
        $cache = static::$cachePath;

        $blade = new Blade($viewPaths, $cache, static::$container);

        static::$viewFactory = $blade->view();
        static::$viewFactory->addExtension('blade', 'blade');
    }

    /**
     * Create dir if it does not exist.
     */
    protected static function createDirIfNotExist($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);  
        }
    }
}
