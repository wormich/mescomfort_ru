<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
header("X-Frame-Options: SAMEORIGIN");
IncludeTemplateLangFile(__FILE__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <? $APPLICATION->ShowHead(); ?>

    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="<?= SITE_TEMPLATE_PATH ?>/css/new/style.css?v=4" type="text/css" data-template-style="true"
          rel="stylesheet"/>
    <link href="<?= SITE_TEMPLATE_PATH ?>/css/new/resize.css?v=4" type="text/css" data-template-style="true"
          rel="stylesheet"/>
    <link href="<?= SITE_TEMPLATE_PATH ?>/css/new/modal.css?v=5" type="text/css" data-template-style="true"
          rel="stylesheet"/>
    <link href="<?= SITE_TEMPLATE_PATH ?>/css/new/modal-resize.css?v=7" type="text/css" data-template-style="true"
          rel="stylesheet"/>

    <script src="<?= SITE_TEMPLATE_PATH ?>/js/new/jquery-v1.12.js" type="text/javascript"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?>/assets/jquery-ui/jquery-ui.min-v1.12.js" type="text/javascript"></script>

    <script src="<?= SITE_TEMPLATE_PATH ?>/assets/fancybox/jquery.fancybox.js" type="text/javascript"></script>
    <link href="<?= SITE_TEMPLATE_PATH ?>/assets/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">

    <script src="<?= SITE_TEMPLATE_PATH ?>/assets/validate/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?>/assets/maskedinput/jquery.maskedinput.min.js"
            type="text/javascript"></script>

    <script src="<?= SITE_TEMPLATE_PATH ?>/assets/fancybox/jquery.fancybox.js" type="text/javascript"></script>
    <link href="<?= SITE_TEMPLATE_PATH ?>/assets/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">

    <link href="<?= SITE_TEMPLATE_PATH ?>/assets/owlslider/owl.carousel.css" rel="stylesheet" type="text/css">
    <script src="<?= SITE_TEMPLATE_PATH ?>/assets/owlslider/owl.carousel.js" type="text/javascript"></script>

    <script src="<?= SITE_TEMPLATE_PATH ?>/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.scrollTo.js" type="text/javascript"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?>/js/new/scripts.js?v2" type="text/javascript"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?>/js/new/data.js" type="text/javascript"></script>

    <script src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.catalog-filter.js" type="text/javascript"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.compare-table.js" type="text/javascript"></script>
    <script src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.mCustomScrollbar.concat.min.js" type="text/javascript"></script>
    <link href="<?= SITE_TEMPLATE_PATH ?>/css/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&amp;subset=cyrillic"
          rel="stylesheet">
    <? if (CSite::InDir('/press-center/news/')): ?>
        <style>
            h1 {
                text-transform: none
            }
        </style>
    <? endif; ?>

    <script src="<?= SITE_TEMPLATE_PATH ?>/js/yamap.js" type="text/javascript"></script>

    <link href="https://fonts.googleapis.com/css?family=Arimo:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext"
          rel="stylesheet">

</head>

<body>

<link href="<?= SITE_TEMPLATE_PATH ?>/blind/panel.css" rel="stylesheet" type="text/css">
<link href="<?= SITE_TEMPLATE_PATH ?>/blind/blind.css" rel="stylesheet" type="text/css">
<script src="<?= SITE_TEMPLATE_PATH ?>/blind/blind.js" type="text/javascript"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/blind/jquery.cookie.min.js" type="text/javascript"></script>

<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>

<div class="container">

    <div class="content-wrapper-emr">
        <div class="content-wrapper">
            <div class="content-center">
                <h1><? $APPLICATION->ShowTitle(false) ?></h1>

