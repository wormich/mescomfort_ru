<?
/** @var RealwebBaseInclude $this */
/** @var RealwebBaseInclude self */
/** @var array $arParams */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Realweb\BaseInclude\BaseIncludeTable;


$arParams["EDIT_TEMPLATE"] = strlen($arParams["EDIT_TEMPLATE"]) > 0 ? $arParams["EDIT_TEMPLATE"] : $arParams["AREA_FILE_SHOW"] . "_inc.php";
$arParams['FORCE_INCLUDE'] = (isset($arParams['FORCE_INCLUDE']) ? $arParams['FORCE_INCLUDE'] : "N");

if (!CModule::IncludeModule('realweb.baseinclude')) {
    return;
}

if ($arParams['GET_ALL'] == "Y" && RealwebBaseInclude::$info === false) {
    RealwebBaseInclude::$info = array_column(BaseIncludeTable::getList()->fetchAll(), null, "CODE");
}


if ($arParams['CODE']) {
    $res = BaseIncludeTable::getAll();
    while ($row = $res->fetch()) {
        RealwebBaseInclude::$info[$row['CODE']] = $row;
    }
}

$bRowFound = false;

if (strlen($arParams['CODE']) > 0) {
    global $MAIN_INCLUDE;
    if (is_array($MAIN_INCLUDE) && isset($MAIN_INCLUDE[$arParams['CODE']])) {
        $row = $MAIN_INCLUDE[$arParams['CODE']];
        $bRowFound = true;
    } else {
        //Пробуем найти по CODE
        $res = BaseIncludeTable::getByCode($arParams['CODE']);
        if ($row = $res->fetch()) {
            $bRowFound = true;
        }
    }
}
$IS_EDIT = 'N';
if ($APPLICATION->GetShowIncludeAreas()) {
    //need fm_lpa for every .php file, even with no php code inside
    $bPhpFile = (!$GLOBALS["USER"]->CanDoOperation('edit_php') && in_array(GetFileExtension($sFileName), GetScriptFileExt()));

    $bCanEdit = $USER->CanDoFileOperation('fm_edit_existent_file', array(SITE_ID, $sFilePath . $sFileName)) && (!$bPhpFile || $GLOBALS["USER"]->CanDoFileOperation('fm_lpa', array(SITE_ID, $sFilePath . $sFileName)));
    $bCanAdd = $USER->CanDoFileOperation('fm_create_new_file', array(SITE_ID, $sFilePathTMP . $sFileName)) && (!$bPhpFile || $GLOBALS["USER"]->CanDoFileOperation('fm_lpa', array(SITE_ID, $sFilePathTMP . $sFileName)));

    if ($bCanEdit || $bCanAdd) {
        $editor = '&site=' . SITE_ID . '&back_url=' . urlencode($_SERVER['REQUEST_URI']) . '&templateID=' . urlencode(SITE_TEMPLATE_ID);

        if ($bRowFound) {
            if ($bCanEdit) {
                $arMenu = array();
                $arIcons = array(
                    array(
                        "URL" => 'javascript:' . $APPLICATION->GetPopupLink(
                                array(
                                    'URL' => "/bitrix/admin/realweb_baseinclude_public_edit.php?lang=" . LANGUAGE_ID . "&from=main.include&template=" . urlencode($arParams["EDIT_TEMPLATE"]) . "&CODE=" . urlencode($arParams['CODE']) . $editor,
                                    "PARAMS" => array(
                                        'width' => 770,
                                        'height' => 570,
                                        'resize' => true
                                    )
                                )
                            ),
                        "DEFAULT" => $APPLICATION->GetPublicShowMode() != 'configure',
                        "ICON" => "bx-context-toolbar-edit-icon",
                        "TITLE" => (strlen($arParams['EDIT_BUTTON']) > 0 ? $arParams['EDIT_BUTTON'] : GetMessage("main_comp_include_edit") . (strlen($arParams['CODE']) == 0 ? '' : ' (' . $arParams['CODE'] . ')')),
                        "ALT" => GetMessage("MAIN_INCLUDE_AREA_EDIT_" . $arParams["AREA_FILE_SHOW"]),
                        "MENU" => $arMenu,
                    ),
                );
            }
        } elseif ($bCanAdd) {
            $arMenu = array();
            $arIcons = array(
                array(
                    "URL" => 'javascript:' . $APPLICATION->GetPopupLink(
                            array(
                                'URL' => "/bitrix/admin/realweb_baseinclude_public_edit.php?lang=" . LANGUAGE_ID . "&from=main.include&CODE=" . urlencode($arParams['CODE']) . "&new=Y&template=" . urlencode($arParams["EDIT_TEMPLATE"]) . $editor,
                                "PARAMS" => array(
                                    'width' => 770,
                                    'height' => 570,
                                    'resize' => true,
                                    "dialog_type" => 'EDITOR',
                                    "min_width" => 700,
                                    "min_height" => 400
                                )
                            )
                        ),
                    "DEFAULT" => $APPLICATION->GetPublicShowMode() != 'configure',
                    "ICON" => "bx-context-toolbar-create-icon",
                    "TITLE" => (strlen($arParams['ADD_BUTTON']) > 0 ? $arParams['ADD_BUTTON'] : GetMessage("main_comp_include_add1") . (strlen($arParams['CODE']) == 0 ? '' : ' (' . $arParams['CODE'] . ')')),
                    "ALT" => GetMessage("MAIN_INCLUDE_AREA_ADD_" . $arParams["AREA_FILE_SHOW"]),
                    "MENU" => $arMenu,
                ),
            );
        }

        if (is_array($arIcons) && count($arIcons) > 0) {
            $this->AddIncludeAreaIcons($arIcons);
        }
        $IS_EDIT = 'Y';
    }
}

if ($bRowFound) {
    $arResult = $row;
    $arResult['IS_EDIT'] = $IS_EDIT;
    $this->IncludeComponentTemplate();
} else {
    if ($arParams['FORCE_INCLUDE'] == 'Y') {
        $arResult = array(
            'ID' => 0,
            'CODE' => $arParams['CODE'],
            'TEXT' => "",
        );

        $this->IncludeComponentTemplate();
    }
}
?>