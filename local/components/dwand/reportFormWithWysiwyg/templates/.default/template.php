<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<section class="s-order" style="width:800px">
    <div class="form-group">
    <div class="form-group-element result"></div>
    <div class="form-group-element">Ваша оценка</div>
    <div class="form-group-element">
        <div class="rating-area">

            <input type="radio" id="star-5" name="rating" value="5">

            <label for="star-5" title="Оценка «5»"></label>

            <input type="radio" id="star-4" name="rating" value="4">

            <label for="star-4" title="Оценка «4»"></label>

            <input type="radio" id="star-3" name="rating" value="3">

            <label for="star-3" title="Оценка «3»"></label>

            <input type="radio" id="star-2" name="rating" value="2">

            <label for="star-2" title="Оценка «2»"></label>

            <input type="radio" id="star-1" name="rating" value="1">

            <label for="star-1" title="Оценка «1»"></label>

        </div>
        <input type="hidden" class="trueRating formElement" name="<?=$arParams['STARS']?>" value="0" />
        <input type="hidden" class="formElement" name="<?=$arParams['SNAP_ELEMENT_CODE']?>" value="<?=$arParams['SNAP_ELEMENT_ID']?>"/>
        <input type="hidden" class="formElement" name="IBLOCK_ID" value="<?=$arParams['IBLOCK_ID']?>"/>
    </div>
    <div class="form-group-element">Ваше имя</div>
    <div class="form-group-element">
        <input type="text" class="formElement" name="NAME">
    </div>

    <div class="form-group-element">
        <div class="wysiwyg-conteiner">
            <?

            CModule::IncludeModule("fileman");/* подключаем модуль */
            $LHE = new CLightHTMLEditor;
            $LHE->Show(array(
                'id' => preg_replace("/[^a-z0-9]/i", '', "PROPERTY[" . $propertyID . "][0]"),/* id поля */
                /* размеры поля */
                'width' => '100%',
                'height' => '200px',
                'inputName' => "DETAIL_TEXT",/* имя поля */
                'content' => '',/* текст в поле по-умолчанию */
                'bUseFileDialogs' => false,
                'bFloatingToolbar' => false,
                'bArisingToolbar' => false,
                'toolbarConfig' => array(/* кнопки редактирования */
                    'Bold', 'Italic', 'Underline', 'RemoveFormat', 'Code', 'Source', 'Video', 'Html',
                    'CreateLink', 'DeleteLink', 'Image', 'Video',
                    'BackColor', 'ForeColor',
                    'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyFull',
                    'InsertOrderedList', 'InsertUnorderedList', 'Outdent', 'Indent',
                    'StyleList', 'HeaderList',
                    'FontList', 'FontSizeList', 'emotions',
                ),
            ));

            ?>
        </div>

    </div>
    <div class="form-group-element">
        <input name="send_button" type="submit" class="button submitReport" value="Отправить"/>
    </div>
</div>
</section>
<?
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/ajax_js.php");
?>