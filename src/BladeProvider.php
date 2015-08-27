<?php

namespace Arrilot\BitrixBlade;

class BladeProvider
{
    /**
     * Register blade engine in Bitrix.
     *
     * @return void
     */
    public static function register()
    {
        global $arCustomTemplateEngines;

        $arCustomTemplateEngines['blade'] = [
            'templateExt' => ['blade'],
            'function'    => 'renderBladeTemplate',
        ];
    }
}
