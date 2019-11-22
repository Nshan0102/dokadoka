<?php
$treeID='category_simpletree';
    $this->widget('application.modules.admin.extensions.SimpleTreeWidget',
        array(
            'model'=>'Page',
            'ajaxUrl' => $this->createAbsoluteUrl('/admin/ctree/simpletree'),
            'id'=>$treeID,
            'model' => 'Category',
            'modelPropertyName' => 'header',
            'modelPropertyId' => 'id',
            'modelPropertyParentId' => 'pid',
            'modelPropertyPosition' => 'order',
            'max_depth' => '3',

            'onSelect'=>'
                var id = data.inst.get_selected().attr("id").replace("node_","");
                $("#contentBox").load("/admin/ctree/edit/id/"+id, function(response, status, xhr) {
                    initCkeditor("Category_upper_text");
                    initCkeditor("Category_text");
                });
                ',
            'onCreate'=>'
                var newid=r.id;
                data.inst.select_node("#node_"+newid,true);
                showAdminMsg("Категория добавлена",3000);
            ',
        ));
?>