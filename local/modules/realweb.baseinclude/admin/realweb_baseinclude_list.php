<?
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;


$pathProlog = realpath(__DIR__ . "/../");

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($pathProlog . "/prolog.php");

if (!$USER->CanDoOperation('edit_other_settings') && !$USER->CanDoOperation('view_other_settings'))
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

Loader::includeModule("realweb.baseinclude");
$isAdmin = $USER->CanDoOperation('edit_other_settings');
Loc::loadMessages(__FILE__);

$sTableID = "tbl_realweb_base_include";

$aTabs = array(
    array("DIV" => "edit1", "TAB" => Loc::getMessage("MAIN_PARAM"), "TITLE" => Loc::getMessage("MAIN_PARAM_TITLE")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$strTitle = Loc::getMessage("REALWEB.BASEINCLUDE.TITLE_PAGE");
$APPLICATION->SetTitle($strTitle);
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$arOrder = (strtoupper($by) === "ID" ? array($by => $order) : array($by => $order, "ID" => "ASC"));
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array(
    "find",
    "find_id",
    "find_code",
    "find_comment",
    "find_text",
);
$lAdmin->InitFilter($arFilterFields);

$arFilter = array(
    "ID" => ($find != "" && $find_type == "id" ? $find : $find_id),
    "CODE" => ($find != "" && $find_type == "code" ? $find : $find_code),
    "COMMENT" => ($find != "" && $find_type == "comment" ? $find : $find_comment),
    "TEXT" => ($find != "" && $find_type == "text" ? $find : $find_text),
);


/*--------------------------------------------------------------------------------------------------------------------*/

if ($lAdmin->EditAction()) {

    foreach ($_POST['FIELDS'] as $ID => $arFields) {



        if (!$lAdmin->IsUpdated($ID))
            continue;
        $ID = (int)$ID;
        $result = \Realweb\BaseInclude\BaseIncludeTable::update($ID, $arFields);
        if (!$result->isSuccess()) {
            $lAdmin->AddUpdateError(GetMessage("REALWEB_BASEINCLUDE_UPDATE_ERROR") . " [" . join(", ", $result->getErrorMessages()) . "]", $ID);
        }


    }
}

/*--------------------------------------------------------------------------------------------------------------------*/

if ($arID = $lAdmin->GroupAction()) {


    if ($_REQUEST['action_target'] == 'selected') {
        $rsData = \Realweb\BaseInclude\BaseIncludeTable::getList(Array('filter' => $arFilter));
        while ($arRes = $rsData->Fetch())
            $arID[] = $arRes['ID'];
    }


    //delete and modify can:

    foreach ($arID as $ID) {
        switch ($_REQUEST['action']) {
            case "delete":
                if ($USER->isAdmin()) {
                    @set_time_limit(0);
                    $result = \Realweb\BaseInclude\BaseIncludeTable::delete($ID);
                    if (!$result->isSuccess()) {
                        $lAdmin->AddGroupError(GetMessage("REALWEB_BASEINCLUDE_DELETE_ERROR") . " [" . join(", ", $result->getErrorMessages()) . "]", $ID);
                    }
                }
                break;
        }


    }

    if (isset($return_url) && strlen($return_url) > 0)
        LocalRedirect($return_url);
}
/*--------------------------------------------------------------------------------------------------------------------*/

$arHeader = array(
    array(
        "id" => "ID",
        "content" => Loc::getMessage("REALWEB.BASEINCLUDE.ID"),
        "sort" => "ID",
        "title" => Loc::getMessage("REALWEB.BASEINCLUDE.ID"),
        "default" => false,
    ),
    array(
        "id" => "COMMENT",
        "content" => Loc::getMessage("REALWEB.BASEINCLUDE.COMMENT"),
        "sort" => "COMMENT",
        "title" => Loc::getMessage("REALWEB.BASEINCLUDE.COMMENT"),
        "default" => false,
    ),
    array(
        "id" => "CODE",
        "content" => Loc::getMessage("REALWEB.BASEINCLUDE.CODE"),
        "sort" => "CODE",
        "title" => Loc::getMessage("REALWEB.BASEINCLUDE.CODE"),
        "default" => true,
    ),
    array(
        "id" => "TEXT",
        "sort" => "TEXT",
        "content" => Loc::getMessage("REALWEB.BASEINCLUDE.TEXT"),
        "title" => Loc::getMessage("REALWEB.BASEINCLUDE.TEXT"),
        "default" => false,
    )
);
$lAdmin->AddHeaders($arHeader);
//$lAdmin->AddVisibleHeaderColumn('ID');
//$lAdmin->AddVisibleHeaderColumn('CODE');
//$lAdmin->AddVisibleHeaderColumn('COMMENT');

if (strlen($arFilter["ID"]) <= 0) {
    unset($arFilter["ID"]);
}
if (strlen($arFilter["CODE"]) <= 0) {
    unset($arFilter["CODE"]);
}

if (strlen($arFilter["COMMENT"]) <= 0) {
    unset($arFilter["COMMENT"]);
}

if (strlen($arFilter["TEXT"]) <= 0) {
    unset($arFilter["TEXT"]);
}

$rsData = \Realweb\BaseInclude\BaseIncludeTable::getList(array(
    'order' => $arOrder,
    "filter" => $arFilter
));

$rsData = new CAdminResult($rsData, $sTableID);


$arSelectedFields = $lAdmin->GetVisibleHeaderColumns();

$arSelectedFieldsMap = array();
foreach ($arSelectedFields as $field) {
    $arSelectedFieldsMap[$field] = true;
}


$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("REALWEB.BASEINCLUDE.nav")));
while ($arRes = $rsData->NavNext(true, "f_")):

    $row =& $lAdmin->AddRow($f_ID, $arRes);
    $row->link = 'realweb_baseinclude_edit.php?ID=' . $f_ID;
    $row->AddViewField("ID", '<a href="realweb_baseinclude_edit.php?ID=' . $f_ID . '&amp;lang=' . LANG . '" title="' . Loc::getMessage("area_act_edit") . '">' . $f_ID . '</a>');
    $row->AddInputField("CODE", array("size" => 20));
    $row->AddInputField("COMMENT", array("size" => 20));

    /***/
    if (array_key_exists("TEXT", $arSelectedFieldsMap)) {
        $sHTML = '<input type="radio" name="FIELDS[' . $f_ID . '][TEXT_TYPE]" value="text" id="' . $f_ID . 'text"';
        if ($row->arRes["TEXT_TYPE"] != "html")
            $sHTML .= ' checked';
        $sHTML .= '><label for="' . $f_ID . 'DETAILtext">text</label> /';
        $sHTML .= '<input type="radio" name="FIELDS[' . $f_ID . '][TEXT_TYPE]" value="html" id="' . $f_ID . 'html"';
        if ($row->arRes["TEXT_TYPE"] == "html")
            $sHTML .= ' checked';
        $sHTML .= '><label for="' . $f_ID . 'DETAILhtml">html</label><br>';

        $sHTML .= '<textarea rows="10" cols="50" name="FIELDS[' . $f_ID . '][TEXT]">' . htmlspecialcharsbx($row->arRes["TEXT"]) . '</textarea>';
        $row->AddEditField("TEXT", $sHTML);
    }
    /***/
