<?php
/**
 * @var string $REQUEST_METHOD
 * @var null|string $Update
 * @var null|string $Apply
 * @var null|string $RestoreDefaults
 * @var string $mid
 *
 * @global $APPLICATION
 * @global $USER
 * @const LANGUAGE_ID
 */

if (empty($mid)) {
    $mid = 'dw.magicbutton';
}

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/options.php');
Loc::loadMessages(__FILE__);

Loader::includeModule($mid);


$sPage = $APPLICATION->GetCurPage() . '?mid=' . urlencode($mid) . '&amp;lang=' . LANGUAGE_ID;

$arTabs = array(
    array(
        'DIV' => 'settings',
        'TAB' => 'Настройки',
        'TITLE' => 'Дополнительные настройки',
        'ICON' => ''
    ),
);

$sOptions = Option::get($mid, 'tables', '');

if ($REQUEST_METHOD == 'POST' && check_bitrix_sessid() && (isset($Update) || isset($Apply) || isset($RestoreDefaults))) {
    if (isset($RestoreDefaults)) {
        Option::delete($mid);
    } elseif (!empty($options)) {
        foreach ($options as $sOptionCode => $sValue) {
            Option::set($mid, $sOptionCode, $sValue);
        }

        if (!empty($_FILES['btn_image']['name'])) {
            $iFile = CFile::SaveFile($_FILES['btn_image'], "dw.magicbutton");

            Option::set($mid, 'btn_image', $iFile);
        }

        LocalRedirect($sPage);
    }
}

$obTabControl = new CAdminTabControl('tabControl', $arTabs);
$obTabControl->Begin();
?>

<form method="post" action="<?= $sPage; ?>" enctype="multipart/form-data">
    <? $obTabControl->BeginNextTab(); ?>
    <tr>
        <td valign="top" width="40%">ID инфоблока (слайдер):
            <br><small><a href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?= Option::get($mid, 'slider_iblock_id', '') ?>&type=dw_pes_landing&lang=ru&find_section_section=0" target="_blank">Перейти</a></small>
        </td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[slider_iblock_id]"
                   value="<?= Option::get($mid, 'slider_iblock_id', '') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">ID инфоблока (услуги):<br><small><a href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?= Option::get($mid, 'service_iblock_id', '') ?>&type=dw_pes_landing&lang=ru&find_section_section=0" target="_blank">Перейти</a></td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[service_iblock_id]"
                   value="<?= Option::get($mid, 'service_iblock_id', '') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">ID инфоблока (Вопрос-ответ):<br><small><a href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?= Option::get($mid, 'faq_iblock_id', '') ?>&type=dw_pes_landing&lang=ru&find_section_section=0" target="_blank">Перейти</a></td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[faq_iblock_id]"
                   value="<?= Option::get($mid, 'faq_iblock_id', '') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">ID инфоблока (результаты формы заказа услуг):<br><small><a href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?= Option::get($mid, 'form_iblock_id', '') ?>&type=dw_pes_landing&lang=ru&find_section_section=0" target="_blank">Перейти</a></td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[form_iblock_id]"
                   value="<?= Option::get($mid, 'form_iblock_id', '') ?>">
        </td>
    </tr>

    <? $obTabControl->Buttons(); ?>

    <input type="submit" name="Update" value=" <?= Loc::getMessage('MAIN_SAVE'); ?>"
           title="<?= Loc::getMessage('MAIN_OPT_SAVE_TITLE'); ?>">
    <input type="submit" name="Apply" value="<?= Loc::getMessage('MAIN_OPT_APPLY'); ?>"
           title="<?= Loc::getMessage('MAIN_OPT_APPLY_TITLE'); ?>">
    <? if (strlen($_REQUEST['back_url_settings']) > 0): ?>
        <input type="button" name="Cancel" value="<?= Loc::getMessage('MAIN_OPT_CANCEL'); ?>"
               title="<?= Loc::getMessage('MAIN_OPT_CANCEL_TITLE'); ?>"
               onclick="window.location='<?= htmlspecialcharsbx(CUtil::addslashes($_REQUEST['back_url_settings'])); ?>'">
        <input type="hidden" name="back_url_settings" value="<?= htmlspecialcharsbx($_REQUEST['back_url_settings']) ?>">
    <? endif; ?>
    <input type="submit" name="RestoreDefaults" title="<?= Loc::getMessage('MAIN_HINT_RESTORE_DEFAULTS'); ?>"
           OnClick="return confirm('<?= AddSlashes(Loc::getMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING')); ?>')"
           value="<?= Loc::getMessage('MAIN_RESTORE_DEFAULTS'); ?>">
    <?= bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>">

    <? $obTabControl->End(); ?>
</form>
