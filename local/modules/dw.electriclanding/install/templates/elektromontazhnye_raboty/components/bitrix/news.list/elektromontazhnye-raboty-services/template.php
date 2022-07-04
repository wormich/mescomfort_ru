<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
<div class="emr_services_list">

    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
            array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <a href="#" class="emr_services_item js-emr_services_item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
             data-text="<?= htmlspecialchars($arItem['DETAIL_TEXT']); ?>" data-name="<?= $arItem['NAME']; ?>">
            <div class="emr_services_item_top js-service-item">

                <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                     title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
                <div class="emr_services_item_title">
                    <? echo $arItem["NAME"] ?>
                </div>
                <p>
                    <? echo $arItem["PREVIEW_TEXT"]; ?>
                </p>


            </div>
            <div class="emr_services_item_bottom">
                <span class="emr_services_item_price"><?= $arItem["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"] ?></span>


            </div>
        </a>
    <? endforeach; ?>

</div>


<div id="service-modal" class="modal hidden">
    <div class="modal-content">
        <div class="">
            <div class="tab-close">
                <div class="modal-content-title"><h1></h1></div>
                <img src="/local/templates/elektromontazhnye_raboty/css/new/img/close-grey.png" alt=""
                     class="modal-btn-close serice-btn-close"></div>
        </div>

        <div class="modal-content-text">

        </div>
    </div>
</div>
