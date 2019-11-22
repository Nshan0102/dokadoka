<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <title><?=CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, target-densityDpi=device-dpi">
    <meta name="format-detection" content="telephone=no" />
	<meta name="robots" content="noindex, nofollow">
    <link rel='stylesheet' href='/css/reset.css' />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/media-queries.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src='/js/jquery.migrate.js'></script>
    <script type="text/javascript" src='/js/jquery.fancybox.js'></script>
    <!--<script type="text/javascript" src='/js/gallery.js'></script>-->
    <script type="text/javascript" src='/js/formstyler.js'></script>
    <script type="text/javascript" src='/js/script.js'></script>
    <link rel="shortcut icon" href="/favicon.ico" />
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!--[if IE 9]>
    <link rel='stylesheet' href='/css/ie9.css'>
    <![endif]-->
	</head>
<?
/* @var $content string */
/* @var $this SiteController */

$isCartItems = !Yii::app()->shoppingCart->isEmpty();
?>
<body>
    <div id="content">
    <div class="header">
       <div class="container">
           <div class="header-wrap">
                <div class="logo">
                    <a href="/">
                            <img src="/img/logo.svg" alt="/">
                    </a>
                </div>
                <ul class="numbers">
                    <li class='velcom'><a href="tel:+375290000000"><span>+375(29)0000000</span></a></li>
                    <li class='mts'><a href="tel:+375330000000"><span>+375(33)0000000</span></a></li> 
                </ul>
           </div>
       </div>
    </div>
    <div class="menus">
        <div class="intro menus__item">
            <a href="/basket" class="basket-items basket-mob">	
                <?php
                if($isCartItems) {
                ?>
                Товаров:
                <?= Yii::app()->shoppingCart->getItemsCount();?>
                <?php } else { ?>
                <span class="basket-active" >Пока пусто</span>
                <? }?>	
            </a>
        </div>
        <div class="category-open menus__item">
                Категории
        </div>
        <p class="menu-button menus__item">
            <!-- <img src="/img/menu-button.svg" alt="Menu"> -->
            Меню
        </p>
            
        <ul class="main-menu">
            <div class="close-menu menu-button">
                <img src="/img/close-button.svg" alt="Close">
            </div>
            <li><a href="/">Главная</a></li>
            <li><a href="/news">Новости</a></li>
            <li><a href="/skidki">Скидки</a></li>
            <li><a href="/pay">Оплата</a></li>
            <li><a href="/contact">Контакты</a></li>
            <li><a href="/delivery">Доставка</a></li>
            <li><a href="/sertifikat">Сертификаты</a></li>
            <li><a href="/adres">Адрес магазина</a></li>
            <!-- <li><a href="/basket">Корзина</a></li> -->
            <a href="/basket" class="basket-items basket-pc">	
                <!-- <?php
                if($isCartItems) {
                ?>
                В корзине товаров: <?= Yii::app()->shoppingCart->getItemsCount();?>. На сумму: <span style="color: #FFEB3B;
                    font-weight: bold;"><?=number_format(Yii::app()->shoppingCart->getCost(),0,'',' ')?> Br</span>
                <?php } else { ?>
                <span class="basket-active" >В корзине нет товаров</span>
                <? }?>	 -->
                <?php
                        if($isCartItems) {
                        ?>
                        Товаров:
                        <?= Yii::app()->shoppingCart->getItemsCount();?>
                        <?php } else { ?>
                        <span class="basket-active" >Пока пусто</span>
                    <? }?>	
               
            </a>
        </ul>
        <select class='submenu-select' name='submenu-select'>
    <?foreach($this->cats as $cat):?>
            <option <?=$this->currentCategory==$cat->id?'selected="selected"':''?> ><?=$cat->header?></option>
    <?endforeach?>
    <!--
            <option >МАЛЫЕ</option>
            <option selected="selected">СРЕДНИЕ</option>
            <option>БОЛЬШИЕ</option>
            <option>СУПЕР</option>
            <option>РИМСКИЕ СВЕЧИ</option>
            <option>ФЕСТИВАЛЬНЫЕ ШАРЫ</option>
            <option>ФОНТАНЫ</option>
            <option>РАКЕТЫ</option>
            <option>ПЕТАРДЫ, ШУТИХИ</option>
            <option>ФОНАРИКИ</option>

        </select>
        <ul class="submenu">
            <li><a href="/category/malye" title="МАЛЫЕ">МАЛЫЕ</a></li>
            <li><a href="/category/srednie" title="СРЕДНИЕ">СРЕДНИЕ</a></li>
            <li><a href="/category/bolshie" title="БОЛЬШИЕ">БОЛЬШИЕ</a></li>
            <li><a href="/category/super" title="СУПЕР">СУПЕР</a></li>
            <li><a href="/category/rimskie-svechi" title="РИМСКИЕ СВЕЧИ">РИМСКИЕ СВЕЧИ</a></li>
            <li><a href="/category/festivalnye-shary" title="ФЕСТИВАЛЬНЫЕ ШАРЫ">ФЕСТИВАЛЬНЫЕ ШАРЫ</a></li>
            <li><a href="/category/fontany" title="ФОНТАНЫ">ФОНТАНЫ</a></li>
            <li><a href="/category/rakety"  title="РАКЕТЫ">РАКЕТЫ</a></li>
            <li><a href="/category/petardy" title="ПЕТАРДЫ, ШУТИХИ">ПЕТАРДЫ, ШУТИХИ</a></li>
            <li><a href="/category/nebesnye-fonariki" title="ФОНАРИКИ">ФОНАРИКИ</a></li>
        </ul>     -->
    </div>
