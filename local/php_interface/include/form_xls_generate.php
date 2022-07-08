<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/xlsxwriter.class.php';

use Bitrix\Main\Loader;
use Bitrix\Main\Application;

Loader::includeModule('form');

$request = Application::getInstance()->getContext()->getRequest();
$WEB_FORM_ID = $request->get('WEB_FORM_ID');

$filename = "form_result_list_fixed_2.xlsx";

header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
//header('Content-Transfer-Encoding: binary');
//header('Cache-Control: must-revalidate');
//header('Pragma: public');

$sTableID = "tbl_form_result_list_".md5($request->get('WEB_FORM_ID'));
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arForm = CForm::GetByID_admin($WEB_FORM_ID);
$WEB_FORM_NAME = $arForm["SID"];

$aOptions = CUserOptions::GetOption("list", $sTableID, array());

$headers = [
    ["id" => "ID", "content" => "ID", "sort" => "s_id", "default" => true],
    ["id" => "DATE_CREATE", "content" => 'Дата создания', "sort" => "s_date_create", "default" => false],
    ["id" => "STATUS", "content" => 'Статус', "sort" => "status", "default" => true],
//    ["id" => "USER_ID", "content" => 'Изменено', "sort" => "s_user_id", "default" => true],
];

$rsFields = CFormField::GetList($WEB_FORM_ID, "ALL", ($v1 = "s_c_sort"), ($v2 = "asc"), ['IN_RESULTS_TABLE' => 'Y', 'ACTIVE' => 'Y'], $v3);

while ($fields = $rsFields->Fetch()) {
    if (strlen($fields['RESULTS_TABLE_TITLE'])>0)
        $r=$fields['RESULTS_TABLE_TITLE'];
    elseif (strlen($fields['TITLE'])>0)
        $r=$fields['TITLE'];
    else
        $r=$fields['SID'];

    $headers[] = ["id"=>$fields['SID'], "content"=>strip_tags($r), "default"=>true];
}

$aColsTmp = explode(",", $aOptions["columns"]);
$aCols = array();
$userColumns = array();
foreach ($aColsTmp as $col)
{
    $col = trim($col);
    if ($col <> "")
    {
        $aCols[] = $col;
        $userColumns[$col] = true;
    }
}

$bEmptyCols = empty($aCols);
$userVisibleColumns = array();

$arVisible = [];
foreach ($headers as $param)
{
    $param["__sort"] = -1;
    if (
        (isset($_SESSION['SHALL']) && $_SESSION['SHALL'])
        || ($bEmptyCols && $param["default"] == true)
        || isset($userColumns[$param["id"]])
    )
    {
        $arVisibleColumns[] = $param["id"];
        $arVisible[] = $param['id'];
        $userVisibleColumns[$param["id"]] = true;
    }
}
unset($userColumns);

$F_RIGHT = CForm::GetPermission($WEB_FORM_ID);

if ($F_RIGHT >= 20)
{
    $arFilterFields = Array(
        "find_id",
        "find_id_exact_match",
        "find_status",
        "find_status_id",
        "find_status_id_exact_match",
        "find_timestamp_1",
        "find_timestamp_2",
        "find_date_create_1",
        "find_date_create_2",
        "find_registered",
        "find_user_auth",
        "find_user_id",
        "find_user_id_exact_match",
        "find_guest_id",
        "find_guest_id_exact_match",
        "find_session_id",
        "find_session_id_exact_match"
    );
    if (is_array($arFormCrmLink))
        $arFilterFields[] = "find_sent_to_crm";
}
else
    $arFilterFields = array(
        "find_id",
        "find_id_exact_match",
        "find_timestamp_1",
        "find_timestamp_2",
        "find_date_create_1",
        "find_date_create_2",
    );


$z = CFormField::GetFilterList($WEB_FORM_ID, array("ACTIVE" => "Y"));

while ($zr = $z->Fetch())
{
    $FID = $WEB_FORM_NAME."_".$zr["SID"]."_".$zr["PARAMETER_NAME"]."_".$zr["FILTER_TYPE"];
    $zr["FID"] = $FID;

    $arrFORM_FILTER[$zr["SID"]][] = $zr;
    $fname = "find_".$FID;
    if ($zr["FILTER_TYPE"]=="date" || $zr["FILTER_TYPE"]=="integer")
    {
        $arFilterFields[] = $fname."_1";
        $arFilterFields[] = $fname."_2";
        $arFilterFields[] = $fname."_0";
    }
    elseif ($zr["FILTER_TYPE"]=="text")
    {
        $arFilterFields[] = $fname;
        $arFilterFields[] = $fname."_exact_match";
    }
    else $arFilterFields[] = $fname;
}

