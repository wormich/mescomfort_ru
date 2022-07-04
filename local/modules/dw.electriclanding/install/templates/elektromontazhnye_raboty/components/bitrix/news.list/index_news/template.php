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
<?if (!empty($arResult["ITEMS"])):

	/*
	$ipaddr = array('85.12.245.141'); 
	$remote = $_SERVER['REMOTE_ADDR']; 
	if (in_array($remote,$ipaddr)) {?><?}
	*/

	if($_COOKIE['dev'] != '2') {?>

		<div class="main-news-wrapper">
			<div class="main-news_title"><a href="/press-center/news/">Новости</a></div>
			<div class="main-news-wrapper_in">
		<?
				foreach($arResult["ITEMS"] as $arItem) {
					$text = $arItem["NAME"];
					$len = strlen($text);
					
					//if($len>83)$text = substr($text, 0, 84)."...";

					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

					$max_length = 40;
					
					if(mb_strlen($arItem["PREVIEW_TEXT"], "UTF-8") > $max_length and mb_stripos($arItem["PREVIEW_TEXT"], '</a>') + 5 < mb_strlen($arItem["PREVIEW_TEXT"])) {

						$endLinkPos = mb_stripos($arItem["PREVIEW_TEXT"], '</a>');

						if($endLinkPos > $max_length) {
							$max_length = $endLinkPos + 5 + ($endLinkPos -  mb_stripos($arItem["PREVIEW_TEXT"], '<a'));
						}

						$text_cut = mb_substr($arItem["PREVIEW_TEXT"], 0, $max_length, "UTF-8");
						var_dump($text_cut);
						$text_explode = explode(" ", $text_cut);
					
						unset($text_explode[count($text_explode) - 1]);
					
						$text_implode = implode(" ", $text_explode);
					
						$arItem["PREVIEW_TEXT"] = $text_implode."...";
					}

					?>
					<div class="main-news_item">
						<div class="main-news_date"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></div>
						<div class="main-news_link"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?echo $text?></a></div>
						<div class="main-news_desc"><?=$arItem["PREVIEW_TEXT"]?></div>
					</div>
					<?
				}
			?></div></div>
<?} else {// start old design
?>
		<div>
		  <a href="/press-center/news/"><h2 style="height: 22px; text-transform: uppercase; color: #0065B4; font: 1.143em/0.875em 'Calibri Bold'; margin-top: -10px;">Новости</h2></a>
		  <ul style="list-style:none;">
				<?foreach($arResult["ITEMS"] as $arItem):?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
				<li style="margin:0px 0px 7px 0px;" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?
					$text = $arItem["NAME"];
					$len = strlen($text);
					if($len>63)$text = substr($text, 0, 64)."...";
					?>
				  <span class="date" style="background: #38D2F3; color: #FFF; padding: 2px 5px;"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" style="color:#747B85;margin: 0px 0px 0px 9px;"><?echo $text?></a>
				</li>
				<?endforeach;?>
		  </ul>
		</div>
<?} //end old design ?>
<?endif;?>