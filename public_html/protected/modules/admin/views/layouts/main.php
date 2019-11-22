<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<script type="text/javascript">
    //@todo admin funcs to separate file, register in common admin controller
    function showAdminMsg(txt, timeout)
    {
        $('#admin_msg_div').css('display','block').html(txt);
        if (timeout>0)
        {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#admin_msg_div').animate({opacity: 1.0}, timeout).fadeOut("slow");
        }
        //$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");
    }

    function processResponseErrors(resp)
    {
        if (resp!=null && resp.errors)
        {
             var errLines='';
             $.each( resp.errors, function(k, v){
                   errLines+=v+'<br>';
             });
             $("html, body").animate({ scrollTop: 0 }, "slow");
             showAdminMsg(errLines,0);
        }
    }
</script>
<div class="container" id="page">
	<div id="mainmenu">

    <?php

        if (!Yii::app()->adminUser->isGuest)
            $this->widget('zii.widgets.CMenu',array(
                'items'=>array(
                    array('label'=>'Начало', 'url'=>array('/admin/main')),
                    //array('label'=>'Реклама', 'url'=>array('/admin/adv/')),
                    array('label'=>'Сервис', 'url'=>array('/admin/main/service')),
                    array('label'=>'Структура страниц', 'url'=>array('/admin/stree/index')),
                    array('label'=>'Категории каталога', 'url'=>array('/admin/ctree/index')),
                    array('label'=>'Товары', 'url'=>array('/admin/items/list')),
                  //  array('label'=>'Заказы', 'url'=>array('/admin/globalOrders/index')),

                    array('label'=>'Logout ('.Yii::app()->adminUser->name.')',
                            'url'=>'#',
                            'linkOptions'=>array('csrf' => true,'submit' => '/admin/main/logout'/*array('item/delete', 'id'=>$item->id)*/, /*'confirm' => 'Точно выходим?'*/),
                    ),
                ),
            ));
    ?>
	</div><!-- mainmenu -->


    <?php foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo "<div class='flash-{$key}'>{$message}</div>";
    } ?>

    
    <div id='admin_msg_div' style=""></div>

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by s@nchez<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>