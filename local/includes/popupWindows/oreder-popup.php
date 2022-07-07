<div class="popup" id="order-popup">
    <h3 class="_centered _upper"><b>Оставить заявку</b></h3>
    <form action="/order/" class="order-form">
        <input type="text" name="text1" style="display:none;" value="">
        <div class="field _half">
            <input type="text" placeholder="ФИО" name="name">
        </div>
        <div class="field _half">
            <input type="text" name="phone" placeholder="Телефон +7 (___) ___-__-__">
        </div>
        <div class="field _half">
            <select name="service">
                <option value="КОСМЕТИЧЕСКИЙ РЕМОНТ">КОСМЕТИЧЕСКИЙ РЕМОНТ</option>
                <option value="КАПИТАЛЬНЫЙ РЕМОНТ">КАПИТАЛЬНЫЙ РЕМОНТ</option>
                <option value="РЕМОНТ ПРЕМИУМ КЛАССА">РЕМОНТ ПРЕМИУМ КЛАССА</option>
                <option value="РЕМОНТ КОМНАТЫ">РЕМОНТ КОМНАТЫ</option>
                <option value="РЕМОНТ КУХНИ">РЕМОНТ КУХНИ</option>
                <option value="РЕМОНТ ВАННОЙ КОМНАТЫ">РЕМОНТ ВАННОЙ КОМНАТЫ</option>
                <option value="РЕМОНТ САНУЗЛА">РЕМОНТ САНУЗЛА</option>
            </select>
        </div>
        <div class="field _half">
            <input type="text" placeholder="E-mail" name="email">
        </div>
        <div class="field _half">
            <select class="city-select" name="city">
                <option>Выбрать город</option>
                <option value="Балашиха">Балашиха</option>
                <option value="Видное">Видное</option>
                <option value="Дзержинский">Дзержинский</option>
                <option value="Долгопрудный">Долгопрудный</option>
                <option value="Домодедово">Домодедово</option>
                <option value="Железнодорожный">Железнодорожный</option>
                <option value="ЖК Гусарская балада, Одинцовский район">ЖК "Гусарская балада", Одинцовский район</option>
                <option value="ЖК пос. Октябрьский Люберецкого района">ЖК пос. Октябрьский Люберецкого района</option>
                <option value="Жуковский">Жуковский</option>
                <option value="Зеленоград">Зеленоград</option>
                <option value="Ивантеевка">Ивантеевка</option>
                <option value="Коммунарка">Коммунарка</option>
                <option value="Королев">Королев</option>
                <option value="Котельники">Котельники</option>
                <option value="Красково">Красково</option>
                <option value="Красногорск">Красногорск</option>
                <option value="Лобня">Лобня</option>
                <option value="Лыткарино">Лыткарино</option>
                <option value="Люберцы">Люберцы</option>
                <option value="Малаховка">Малаховка</option>
                <option value="Москва">Москва</option>
                <option value="Московский">Московский</option>
                <option value="Мытищи">Мытищи</option>
                <option value="Нахабино">Нахабино</option>
                <option value="Одинцово">Одинцово</option>
                <option value="Островцы">Островцы</option>
                <option value="Подольск">Подольск</option>
                <option value="Пушкино">Пушкино</option>
                <option value="Раменское">Раменское</option>
                <option value="Рассказовка">Рассказовка</option>
                <option value="Реутов">Реутов</option>
                <option value="Томилино">Томилино</option>
                <option value="Химки">Химки</option>
                <option value="Щёлково">Щёлково</option>
                <option value="Щербинка">Щербинка</option>
                <option value="Юбилейный">Юбилейный</option>
                <option disabled>---------------</option>
                <option value="другой">Другой город</option>
            </select>
        </div>
        <div class="field _half">
            <div class="city-status"></div>
        </div>
        <div class="form-note"><b>Минимальная сумма заказа - 50 000 рублей.</b></div>
<!--        <div class="captcha-wrapper">-->
<!--            <div class="captcha-error captcha-error&#45;&#45;js">Необходимо правильно заполнить капчу!</div>-->
<!--            <div class="g-recaptcha" id="g-recaptcha-order"></div>-->
<!--        </div>-->
        <div class="tsaf-agreement tsaf-agreement--js">
            <input type="checkbox" name="agreement" id="input-tsaf-agreement" value="Да">
            <label for="input-tsaf-agreement" class="tsaf-agreement__label tsaf-agreement__label--js">
                Я даю согласие на обработку своих персональных данных в соответствии с <a href="/files/agreement_personal.pdf" target="_blank">Положением об организации обработки и защиты персональных данных клиентов АО «Мосэнергосбыт»</a>
            </label>
        </div>
      <input type="hidden" name="hash" value="<?=getControlStringHash()?>">
        <div class="submit"> <!--onclick="yaCounter38950510.reachGoal('zakaz'); return true;"-->
            <input type="submit" value="Оставить заявку" class="b-btn _btn">
        </div>
    </form>
</div>