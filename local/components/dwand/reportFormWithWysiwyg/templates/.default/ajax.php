<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
if(Loader::includeModule("iblock")){

    $el = new CIBlockElement;

    $PROP = $_REQUEST;

    $arLoadProductArray = [
        "MODIFIED_BY" => 1,
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => 26,
        "PROPERTY_VALUES" => $PROP,
        "NAME" => $_REQUEST['NAME'],
        "ACTIVE" => "Y",
        "DETAIL_TEXT" => $_REQUEST['DETAIL_TEXT']
    ];

    if ($PRODUCT_ID = $el->Add($arLoadProductArray))
        echo "success";
    else
        echo "Error: " . $el->LAST_ERROR;

}
?>