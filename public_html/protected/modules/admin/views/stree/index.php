<?php $this->pageTitle=Yii::app()->name; ?>

<h2>Структура страниц</h2>

<?php
$this->widget('application.modules.admin.extensions.wyswyg.Wyswyg',
        array(
            "type"=>'ckeditor',
            "height"=>'375',
            "width"=>'100%',
        )
);
?>
<div id='stree_div' style="float: left; width: 220px; display: inline;overflow: hidden;">
    <?php
        echo $this->renderPartial('ajaxtree',null,false,false);
    ?>
</div>



<div id='contentBox' style="float: left; vertical-align: top; display: inline; padding-left: 10px;">

</div>