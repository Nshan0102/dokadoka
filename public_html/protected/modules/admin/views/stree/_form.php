<div class="form">

    <?php
        $form=$this->beginWidget('CActiveForm',
             array(
                 'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                ));

    ?>

	<p class="note">Поля со <span class="required">*</span> обязательны.</p>

	<?php echo CHtml::errorSummary($model); ?>


	<div class="row">
        id: <?php echo $model->id ?>, последнее изменение: <?php echo $model->update_time ?>
	</div>
    
	<div class="row">
		<?php echo $form->labelEx($model,'header'); ?>
		<?php echo $form->textField($model,'header',array('size'=>80,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'header'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'uri'); ?>
		<?php echo $form->textField($model,'uri',array('size'=>80,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'uri'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'visible'); ?>
		<?php echo $form->checkBox($model,'visible'); ?>
		<?php echo $form->error($model,'visible'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'html_title'); ?>
        <?php echo $form->textField($model, 'html_title',array('size'=>80,'maxlength'=>255)); ?>
        <?php echo $form->error($model, 'html_title'); ?>
	</div>

    <div class="row">
   		<?php echo $form->labelEx($model,'upper_text'); ?>
   		<?php echo $form->textArea($model, 'upper_text'); ?>
   		<?php echo $form->error($model,'upper_text'); ?>
   	</div>
       <div class="row">
   		<?php echo $form->labelEx($model,'text'); ?>
           <?php echo $form->textArea($model, 'text');?>
   		<?php echo $form->error($model,'text'); ?>
   	</div>

	<div class="row">
        <?php echo $form->labelEx($model,'meta_kw'); ?>
        <?php echo $form->textArea($model, 'meta_kw',array('cols'=>50,'rows'=>7)); ?>
        <?php echo $form->error($model, 'meta_kw'); ?>
	</div>
	<div class="row">
        <?php echo $form->labelEx($model,'meta_descr'); ?>
        <?php echo $form->textArea($model, 'meta_descr',array('cols'=>50,'rows'=>7)); ?>
        <?php echo $form->error($model, 'meta_descr'); ?>
	</div>

	<div class="row buttons">
        <script type="text/javascript">
        function refreshTree()
        {
            $('#simpletree_widget').jstree('refresh');
        }
        </script>
        <input type="button" id="page_struct_form_button1" value="Сохранить">
        <script type="text/javascript">

        jQuery('body').undelegate('#page_struct_form_button1','click')
                .delegate('#page_struct_form_button1','click',function(){


            for ( instance in CKEDITOR.instances )
                CKEDITOR.instances[instance].updateElement();

            jQuery.ajax({
                'success':function(data){
                    var r=jQuery.parseJSON(data);
                    if (r!=null && r.status)
                    {
                        refreshTree();
                        $('#simpletree_widget').jstree('select_node','#node_<?=$model->id ?>');//ok
                        showAdminMsg('Изменения сохранены',3000);
                    }
                    else
                    {
                        processResponseErrors(r);
                    }
                 },
                'type':'POST',
                'url':'/admin/stree/edit/id/<?php echo $model->id ?>',
                'cache':false,
                'data':jQuery(this).parents("form").serialize()}
            );
            return false;});

</script>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->