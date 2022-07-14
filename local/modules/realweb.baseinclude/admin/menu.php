<?
if (!CModule::IncludeModule('realweb.baseinclude')) {
    return;
}
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
$aMenu[] = array( 
    "parent_menu" => "global_menu_content",
    "section" => "realweb_main_include",
    "sort" => 700,
    "text" => Loc::getMessage("REALWEB.BASEINCLUDE.SEPARATOR"),
    "title" => Loc::getMessage("REALWEB.BASEINCLUDE.SETTINGS_TITLE"),
    "icon" => "",
    "page_icon" => "",
    "items_id" => "menu_realweb_main_include",
    "module_id" => "realweb.baseinclude",
    "url" => "realweb_baseinclude_list.php?lang=".LANGUAGE_ID,
    "more_url" => array(
        "realweb_baseinclude_list.php",
        "realweb_baseinclude_edit.php",
    ),
);
return $aMenu;
?>
