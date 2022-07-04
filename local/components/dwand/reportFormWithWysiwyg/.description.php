<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("DW_COMPOSITE_NAME"),
	"DESCRIPTION" => GetMessage("DW_COMPOSITE_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "Y",
	"PATH" => array(
		"ID" => "dWand",
		"SORT" => 2000,
		"NAME" => GetMessage("DW_COMPONENTS"),
		"CHILD" => array(
			"ID" => "dWand",
			"NAME" => GetMessage("DW_FORM"),
			"SORT" => 10,
		),
	),
);

?>