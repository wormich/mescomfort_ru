<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<footer class="footer">
    <div class="wrap cf">
        <div class="footer__copyright">Copyright © <?=date('Y')?> МЭС-КОМФОРТ <br>Все права защищены</div>
        <div class="footer__call">

            <?
            $APPLICATION->IncludeComponent(
                "realweb:base.include",
                "",
                array(
                    "CODE" => 'CONT',
                    "COMPONENT_TEMPLATE" => ".default",
                    "EDIT_TEMPLATE" => ""
                ),
                false,
                array(
                    "SHOW_ICON" =>  'N',
                )
            );
            ?>

        </div>
        <div class="footer__cta"><a href="#send-review-popup" class="b-btn open-popup">Оставить отзыв о ремонте</a></div>
        <div class="footer__address"><?
            $APPLICATION->IncludeComponent(
                "realweb:base.include",
                "",
                array(
                    "CODE" => 'ADR',
                    "COMPONENT_TEMPLATE" => ".default",
                    "EDIT_TEMPLATE" => ""
                ),
                false,
                array(
                    "SHOW_ICON" =>  'N',
                )
            );
            ?></div>
    </div>
</footer>

<?
$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/local/includes/popupWindows/oreder-popup.php"
    )
);

$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/local/includes/popupWindows/send-review-popup.php"
    )
);

$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/local/includes/popupWindows/thanks-popup.html"
    )
);

$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/local/includes/popupWindows/thanks-popup2.html"
    )
);


?>


<!---- скрипты подвала --->
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.fancybox.pack.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.form.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.validate.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/js.js?v=0.9"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/internalFooterScripts.js"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCaptchas&render=explicit" async defer></script>
<noscript><div><img src="https://mc.yandex.ru/watch/38950510" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

</body>
</html>