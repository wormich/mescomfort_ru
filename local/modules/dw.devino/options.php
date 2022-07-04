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
    $mid = 'dw.devino';
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

    <input type="hidden"
           name="options[template_1]"
           placeholder="template_1"
           value="<?= Option::get($mid, 'template_1', '') ?>">

    <input type="hidden"
           name="options[template_2]"
           placeholder="template_2"
           value="<?= Option::get($mid, 'template_2', '') ?>">

    <tr>
        <td valign="top" width="40%">Название ораганизации (для почтовых шаблонов):</td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[company]"
                   value="<?= Option::get($mid, 'company', 'АО «Мосэнергосбыт»') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">Devino логин:</td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[login]"
                   value="<?= Option::get($mid, 'login', '') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">Devino пароль:</td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[password]"
                   value="<?= Option::get($mid, 'password', '') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">Devino отправитель SMS (В личном кабинете devino):</td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[sms_id]"
                   value="<?= Option::get($mid, 'sms_id', 'MOSENRGSBYT') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">E-mail отправителя уведомления:</td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[email_from]"
                   value="<?= Option::get($mid, 'email_from', '') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">Шаблоны E-mail уведомлений:</td>
        <td valign="top" width="60%">
            <a href="/bitrix/admin/message_edit.php?lang=ru&ID=<?= Option::get($mid, 'template_1', ''); ?>"
               target="_blank">Редактировать шаблон "Ваша заявка принята"</a><br>
            <a href="/bitrix/admin/message_edit.php?lang=ru&ID=<?= Option::get($mid, 'template_2', '') ?>"
               target="_blank">Редактировать шаблон "Не дозвонились"</a>
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">ID Web-Форм (через запятую):</td>
        <td valign="top" width="60%">
            <input type="text"
                   name="options[forms_id]"
                   value="<?= Option::get($mid, 'forms_id', '') ?>">
        </td>
    </tr>

    <tr>
        <td valign="top" width="40%">Шаблон SMS-уведомления:</td>
        <td valign="top" width="60%">
            <textarea name="options[sms_template]" id="" cols="80" rows="10"><?= Option::get($mid, 'sms_template',
                    'Не дозвонились вам по заявке №#RESULT_ID# на сайте mes-elektrik.ru.
Пожалуйста, перезвоните нам по номеру +74995503377 с 8:30 до 20:30.') ?></textarea>
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
