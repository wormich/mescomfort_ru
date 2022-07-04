<?

use Bitrix\Main\Loader as Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


class DWLandingFormComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);

        if ($arParams['CACHE_TIME'] <= 0) {
            $arParams['CACHE_TIME'] = 604800;
        }

        return $arParams;
    }

    public function executeComponent()
    {

        if (check_bitrix_sessid() && $_REQUEST['action'] == 'send_request') {
            $this->processRequest();
        }

        $this->IncludeComponentTemplate();
    }

    public function processRequest()
    {
        global $APPLICATION;

        $arRequest = $_REQUEST['REQUEST'];

        Loader::includeModule('iblock');

        $iResID = (new CIBlockElement())->Add([
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'NAME' => $arRequest['FIRSTNAME'] . ' ' . $arRequest['SECONDNAME'],
            'DATE_ACTIVE_FROM' => date('d.m.Y H:i:s'),
            'PROPERTY_VALUES' => [
                'PHONE' => $arRequest['PHONE'],
                'EMAIL' => $arRequest['EMAIL'],
                'COMMENT' => $arRequest['COMMENT'],
                'STREET' => $arRequest['STREET'],
                'KORP' => $arRequest['KORP'],
                'HOME' => $arRequest['HOME'],
                'KVARTIRA' => $arRequest['KVARTIRA'],
            ]
        ]);

        if ($iResID > 0) {
            $APPLICATION->RestartBuffer();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => true,
                'message' => $this->arParams['SUCCESS_MESSAGE']
            ]);
            exit;
        } else {
            $APPLICATION->RestartBuffer();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => false,
                'message' => $this->arParams['ERROR_MESSAGE']
            ]);
            exit;
        }
    }

}
