<?php $this->pageTitle='Реклама'; ?>
<h1>Рекламные блоки</h1>
<script type="text/javascript">
    function loadAdvPreview(code)
    {
        //alert("id:"+code);
        $('#adv_preview_block').html('<img src="/images/admin/loading.gif" />');
        $.get('/admin/adv/view/id/'+code, function(data){$('#adv_preview_block').html(data)});
    }
</script>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
      'dataProvider'=>$dataProvider,
      'columns' => array(
            'code', 'name',
          array(            // display a column with "view", "update" and "delete" buttons
                    'class'=>'CButtonColumn',


                    'template'=>'{update}{view}',
                    'buttons'=>array
                    (
                        /*
                        'update' => array
                        (
                            'label'=>'Редактировать',
                            'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
                            'url'=>'Yii::app()->createUrl("admin/adv", array("code"=>$data->code))',
                        ),
                        */
                        'view' => array
                        (
                            'label'=>'посмотреть',
                            'url'=>'$data->code',
                            'click'=>'function(){loadAdvPreview($(this).attr("href"));return false;}',

                            //I suppose it won't work. 'click' can't contain $data or $row references, only url. Check out help:
                            //In the PHP expression for the 'url' option and/or 'visible' option, the variable $row refers to the current row number (zero-based), and $data refers to the data model for the row.
                            //via http://www.yiiframework.com/forum/index.php?/topic/9751-refresh-cgridview-after-cbuttoncolumn-ajax-request/
                        ),
                    ),

              
                 ),
      ),
    //'selectionChanged'=>'updateEditForm',//a javascript function that will be invoked after the row selection is changed.
  ));

/*
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'title',          // display the 'title' attribute
        'category.name',  // display the 'name' attribute of the 'category' relation
        'content:html',   // display the 'content' attribute as purified HTML
        array(            // display 'create_time' using an expression
            'name'=>'create_time',
            'value'=>'date("M j, Y", $data->create_time)',
        ),
        array(            // display 'author.username' using an expression
            'name'=>'authorName',
            'value'=>'$data->author->username',
        ),
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        ),
    ),
));

*/
?>

<b>Превью</b><br>
<div id="adv_preview_block"></div>