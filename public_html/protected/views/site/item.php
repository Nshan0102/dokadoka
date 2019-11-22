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
                        <span class="price">Цена: <?=number_format($catitem->price?$catitem->price:$catitem->oldprice,0,'',' ')?> руб.</span>
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
        <? if ($promoCode = FrontendItem::getCurrentPromocode()):?>
            <strong>Ваш промокод <?=$promoCode?> активен</strong>
        <?else:?>
            <?if($this->promoCodeMessage):?>
            <!-- <div><strong><?=$this->promoCodeMessage?></strong></div> -->
            <script>alert('<?=$this->promoCodeMessage?>');</script>
        <?endif?>
            <form action="" method='post' id='promocode-form'>
                <input type="hidden" name="<?=Yii::app()->request->csrfTokenName?>" value="<?=Yii::app()->request->csrfToken ?>">
                <label>
                    <input type="text" name="promocode" value="" placeholder="Промокод">
                </label>
                <input type="submit" value="Применить">
            </form>
        <?endif?>
    </div>

</div>