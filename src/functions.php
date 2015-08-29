<?php

use Arrilot\BitrixBlade\BladeProvider;
use Illuminate\Container\Container;

if (!function_exists('renderBladeTemplate')) {
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
        $view = BladeProvider::getViewFactory();

        BladeProvider::updateViewPaths($template->__folder);

        global $APPLICATION, $USER;

        echo $view->file($_SERVER['DOCUMENT_ROOT'].$templateFile, compact(
            'arParams',
            'arResult',
            'arLangMessages',
            'template',
            'templateFolder',
            'parentTemplateFolder',
            'APPLICATION',
            'USER'
        ))->render();

        $epilogue = $templateFolder.'/component_epilog.php';
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$epilogue)) {
            $component = $template->__component;
            $component->SetTemplateEpilog([
                'epilogFile'     => $epilogue,
                'templateName'   => $template->__name,
                'templateFile'   => $template->__file,
                'templateFolder' => $template->__folder,
                'templateData'   => false,
            ]);
        }
    }
}
