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
       <input type="hidden" name="hash" value="<?=getControlStringHash()?>">
        <div class="submit">
            <input type="submit" value="Отправить" class="b-btn _btn">
        </div>
    </form>
</div>