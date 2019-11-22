<?php
require dirname(__FILE__).'/_header.php';
/** @var GlobalOrdersController $this  */

$dataProvider = new CArrayDataProvider(GlobalOrdersPhone::model()->findAll());
$dataProvider->setPagination(false);
//$dataProvider->pagination = false;

?>

<?=CHtml::beginForm()?>
<h6>Добавить клиентов</h6>
<label>
    телефон1;имя<br>
    телефон2<br>
    телефон3;имя3<br>
<textarea name="multi_add" style="width: 300px;height: 150px">

</textarea><br>
    <input type="submit" value="Добавить">
</label>
<?=CHtml::endForm()?>

<?
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'items_grid',
        'dataProvider'=>$dataProvider,
        //'filter' => $model,
        'columns' => array(

            array(
                'name'=>'name',
                'header'=>'имя',
            ),
            array(
                'name'=>'phone',
                'header'=>'телефон',
            ),

            array(
                'name'=>'promo_code',
                'header'=>'промокод',
            ),

            array(
                'name'=>'discount',
                'header'=>'скидка',
            ),

/*
            array(
                'class'=>'CButtonColumn',
                'template'=>'{delete}',
                //'template'=>'{update}{delete}',
                'buttons'=>array
                (

                    'update' => array
                    (
                        'label'=>'Редактировать',
                        'url'=>'Yii::app()->createUrl("admin/globalorders/edit", array("id"=>$data->id))',
                    ),

                    'delete' => array
                    (
                        'label'=>'Удалить',
                        'url'=>'Yii::app()->createUrl("admin/globalorders/delete", array("id"=>$data->id))',
                    ),
                ),
            ),
*/
        ),
    ));

?>

&nbsp;