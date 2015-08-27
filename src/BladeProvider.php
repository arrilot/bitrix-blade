<?php

namespace Arrilot\BitrixBlade;

use Philo\Blade\Blade;

class BladeProvider
{
    /**
     * Local path to blade cache storage.
     *
     * @var string
     */
    protected static $cachePath;

    /**
     * Register blade engine in Bitrix.
     *
     * @param string $cachePath
     *
     * @return void
     */
    public static function register($cachePath = 'bitrix/cache/blade')
    {
        global $arCustomTemplateEngines;

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
        $cache = $_SERVER['DOCUMENT_ROOT'].'/'.self::$cachePath;

        $blade = new Blade(false, $cache);

        $view = $blade->view();
        $view->addExtension('blade', 'blade');

        return $view;
    }
}
