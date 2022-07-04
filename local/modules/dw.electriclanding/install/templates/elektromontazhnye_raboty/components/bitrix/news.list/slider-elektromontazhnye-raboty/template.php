<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="owl-carousel" id="mainslider">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

?>
	<div class="alert_fire" style="<? if(!empty($arItem["DISPLAY_PROPERTIES"]["PICTURE"]["VALUE"])){?>background-image:url('<?=$arItem["DISPLAY_PROPERTIES"]["PICTURE"]["FILE_VALUE"]["SRC"]?>');<?}?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	<a href="<? if(!empty($arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"])){?><?=$arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"]?><?}?>" class="alert_fire_slider">
	<div class="alert_fire_title">
		 <?=$arItem["DISPLAY_PROPERTIES"]["DESC"]["VALUE"]?>
	</div>
 <? if(!empty($arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"])){?><span class="alert_fire_link_more">Узнать подробнее</span> <?}?>
</a>
</div>
<?endforeach;?>
</div>
