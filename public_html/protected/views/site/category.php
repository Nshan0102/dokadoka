<?
/* @var $category FrontendCategory */
/* @var $items FrontendItem[] */
?>

<div class="content category">

   <div class="category-intro">
            <?=$category->upper_text?>
        </div>
        <div><?=$category->text?></div>
    

    <ul class="goods-list">
        <?foreach($items as $item):?>
            <li class="itemContainer" data-id="<?=$item->id?>">
                <? $this->renderPartial('_item',array('item'=>$item),false,false);?>
            </li>
        <?endforeach?>
    </ul>
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