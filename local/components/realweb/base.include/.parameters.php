<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$arComponentParameters = array(
	"GROUPS" => array(
		"PARAMS" => array(
			"NAME" => GetMessage("MAIN_INCLUDE_PARAMS"),
		),
	),
	
	"PARAMETERS" => array(
	),
);

$arComponentParameters["PARAMETERS"]["GET_ALL"] = array(
		"NAME" => GetMessage("MAIN_INCLUDE_CODE"), 
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"PARENT" => "PARAMS",
	);
$arComponentParameters["PARAMETERS"]["CODE"] = array(
    "NAME" => GetMessage("MAIN_INCLUDE_CODE"),
    "TYPE" => "STRING",
    "DEFAULT" => "",
    "PARENT" => "PARAMS",
);



?>