<div class="wrapper">
    <div class="main inner">
<!--        --><?// $this->widget('application.components.SiteBreadcrumbs', array('links'=>$this->breadcrumbs)); ?>
        
        <?=$content;?>
        <div class="hidden-md hidden-lg" style="margin: 5px 0px 10px 0px;">
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
		<div class="sidebar sidebar-fix">
            <ul class="sidebar-menu sidebar-fix-menu">
            <div class="close-menu close-category">
                <img src="/img/close-button.svg" alt="Close">
            </div>
			 <h1 class="catal hidden-md hidden-lg">Каталог товаров: </h1>
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
    </div>
</div>
    </div>
<div class="footer">

        <p class="copyright">copyright <?=date('Y')?></p>
        <form action="/search" method='post' id='search-form'>
            <input type="hidden" name="<?=Yii::app()->request->csrfTokenName?>" value="<?=Yii::app()->request->csrfToken ?>">
            <input type="text" id='search-query' class='search-field' name='search_query' value='' placeholder='искать на сайте'>
            <input type="submit" id='search-button' value='жги!'>
        </form>
    </div>

<div id="basketPopup">
    <div class="basket-popup-fixed"></div>
    <div class="pop">
	 <div class="dm-table">
        <div class="dm-cell">
            <div class="dm-modal">  
        <div class="intro-basket">
            <div class="inner">
                <a href="/basket" class="basket-items">
                </a>
            </div>
        </div>

        <div class="img-wrap">
        </div>

        <div class="text">
        </div>
        <div class="buy" style="margin: 0 auto;width: 180px;">
            <div class="counter" style="float: left;">
                <span class='minus'></span>
                <input type="text" value="1" class='counter-value' disabled="disabled">
                <span class='plus'></span>
            </div>
           <div style="float: left;"> <a href="#" class="add-to-basket" >Добавить</a></div>
        </div>
        <div class="butto">
        <a href="#" class="button button-gray" onclick="$('#basketPopup').hide();return false;"><i class="fa fa-times"></i>Закрыть<span class="hidden-sm hidden-xs"> окно и заказывать дальше</span></a>
        <a href="/basket"  class="button button-orange"><i class="fa fa-check"></i>Оформить заказ</a>
		</div>
    </div>
</div>
</div>
    </div>
</div>

<div id="basketPopup2">
    <div class="basket-popup-fixed"></div>
    <div class="pop">
        
    <div class="dm-table">
        <div class="dm-cell">
            <div class="dm-modal">               
                <div class="youtube">
        </div>

        <div class="text">
        </div>
 <div class="butto">        
     <a href="#" class="button button-gray" onclick="$('#basketPopup2').hide();return false;"><i class="fa fa-times"></i>Закрыть</a>
	 <a href="#"  class="make-order add-to-basket-in-youtube button button-orange"><i class="fa fa-check"></i>В корзину</a>
		</div>
            </div>
        </div>
    </div>
</div>
   
</div>


</body>
</html>

