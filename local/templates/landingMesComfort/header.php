<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1200, user-scalable=no">
    <title>МЭС-КОМФОРТ - Ремонт любой сложности от профессионалов!</title>
    <meta name="keywords" content="МЭС-КОМФОРТ"/>
    <meta name="description"
          content="Капитальный подход к любому клиенту. До 15 июля эскизный дизайн-проект в подарок! Бесплатный выезда мастера по Москвe. Гарантия 2 года. +7 (495) 988-31- 99, Москва и Московская область">
    <? $APPLICATION->ShowHead(); ?>
    <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/css/jquery.fancybox.css">
    <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/css/styles.css?v=0.9">

    <script type="text/javascript">
        var __cs = __cs || [];
        __cs.push(["setCsAccount", "VbFewbBHo1saIyZZ4gGIlAPGiettIZkz"]);
    </script>
    <script type="text/javascript" async src="https://app.comagic.ru/static/cs.min.js"></script>

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NPHP59"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<? $APPLICATION->ShowPanel() ?>
<header class="header">
    <div class="wrapper cf">
        <div class="header__logo"><img src="<?= SITE_TEMPLATE_PATH ?>/img/logo.png"
                                       alt="МЭС-КОМФОРТ - Ремонт любой сложности"></div>
        <div class="header__phone"><span class="icon-phone"></span><span class="phone">

                <?
                $APPLICATION->IncludeComponent(
                    "realweb:base.include",
                    "",
                    array(
                        "CODE" => 'PHONE',
                        "COMPONENT_TEMPLATE" => ".default",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false,
                    array(
                        "SHOW_ICON" => 'N',
                    )
                );
                ?>
                </b></span>
            <div><a href="#order-popup" class="b-btn open-popup">оставить заявку</a></div>
        </div>
    </div>
</header>