$sess_filter = "FORM_RESULT_LIST_".$WEB_FORM_NAME;

InitBVar($find_id_exact_match);
InitBVar($find_status_id_exact_match);
InitBVar($find_user_id_exact_match);
InitBVar($find_guest_id_exact_match);
InitBVar($find_session_id_exact_match);

if (CheckFilter())
{
    if ($F_RIGHT >= 20)
    {
        $arFilter = Array(
            "ID"						=> $find_id,
            "ID_EXACT_MATCH"			=> $find_id_exact_match,
            "STATUS"					=> $find_status,
            "STATUS_ID"					=> $find_status_id,
            "STATUS_ID_EXACT_MATCH"		=> $find_status_id_exact_match,
            "TIMESTAMP_1"				=> $find_timestamp_1,
            "TIMESTAMP_2"				=> $find_timestamp_2,
            "DATE_CREATE_1"				=> $find_date_create_1,
            "DATE_CREATE_2"				=> $find_date_create_2,
            "REGISTERED"				=> $find_registered,
            "USER_AUTH"					=> $find_user_auth,
            "USER_ID"					=> $find_user_id,
            "USER_ID_EXACT_MATCH"		=> $find_user_id_exact_match,
            "GUEST_ID"					=> $find_guest_id,
            "GUEST_ID_EXACT_MATCH"		=> $find_guest_id_exact_match,
            "SESSION_ID"				=> $find_session_id,
            "SESSION_ID_EXACT_MATCH"	=> $find_session_id_exact_match
        );
        if (is_array($arFormCrmLink))
            $arFilter["SENT_TO_CRM"] = $find_sent_to_crm;

    }
    else
        $arFilter = Array(
            "ID"						=> $find_id,
            "ID_EXACT_MATCH"			=> $find_id_exact_match,
            "TIMESTAMP_1"				=> $find_timestamp_1,
            "TIMESTAMP_2"				=> $find_timestamp_2,
            "DATE_CREATE_1"				=> $find_date_create_1,
            "DATE_CREATE_2"				=> $find_date_create_2,
        );


    if (is_array($arrFORM_FILTER))
    {
        foreach ($arrFORM_FILTER as $arrF)
        {
            foreach ($arrF as $arr)
            {
                if ($arr["FILTER_TYPE"]=="date" || $arr["FILTER_TYPE"]=="integer")
                {
                    $arFilter[$arr["FID"]."_1"] = ${"find_".$arr["FID"]."_1"};
                    $arFilter[$arr["FID"]."_2"] = ${"find_".$arr["FID"]."_2"};
                    $arFilter[$arr["FID"]."_0"] = ${"find_".$arr["FID"]."_0"};
                }
                elseif ($arr["FILTER_TYPE"]=="text")
                {
                    $arFilter[$arr["FID"]] = ${"find_".$arr["FID"]};
                    $exact_match = (${"find_".$arr["FID"]."_exact_match"}=="Y") ? "Y" : "N";
                    $arFilter[$arr["FID"]."_exact_match"] = $exact_match;
                }
                else $arFilter[$arr["FID"]] = ${"find_".$arr["FID"]};
            }
        }
    }
}

$result = CFormResult::GetList($WEB_FORM_ID, $by, $order, $arFilter, $is_filtered);
$result = new CAdminResult($result, $sTableID);

$writer = new XLSXWriter();
$excelHeader = [];
$excelTitle = [];
$excelWidth = [];

foreach ($headers as $header) {
    if (in_array($header['id'], $arVisibleColumns)) {

        $excelHeader[] = 'string';
        $excelTitle[] = $header['content'];
        $excelWidth[] = 20;
    }
}

$writer->writeSheetHeader('Sheet1', $excelHeader, $col_options = ['suppress_row'=>true, 'widths' => $excelWidth]);
$writer->writeSheetRow('Sheet1', $excelTitle, [
    'halign' => 'left',
    'border' => 'left,right,top,bottom',
    'wrap_text' => true,
    'font-style' => 'bold'
]);

