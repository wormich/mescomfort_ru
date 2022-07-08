<?php

use Local\Helpers\Constants;

AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);
function _Check404Error()
{
    if (defined('ERROR_404') && ERROR_404 == 'Y') {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/404.php';
        require $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/footer.php';
    }
}

AddEventHandler('main', 'OnEpilog', 'setCanonical', 1);
function setCanonical()
{
    global $APPLICATION;

    if (strpos($APPLICATION->GetCurPageParam(), '?') !== false) {
        if ($APPLICATION->GetPageProperty('canonical') == '') {
            CMain::IsHTTPS() ? $s = 's' : $s = '';
            $canon_url = 'http' . $s . '://' . SITE_SERVER_NAME . $APPLICATION->GetCurPage();
            $APPLICATION->AddHeadString('<link href="' . $canon_url . '" rel="canonical" />', true);
        }
    }
}


AddEventHandler('iblock', 'OnBeforeIBlockElementDelete', 'dw_OnBeforeIBlockElementDeleteHandler');
function dw_OnBeforeIBlockElementDeleteHandler($ID)
{
    global $APPLICATION, $USER;
    $arUserGroups = CUser::GetUserGroup($USER->getID());

    if (in_array(11, $arUserGroups)) {
        global $APPLICATION;
        $APPLICATION->throwException('Вы не можете удалять элементы, созданные другими пользователями!');
        return false;
    }
}


AddEventHandler('form', 'onBeforeResultDelete', 'dw_onBeforeResultDelete');
function dw_onBeforeResultDelete($WEB_FORM_ID, $RESULT_ID, $CHECK_RIGHTS)
{
    global $APPLICATION, $USER;
    $arUserGroups = CUser::GetUserGroup($USER->getID());

    if (in_array(10, $arUserGroups)) {
        $exception = new CAdminException([]);
        $exception->AddMessage(['text' => 'Недостаточно прав']);
        $APPLICATION->ThrowException($exception);
        return false;
    }
}

AddEventHandler('form', 'onAfterResultAdd', 'dw_onAfterResultAdd');
function dw_onAfterResultAdd($WEB_FORM_ID, $RESULT_ID)
{
    if ($WEB_FORM_ID == Constants::NEW_SERVICE_ORDER) {
        $arResult = [];
        $arFormRes = CFormResult::GetDataByID(
            $RESULT_ID,
            [],
            $arResult,
            $arAns = []
        );

        if ($arFormRes['service'][0]['USER_TEXT'] == 'Солнечные электростанции') {
            $arEventFields = [
                'RESULT_ID' => $RESULT_ID,
                'DATE_CREATE' => $arResult['DATE_CREATE'],
                'SUBSERVICE' => $arFormRes['subservices'][0]['USER_TEXT'],
                'WEB_FORM_NAME' => $arResult['SID']
            ];
            CEvent::Send('SES_REQUEST', SITE_ID, $arEventFields, 'N', Constants::NEW_SES_MAIL_TEMP);
        }

    }
}

AddEventHandler('form', 'onAfterResultUpdate', 'dw_onAfterResultUpdate');
function dw_onAfterResultUpdate($WEB_FORM_ID, $RESULT_ID, $CHECK_RIGHTS)
{
    global $USER;
    $arUser = CUser::GetByID($USER->getID())->Fetch();

    if ($WEB_FORM_ID == Constants::NEW_SERVICE_ORDER) {
        $arVALUE = array();
        $FIELD_SID = 'operator_name'; // символьный идентификатор вопроса
        $ANSWER_ID = 40; // ID поля ответа
        $arVALUE[$ANSWER_ID] = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
        CFormResult::SetField($RESULT_ID, $FIELD_SID, $arVALUE);

        $arResult = [];
        $arFormRes = CFormResult::GetDataByID(
            $RESULT_ID,
            [],
            $arResult,
            $arAns = []
        );

        $checkStatus = $arResult['STATUS_TITLE'] == Constants::NOT_RINGING_STATUS;

        if ($checkService && $checkStatus) {
            $arEventFields = [
                'RESULT_ID' => $RESULT_ID,
                'DATE_CREATE' => $arResult['DATE_CREATE'],
                'WEB_FORM_NAME' => $arResult['SID'],
                'STATUS' => $arResult['STATUS_TITLE'],
                'STATUS_COMMENT' => $arFormRes['comment_status'][0]['USER_TEXT']
            ];
            CEvent::Send('SES_REQUEST', (SITE_ID == 'ru' || SITE_ID == 'en' ? 's1' : SITE_ID), $arEventFields, 'N', Constants::UP_SES_MAIL_TEMP);
        }

    }
}

AddEventHandler('form', 'onAfterResultStatusChange', 'dw_onAfterResultStatusChange');
function dw_onAfterResultStatusChange($WEB_FORM_ID, $RESULT_ID, $NEW_STATUS_ID, $CHECK_RIGHTS)
{
    global $USER;
    $arUser = CUser::GetByID($USER->getID())->Fetch();

    if ($WEB_FORM_ID == Constants::NEW_SERVICE_ORDER) {
        $arVALUE = array();
        $FIELD_SID = 'operator_name'; // символьный идентификатор вопроса
        $ANSWER_ID = 40; // ID поля ответа
        $arVALUE[$ANSWER_ID] = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
        CFormResult::SetField($RESULT_ID, $FIELD_SID, $arVALUE);
    }
}

