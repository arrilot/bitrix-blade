<?php

namespace Arrilot\BitrixBlade;

use CBitrixComponentTemplate;
use CIBlock;
use InvalidArgumentException;

class AdminActions
{
    protected static $panelButtons = [];

    /**
     * Get edit area id for specific type
     *
     * @param CBitrixComponentTemplate $template
     * @param $type
     * @param $element
     * @return string
     */
    public static function getEditArea($template, $type, $element)
    {
        return $template->GetEditAreaId($type . '_' . $element['ID']);
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     */
    public static function editIBlockElement($template, $element)
    {
        if (!$element["IBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and IBLOCK_ID');
        }

        $buttons = static::getIBlockElementPanelButtons($element);
        $link = $buttons["edit"]["edit_element"]["ACTION_URL"];

        $template->AddEditAction('iblock_element_' . $element['ID'], $link, CIBlock::GetArrayByID($element["IBLOCK_ID"], "ELEMENT_EDIT"));
    }
    
    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @param string $confirm
     */
    public static function deleteIBlockElement($template, $element, $confirm = 'Вы уверены что хотите удалить элемент?')
    {
        if (!$element["IBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and IBLOCK_ID');
        }
    
        $buttons = static::getIBlockElementPanelButtons($element);
        $link = $buttons["edit"]["delete_element"]["ACTION_URL"];

        $template->AddDeleteAction('iblock_element_' . $element['ID'], $link, CIBlock::GetArrayByID($element["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => $confirm));
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $section
     */
    public static function editIBlockSection($template, $section)
    {
        if (!$section["IBLOCK_ID"] || !$section['ID']) {
            throw new InvalidArgumentException('Section must include ID and IBLOCK_ID');
        }
    
        $buttons = static::getIBlockSectionPanelButtons($section);
        $link = $buttons["edit"]["edit_section"]["ACTION_URL"];

        $template->AddEditAction('iblock_section_' . $section['ID'], $link, CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_EDIT"));
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $section
     * @param string $confirm
     */
    public static function deleteIBlockSection($template, $section, $confirm = 'Вы уверены что хотите удалить раздел?')
    {
        if (!$section["IBLOCK_ID"] || !$section['ID']) {
            throw new InvalidArgumentException('Section must include ID and IBLOCK_ID');
        }
    
        $buttons = static::getIBlockSectionPanelButtons($section);
        $link = $buttons["edit"]["delete_section"]["ACTION_URL"];

        $template->AddDeleteAction('iblock_section_' . $section['ID'], $link, CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => $confirm));
    }
    
    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @param string $label
     */
    public static function editHLBlockElement($template, $element, $label = 'Изменить элемент')
    {
        if (!$element["HLBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and HLBLOCK_ID');
        }

        $linkTemplate = '/bitrix/admin/highloadblock_row_edit.php?ENTITY_ID=%s&ID=%s&lang=ru&bxpublic=Y';
        $link = sprintf($linkTemplate, (int) $element["HLBLOCK_ID"], (int) $element["ID"]);

        $template->AddEditAction('hlblock_element_' . $element['ID'], $link, $label);
    }
    
    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @param string $label
     * @param string $confirm
     */
    public static function deleteHLBlockElement($template, $element, $label = 'Удалить элемент', $confirm = 'Вы уверены что хотите удалить элемент?')
    {
        if (!$element["HLBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and HLBLOCK_ID');
        }

        $linkTemplate = '/bitrix/admin/highloadblock_row_edit.php?action=delete&ENTITY_ID=%s&ID=%s&lang=ru&sessid=%s';
        $link = sprintf($linkTemplate, (int) $element["HLBLOCK_ID"], (int) $element["ID"], bitrix_sessid_get());
    
        $template->AddDeleteAction('hlblock_element_' . $element['ID'], $link, $label, array("CONFIRM" => $confirm));
    }

    /**
     * @param $element
     * @return array
     */
    protected static function getIBlockElementPanelButtons($element)
    {
        if (!isset(static::$panelButtons['iblock_element'][$element['ID']])) {
            static::$panelButtons['iblock_element'][$element['ID']] = CIBlock::GetPanelButtons(
                $element["IBLOCK_ID"],
                $element['ID'],
                0,
                []
            );
        }

        return static::$panelButtons['iblock_element'][$element['ID']];
    }

    /**
     * @param $section
     * @return array
     */
    protected static function getIBlockSectionPanelButtons($section)
    {
        if (!isset(static::$panelButtons['iblock_section'][$section['ID']])) {
            static::$panelButtons['iblock_section'][$section['ID']] = CIBlock::GetPanelButtons(
                $section["IBLOCK_ID"],
                0,
                $section['ID'],
                []
            );
        }

        return static::$panelButtons['iblock_section'][$section['ID']];
    }
}