while($arRes = $result->NavNext(true, "f_")) {
    $row =& $lAdmin->AddRow($f_ID, $arRes);

    $row->AddSelectField("STATUS_ID",$arValues);

    $arFilter = array("RESULT_ID" => $f_ID);
    $arrAnswers = array();
    $arrColumns = array();
    $arrAnswersSID = array();

    CForm::GetResultAnswerArray($WEB_FORM_ID, $arrColumns, $arrAnswers, $arrAnswersSID, $arFilter);
    if (!is_array($arrAnswers[$f_ID]))
        $arrAnswers[$f_ID] = array();

    $answer = [];
    $arrAnswers = current($arrAnswers);

    if (in_array('STATUS', $arVisible)) {
        $propertyKey = array_search('STATUS', $arVisible);
        $answer[$propertyKey] = $arRes['STATUS_TITLE'];
    }

    if (in_array('DATE_CREATE', $arVisible)) {
        $propertyKey = array_search('DATE_CREATE', $arVisible);
        $answer[$propertyKey] = $arRes['DATE_CREATE'];
    }

    if (in_array('ID', $arVisible)) {
        $propertyKey = array_search('ID', $arVisible);
        // $answer[$propertyKey] = $arRes['ID'];
        $answer[$propertyKey] = 111;
    }

    foreach ($arrAnswers as $arAnswer) {
        $answ = current($arAnswer);
        if (in_array($answ['SID'], $arVisible)) {
            $propertyKey = array_search($answ['SID'], $arVisible);

            $answer[$propertyKey] = ($answ['USER_TEXT']) ? $answ['USER_TEXT'] : "";
        }
    }
    ksort($answer);

    $writer->writeSheetRow('Sheet1', $answer, [
        'halign' => 'left',
        'border' => 'left,right,top,bottom',
        'wrap_text' => true
    ]);

}

$writer->writeToStdOut();

function CheckFilter()
{
    global $strError, $MESS, $arrFORM_FILTER;
    global $find_date_create_1, $find_date_create_2, $lAdmin;
    $str = "";

    CheckFilterDates($find_date_create_1, $find_date_create_2, $date1_wrong, $date2_wrong, $date2_less);
    if ($date1_wrong=="Y") $str.= GetMessage("FORM_WRONG_DATE_CREATE_FROM")."<br>";
    if ($date2_wrong=="Y") $str.= GetMessage("FORM_WRONG_DATE_CREATE_TO")."<br>";
    if ($date2_less=="Y") $str.= GetMessage("FORM_FROM_TILL_DATE_CREATE")."<br>";

    if (is_array($arrFORM_FILTER))
    {
        foreach ($arrFORM_FILTER as $arrF)
        {
            if (is_array($arrF))
            {
                foreach ($arrF as $arr)
                {
                    $title = ($arr["TITLE_TYPE"]=="html") ? strip_tags(htmlspecialcharsback($arr["TITLE"])) : $arr["TITLE"];
                    if ($arr["FILTER_TYPE"]=="date")
                    {
                        $date1 = $_GET["find_".$arr["FID"]."_1"];
                        $date2 = $_GET["find_".$arr["FID"]."_2"];
                        CheckFilterDates($date1, $date2, $date1_wrong, $date2_wrong, $date2_less);
                        if ($date1_wrong=="Y")
                            $str .= str_replace("#TITLE#", $title, GetMessage("FORM_WRONG_DATE1"))."<br>";
                        if ($date2_wrong=="Y")
                            $str .= str_replace("#TITLE#", $title, GetMessage("FORM_WRONG_DATE2"))."<br>";
                        if ($date2_less=="Y")
                            $str .= str_replace("#TITLE#", $title, GetMessage("FORM_DATE2_LESS"))."<br>";
                    }
                    if ($arr["FILTER_TYPE"]=="integer")
                    {
                        $int1 = intval($_GET["find_".$arr["FID"]."_1"]);
                        $int2 = intval($_GET["find_".$arr["FID"]."_2"]);
                        if ($int1>0 && $int2>0 && $int2<$int1)
                        {
                            $str .= str_replace("#TITLE#", $title, GetMessage("FORM_INT2_LESS"))."<br>";
                        }
                    }
                }
            }
        }
    }
    $strError .= $str;
    if (strlen($str)>0)
    {
        $lAdmin->AddFilterError($str);
        return false;
    }
    else return true;
}