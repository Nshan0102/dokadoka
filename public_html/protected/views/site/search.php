<?php $this->pageTitle='Результаты поиска'; ?>

<div class="content category">
<h2>Результаты поиска по запросу '<?=CHtml::encode($query); ?>'</h2>



<ul class="goods-list">
    <?foreach($results as $item):?>
        <li class="itemContainer" data-id="<?=$item->id?>">
            <? $this->renderPartial('_item',array('item'=>$item),false,false);?>
        </li>
    <?endforeach?>
</ul>

</div>