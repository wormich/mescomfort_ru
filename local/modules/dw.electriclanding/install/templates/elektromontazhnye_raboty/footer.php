<? use Bitrix\Main\Config\Option;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?
IncludeTemplateLangFile(__FILE__);
?>
<div class="totop" onclick="$('body,html').animate({scrollTop:0});" style="display: block;"></div>
</div>
</div>
</div>
</div>

<div id="popap-modal" class="modal hidden">
    <div class="modal-content">
        <div class="">
            <div class="tab-close">
                <div class="modal-content-title"><h1>Заказать электромонтажные работы</h1></div>
                <img src="/local/templates/elektromontazhnye_raboty/css/new/img/close-grey.png" alt=""
                     class="modal-btn-close"></div>
        </div>

        <div class="modal-content-text">

            <? $APPLICATION->IncludeComponent(
                'dw:landing.form',
                '',
                array(
                    'IBLOCK_ID' => Option::get('dw.electriclanding', 'form_iblock_id', ''),
                    'SUCCESS_MESSAGE' => 'Ваша заявка принята! В течение 1 рабочего дня наши менеджеры свяжутся с Вами для уточнения информации.',
                    'ERROR_MESSAGE' => 'Во время отправки произошла ошибка, попробуйте позже.'
                )
            ); ?>

        </div>
    </div>
</div>


</body>
</html>
