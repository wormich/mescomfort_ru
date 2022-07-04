<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
$name='SIMPLE_QUESTION_158';
$mail='SIMPLE_QUESTION_582';
$phone='SIMPLE_QUESTION_390';
$service='SIMPLE_QUESTION_367';
$arAnswer = \DwDevino\Helpers::getAnswerByFormResultID($_REQUEST['id']);
$sEmail = $arAnswer[$mail][0]['USER_TEXT'];

/**
 * Шаблон
 */
$arMessageTemplate = $rsMess = CEventMessage::GetList(
    $by = 'site_id',
    $order = 'desc',
    ['TYPE_ID' => 'DEVINO_EMAIL_NOTIFICATION']
)->Fetch()['MESSAGE'];

global $USER;

$sPhones = '+7(499) 550 33 77 с 8:30 до 20:30, без выходных.';

if ($USER->GetID() == \DwDevino\Helpers::MTC) {
    $sPhones = '+7(499) 550-03-03 доб. 5999 или 5618 или 5629 в будние дни с 8:30 до 17:15 (в пт. до 16:00).';
}

$arMessageTemplate = str_replace('#PHONES#', $sPhones, $arMessageTemplate);
$arMessageTemplate = str_replace('#FIO#', $arAnswer[$name][0]['USER_TEXT'], $arMessageTemplate);
$arMessageTemplate = str_replace('#ID#', $_REQUEST['id'], $arMessageTemplate);
$arMessageTemplate = str_replace('#DATE_CREATE#', date('d.m.Y H:i:s'), $arMessageTemplate);
$arMessageTemplate = str_replace(
    '#SERVICE_NAME#',
    $arAnswer[$service][0]['USER_TEXT'] , $arMessageTemplate
);

echo $arMessageTemplate . '<br><br>';

/**
 * Отображение истории
 */
if (filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
    $arEmailNotice = \DwDevino\Helpers::getNoticeByEmail($_REQUEST['id'], $sEmail);
    if ($arEmailNotice) {
        echo '<p><b>E-mail уведомления:</b></p>';
    }
    foreach ($arEmailNotice as $iKey => $arItem) {
        $iKey = $iKey + 1;
        echo '<p>' . $iKey . ': Отправлено  в <b>' . $arItem['UF_DATE_CREATE'] . '</b> на email: <b>' . $arItem['UF_EMAIL'] . '</b> ' . $arItem['UF_FROM'] . ' </p>';
    }
}

/**
 * Отправка E-mail увкедомления
 */
if (intval($_REQUEST['id']) != 0 && $_REQUEST['send'] == 'Y') {
    $arAnswer = \DwDevino\Helpers::getAnswerByFormResultID($_REQUEST['id']);

    $iSendMessage = \CEvent::Send('DEVINO_EMAIL_NOTIFICATION', 's1', [
        'EMAIL_FROM' => \Bitrix\Main\Config\Option::get('dw.devino', 'email_from'),
        'EMAIL_TO' => $arAnswer[$mail][0]['USER_TEXT'],
        'ID' => $_REQUEST['id'],
        'FIO' => $arAnswer[$name][0]['USER_TEXT'],
        'PHONES' => $sPhones,
        'DATE_CREATE' => date('d.m.Y H:i:s'),
        'SERVICE_NAME' => $arAnswer[$service][0]['USER_TEXT'] . '. ' ,
        'SUBJECT' => \Bitrix\Main\Config\Option::get('dw.devino',
                'company') . '. Не смогли дозвониться до вас по заявке №'.$_REQUEST['id']
    ]);

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
        'UF_EMAIL' => $arAnswer[$mail][0]['USER_TEXT'],
        'UF_PHONE' => '',
        'UF_DATE_CREATE' => date('d.m.Y H:i:s'),
        'UF_FROM' => $sMTC
    ]);

    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    if ($result && $iSendMessage) {
        echo 'Уведомление отправлено на адрес ' . $arAnswer[$mail][0]['USER_TEXT'] . ' в ' . date('d.m.Y H:i:s');
    } else {
        echo 'Во время отправки произошла ошибка, свяжитесь с администратором сайта';
    }
    exit;
}
