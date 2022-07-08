<?php
global  $USER;
use \Bitrix\Main\Mail\Event;
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("MyClass", "OnAfterIBlockElementAddHandler"));
$classPath = '/local/php_interface/classes/';
CModule::AddAutoloadClasses(
  '', array(
    '\Local\Helpers\Constants' => $classPath . "helpers/Constants.php",
    '\Local\Entities\InterraoWebformQuizTable' => $classPath . "Entities/InterraoWebformQuizTable.php",
    '\Local\Entities\FormAnswerTable' => $classPath . "Entities/FormAnswerTable.php",
    '\Local\Entities\FormFieldTable' => $classPath . "Entities/FormFieldTable.php",
    '\Local\Entities\FormResultAnswerTable' => $classPath . "Entities/FormResultAnswerTable.php",
    '\Local\Form\FormQuestionsList' => $classPath . "form/FormQuestionsList.php",
    '\Local\Iblock\IblockServicesList' => $classPath . "iblock/IblockServicesList.php",
    '\Local\Form\QrGenerate' => $classPath . "form/QrGenerate.php",
  )
);

include_once 'handlers.php';
include_once 'helpers.php';
include_once 'functions.php';
class MyClass
{
    // создаем обработчик события "OnAfterIBlockElementAdd"
    function OnAfterIBlockElementAddHandler(&$arFields)
    {

        AddMessage2log($arFields);
        switch ($arFields['IBLOCK_ID']){
            case 27:
                $filter=["GROUPS_ID "=>[7]];
                $rsUsers = CUser::GetList([], [], $filter, ["FIELDS"=>['ID', 'NAME', 'EMAIL']]);
                while ($arUser = $rsUsers->Fetch()) {
                    $fildsMSG = [
                                "NAME" => $arFields['NAME'],
                                "MAIL" => $arFields['PROPERTY_VALUES']['email'],
                                "PHONE" =>$arFields['PROPERTY_VALUES']['phone'],
                                "CITY" => $arFields['PROPERTY_VALUES']['city'],
                                "SERVICE" => $arFields['PROPERTY_VALUES']['service'],
                                "ID" =>  $arFields['ID'],
                                "EMAIL_KC"=> $arUser['EMAIL']
                            ];
                    addMessage2log($fildsMSG);
                    $event = new CEvent;
                    $event->Send('Statement', 's1', $fildsMSG, "N");
                }
                break;

            case 28:
                $filter=["GROUPS_ID "=>[7]];
                $rsUsers = CUser::GetList([], [], $filter, ["FIELDS"=>['ID', 'NAME', 'EMAIL']]);
                while ($arUser = $rsUsers->Fetch()) {
                    $fildsMSG = [
                        "NAME" => $arFields['NAME'],
                        "PHONE" =>$arFields['PROPERTY_VALUES']['phone'],
                        "MESSAGE" => $arFields['DETAIL_TEXT'],
                        "ID" =>  $arFields['ID'],
                        "EMAIL_KC"=> $arUser['EMAIL']
                    ];
                    addMessage2log($fildsMSG);
                    $event = new CEvent;
                    $event->Send('Reports', 's1', $fildsMSG, "N");
                }
                break;
        }
    }

}

if (\Bitrix\Main\Loader::includeModule('dw.devino')) {
    AddEventHandler('main', 'OnAdminListDisplay', '\DwDevino\Handlers::createElementMenuItems');
    AddEventHandler('form', 'onAfterResultAdd', '\DwDevino\Handlers::sendMessageOnAfterResultAdd');
}

function getControlStringHash(){
    CModule::IncludeModule("iblock");
    $returnFromCI = CIBlockElement::GetList(
        ['RAND' => 'rand'],
        ['IBLOCK_ID' => 29, 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y', 'ACTIVE_DATE' => 'Y', "PERMISSIONS_BY" => 1],
        false,
        ['nTopCount' => 1],
        ['ID','NAME']
    );
    if ($arControlHashRND = $returnFromCI->Fetch())
    {
        return $arControlHashRND['NAME'];
    }
}
?>