endwhile;

$lAdmin->AddFooter(
    array(
        array("title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()),
        array("counter" => true, "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"),
    )
);
$lAdmin->AddGroupActionTable(Array(
    "delete" => GetMessage("MAIN_ADMIN_LIST_DELETE"),
));

$aContext = array(
    array(
        "TEXT" => GetMessage("MAIN_ADD"),
        "LINK" => "realweb_baseinclude_edit.php?lang=" . LANG,
        "TITLE" => GetMessage("REALWEB.BASEINCLUDE.BTN_NEW"),
        "ICON" => "btn_new",
    ),
);
$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
$oFilter = new CAdminFilter(
    $sTableID . "_filter",
    array(
        "comment" => Loc::getMessage("REALWEB.BASEINCLUDE.COMMENT"),
        "id" => Loc::getMessage("REALWEB.BASEINCLUDE.ID"),
        "code" => Loc::getMessage("REALWEB.BASEINCLUDE.CODE"),
        "text" => Loc::getMessage("REALWEB.BASEINCLUDE.TEXT"),

    )
);
?>

    <form name="find_form" id="find_form" method="get" action="<? echo $APPLICATION->GetCurPage(); ?>">
        <?
        $oFilter->Begin();
        ?>
        <tr>
            <td><?= Loc::getMessage("REALWEB.BASEINCLUDE.CODE") ?>:</td>
            <td>
                <input type="text" name="find_code" size="47" value="<? echo htmlspecialcharsbx($find_code) ?>">
                &nbsp;<?= ShowFilterLogicHelp() ?>
            </td>
        </tr>
        <tr>
            <td><?= Loc::getMessage("REALWEB.BASEINCLUDE.COMMENT") ?>:</td>
            <td>
                <input type="text" name="find_comment" size="47" value="<? echo htmlspecialcharsbx($find_comment) ?>">
                &nbsp;<?= ShowFilterLogicHelp() ?>
            </td>
        </tr>

        <tr>
            <td><?= Loc::getMessage("REALWEB.BASEINCLUDE.ID") ?>:</td>
            <td>
                <input type="text" name="find_id" size="47" value="<? echo htmlspecialcharsbx($find_id) ?>">
                &nbsp;<?= ShowFilterLogicHelp() ?>
            </td>
        </tr>

        <tr>
            <td><?= Loc::getMessage("REALWEB.BASEINCLUDE.TEXT") ?>:</td>
            <td>
                <input type="text" name="find_code" size="47" value="<? echo htmlspecialcharsbx($find_text) ?>">
                &nbsp;<?= ShowFilterLogicHelp() ?>
            </td>
        </tr>
        <?
        $oFilter->Buttons(array("table_id" => $sTableID, "url" => $APPLICATION->GetCurPage(), "form" => "find_form"));
        $oFilter->End();
        ?>
    </form>

<? $lAdmin->DisplayList(); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");