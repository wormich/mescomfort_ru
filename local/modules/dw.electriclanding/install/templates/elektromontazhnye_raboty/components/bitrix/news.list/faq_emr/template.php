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

<script>
	$(function(){
		var emrFaq=$('#emrFaq').owlCarousel({
			autoWidth: true, 
			nav:true
		})
		
		$('.emr-faq-list_item').on('click',function(){			
			$('.emr-faq-list_item').removeClass('current'); 
			$(this).addClass('current');
			eind = $(this).parent().index();
			$('#emrAnswer').find('.emr-answer-list_item').removeClass('current').eq(eind).addClass('current');
		})
	})
</script>

<?$count=1;?>
<div class="emr-faq-list">
	<div id="emrFaq" class="owl-carousel">
		<?foreach($arResult["ITEMS"] as $arItem):?>
		
		<div class="emr-faq-list_item<?if($count==1){?> current<?}?>" style="width: <?=$arItem['PROPERTIES']['FIELD_WIDTH']['VALUE']?>px" ><span><?=$arItem["PREVIEW_TEXT"]?></span></div>
		<?$count++;?>
		<?endforeach;?>
	</div>
</div>

<?$count=1;?>
<div class="emr-answer-list" id="emrAnswer">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div class="emr-answer-list_item<?if($count==1){?> current<?}?>">
			<?=$arItem["DETAIL_TEXT"]?>
		</div>
	<?$count++;?>
	<?endforeach;?>
</div>

<!--
<div class="questions_lst">
<div class="slider">
<a class="prev"></a>
 <a class="next"></a>
<div>
<div class="sectiontable">
 <ul class="tabs">
<?$count=1;
foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	 <li class="<?if($count==1){?>current<?}?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?=$arItem["PREVIEW_TEXT"]?></li>
<?$count++;?>
<?endforeach;?>
 </ul>
	<?
$count=1;
foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="box <?if($count==1){?>visible<?}?>">
 <p><?=$arItem["DETAIL_TEXT"]?></p>
 </div>
<?$count++;
endforeach;?>
</div>
</div>
</div>
<div style="clear: both;float: none;"></div></div>
-->