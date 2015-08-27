<?php

use Philo\Blade\Blade;

if (! function_exists('renderBladeTemplate')) {
    /**
     * Render blade template callback.
     *
     * @param $templateFile
     * @param $arResult
     * @param $arParams
     * @param $arLangMessages
     * @param $templateFolder
     * @param $parentTemplateFolder
     * @param $template
     *
     * @return void
     */
    function renderBladeTemplate($templateFile, $arResult, $arParams, $arLangMessages, $templateFolder, $parentTemplateFolder, $template)
    {
        $views = [$_SERVER['DOCUMENT_ROOT']];
        $cache = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/cache/blade';

        $blade = new Blade($views, $cache);
        $view = $blade->view();
        $view->addExtension('blade', 'blade');

        echo $view->file($_SERVER['DOCUMENT_ROOT'] . $templateFile, compact(
            'arParams',
            'arResult',
            'arLangMessages',
            'template',
            'templateFolder',
            'parentTemplateFolder'
        ))->render();

        $epilogue = $templateFolder . "/component_epilog.php";
        if(file_exists($_SERVER["DOCUMENT_ROOT"].$epilogue))
        {
            $component = $template->__component;
            $component->SetTemplateEpilog([
                'epilogFile' => $epilogue,
                'templateName' => $template->__name,
                'templateFile' => $template->__file,
                'templateFolder' => $template->__folder,
                'templateData' => false,
            ]);
        }
    }
}