AddEventHandler('main', 'OnBuildGlobalMenu', 'ASDFavoriteOnBuildGlobalMenu');
function ASDFavoriteOnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
{
    global $APPLICATION, $USER;

    $group = \Bitrix\Main\GroupTable::getList([
        'select' => ['ID'],
        'filter' => [
            'STRING_ID' => 'webform_administrator'
        ]
    ])->fetch();

    $arUserGroups = CUser::GetUserGroup($USER->getID());

    if (in_array(12, $arUserGroups)) {
        unset($aModuleMenu[0]);
        unset($aModuleMenu[1]['items'][1]);
        unset($aModuleMenu[5]);
    }

    if (in_array(10, $arUserGroups)) {
        unset($aModuleMenu[0]);
        unset($aModuleMenu[1]['items'][1]);
        unset($aModuleMenu[5]);
    }

    if (in_array(11, $arUserGroups)) {
        unset($aModuleMenu[0]);
        unset($aModuleMenu[1]['items'][1]);
        unset($aModuleMenu[7]);
    }

    if (in_array(12, $arUserGroups)) {
        foreach ($aModuleMenu as $k => $v) {
            if($v['section'] == 'iblock' || $v['text'] === 'Списки' || $v['text'] === 'Тестовый модуль')
            {
                unset($aModuleMenu[$k]);
            }
        }
    }

    if (in_array($group['ID'], $arUserGroups)) {
        foreach ($aModuleMenu as $k => $v) {
            if($v['section'] == 'iblock' || $v['text'] === 'Списки' || $v['text'] === 'Тестовый модуль')
            {
                unset($aModuleMenu[$k]);
            }
        }
    }
}

AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'onBeforePropHandler');
function onBeforePropHandler(&$arFields)
{

    $arCodes = ['FEM', 'PNE', 'AKB'];
    foreach ($arFields['PROPERTY_VALUES'] as $id => &$propFields) {
        $prop = CIBlockProperty::GetByID($id)->Fetch();
        if (in_array($prop['CODE'], $arCodes)) {
            foreach ($propFields as &$propField) {
                $propField['VALUE'] = (float)trim(str_replace(',', '.', $propField['VALUE']));
            }
        }

    }
}

/**
 * События для вывода таба с анкетой на веб-форме Заказ услуги (new!)
 */
AddEventHandler("main", "OnAdminTabControlBegin", "\Local\Form\FormQuestionsList::WebFormOnAdminTabControlBegin");
AddEventHandler('form', 'onAfterResultAdd', '\Local\Form\FormQuestionsList::WebFormAdminResult');
AddEventHandler('form', 'onAfterResultUpdate', '\Local\Form\FormQuestionsList::WebFormAdminResult');
AddEventHandler("main", "OnAdminContextMenuShow", "\Local\Form\FormQuestionsList::FormQuestionListCSVMenu");


AddEventHandler("iblock", "OnAfterIBlockElementAdd", "\Local\Iblock\IblockServicesList::saveItemsToFormAfterUpdate");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "\Local\Iblock\IblockServicesList::saveItemsToFormAfterUpdate");

RegisterModuleDependences("main", "OnAfterEpilog", "main", "\Local\Form\FormQuestionsList", "EndBuffer", "100");

/**
 * Событие добавления кнопки сохранения Excel файла
 */
AddEventHandler("main", "OnAdminContextMenuShow", "OnAdminContextMenuShowExcel");


function OnAdminContextMenuShowExcel(&$items)
{
    if ($GLOBALS["APPLICATION"]->GetCurPage(true) == "/bitrix/admin/form_result_list.php") {

        $arQuery = [
            'WEB_FORM_ID' => $_REQUEST['WEB_FORM_ID'],
            'set_filter' => $_REQUEST['set_filter'],
            'find_id' => $_REQUEST['find_id'],
            'find_date_create_1' => $_REQUEST['find_date_create_1'],
            'find_date_create_2' => $_REQUEST['find_date_create_2'],
            'find_SIMPLE_FORM_6_phone_USER_text' => $_REQUEST['find_SIMPLE_FORM_6_phone_USER_text'],
            'find_SIMPLE_FORM_6_service_USER_text' => $_REQUEST['find_SIMPLE_FORM_6_service_USER_text'],
            'find_SIMPLE_FORM_6_subservices_USER_text' => $_REQUEST['find_SIMPLE_FORM_6_subservices_USER_text'],
            'find_SIMPLE_FORM_6_mail_USER_text' => $_REQUEST['find_SIMPLE_FORM_6_mail_USER_text'],
        ];

        if (current($items)['ICON'] === 'btn_new') {
            $items[] = [
                'TEXT' => 'Экспортировать в Excel',
                'TITLE' => 'Экспортировать в Excel',
                'ICON' => 'adm-menu-excel',
                // 'LINK' => '/local/php_interface/include/form_xls_generate.php?WEB_FORM_ID=' . $_REQUEST['WEB_FORM_ID']
                'LINK' => '/local/php_interface/include/form_xls_generate_fixed.php?' . http_build_query($arQuery)
            ];
        }
    }
}
