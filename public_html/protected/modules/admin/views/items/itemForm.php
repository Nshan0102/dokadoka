<?php
/** @var CActiveForm $form */
/** @var Item $model */
$this->pageTitle='Редактирование товара';
?>
<div class="form centered" style="width: 600px;">
<h1>Редактирование товара</h1>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'item-itemForm-form',
	//'enableAjaxValidation'=>true,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>


<?php
    $this->widget('application.modules.admin.extensions.wyswyg.Wyswyg',
            array(
                "type"=>'ckeditor',
                "height"=>'375',
                "width"=>'600',
            )
    ); ?>

    <script type="text/javascript">

        window.onload=function(){
            initCkeditor("Item_text");
            initCkeditor("Item_upper_text");
        }

    </script>
    <p class="note">Поля со <span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'header'); ?>
		<?php echo $form->textField($model,'header'); ?>
		<?php echo $form->error($model,'header'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'artikul'); ?>
		<?php echo $form->textField($model,'artikul'); ?>
		<?php echo $form->error($model,'artikul'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'unicode'); ?>
		<?php echo $form->textField($model,'unicode'); ?>
		<?php echo $form->error($model,'unicode'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'order'); ?>
		<?php echo $form->textField($model,'order'); ?>
		<?php echo $form->error($model,'order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'catid'); ?>
		<?php
/*$rows=$db->createCommand($sql)->queryAll();
 $roles=CHtml::listData($rows, 'id', 'name');*/

        $list = CHtml::listData(Category::model()->getList4Selects(), 'id', 'header');
        echo $form->dropDownList($model,'catid',$list);
        ?>
		<?php echo $form->error($model,'catid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'uri'); ?>
		<?php echo $form->textField($model,'uri'); ?>
		<?php echo $form->error($model,'uri'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'visible'); ?>
        <?php echo $form->checkBox($model,'visible'); ?>
		<?php echo $form->error($model,'visible'); ?>
	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'price'); ?>
   		<?php echo $form->textField($model,'price'); ?>
   		<?php echo $form->error($model,'price'); ?>
   	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'oldprice'); ?>
   		<?php echo $form->textField($model,'oldprice'); ?>
   		<?php echo $form->error($model,'oldprice'); ?>
   	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'promo_price'); ?>
   		<?php echo $form->textField($model,'promo_price'); ?>
   		<?php echo $form->error($model,'promo_price'); ?>
   	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'height'); ?>
		<?php echo $form->textField($model,'height'); ?>
		<?php echo $form->error($model,'height'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'worktime'); ?>
		<?php echo $form->textField($model,'worktime'); ?>
		<?php echo $form->error($model,'worktime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'caliber'); ?>
		<?php echo $form->textField($model,'caliber'); ?>
		<?php echo $form->error($model,'caliber'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zalps'); ?>
		<?php echo $form->textField($model,'zalps'); ?>
		<?php echo $form->error($model,'zalps'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ishit'); ?>
		<?php echo $form->checkBox($model,'ishit'); ?>
		<?php echo $form->error($model,'ishit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isbonus'); ?>
		<?php echo $form->checkBox($model,'isbonus'); ?>
		<?php echo $form->error($model,'isbonus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isrecommended'); ?>
		<?php echo $form->checkBox($model,'isrecommended'); ?>
		<?php echo $form->error($model,'isrecommended'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'showonindex'); ?>
		<?php echo $form->checkBox($model,'showonindex'); ?>
		<?php echo $form->error($model,'showonindex'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
        <?php
           if ($model->image)
           {
               ?>
               <img src="<?=$model->image;?>" />
               <br><input type="submit" name="delimage" value="Удалить изображение">
           <?php
           }
           else
           {
              echo $form->fileField($model,'image');
              echo $form->error($model,'image');
           }
        ?>
		<?php echo $form->error($model,'image'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'imagebig'); ?>
        <?php
           if ($model->imagebig)
           {
               ?>
               <a href="<?=$model->imagebig;?>" target="_blank" title="посмотреть в новом окне"><?=$model->imagebig;?></a>
               <br><input type="submit" name="delimagebig" value="Удалить большое изображение">
           <?php
           }
           else
           {
              echo $form->fileField($model,'imagebig');
              echo $form->error($model,'imagebig');
           }
        ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'upper_text'); ?>
		<?php echo $form->textArea($model,'upper_text',array('cols'=>50,'rows'=>7)); ?>
		<?php echo $form->error($model,'upper_text'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'shorttext'); ?>
        <?php echo $form->textArea($model,'shorttext',array('cols'=>50,'rows'=>7)); ?>
        <?php echo $form->error($model,'shorttext'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'text'); ?>
        <?php echo $form->textArea($model,'text'); ?>
        <?php echo $form->error($model,'text'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'html_title'); ?>
		<?php echo $form->textField($model,'html_title'); ?>
		<?php echo $form->error($model,'html_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dimensions'); ?>
		<?php echo $form->textField($model,'dimensions'); ?>
		<?php echo $form->error($model,'dimensions'); ?>
	</div>

	<div class="row">
		<?php
            echo $form->labelEx($model,'rating');
		    echo $form->textField($model,'rating');
		    echo $form->error($model,'rating');
        ?>
	</div>



	<div class="row">
		<?php echo $form->labelEx($model,'meta_kw'); ?>
		<?php echo $form->textArea($model,'meta_kw',array('cols'=>50,'rows'=>7)); ?>
		<?php echo $form->error($model,'meta_kw'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'meta_descr'); ?>
		<?php echo $form->textArea($model,'meta_descr',array('cols'=>50,'rows'=>7)); ?>
		<?php echo $form->error($model,'meta_descr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'videocode'); ?>
		<?php echo $form->textField($model,'videocode'); ?>
		<?php echo $form->error($model,'videocode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'videocode2'); ?>
		<?php echo $form->textArea($model,'videocode2',array('cols'=>50,'rows'=>7)); ?>
		<?php echo $form->error($model,'videocode2'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->