<?php
namespace Local\Iblock;

use Local\Entities\FormAnswerTable;
use Local\Entities\FormFieldTable;
use Local\Helpers\Constants;

class IblockServicesList
{
    public static function saveItemsToFormAfterUpdate(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == Constants::IBLOCK_CATALOG_SERVICES) {

            $arElements = \Bitrix\Iblock\ElementTable::getList([
                'select' => [
                    'ID',
                    'NAME',
                    'IBLOCK_SECTION_ID'
                ],
                'filter' => [
                    'IBLOCK_ID' => Constants::IBLOCK_CATALOG_SERVICES,
                    'ACTIVE' => 'Y',
                    'ID' => $arFields['ID']
                ],
                'limit' => 1
            ]);

            $fieldId = FormFieldTable::getList([
                'select' => ['ID'],
                'filter' => ['SID' => 'subservices']
            ])->fetch()['ID'];

            if ($element = $arElements->fetch()) {
                $data = [
                    'MESSAGE' => $element['NAME'],
                    'VALUE' => $element['ID'],
                    'FIELD_TYPE' => 'dropdown',
                    'FIELD_ID' => $fieldId
                ];

                $checkElement = FormAnswerTable::getList([
                    'select' => ['ID'],
                    'filter' => [
                        'FIELD_ID' => $fieldId,
                        'VALUE' => $element['ID']
                    ]
                ]);

                if ($el = $checkElement->fetch()) {
                    FormAnswerTable::update($el['ID'], $data);
                } else {
                    FormAnswerTable::add($data);
                }
            }
        }
    }
}