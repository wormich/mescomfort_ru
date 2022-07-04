<?

use Bitrix\Main\Config\Option;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title",
    "Электромонтажные работы - закажите все работы по электрике в Петроэлектросбыте");
$APPLICATION->SetPageProperty("keywords", "Электромонтажные работы, вызов электрика, услуги электрика");
$APPLICATION->SetPageProperty("description",
    "Комплекс монтажных и проектных работ для квартиры и частного дома. Заменим проводку, перенесем счетчик, соберем люстру, подключим бытовую технику, заменим щиток. Все работы выполняем в полном соответствии с правилами СНИП, ПТЭЭП, ПУЭ. Доверьте электробезопасность в своем доме специалистам. Работаем на рынке электромонтажных работ 24 года!");
$APPLICATION->SetTitle("Электромонтажные работы");
?>

<span class="h1_descr">
<? $APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/include_landing/elektromontazhnye-raboty-descr.php"
    )
); ?>
</span>

<style>
    #popap-modal {
        display: block
    }

    ;
    .modal-content {
        max-width: 700px !important;
    }
</style>

<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "slider-elektromontazhnye-raboty",
    array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array(0 => "", 1 => "",),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => Option::get('dw.electriclanding', 'slider_iblock_id', ''),
        "IBLOCK_TYPE" => "dw_pes_landing",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "20",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array(0 => "", 1 => "DESC", 2 => "LINK", 3 => "PICTURE",),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N"
    )
); ?>
<h2>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include_landing/elektromontazhnye-raboty-services-title.php"
        )
    ); ?>
    <a class="alert_fire_link_more modal-link" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a>
</h2>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "elektromontazhnye-raboty-services",
    array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array(0 => "", 1 => "",),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => Option::get('dw.electriclanding', 'service_iblock_id', ''),
        "IBLOCK_TYPE" => "dw_pes_landing",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "20",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array(0 => "", 1 => "PRICE", 2 => "",),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N"
    )
); ?>
<a class="alert_fire_link_more modal-link mobapart" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a>
<h2>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include_landing/elektromontazhnye-raboty-advantages-title.php"
        )
    ); ?>
    <a class="alert_fire_link_more modal-link" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a>
</h2>
<div class="emr_advantages_list">
    <div class="emr_advantages_item">
        <img src="/local/templates/elektromontazhnye_raboty/images/advantage1.png">
        <div class="emr_advantages_item_right">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-advantage1.php"
                )
            ); ?>
        </div>
    </div>
    <div class="emr_advantages_item">
        <img src="/local/templates/elektromontazhnye_raboty/images/advantage2.png">
        <div class="emr_advantages_item_right">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-advantage2.php"
                )
            ); ?>
        </div>
    </div>
    <div class="emr_advantages_item">
        <img src="/local/templates/elektromontazhnye_raboty/images/advantage3.png">
        <div class="emr_advantages_item_right">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-advantage3.php"
                )
            ); ?>
        </div>
    </div>
</div>
<a class="alert_fire_link_more modal-link mobapart" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a>
<h2><? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include_landing/elektromontazhnye-raboty-steps-title.php"
        )
    ); ?> <a class="alert_fire_link_more modal-link" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a></h2>
<div class="emr_steps_list">
    <div class="emr_step_item">
        <div class="emr_step_item_number">
            1
        </div>
        <div class="emr_step_item_title">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-number1.php"
                )
            ); ?>
        </div>
    </div>
    <div class="emr_step_item">
        <div class="emr_step_item_number">
            2
        </div>
        <div class="emr_step_item_title">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-number2.php"
                )
            ); ?>
        </div>
    </div>
    <div class="emr_step_item">
        <div class="emr_step_item_number">
            3
        </div>
        <div class="emr_step_item_title">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-number3.php"
                )
            ); ?>
        </div>
    </div>
    <div class="emr_step_item">
        <div class="emr_step_item_number">
            4
        </div>
        <div class="emr_step_item_title">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-number4.php"
                )
            ); ?>
        </div>
    </div>
    <div class="emr_step_item">
        <div class="emr_step_item_number">
            5
        </div>
        <div class="emr_step_item_title">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include_landing/elektromontazhnye-raboty-number5.php"
                )
            ); ?>
        </div>
    </div>
</div>
<a class="alert_fire_link_more modal-link mobapart" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a>
<div class="emr_steps_bottom_list">
    <div class="emr_step_bottom_item">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE" => "",
                "PATH" => "/include_landing/elektromontazhnye-raboty-bottom-number1.php"
            )
        ); ?>
    </div>
    <div class="emr_step_bottom_item">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE" => "",
                "PATH" => "/include_landing/elektromontazhnye-raboty-bottom-number2.php"
            )
        ); ?>
    </div>
    <div class="emr_step_bottom_item">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE" => "",
                "PATH" => "/include_landing/elektromontazhnye-raboty-bottom-number3.php"
            )
        ); ?>
    </div>
</div>
<a class="alert_fire_link_more modal-link mobapart" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a>
<h2><? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include_landing/elektromontazhnye-raboty-questions-title.php"
        )
    ); ?> <a class="alert_fire_link_more modal-link" href="zakazat-elektromontazhnye-raboty">Отправить заявку</a></h2>
<script>
    (function ($) {
        $(function () {

            $('ul.tabs').delegate('li:not(.current)', 'click', function () {
                $(this).addClass('current').siblings().removeClass('current')
                    .parents('div.sectiontable').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
            })

        })
    })(jQuery)


    $(document).ready(function () {

        $(".slider .next").click(function () {
            par = $(this).parents('.questions_lst');
            cur = par.find('.tabs').find('.current');
            if (cur.next('li')[0]) cur.hide().next('li').show().trigger('click');
        });

        $(".slider .prev").click(function () {

            par = $(this).parents('.questions_lst');
            cur = par.find('.tabs').find('.current');
            if (cur.prev('li')[0]) cur.hide().prev('li').show().trigger('click');
        });
    })
</script>

<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "faq_emr",
    array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "N",
        "COMPONENT_TEMPLATE" => "faq_emr",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array(0 => "", 1 => "",),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => Option::get('dw.electriclanding', 'faq_iblock_id', ''),
        "IBLOCK_TYPE" => "dw_pes_landing",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "200",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array(0 => "FIELD_WIDTH", 1 => "",),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N"
    )
); ?>


<div class="alert_fire zakaz">
    <a href="zakazat-elektromontazhnye-raboty" class="alert_fire_slider modal-link">
        <div class="alert_fire_title">Заказать электромонтажные работы</div>
        <span class="alert_fire_link_more">Отправить заявку</span> </a>
</div>


<br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
