<?php
 if (!Yii::app()->shoppingCart->isEmpty())
 {
?>
   <div>
       <img src="/i/basket2.png" width="17" height="16" />
        <a href="/basket">В корзине товаров: <?= Yii::app()->shoppingCart->getItemsCount();?>.
            На сумму: <?=number_format(Yii::app()->shoppingCart->getCost(),'',' ');?></a>
   </div>
<?php
 }
?>