<?
IncludeTemplateLangFile(__FILE__);

//$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/include_areas/blind/blind.js');
//$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/include_areas/blind/jquery.cookie.js');
//$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/include_areas/blind/panel.css');
//$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/include_areas/blind/blind.css');
?>
<link href="<?=SITE_TEMPLATE_PATH?>/include_areas/blind/panel.css" type="text/css"  data-template-style="true"  rel="stylesheet" />
<link href="<?=SITE_TEMPLATE_PATH?>/include_areas/blind/blind.css" type="text/css"  data-template-style="true"  rel="stylesheet" />

<div class="wrap" style="">
    <div class="impaired" onclick="yaCounter23766619.reachGoal('BLIND'); return true;">Версия для слабовидящих</div>
    <a href="/for_customers/anketa/" class="anketa">Анкета абонента</a>
</div>
<div class="blind-version-block" style="display: none"><div class="blind-version-fixed"><div class="blind-version-inner">
    
    <div class="color-block">
        <span class="name">Цвет:</span>
        <span class="ico wb sel" data-size="whiteblack" ><i></i></span>
        <span class="ico bw" data-size="blackwhite" ><i></i></span>
        <span class="ico blue" data-size="blue" ><i></i></span>
    </div>
    
    <div class="size-block">
        <span class="name">Размер шрифта:</span>
        <span class="ico size1 sel" data-size="s14"><i></i><b>a</b></span>
        <span class="ico size2" data-size="s16" ><i></i><b>a</b></span>
        <span class="ico size3" data-size="s18" ><i></i><b>a</b></span>
    </div>
    
    <div class="interval-block">
        <span class="name">Интервал:</span>
        <span class="ico size1 sel" data-size="w1" ><i></i></span>
        <span class="ico size2" data-size="w2" ><i></i></span>
        <span class="ico size3" data-size="w3" ><i></i></span>
    </div>
    
    <div class="img-block">
        <span class="name">Изображения:</span>
        <span class="toggle"><b class="img-on">Выкл</b><b class="img-off" style="display: none">Вкл</b></span>         
    </div>
    
    <div class="font-block">
        <span class="name">Выбор шрифта:</span>
        <span class="ico sel" data-size="arial">Arial<i></i></span>
        <span class="ico" data-size="times">Times New Roman<i></i></span>       
    </div>
    
    <div class="reset">Обычная версия</div>
    
</div></div></div>
