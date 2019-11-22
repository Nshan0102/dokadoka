<?php $this->pageTitle='Товары'; ?>
<h1>Товары</h1>
<?=CHtml::ajaxLink('Сохранить видимость', Yii::app()->createUrl('admin/items/saveVisibility'),
    array(
        'type'=>'POST',
        'data'=>'js:{
             //"ids" : $.fn.yiiGridView.getChecked("items_grid","checked_col"),
             "ids" : getItemsCheckedBoxes(),
             "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"
            }',
        //'success'=>'js:$.fn.yiiGridView.update("items_grid")',
        //'success'=>'js:function(){alert("Сохранено");setTimeout(location.reload(), 1000)}',
        'success'=>'js:function(){alert("Сохранено");location.reload()}',
    )

);


?>

<?php
/** @var CActiveForm $form */
/** @var $model Item */
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'items-search',
    'method'=>'get',
    //'enableAjaxValidation'=>true,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?=$form->dropDownList($model, 'visible', array(''=>'все', '0'=>'только невидимые', '1'=>'только видимые'))?>
<input type="submit" value="показать">
<?php $this->endWidget(); ?>





<?php

$dataProvider=$model->search();
//$dataProvider->pagination->pageSize=Yii::app()->params['adminPerPage'];
$dataProvider->pagination->pageSize = 1000;
$this->widget('zii.widgets.grid.CGridView', array(
      'id'=>'items_grid',
      'dataProvider'=>$dataProvider,
      'filter' => $model,
      'columns' => array(
          /*
          array(
              //'class'=>'CCheckBoxColumn',
              'class'=>'MyCheckboxColumn',
              'header'=>'Отображать?',
              'checked'=>'($data->visible==1)?"checked":""',
              'id'=>'checked_col',
              //'name'=>'checked_col[]',
             // 'name'=>'visible',
              'checkBoxHtmlOptions'=>array(
                  'name'=>'cb[$data->id]',
              ),

          ),
          */
          array(
              'id'=>'checked_col',
              'name' => 'visible',
              'type'=>'raw',
              'header' => "видимость",
              'filter' => false,

              'value' => 'CHtml::checkBox("cb[$data->id]",$data->visible,array("class"=>"viscb","data-id"=>$data->id))',
          ),
          'unicode',
          'header',
          'oldprice',
          'price',
          'promo_price',
          'uri',
          //'order',
           array(
               'name' => 'category_header',
               // 'value'=>'$data->category->header',
               'value'=>'is_object($data->category)?$data->category->header:"n/a"',
               'filter'=>CHtml::listData(Category::model()->getList4Selects(), 'id', 'header'),
            ),
          array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons'=>array
            (

                'update' => array
                (
                    'label'=>'Редактировать',
                    //'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
                    'url'=>'Yii::app()->createUrl("admin/items/itemForm", array("id"=>$data->id))',
                ),

                'delete' => array
                (
                    'label'=>'Удалить',
                    'url'=>'Yii::app()->createUrl("admin/items/delete", array("id"=>$data->id))',
                ),
            ),
          ),
      ),
    //'selectionChanged'=>'updateEditForm',//a javascript function that will be invoked after the row selection is changed.
  ));

?>
<a href="/admin/items/itemForm">добавить товар</a>

<script type="text/javascript">

    function getItemsCheckedBoxes()
    {
        var tableID='items_grid';
        var checked = {};
        var dataid;
        $('input.viscb').each(function(i){
            dataid=$(this).attr('data-id');
            if(this.checked)
                checked[dataid]=1;
            else
                checked[dataid]=0;
        });
        return checked;
    }
</script>
<br>
