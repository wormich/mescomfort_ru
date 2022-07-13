<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
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

$FORM_ID = 4;
$arHashRND = [];
$arHashes = [];
CModule::IncludeModule("form");
CModule::IncludeModule("iblock");
if (!$_REQUEST['username'] || $_REQUEST['age'] || $_REQUEST['last_name']) {

    echo json_encode(['type' => 'error', 'data' => 'Ошибка отправки, возможно установлен бот рассылки!'], JSON_UNESCAPED_UNICODE);
    exit;
}
$returnFromCI = CIBlockElement::GetList([], ['IBLOCK_ID' => 29, 'NAME' => trim($_REQUEST['hash']), 'ACTIVE' => 'Y', 'PERMISSIONS_BY' => 1, 'ACTIVE_DATE' => 'Y']);
while ($arControlHashRND = $returnFromCI->Fetch()) {
    $arHashRND[] = $arControlHashRND;
    $arHashes[] = $arControlHashRND['NAME'];
}

if (!in_array($_REQUEST['username'], $arHashes)) {
    echo json_encode(['type' => 'error', 'data' => 'Ошибка отправки, возможно установлен бот рассылки!'], JSON_UNESCAPED_UNICODE);
    exit;
}
$arValues = array(
    "form_text_34" => $_REQUEST['name'],
    "form_text_35" => $_REQUEST['phone'],
    "form_textarea_36" => $_REQUEST['msg'],
    "form_text_44" => $_REQUEST['form_text_user_ip']
);

if (!count($arHashRND) > 0) {
    echo json_encode(['type' => 'error', 'data' => 'Ошибка отправки, возможно установлен бот рассылки!'], JSON_UNESCAPED_UNICODE);
} elseif ($RESULT_ID = CFormResult::Add($FORM_ID, $arValues)) {
    echo json_encode(['type' => 'ok'], JSON_UNESCAPED_UNICODE);
}