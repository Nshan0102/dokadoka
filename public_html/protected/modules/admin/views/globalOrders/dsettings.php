<?php
require dirname(__FILE__).'/_header.php';
/** @var GlobalOrdersController $this  */
/** @var CActiveForm $form */
/** @var GlobalOrdersDiscount $model */
$dataProvider = new CArrayDataProvider(GlobalOrdersDiscount::model()->findAll());
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'items_grid',
        'dataProvider'=>$dataProvider,
        //'filter' => $model,
        'columns' => array(

            array(
                'name'=>'min_items',
                'header'=>'мин.товаров'
            ),
            array(
                'name'=>'min_price',
                'header'=>'мин.цена каждого'
            ),
            array(
                'name'=>'discount',
                'header'=>'скидка'
            ),

            array(
                'class'=>'CButtonColumn',
                'template'=>'{delete}',
                'buttons'=>array
                (



                    'delete' => array
                    (
                        'label'=>'Удалить',
                        'url'=>'Yii::app()->createUrl("admin/globalorders/deleteDS", array("id"=>$data->id))',
                    ),
                ),
            ),
        ),
    ));

?>


<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'item-itemForm-form',
        //'enableAjaxValidation'=>true,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    )); ?>

    <div class="row">
        <?=$form->labelEx($model,'min_items'); ?>
        <?=$form->textField($model,'min_items'); ?>
        <?=$form->error($model,'min_items'); ?>
    </div>


    <div class="row">
        <?=$form->labelEx($model,'min_price'); ?>
        <?=$form->textField($model,'min_price'); ?>
        <?=$form->error($model,'min_price'); ?>
    </div>

    <div class="row">
        <?=$form->labelEx($model,'discount'); ?>
        <?=$form->textField($model,'discount'); ?>
        <?=$form->error($model,'discount'); ?>
    </div>



    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить'); ?>
    </div>

<?php $this->endWidget(); ?>