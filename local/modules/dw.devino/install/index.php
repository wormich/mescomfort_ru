<?

use Bitrix\Main\Config\Option;
use \Bitrix\Main\EventManager;

class dw_devino extends CModule
{
    const MODULE_ID = 'dw.devino';

    public $MODULE_ID = 'dw.devino',
        $MODULE_VERSION,
        $MODULE_VERSION_DATE,
        $MODULE_NAME = 'Интеграция с Devino',
        $PARTNER_NAME = 'Interrao',
        $PARTNER_URI = '';

    public function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . 'version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

    function InstallFiles($arParams = array())
    {
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/php_interface')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.') {
                        continue;
                    }
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/' . $item,
                        $ReWrite = true, $Recursive = true);
                }
                closedir($dir);
            }
        }

        $sTemplate_1 = 'Уважаемый(ая) #FIO#,<br><br>Мы приняли вашу заявку №#ID# на оказание услуги «#SERVICE_NAME#» от #DATE_CREAT.<br>Пожалуйста, ожидайте звонка от нашего специалиста в интервале с 8:30 до 20:30, без выходных.<br><br>Благодарим за заказ.<br><br>--<br>С уважением,<br>Служба поддержки АО «Мосэнергосбыт»<br><span style="color: #747070;">Сообщение сформировано автоматически, пожалуйста, не отвечайте на данное сообщение. Связаться со службой поддержки вы можете по номеру +7(499)5503377</span><br>';

        $iMailEvent = (new CEventType())->Add([
            'LID' => 's1',
            'EVENT_NAME' => 'ADD_NEW_FORM_RESULT',
            'NAME' => 'Создание результата в модуле Веб-форм',
            'DESCRIPTION' => ''
        ]);

        if ($iMailEvent > 0) {
            $iTemplate_1 = (new CEventMEssage)->Add([
                'ACTIVE' => 'Y',
                'EVENT_NAME' => 'ADD_NEW_FORM_RESULT',
                'LID' => 's1',
                'EMAIL_FROM' => '#EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'SUBJECT' => '#SUBJECT#',
                'BODY_TYPE' => 'html',
                'MESSAGE' => $sTemplate_1
            ]);
            Option::set('dw.devino', 'template_1', $iTemplate_1);
        }

        $sTemplate_2 = 'Уважаемый(ая) #FIO#,<br><br>К сожалению, не смогли дозвониться до вас по заявке №#ID# на оказание услуги «#SERVICE_NAME#» от #DATE_CREATE#.<br>Пожалуйста, перезвоните нам по контактному номеру +7(499) 550 33 77 с 8:30 до 20:30, без выходны.<br><br>--<br>С уважением,<br>Служба поддержки АО «Мосэнергосбыт»<br><span style="color: #747070;">Сообщение сформировано автоматически, пожалуйста, не отвечайте на данное сообщение. Связаться со службой поддержки вы можете по номеру +7(499)5503377</span>';

        $iMailEvent = (new CEventType())->Add([
            'LID' => 's1',
            'EVENT_NAME' => 'DEVINO_EMAIL_NOTIFICATION',
            'NAME' => 'DEVINO Уведомление',
            'DESCRIPTION' => ''
        ]);

        if ($iMailEvent > 0) {
            $iTemplate_2 = (new CEventMEssage)->Add([
                'ACTIVE' => 'Y',
                'EVENT_NAME' => 'DEVINO_EMAIL_NOTIFICATION',
                'LID' => 's1',
                'EMAIL_FROM' => '#EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'SUBJECT' => '#SUBJECT#',
                'BODY_TYPE' => 'html',
                'MESSAGE' => $sTemplate_2
            ]);
            Option::set('dw.devino', 'template_2', $iTemplate_2);
        }

        \Bitrix\Main\Loader::includeModule('highloadblock');

        $result = \Bitrix\Highloadblock\HighloadBlockTable::add(array(
            'NAME' => 'DevinoNotificationsList',
            'TABLE_NAME' => 'dw_devino_notifications',
        ));

        if ($result->isSuccess()) {
            $id = $result->getId();

            $UFObject = 'HLBLOCK_' . $id;

            $arCartFields = [
                'UF_STATUS' => [
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_STATUS',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => ['ru' => 'Статус', 'en' => ''],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_SMS_ID' => [
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_SMS_ID',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => ['ru' => 'ID в Devino', 'en' => ''],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_DATE_CREATE' => [
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_DATE_CREATE',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => ['ru' => 'Дата уведмоления', 'en' => ''],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_PHONE' => [
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_PHONE',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => ['ru' => 'Телефон клиента', 'en' => ''],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_EMAIL' => [
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_EMAIL',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => ['ru' => 'E-mail клиента', 'en' => ''],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ],
                'UF_RESULT_ID' => [
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_RESULT_ID',
                    'USER_TYPE_ID' => 'integer',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => ['ru' => 'ID заявки', 'en' => ''],
                    "LIST_COLUMN_LABEL" => ['ru' => '', 'en' => ''],
                    "LIST_FILTER_LABEL" => ['ru' => '', 'en' => ''],
                    "ERROR_MESSAGE" => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE" => ['ru' => '', 'en' => ''],
                ]
            ];

            foreach($arCartFields as $arCartField){
                $obUserField  = new CUserTypeEntity;
                $obUserField->Add($arCartField);
            }
        }

        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }


    public function InstallEventsHandlers()
    {
    }

    public function UnInstallEventsHandlers()
    {
    }

    public function DoInstall()
    {
        RegisterModule($this->MODULE_ID);

        $this->InstallFiles();
        $this->InstallEventsHandlers();
    }

    public function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);

        $this->UnInstallFiles();
        $this->UnInstallEventsHandlers();
    }
}
