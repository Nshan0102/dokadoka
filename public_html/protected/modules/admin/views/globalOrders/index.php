<?php
require dirname(__FILE__).'/_header.php';
/** @var GlobalOrdersController $this  */

$dataProvider = new CArrayDataProvider(GlobalOrdersOrder::model()->findAll());
//$dataProvider->pagination = false;
$dataProvider->setPagination(false);
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'items_grid',
        'dataProvider'=>$dataProvider,
        //'filter' => $model,
        'columns' => array(

            array(
                'name'=>'when',
                'header'=>'когда',
            ),
            array(
                'name'=>'sum',
                'header'=>'сумма',
            ),
            array(
                'name'=>'phone_id',
                'header'=>'телефон',
                'type'=>'raw',
                'value'=>'$data->phone->phone'
            ),
            array(
                'name'=>'phone_name',
                'header'=>'имя',
                'type'=>'raw',
                'value'=>'$data->phone->name'
            ),

            array(
                'name'=>'data',
                'header'=>'данные',
                'type'=>'raw',
                'value' => 'nl2br($data->getDataPretty())'
               // 'value'=>'"<pre>".print_r(json_decode($data->data,1),1)."</pre>"'
            ),

            array(
                'name'=>'source',
                'header'=>'источник',
            ),


            array(
                'class'=>'CButtonColumn',
                'template'=>'{delete}',
                //'template'=>'{update}{delete}',
                'buttons'=>array
                (

                    'update' => array
                    (
                        'label'=>'Редактировать',
                        'url'=>'Yii::app()->createUrl("admin/globalOrders/edit", array("id"=>$data->id))',
                    ),

                    'delete' => array
                    (
                        'label'=>'Удалить',
                        'url'=>'Yii::app()->createUrl("admin/globalOrders/delete", array("id"=>$data->id))',
                    ),
                ),
            ),
        ),
    ));

?>

&nbsp;