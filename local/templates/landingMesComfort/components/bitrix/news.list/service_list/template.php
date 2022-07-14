<?php
foreach ($arResult["ITEMS"] as $key => $arItem) {
    ?>
    <a class="link-anchor" id="s-r<?= $key + 1; ?>" name="s-r<?= $key + 1; ?>"></a>
    <section class="section s-r<?= $key + 1; ?>"
             style="background-image:url(<?= $arItem['PREVIEW_PICTURE']['SRC']; ?>)">
        <div class="wrap cf section__main">
            <div class="section__left">
                <h2>
                    <?= $arItem['NAME']; ?>

                </h2>
                <?if ($arItem['PROPERTIES']['SPECIAL']['VALUE']=='Да'){?>
                <img src="/local/templates/landingMesComfort/img/spec.png" class="section__pin_spec" alt="">
                <?}?>
                <?= $arItem['PREVIEW_TEXT']; ?>

            </div>
            <div class="section__right">

                <?= $arItem['DETAIL_TEXT']; ?>

                <h4>
                    Стоимость работ: <br>
                    от <span><?= $arItem['PROPERTIES']['PRICE']['VALUE']; ?></span> руб. <? if ($key < 2) {
                        ?>за м<sup>2</sup><? } ?></h4>
                <?if (!empty($arItem['PROPERTIES']['PRICE_COMMENT']['VALUE'])){?>
                <div class="price-type">
                    <?= $arItem['PROPERTIES']['PRICE_COMMENT']['VALUE']; ?>
                </div>
                <?}?>
                <div>
                    <a href="#" class="b-btn _medium _upper btn-order" data-service="<?= $arItem['NAME']; ?>">Оставить
                        заявку</a>
                </div>
            </div>
        </div>
        <div class="wrap slider" data-column="3" data-pan="25">
            <div class="slider__sld">
                <table>
                    <tbody>
                    <tr>
                        <?foreach ($arItem['PROPERTIES']['GALLERY']['VALUE'] as $kp=>$pic){?>
                                <?
                            $src=CFile::GetPath($pic);

                            $renderImage = CFile::ResizeImageGet($pic, Array("width" => 320, "height" => 240),BX_RESIZE_IMAGE_IMPACT);


                            ?>
                        <td>
                            <div class="slider__item" id="bx_565502798_435" style="width: 330px;"><a
                                        href="<?=$src;?>"
                                        rel="s-r<?=$key+1;?>"><img
                                            src="<?=$renderImage['src'];?>"
                                            alt="<?= $arItem['NAME']; ?> - фото <?=$kp;?>"></a></div>
                        </td>
                      <?}?>
                    </tr>
                    </tbody>
                </table>
            </div>

            <a href="#prev" class="prev icon-larr"></a><a href="#next" class="next icon-rarr"></a></div>


    </section>


<? } ?>
