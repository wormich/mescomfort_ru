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
//    "IBLOCK_ID"      => 28,
//    "PROPERTY_VALUES"=> $PROP,
//    "NAME"           => $_REQUEST['name'],
//    "ACTIVE"         => "N",            // активен
//    "DETAIL_TEXT"    =>  $_REQUEST['msg'],
//);
//
//if($PRODUCT_ID = $el->Add($arLoadProductArray))
//    echo json_encode(['type'=>'ok'], JSON_UNESCAPED_UNICODE);
//else
//    echo json_encode(['type'=>'Error', 'data'=>'Ошибка обработки данных'], JSON_UNESCAPED_UNICODE);
//print_r($_REQUEST);

$FORM_ID=3;
CModule::IncludeModule("form");

$arValues = array (
    "form_text_29"                 => $_REQUEST['name'],
    "form_text_30"                 => $_REQUEST['phone'],
    "form_textarea_43"             => $_REQUEST['msg']
);
if ($RESULT_ID = CFormResult::Add($FORM_ID, $arValues))
{
    echo json_encode(['type'=>'ok'], JSON_UNESCAPED_UNICODE);
}else{
    echo json_encode(['type'=>'Error', 'data'=>'Ошибка обработки данных'], JSON_UNESCAPED_UNICODE);
}
