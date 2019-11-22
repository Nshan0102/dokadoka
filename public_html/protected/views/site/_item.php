<?php
/** @var $item FrontendItem */
/** @var $catitems FrontendItem[] */
if (isset($isFullInfo))
    $isFull=true;
else
    $isFull=false;

?>
    
    <div class="item-code">
                <a href='<?=$item->buildHref()?>' class="title"><?=$item->header?></a>
<div class="hidden-xs">
                <p class="code-title ">код товара:</p>
                <p class="number hidden-sm hidden-md"><?=$item->getCode();?></p>
				</div>
				
    </div>
    <div class="left">
        <div class="img-wrap">
            <a href='<?=$item->buildHref()?>'>
                <?if ($item->image):?>
                    <?if ($item->imagebig):?>
                        <img src="<?=$item->image?>" alt="" title="<?=CHtml::encode($item->header)?>" class="fancybox" />
                    <?else:?>
                        <img src="<?=$item->image?>" alt="" />
                    <?endif?>
                <?endif?>
            </a>
        </div>
       <div class="visible-ss hidden-sm hidden-md">
				 <?if (!$isFull && $item->videocode):?>
                <noindex>
                  <a href="<?=$item->videocode?>" title="<?=CHtml::encode($item->header)?> по цене: <?=$item->getCurrentPriceFormatted()?> руб." class="button-1 btn-youtube watch-video fancybox iframe" data-id="<?=$item->id?>">
				   <i class="fa fa-youtube-play"></i>
                     <strong>Смотреть</strong>
                  </a>
                </noindex>
            <?endif?>
			</div>
    </div>

    <div class="right">
        <div class="table">
            <!--<p class="table-title">Основные характеристики:</p>-->

            <ul>
                <li class='item1'>
                    <p class="name">Залпов (шт.):</p>
                    <p class="value"><?=$item->zalps?></p>

                </li>
                <li class='item2'>
                    <p class="name">Калибр (мм):</p>
                    <p class="value"><?=$item->caliber?></p>
                </li>
                <li class='item3'>
                    <p class="name">Время (сек):</p>
                    <p class="value"><?=$item->worktime?></p>
                </li>
                <li class='item4'>
                    <p class="name">Высота (м):</p>
                    <p class="value"><?=$item->height?></p>
                </li>
              <!--  <li class='item5'>
                    <p class="name">Размеры (ШхДхВ см):</p>
                    <p class="value"><?=$item->dimensions?></p>
                </li> -->
				<div class="hidden-ss">
				
				 <?if (!$isFull && $item->videocode):?>
                <noindex>
                  <a href="<?=$item->videocode?>" title="<?=CHtml::encode($item->header)?> по цене: <?=$item->getCurrentPriceFormatted()?> руб." class="button-1 btn-youtube watch-video fancybox iframe" data-id="<?=$item->id?>">
				  
				   <i class="fa fa-youtube-play"></i>
                     <strong>Смотреть</strong>
                  </a>
                </noindex>
            <?endif?>
			</div>
            </ul>
            
        </div>

        <div class="add-wrap">

            <p class="price">




                <?if ($item->hasOldPrice()):?>
                    <br /><span>Цена:</span><span style="    text-decoration: line-through;"> <?=$item->getOldPriceFormatted()?> руб.</span>
                    <?if ($item->hasPersonalDiscount()):?>
					
                        <br /><span>Ваша скидка:</span><?=$item->getDiscountPercent()?>%
                    <?elseif ($item->hasDiscount()):?>
                        <br /><span>Скидка:</span><span style="color: #F44336;"> <?=$item->getDiscountPercent()?>%</span>
                    <?endif?>

                <?else:?>
                    <?if ($item->hasPersonalDiscount()):?>
                        <br />Цена: <?=$item->getOldPriceFormatted()?> руб.
                        <br />Ваша скидка: <?=$item->getDiscountPercent()?>%
                    <?endif?>
                <?endif?>
                <br />
		<?if ($item->hasOldPrice()):?>

	                <?if($item->hasPersonalDiscount()):?>
	                    Ваша цена со скидкой:
	                <?else:?>
	                    Цена со скидкой:<br />
	                <?endif?>
		<?else:?>
			Цена:
		<?endif?>

                <span class="newPrice" style="color: #E91E63;border-bottom: 2px solid #8BC34A;"><?=$item->getCurrentPriceFormatted()?> руб.</span>

            </p>
			
	<a href="#" class="button button-orange add-to-basket" style="margin-top: 16px;"  data-id="<?=$item->id?>">
  <i class="fa fa-shopping-cart"></i>В <strong>корзину</strong>
    </a>
           

        </div>
		

    </div>
	
    <div class="descr">
        <p><?=($isFull?$item->text:$item->shorttext)?></p>
    </div>

    <?if (!$isFull):?>
        <a href="<?=$item->buildHref();?>" class="more">подробнее...</a>
    <?endif?>
