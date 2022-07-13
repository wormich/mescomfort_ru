<?php

namespace DwDevino;

class Handlers
{
    public static function createElementMenuItems(&$list)
    {

        $arAvailableWebForm = Helpers::getAvailableWebFormIDs();

        if (strpos($list->table_id, 'form_result_list')) {
            foreach ($list->aRows as $row) {

                $sWebFormID = explode('&', $row->aActions[0]['ACTION'])[1];
                $iWebFormID = preg_replace("/[^0-9]/", '', $sWebFormID);
                addmessage2log($row->aActions[0]['ACTION']);
                if (!in_array($iWebFormID, $arAvailableWebForm)) {
                    continue;
                }

                switch ($iWebFormID){
                    case 3:
                        $name='SIMPLE_QUESTION_158';
                        $mail='SIMPLE_QUESTION_582';
                        $phone='SIMPLE_QUESTION_390';
                        break;
                }

                $arAnswer = Helpers::getAnswerByFormResultID($row->id);
                $sEmail = $arAnswer[$mail][0]['USER_TEXT'];
                $sPhone = $arAnswer[$phone][0]['USER_TEXT'];
//                addMessage2Log($sEmail);
//                addMessage2Log($sPhone);
                $sLastSendEmail = '';
                $sLastSendPhone = '';

                if (filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
                    $arEmailNotice = Helpers::getNoticeByEmail($row->id, $sEmail);
                    if (!empty($arEmailNotice)) {
                        $sLastSendEmail = ', последнее ' . end($arEmailNotice)['UF_DATE_CREATE'];
                    }
                    $sText = 'Отправить E-mail (кол-во: ' . count($arEmailNotice) . $sLastSendEmail .')';
                    $row->aActions['send_email'] = [
                        'ICON' => 'edit',
                        'TEXT' => $sText,
                        'ACTION' => 'javascript:send_email(' . $row->id . ')'
                    ];
                }

                if ($sPhone) {

                    $arPhoneNotice = Helpers::getNoticeByPhone($row->id, $sPhone);

                    if (!empty($arPhoneNotice)) {
                        $sLastSendPhone = ', последнее ' . end($arPhoneNotice)['UF_DATE_CREATE'];
                    }
                    $row->aActions['send_sms'] = [
                        'ICON' => 'edit',
                        'TEXT' => 'Отправить SMS (кол-во: ' . count($arPhoneNotice) . $sLastSendPhone .')',
                        'ACTION' => 'javascript:send_sms(' . $row->id . ')'
                    ];
                    addMessage2log($row->aActions);
                }

                /*$row->aActions['show_all'] = [
                    'ICON' => 'edit',
                    'TEXT' => 'История уведомлений',
                    'ACTION' => 'javascript:show_all(' . $row->id . ')'
                ];*/
            }
        }
    }

    public static function sendMessageOnAfterResultAdd($WEB_FORM_ID, $RESULT_ID)
    {
        switch ($WEB_FORM_ID){
            case 3:
                $name='SIMPLE_QUESTION_158';
                $mail='SIMPLE_QUESTION_582';
                $phone='SIMPLE_QUESTION_390';
                $service='SIMPLE_QUESTION_367';
                break;
        }
        $arAvailableWebForm = Helpers::getAvailableWebFormIDs();

        if (!in_array($WEB_FORM_ID, $arAvailableWebForm)) {
            return false;
        }

        $arAnswer = Helpers::getAnswerByFormResultID($RESULT_ID);


        if (filter_var($arAnswer[$mail][0]['USER_TEXT'], FILTER_VALIDATE_EMAIL)) {
            addMessage2Log('вы тут верификация мыла');

            \CEvent::Send('ADD_NEW_FORM_RESULT', 's1', [
                'EMAIL_FROM' => \Bitrix\Main\Config\Option::get('dw.devino', 'email_from'),
                'EMAIL_TO' => $arAnswer[$mail][0]['USER_TEXT'],
                'ID' => $RESULT_ID,
                'FIO' => $arAnswer[$name][0]['USER_TEXT'],
                'DATE_CREATE' => date('d.m.Y H:i:s'),
                'SERVICE_NAME' => $arAnswer['SIMPLE_QUESTION_367'][0]['USER_TEXT'],
                'SUBJECT' => \Bitrix\Main\Config\Option::get('dw.devino', 'company') . '. Мы приняли вашу заявку №'.$RESULT_ID
            ]);
        }
    }
}
