<?php $this->pageTitle='Реклама'; ?>

<h1>Редактирование рекламной площадки</h1>
<?php
foreach(Yii::app()->user->getFlashes() as $key => $message)
{
    echo "<div class='flash-{$key}'>{$message}</div>";
}
?>


<?php
    $form=$this->beginWidget('CActiveForm'
    /*
                             array(
                                'id'=>'page_form1',
                                'enableAjaxValidation'=>TRUE,
                                'clientOptions'=>array('validateOnSubmit'=>TRUE),
                                )
    */
);

?>

<div class="form">

	<?php
    echo CHtml::errorSummary($this->_model);
    ?>


	<div class="row">
        code: <?php echo $this->_model->code ?>
	</div>
	<div class="row">
        название: <b><?php echo $this->_model->name ?></b>
	</div>

	<div class="row">
		<?php echo $form->labelEx($this->_model,'text'); ?>
		<?php
            //echo CHtml::activeTextArea($model,'text',array('rows'=>10, 'cols'=>70));

       $this->widget('application.extensions.editor.CKkceditor',array(
        "model"=>$this->_model,                # Data-Model
        "attribute"=>'text',         # Attribute in the Data-Model
        "height"=>'400px',
        "width"=>'95%',
        "filespath"=>dirname(Yii::app()->request->scriptFile)."/media/",
        "filesurl"=>Yii::app()->baseUrl."/media/",


        "config"=>array(
            //'autoUpdateElement'=>1,
            'toolbar' => array(
                array('Source','-','AjaxSave','Preview'),
                array('Cut','Copy','Paste','PasteText','PasteFromWord'),
                array('Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
                '/',
                array('Bold','Italic','Underline','Strike','-','Subscript','Superscript'),
                array('NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'),
                array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
                array('Link','Unlink','Anchor'),
                array('Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'),
                '/',
                array('Styles','Format','Font','FontSize'),
                array('TextColor','BGColor'),
                array('Maximize', 'ShowBlocks')

            ),
            'language'=>'ru',
            //'language'=>'en',

    ) ));
        ?>
		<?php echo $form->error($this->_model,'text'); ?>
	</div>

	


	<div class="row buttons">
		<?php
           echo CHtml::submitButton('Сохранить');

            /*
            echo CHtml::ajaxSubmitButton('Сохранить',
                                         $this->createUrl('/admin/adv/update/id/'.$this->_model->code)
                                         );
            */

        ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->