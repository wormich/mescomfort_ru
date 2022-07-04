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
<table class="inform_of_rez_table">
<tbody>
<tr class="odd" style="text-align: center;">
	<td>
 <strong>Дата и время <br>
		 прекращения доступа</strong>
	</td>
	<td>
 <strong>Описание</strong>
	</td>
	<td>
 <strong>Дата и время <br>
		 возобновления доступа</strong>
	</td>
	<td>
 <strong>Причина</strong>
	</td>
</tr>
<tr>
	<? if(!count($arResult["ITEMS"])){?>
	<tr>
		<td></td><td></td><td></td><td></td></tr>
	<?}?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<tr class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
<td><?echo $arItem["DISPLAY_PROPERTIES"]["EVENT_DATE_STOP"]["VALUE"];?></td>
		<td><?echo $arItem["PREVIEW_TEXT"];?></td>
<td><?echo $arItem["DISPLAY_PROPERTIES"]["EVENT_DATE_START"]["VALUE"];?></td>
<td><?echo $arItem["FIELDS"]["DETAIL_TEXT"];?></td>
		
	</tr>
<?endforeach;?>
</tbody>
</table>
 <br>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>

