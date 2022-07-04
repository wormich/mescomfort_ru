<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?>
<?$APPLICATION->IncludeComponent(
	"dwand:reportFormWithWysiwyg",
	".default",
	Array(
		"ADD_SECTIONS_CHAIN" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_ID" => "26",
		"IBLOCK_TYPE" => "Lists",
		"SNAP_ELEMENT_CODE" => "SNAP_ELEMENT_ID",
        "SNAP_ELEMENT_ID" => 15528,
		"STARS" => "STARS"
	)
);?>
   <?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>