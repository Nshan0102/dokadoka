<?php $this->pageTitle='Ошибка'; ?>

<div style="float:left; width:700px; background-color:#f1faff;">
<center>Error <?=$code; ?>

<div class="error">
    <?php
    echo CHtml::encode($message);
    //print_r($_GET);
    ?>
	<br /><br />
	<span style="font-size:18px;">Выбирайте что Вас интересует:</span>
</div>
	</center>
<p style="margin-left: 40px; ">
	<br />
<div style="float:left;margin-left:50px;">
	
					<p>
						<a href="/category/malye" title="малые батареи салютов"><img alt="малые батареи салютов" src="/i/batarei-salyutov-malye.jpg" /></a></p>

					<p>
						<a href="/category/srednie" title="средние батареи салютов"><img alt="средние батареи салютов" src="/i/batarei-salyutov-srednie.jpg" /></a></p>

					<p>
						<a href="/category/bolshie" title="большие батареи салютов"><img alt="большие батареи салютов" src="/i/batarei-salyutov-bolshie.jpg" /></a></p>

					<p>
						<a href="/category/super" title="супер батареи салютов"><img alt="супер батареи салютов" src="/i/super-batarei-salyutov.jpg" /></a></p>

</div>
</div>
</div>