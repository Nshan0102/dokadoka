<?php
   if ($this->getAction()->getId()=='index')
   {
?>
   <div style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#000; background-color:#ffffd6; line-height:18px; position:relative; float:left; padding:20px; margin-top:7px;">
      <?=$idxPage->upper_text?>
   </div>
<?php
   }
?>

<div class="wline"></div>

<!--noindex-->
<?php
//$this->pageTitle='Главная';

foreach ($items as $item)
{
    $this->renderPartial('_item',array('item'=>$item),false,false);
}
?>
<!--/noindex-->
<div style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#000; background-color:#ffffd6; line-height:18px; position:relative; float:left; padding:20px; margin-top:7px;">
    <?=$idxPage->text?>
</div>


