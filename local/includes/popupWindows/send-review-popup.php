<div class="popup" id="send-review-popup">
    <h3 class="_centered _upper"><b>Оставить отзыв</b></h3>
    <form action="/order/" class="review-form">
        <input type="text" name="text1" style="display:none;" value="">
        <div class="field">
            <input type="text" placeholder="Ваше имя" name="name">
        </div>
        <div class="field">
            <input type="text" placeholder="Телефон +7 (___) ___-__-__" name="phone">
        </div>
        <div class="field">
            <textarea placeholder="Ваш отзыв" name="msg"></textarea>
        </div>
        <div class="tsaf-agreement tsaf-agreement--js">
            <label for="input-tsaf-agreement" class="tsaf-agreement__label tsaf-agreement__label--js">
                Нажимая кнопку, я <a href="/upload/agreement_personal.pdf" target="_blank">даю согласие на обработку своих персональных данных</a>
            </label>
        </div>
       <input type="hidden" name="username" value="<?=getControlStringHash()?>">

        <div style="display:none">
            <input type="text" name="last_name" value="">
            <input type="text" name="age" value="">
        </div>
        <input type="hidden" name="form_text_user_ip" value="<?= \Bitrix\Main\Service\GeoIp\Manager::getRealIp() ?>">
        <div class="submit">
            <input type="submit" value="Отправить" class="b-btn _btn">
        </div>
    </form>
</div>