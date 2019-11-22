<script type="text/javascript">
    var firstTime=false;
</script>
<?php
    $treeID='simpletree_widget';

    $this->widget('application.modules.admin.extensions.SimpleTreeWidget',
        array(
            'singleRoot'=>true,
            'model'=>'Page',
            'ajaxUrl' => $this->createAbsoluteUrl('/admin/stree/simpletree'),//
            'id'=>$treeID,
            'model' => 'Page',
            'modelPropertyName' => 'header',
            'modelPropertyId' => 'id',
            'modelPropertyParentId' => 'pid',
            'modelPropertyPosition' => 'order',

            'onRename'=>'
                var id = data.inst.get_selected().attr("id").replace("node_","");
            ',

            'onSelect'=>'
                var id = data.inst.get_selected().attr("id").replace("node_","");
                $("#contentBox").load("/admin/stree/edit/id/"+id, function(response, status, xhr) {
                    initCkeditor("Page_upper_text");
                    initCkeditor("Page_text");
                });
            ',

            'onCreate'=>'
                var newid=r.id;
                data.inst.select_node("#node_"+newid,true);
                showAdminMsg("Страница добавлена",3000);
            ',
        ));
?>