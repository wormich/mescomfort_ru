<?php
/** @var CBitrixComponent $component */
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
?>

<section class="techcon__order wrapper" id="order">
    <div class="techcon__order-content">



        <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" class="request-form js-request-form">

            <div class="alert alert-success">
                <?= $arParams['SUCCESS_MESSAGE']; ?>
            </div>

            <div class="alert alert-danger">
                <?= $arParams['ERROR_MESSAGE']; ?>
            </div>

            <?= bitrix_sessid_post(); ?>
            <input type="hidden" name="action" value="send_request">
            <input type="hidden" name="check" value="">

            <div class="main-block">
                <div class="form-field">
                    <div>Имя:*</div>
                    <input type="text" name="REQUEST[FIRSTNAME]" required>
                </div>
                <div class="form-field">
                    <div>Фамилия:*</div>
                    <input type="text" name="REQUEST[SECONDNAME]" required>
                </div>
            </div>
            <div class="main-block">
                <div class="form-field">
                    <div>Телефон для связи:*</div>
                    <input type="text" id="callphone" name="REQUEST[PHONE]" required>
                </div>
                <div class="form-field">
                    <div>E-mail:*</div>
                    <input type="email" name="REQUEST[EMAIL]" required>
                </div>
            </div>
            <div class="position-block">
                <div class="form-field">
                    <div>Улица:*</div>
                    <input type="text" name="REQUEST[STREET]" required>
                </div>
                <div class="form-field">
                    <div>Дом:</div>
                    <input type="text" name="REQUEST[HOME]">
                </div>
                <div class="form-field">
                    <div>Корпус:</div>
                    <input type="text" name="REQUEST[KORP]">
                </div>
                <div class="form-field">
                    <div>Квартира:</div>
                    <input type="text" name="REQUEST[KVARTIRA]">
                </div>
            </div>
            <div class="comment-block">
                <div class="form-field">
                    <div>Комментарий:</div>
                    <textarea name="REQUEST[COMMENT]" id="" cols="30" rows="5"></textarea>
                </div>
            </div>
            <div class="text-block">
                <div>Поля, отмеченные *, обязательны для заполнения</div>
                <div>Подтверждаю согласие АО «Петроэлектросбыт» на обработку моих контактных данных, включая их
                    передачу монтажной организации.
                </div>
            </div>
            <div class="submit-block">
                <button type="submit">Отправить</button>
            </div>
        </form>
    </div>
</section>
