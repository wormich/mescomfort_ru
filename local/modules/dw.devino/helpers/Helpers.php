<?php

namespace DwDevino;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use Bitrix\Main\Loader;

class Helpers
{
    const MTC = 839;

    public static function getUsersGroup($iUserID) {
        $arUserGroups = \CUser::GetUserGroup($iUserID);
    }

    public static function getAvailableWebFormIDs()
    {
        $arAvailableWebForm = explode(',', \Bitrix\Main\Config\Option::get('dw.devino', 'forms_id'));

        foreach ($arAvailableWebForm as &$iWebForm) {
            $iWebForm = trim($iWebForm);
        }
        unset($iWebForm);

        return $arAvailableWebForm;
    }

    public static function getAnswerByFormResultID($iFormResultID)
    {
        Loader::IncludeModule('form');

        return \CFormResult::GetDataByID($iFormResultID, [], $arResult, $arAnswerWithKeys);
    }

    public static function GetEntityDataClass($HlBlockId)
    {
        Loader::IncludeModule('highloadblock');

        if (empty($HlBlockId) || $HlBlockId < 1) {
            return false;
        }

        $hlblock = HLBT::getById($HlBlockId)->fetch();
        $entity = HLBT::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        return $entity_data_class;
    }

    public static function getNoticeByEmail($iResultID, $sEmail)
    {
        Loader::includeModule('highloadblock');

        $hlblock = HLBT::getList([
            'filter' => ['=NAME' => 'DevinoNotificationsList']
        ])->fetch();

        $entity_data_class = Helpers::GetEntityDataClass($hlblock['ID']);

        $arAnswers = $entity_data_class::getList(array(
            // 'order' => ['ID' => 'DESC'],
            'filter' => [
                'UF_RESULT_ID' => $iResultID,
                'UF_EMAIL' => $sEmail,
            ]
        ))->fetchAll();

        return $arAnswers;
    }

    public static function getNoticeByPhone($iResultID, $sPhone)
    {
        Loader::includeModule('highloadblock');

        $hlblock = HLBT::getList([
            'filter' => ['=NAME' => 'DevinoNotificationsList']
        ])->fetch();

        $entity_data_class = Helpers::GetEntityDataClass($hlblock['ID']);

        $arAnswers = $entity_data_class::getList(array(
            // 'order' => ['ID' => 'DESC'],
            'filter' => [
                'UF_RESULT_ID' => $iResultID,
                'UF_PHONE' => $sPhone,
            ]
        ))->fetchAll();

        return $arAnswers;
    }

    public static function updateSmsStatus($iSmsID, $sStatus)
    {
        Loader::includeModule('highloadblock');

        $hlblock = HLBT::getList([
            'filter' => ['=NAME' => 'DevinoNotificationsList']
        ])->fetch();

        $entity_data_class = Helpers::GetEntityDataClass($hlblock['ID']);

        $entity_data_class::update($iSmsID, [
            'UF_STATUS' => $sStatus
        ]);
    }

    public static function updateSmsStatusTime($iSmsID, $sTime)
    {
        Loader::includeModule('highloadblock');

        $hlblock = HLBT::getList([
            'filter' => ['=NAME' => 'DevinoNotificationsList']
        ])->fetch();

        $entity_data_class = Helpers::GetEntityDataClass($hlblock['ID']);

        $entity_data_class::update($iSmsID, [
            'UF_DATE_CREATE' => $sTime
        ]);
    }

    public static function processRequest($sUrl, $sMethod, $arParams, $bAddHeader = false)
    {
        $ch = curl_init($sUrl);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $sMethod);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arParams);

        if ($bAddHeader) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($arParams)
                )
            );
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
