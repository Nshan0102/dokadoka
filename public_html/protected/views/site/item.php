<?php
/** @var $item FrontendItem */
/** @var $catitems FrontendItem[] */
?>

<div class="content item">
    <div class="goods-list">
        <ul class="goods-list">
                <li class="itemContainer" data-id="<?=$item->id?>">
                    <?
                    $this->renderPartial('_item',array('item'=>$item,'isFullInfo'=>true,'catitems'=>$catitems),false,false);
                    ?>
                </li>
        </ul>
    </div>


    <?if ($item->videocode2):?>
        <div class="item-video">
            <?=$item->videocode2?>
        </div>
    <?endif?>

    
    <div class="similar-items">
        <p class="title">Другие товары из этой категории:</p>

        <ul>
            <?foreach ($catitems as $catitem):?>
                <?if($catitem->id == $item->id) continue;?>
                <li>
                    <a href="<?=$catitem->buildHref()?>">
                        <?if ($catitem->image):?>
                            <img src="<?=$catitem->image?>" alt="similar-img">
                        <?endif?>
                        <span class="name"><?=$catitem->header?></span>
                        <?if($catitem->price||$catitem->oldprice):?>
                        <span class="price">
                            <?if ($catitem->hasOldPrice()):?>
                                <br/><span>Цена:</span>
                                <s style='color:red'>
                                    <span style='color:black'>
                                        <?=$catitem->getOldPriceFormatted()?> руб.
                                    </span>
                                </s>
                                <span class="newPrice" style="color: #E91E63;border-bottom: 2px solid #8BC34A;">
                                    <?=$catitem->getCurrentPriceFormatted()?> руб.
                                </span>
                                <?if ($catitem->hasPersonalDiscount()):?>
                                    <br /><span>Ваша скидка:</span>
                                    <span style="color: red;"><?=$catitem->getDiscountPercent()?>%</span>
                                <?elseif ($item->hasDiscount()):?>
                                    <br /><span>Скидка:</span><span style="color: red;"> <?=$catitem->getDiscountPercent()?>%</span>
                                <?endif?>
                            <?else:?>
                                <?if ($catitem->hasPersonalDiscount()):?>
                                    <br /> Цена: <s style='color:red'>
                                        <span style='color:black'>
                                            <?=$catitem->getOldPriceFormatted()?> руб.
                                        </span></s>
                                    <span class="newPrice" style="color: #E91E63;border-bottom: 2px solid #8BC34A;">
                                        <?=$catitem->getCurrentPriceFormatted()?> руб.
                                    </span>
                                    <br />Ваша скидка: <span style="color: red;"><?=$catitem->getDiscountPercent()?>%</span>
                                <?else:?>
                                    <br /> Цена: <span style='color:black'>
                                        <?=$catitem->getCurrentPriceFormatted()?> руб.
                                    </span>
                                <?endif?>
                            <?endif?>
                        </span>
                        <?endif?>
                    </a>
                </li>
            <?endforeach?>


        </ul>
    </div>


</div>

<div class="sidebar sidebar-items">
         
    <ul class="sidebar-menu">
    <h2 class="catal hidden-md hidden-lg">Каталог товаров: </h2>
        <?foreach($this->cats as $cat):?>
            <li class='item<?=$cat->id?> <?=$this->currentCategory==$cat->id?'active':''?>' >
                <a href="<?=$cat->buildHref()?>">
                    <?if($cat->image):?>
                        <img src="<?=$cat->image?>" width="32" />
                    <?endif?>
                    <?=$cat->header?>
                </a>
            </li>
        <?endforeach?>
    </ul>
    <div class="hidden-xs hidden-sm" style="margin-top: 15px;">
        <!--Nshan-->
    </div>

</div>