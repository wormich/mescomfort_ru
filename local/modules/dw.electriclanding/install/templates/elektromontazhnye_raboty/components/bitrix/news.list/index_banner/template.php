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
<?if (!empty($arResult["ITEMS"])):?>
	<?if($_COOKIE['dev'] != '2') {?>
			<div class="slider-wrapper"><div class="slider-wrapper-n">
				<div class="owl-carousel" id="mainslider"><?
					foreach($arResult["ITEMS"] as $arItem) {

						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

					?><a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>">
						<div class="slider-item" style="background-image: url(<?=$arItem['DISPLAY_PROPERTIES']['PIC_NEW']['FILE_VALUE']['SRC'];?>)">
							<div class="slider-text">
								<span><?
						if(IS_MOBILE){
 echo ($arItem['DISPLAY_PROPERTIES']['DESC_ADAPT']['~VALUE']['TEXT']) ? $arItem['DISPLAY_PROPERTIES']['DESC_ADAPT']['~VALUE']['TEXT'] : $arItem['NAME'];
						}else{
 echo $arItem['DISPLAY_PROPERTIES']['DESC_NEW']['~VALUE']['TEXT'];
						}?></span>
								<span><?=((IS_MOBILE) ? : $arItem['DISPLAY_PROPERTIES']['DESC_SUBDESC']['VALUE'])?></span>
							</div>
						</div>
					</a><?
					}
			?></div></div></div><?
			
			?><div class="slider-navigation" id="slider_nav"><div class="slider-navigation-in"><?
				$i = 0;
				foreach($arResult["ITEMS"] as $key => $arItem) {
?><div class="slider-navigation-item<?if($key==0):?> active<?endif?>"><span><?=($arItem['DISPLAY_PROPERTIES']['DESC_ADAPT']['~VALUE']['TEXT']) ? $arItem['DISPLAY_PROPERTIES']['DESC_ADAPT']['~VALUE']['TEXT'] : $arItem['NAME'];?></span><i></i></div><?
				}
			?></div></div><?
} else {
// start old design
	?>

			<div class="news-block-wrap">
				<div class="news-block-slider">
				  <ul class="bxslider">
					<?foreach($arResult["ITEMS"] as $arItem):?>
								<?
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								?>
						<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						  <article class="slider-item">
							<?if (!empty($arResult["ITEMS"])):?>
								<div class="image"><img src="<?=$arItem['DISPLAY_PROPERTIES']['PICTURE']['FILE_VALUE']['SRC'];?>" alt="<?=$arItem['NAME'];?>" width="766" height="236"></div>
							<?endif?>
							<div class="text">
								<?=($arItem['DISPLAY_PROPERTIES']['DESC']['VALUE']) ? '<h1>'.$arItem['DISPLAY_PROPERTIES']['DESC']['VALUE'].'</h1>':''; ?>
								<?=($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']) ? '<div class="read-more"><a href="'.$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'].'" class="button">ПОДРОБНЕЕ<span class="arrow"></span></a></div>':''; ?>
							</div>
						  </article>
						</li>
					  <?endforeach;?>
				  </ul>
				</div>
				<div class="news-block-controls" id="news-slider-controls">
				  <ul>
					<?foreach($arResult["ITEMS"] as $key => $arItem):?>
						<li<?if($key==0):?> class="active"<?endif?>>
						  <a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'];?>" data-slide-index="<?=$key;?>"><?=$arItem['NAME'];?></a><span class="bg arrow"></span><span class="bg holes"></span>
						</li>
					  <?endforeach;?>
				  </ul>
				</div>
			</div>
<?} //end old design?>
<?endif?>