<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//use Bitrix\Main\Loader;
//Loader::IncludeModule('iblock');
//
//$el = new CIBlockElement;
//
//$PROP=$_REQUEST;
//
//$arLoadProductArray = Array(
//    "MODIFIED_BY"    => 1, // элемент изменен текущим пользователем
//    "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
//    "IBLOCK_ID"      => 27,
//    "PROPERTY_VALUES"=> $PROP,
//    "NAME"           => $_REQUEST['name'],
//    "ACTIVE"         => "Y",            // активен
//);
//
//if($PRODUCT_ID = $el->Add($arLoadProductArray))
//    echo json_encode(['type'=>'ok'], JSON_UNESCAPED_UNICODE);
//else
//    echo json_encode(['type'=>'Error', 'data'=>'Ошибка обработки данных'], JSON_UNESCAPED_UNICODE);
////print_r($_REQUEST);
///
///


$FORM_ID=3;
$arHashRND = [];
CModule::IncludeModule("form");
CModule::IncludeModule("iblock");
if (!$_REQUEST['hash']) {
    echo json_encode(['type'=>'error', 'data'=>'Ошибка отправки, возможно установлен бот рассылки!'], JSON_UNESCAPED_UNICODE);
    exit;
}
$returnFromCI = CIBlockElement::GetList([],['IBLOCK_ID' => 29,'NAME' => trim($_REQUEST['hash']), 'ACTIVE' => 'Y',  'PERMISSIONS_BY'=>1, 'ACTIVE_DATE' => 'Y']);
while ($arControlHashRND = $returnFromCI->Fetch())
{
    $arHashRND[]=$arControlHashRND;
}
$arValues = array (
    "form_text_29"                 => $_REQUEST['name'],
    "form_text_30"                 => $_REQUEST['phone'],
    "form_text_31"             => $_REQUEST['service'],
    "form_text_32"             => $_REQUEST['email'],
    "form_text_33"             => $_REQUEST['city'],
);
if (!count($arHashRND)>0){
    echo json_encode(['type'=>'error', 'data'=>'Ошибка отправки, возможно установлен бот рассылки!'], JSON_UNESCAPED_UNICODE);
}
elseif ($RESULT_ID = CFormResult::Add($FORM_ID, $arValues)) {
    echo json_encode(['type'=>'ok'], JSON_UNESCAPED_UNICODE);
}

//$rsQuestions = CFormField::GetList(
//    $FORM_ID, "ALL",[],[],[],false
//);
//while ($arQuestion = $rsQuestions->Fetch())
//{
//    echo "<pre>"; print_r($arQuestion); echo "</pre>";
//}
?>