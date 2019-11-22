<?php
$this->pageTitle='Контакты';
/** @var $form CActiveForm */
/** @var $model ContactForm */
/** @var $this SiteController */
?>


<div class="content news">
<?if(Yii::app()->user->hasFlash('contact')): ?>
<div class="flash-success" style="float:left; background-color:#f1faff; margin:10px 10px 10px 20px; padding:10px;">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?else: ?>



<? $form=$this->beginWidget('CActiveForm', array('id'=>'contact-form')); ?>

<b>Телефоны для справок:</b><br /><br />
VEL <a href="tel:+375290000000">+375290000000</a><br /><br />
MTS <a href="tel:+375330000000">+375330000000</a><br /><br />
<!--<b>Skype:</b>    <a href="skype:fireworks.by?add">Добавить пользователя fireworks.by в контакт лист Skype</a>-->




<p><h2>Написать письмо:</h2></p>
	<?php echo $form->errorSummary($model); ?>
    <table width="600" border="0">
  <tr>
    <td><div class="row"><?php echo $form->labelEx($model,'name'); ?></div></td>
    <td><?php echo $form->textField($model,'name'); ?>
      <?php echo $form->error($model,'name'); ?></td></tr>
  <tr>
    <td><div class="row"><?php echo $form->labelEx($model,'email'); ?></div></td>
    <td><?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?></td></tr>
  <tr>
    <td><div class="row"><?php echo $form->labelEx($model,'phone'); ?></div></td>
    <td><?php echo $form->textField($model,'phone'); ?>
		<?php echo $form->error($model,'phone'); ?></td>
  </tr>
  <tr>
    <td valign="top" style="vertical-align:top;"><div class="row"><?php echo $form->labelEx($model,'body'); ?></div></td>
    <td><?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'body'); ?></td></tr>
  <tr>
    <td><div class="row"><?php echo $form->labelEx($model,'verifyCode'); ?></div></td>
    <td><div><?php echo $form->error($model,'verifyCode'); ?><?php echo $form->textField($model,'verifyCode'); ?></div><div style="width:150px;"><?php $this->widget('CCaptcha'); ?></div></td></tr>
  <tr>
    <td>&nbsp;</td>
    <td><div class="row buttons"> <?php echo CHtml::submitButton('Отправить'); ?> </div></td></tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr></table>
<? $this->endWidget(); ?>



<? endif; ?>

</div>