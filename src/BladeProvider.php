<?php

namespace Arrilot\BitrixBlade;

use Illuminate\Container\Container;

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
     * Register blade engine in Bitrix.
     *
     * @param string $baseViewPath
     * @param string $cachePath
     */
    public static function register($baseViewPath = 'local/views', $cachePath = 'bitrix/cache/blade')
    {
        global $arCustomTemplateEngines;

        self::$baseViewPath = $baseViewPath;
        self::$cachePath = $cachePath;

        $arCustomTemplateEngines['blade'] = [
            'templateExt' => ['blade'],
            'function'    => 'renderBladeTemplate',
        ];
    }

    /**
     * Get view factory.
     *
     * @return Illuminate\View\Factory
     */
    public static function getViewFactory()
    {
        $cache = $_SERVER['DOCUMENT_ROOT'].'/'.static::$cachePath;
        $viewPaths = [
            $_SERVER['DOCUMENT_ROOT'].'/'.static::$baseViewPath
        ];

        $blade = new Blade($viewPaths, $cache);

        $view = $blade->view();
        $view->addExtension('blade', 'blade');

        return $view;
    }


    /**
     * Update paths where blade tries to find additional views.
     *
     * @param string $templateDir
     *
     * @return void
     */
    public static function updateViewPaths($templateDir)
    {
        $newPaths = [
            $_SERVER['DOCUMENT_ROOT'].$templateDir,
            $_SERVER['DOCUMENT_ROOT'].'/'.static::$baseViewPath
        ];

        $finder = Container::getInstance()->make('view.finder');
        $finder->setPaths($newPaths);
    }
}
