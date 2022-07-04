<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
addMessage2log('sms тут');
$name='SIMPLE_QUESTION_158';
$mail='SIMPLE_QUESTION_582';
$phone='SIMPLE_QUESTION_390';
$service='SIMPLE_QUESTION_367';
$sLogin = \Bitrix\Main\Config\Option::get('dw.devino', 'login');
$sPassword = \Bitrix\Main\Config\Option::get('dw.devino', 'password');

if (!$sLogin || !$sPassword) {
    echo 'Вы не указали логин и пароль в <a href="/bitrix/admin/settings.php?mid=dw.devino&lang=ru" target="_blank">настроках модуля</a>';
    exit;
}

$arAnswer = \DwDevino\Helpers::getAnswerByFormResultID($_REQUEST['id']);
$sPhone = $arAnswer[$phone][0]['USER_TEXT'];
addMessage2log($arAnswer);
/**
 * Шаблон
 */
global $USER;

$smsTemplate = \Bitrix\Main\Config\Option::get('dw.devino', 'sms_template');
$smsTemplate = str_replace('#RESULT_ID#', $_REQUEST['id'], $smsTemplate);

if ($USER->GetID() == \DwDevino\Helpers::MTC) {
    $smsTemplate = str_replace(
        '+74995503377 с 8:30 до 20:30.',
        '+7(499)550-0303 доб. 5999;5616;5629 будни 8:30-17:15(пт.16:00).',
        $smsTemplate);
}

echo $smsTemplate . '<br><br>';

/**
 * Отображение истории
 */
if ($sPhone) {

    /**
     * Обновление статусов
     */
    include_once('PHP_rest_client/smsClient.php');
    $client = new SMSClient($sLogin, $sPassword);
    $client->getSessionID();
    $arSmsNotice = \DwDevino\Helpers::getNoticeByPhone($_REQUEST['id'], $sPhone);
    foreach ($arSmsNotice as $iKey => $arItem) {
        if ($arItem['UF_SMS_ID']) {
            $smsStatus = $client->getSMSState($arItem['UF_SMS_ID']);

            /*if ($smsStatus['StateDescription'] == 'Неизвестный') {
                $smsStatus['StateDescription'] = 'В обработке';
            }*/

            \DwDevino\Helpers::updateSmsStatus($arItem['ID'], $smsStatus['StateDescription']);

            if ($smsStatus['CreationDateUtc']) {
                $dateUpdate = \Bitrix\Main\Type\DateTime::createFromTimestamp($smsStatus['TimeStampUtc'] / 1000);
                \DwDevino\Helpers::updateSmsStatusTime($arItem['ID'], $dateUpdate->format('d.m.Y H:i:s'));
            }
        }
    }

    $arSmsNotice = \DwDevino\Helpers::getNoticeByPhone($_REQUEST['id'], $sPhone);
    if ($arSmsNotice) {
        echo '<p><b>SMS уведомления:</b></p>';
    }

    foreach ($arSmsNotice as $iKey => $arItem) {
        $iKey = $iKey + 1;
        if (!$arItem['UF_STATUS']) {
            $arItem['UF_STATUS'] = 'Доставлено';
        }
        echo '<p>' . $iKey . '. <b>' . $arItem['UF_DATE_CREATE'] . '</b> ' . $arItem['UF_PHONE'] . ': <b>' . $arItem['UF_STATUS'] . '</b> ' . $arItem['UF_FROM'] . ' </p>';
    }
}

/**
 * Отправка SMS уведомления
 */
if (intval($_REQUEST['id']) != 0 && $_REQUEST['send'] == 'Y') {

    include_once('PHP_rest_client/smsClient.php');

    $client = new SMSClient($sLogin, $sPassword);
    $client->getSessionID();
    $arAnswer = \DwDevino\Helpers::getAnswerByFormResultID($_REQUEST['id']);
    print_r($arAnswer);
    try {
        $arAnswer = \DwDevino\Helpers::getAnswerByFormResultID($_REQUEST['id']);

        $smsResult = $client->send(
            'MOSENRGSBYT',
            preg_replace("/[^0-9]/", '', $arAnswer[$phone][0]['USER_TEXT']),
            $smsTemplate
        );

        $iSMSid = $smsResult[0];

        if (is_array($smsResult) && !empty($smsResult)) {

            \Bitrix\Main\Loader::includeModule('highloadblock');

            $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
                'filter' => ['=NAME' => 'DevinoNotificationsList']
            ])->fetch();

            $entity_data_class = \DwDevino\Helpers::GetEntityDataClass($hlblock['ID']);

            $sMTC = 'КЦ';
            if ($USER->GetID() == \DwDevino\Helpers::MTC) {
                $sMTC = 'МТЦ';
            }

            $result = $entity_data_class::add([
                'UF_RESULT_ID' => $_REQUEST['id'],
                'UF_EMAIL' => '',
                'UF_PHONE' => $arAnswer[$phone][0]['USER_TEXT'],
                'UF_DATE_CREATE' => date('d.m.Y H:i:s'),
                'UF_SMS_ID' => $iSMSid,
                'UF_STATUS' => '',
                'UF_FROM' => $sMTC
            ]);

            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            echo 'Уведомление успешно отправлено на номер ' . $arAnswer[$phone][0]['USER_TEXT'] . ' в ' . date('d.m.Y H:i:s');
            exit;
        }
    } catch (SMSError_Exception $e) {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        echo 'Во время отправки произошла ошибка, свяжитесь с администратором сайта.';
        exit;
    }
}
