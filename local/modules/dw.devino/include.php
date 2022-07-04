<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'dw.devino',
    array(
        'DwDevino\Handlers' => 'handlers/Handlers.php',
        'DwDevino\Helpers' => 'helpers/Helpers.php',
    )
);
