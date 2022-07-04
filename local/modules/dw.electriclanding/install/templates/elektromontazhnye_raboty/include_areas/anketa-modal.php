<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
<?
/* if (preg_match('/^\/for_customers\/anketa/', $APPLICATION->GetCurPage())) {
  return;
} */
if ($_COOKIE['anketaSent'] == '1'
  || parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER["HTTP_HOST"]
  || $USER->isAdmin()
  || $_SERVER['REQUEST_URI'] != '/for_customers/anketa/'
) {
  return;
}
?>
<style>
#overlay_anketa {
    position: fixed;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, .5);
    top: 0;
    left: 0;
    z-index: 100;
}

#overlay_anketa .modal {
	width: auto;	
    max-width: 790px;
    position: relative;
    left: 0%;
    top: 150px;
    margin: 0 auto;
    background: #fff;
}

#overlay_anketa .modal-wrap {
    padding: 30px 40px;
}

#overlay_anketa .modal-wrap h2 {
    text-align: center;
    color: #000;
    font-size: 18px;
    font-weight: 400;
}

#overlay_anketa .modal-wrap p {
    font-size: 14px;
    line-height: 18px;
    margin: 10px 0;
}

#overlay_anketa .modal-wrap--alert {
    font-size: 14px;
    line-height: 18px;
    color: #747b85;
    background: #f9f9f9;
}

#overlay_anketa .modal-wrap--bottom {
    border-top: 1px solid #e3e5e6; 
	overflow: hidden; 
	padding-bottom: 0
}

#overlay_anketa .modal-ok {
    float: left;
    margin-bottom: 30px;
    border: none;
    text-decoration: none;
    background: #08c;
    color: #fff;
    text-transform: uppercase;
    text-align: center;
    height: 35px;
    cursor: pointer;
    padding: 5px 10px;
}

#overlay_anketa .modal-cancel {
    float: right;
    margin-bottom: 30px;
    text-decoration: none;
    border: none;
    color: #fff;
    text-transform: uppercase;
    text-align: center;
    height: 35px;
    padding: 5px 10px;
    background-color: #bdcdd4!important;
}
</style>
<div id="overlay_anketa">
    <div class="modal">
        <div class="modal-wrap">
            <h2>Уважаемые абоненты!</h2>
			<p>Ваша безопасность и осведомленность приоритетна для нас!</p>
			<p>Пожалуйста, заполните анкету.</p>
			<p>Это поможет АО «Петроэлектросбыт» обезопасить Вас и Ваших близких от мошенников, а также оперативно информировать по вопросам энергоснабжения Вашей квартиры/дома.</p>
			<!--
            <p>Мы хотим, чтобы Вы максимально оперативно получали от нас актуальные сведения по энергоснабжению, сообщения о новых возможностях взаимодействия и изменениях в законодательстве.</p>
            <p><b>Пожалуйста, заполните данную анкету.</b> Это поможет Вам получать важную информацию из первых рук и обезопасит Вас и Ваших близких от мошеннических действий!</p>
			-->
        </div>
        <div class="modal-wrap modal-wrap--bottom">
            <button id="modal-ok" class="modal-ok">Заполнить анкету сейчас</button>
            <button id="" class="modal-cancel">Заполнить позже</button>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    function goTo (url) {
        var a = document.createElement("a");
        if (!a.click) { //for IE
            window.location = url;
            return;
        }
        a.setAttribute("href", url);
        a.style.display = "none";
        document.body.appendChild(a);
        a.click();
    }

    $('#overlay_anketa .modal-ok').on('click', function() {
        $('#overlay_anketa').fadeOut();
        goTo('https://www.pes.spb.ru/for_customers/anketa/');
    });

    $('#overlay_anketa .modal-cancel').on('click', function() {
        $('#overlay_anketa').fadeOut();
    });
});
</script>
