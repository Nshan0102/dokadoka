<?php $this->pageTitle='Сервис'; ?>

<div class="form smallcenter">
    <h1>Сервис</h1>
    <h2>Очистить кэш</h2>
    <?php $form=$this->beginWidget('CActiveForm', array('id'=>'clear-cache-form',)); ?>
        <div class="row submit">
            <?php echo CHtml::submitButton('Очистить',array('name'=>'clear_cache')); ?>
        </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->



<div class="form smallcenter">
    <h2>Изменить свой пароль</h2>
    <?php $form=$this->beginWidget('CActiveForm', array('id'=>'change-pass-form',)); ?>
        <div class="row">
            <?php echo CHtml::label('Новый пароль','admin_password'); ?>
            <?php echo CHtml::textField('admin[password]'); ?>
        </div>
        <div class="row">
            <?php echo CHtml::label('Повторите пароль','admin_password_repeat'); ?>
            <?php echo CHtml::textField('admin[password_repeat]'); ?>
        </div>

        <div class="row submit">
            <?php echo CHtml::submitButton('Изменить',array('name'=>'change_pass')); ?>
        </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->


