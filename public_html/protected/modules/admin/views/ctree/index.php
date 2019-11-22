<?php $this->pageTitle=Yii::app()->name; ?>

<h2>Категории</h2>

<?php

$this->widget('application.modules.admin.extensions.jfu.JFUheaderWidget');

$this->widget('application.modules.admin.extensions.wyswyg.Wyswyg',
        array(
            "type"=>'ckeditor',
            "height"=>'375',
            "width"=>'100%',
        )
);
?>

<div id='stree_div' style="float: left; width: 220px; display: inline;overflow: hidden;">
    <?php echo $this->renderPartial('ajaxtree',null,false,false);?>
</div>

<div id='contentBox' style="float: left; vertical-align: top; display: inline; padding-left: 10px;">

</div>

<script type="text/javascript">
function deleteImage(modelid)
{
    jQuery.ajax({
        'success':function(data){
            var r=jQuery.parseJSON(data);
            if (r!=null && r.status)
            {
                refreshTree();
                $('#category_simpletree').jstree('select_node','#node_'+modelid);
                showAdminMsg('Изображение удалено',3000);
            }
            else
            {
                processResponseErrors(r);
            }
         },
        'type':'POST',
        'url':'/admin/ctree/deleteimage/id/'+modelid,
        'cache':false,
        'data':{"<?=Yii::app()->request->csrfTokenName?>":"<?=Yii::app()->request->csrfToken ?>"}
        }
    );
}
</script>