<?php

class SimpleTreeWidget extends CInputWidget
{
    private $baseUrl;
                    
    public $ajaxUrl;
    public $id = 'simpletree_widget';
    public $model;
    public $modelPropertyName = 'header';
    public $modelPropertyId = 'id';
    public $modelPropertyParentId = 'pid';
    public $modelPropertyPosition = 'order';
    public $theme = 'default';
    public $enableCSRFprotection=true;
    public $onSelect;
    public $onCreate;
    public $onMove;
    public $onRemove='';
    public $onRename='';
    public $processErrors='processResponseErrors(r)';

    public $singleRoot=false;

    //http://www.jstree.com/documentation/types
    public $max_depth='-2';//Defines maximum depth of the tree (-1 means unlimited, -2 means disable max_depth checking in the tree).
    public $max_children='-2';//Defines maximum number of root nodes (-1 means unlimited, -2 means disable max_children checking in the tree).

    public function run()
    {
        //if this is an Ajax request, do Ajax and die.
        //It is recommended to change $ajaxUrl to call SimpleTreeWidget::performAjax() directly from a controller.
        if (Yii::app()->request->isAjaxRequest && isset($_POST['simpletree']))
        {       
            self::performAjax();
            Yii::app()->end();
        }
        //register assets
        //$clientScript = Yii::app()->getClientScript();
        $clientScript = Yii::app()->clientScript;
        $dir = dirname(__FILE__).'/SimpleTree';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
        $clientScript->registerCoreScript('jquery');
        $clientScript->registerScriptFile($this->baseUrl . '/_lib/jquery.cookie.js', CClientScript::POS_HEAD);
        $clientScript->registerScriptFile($this->baseUrl . '/_lib/jquery.hotkeys.js', CClientScript::POS_HEAD);
        $clientScript->registerScriptFile($this->baseUrl . '/jquery.jstree.js', CClientScript::POS_HEAD);

        if ($this->enableCSRFprotection)
            $csrfParam='"'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'",';
        else
            $csrfParam='';
        
        //create container node
        echo "<div id='$this->id'>node</div>";
        $clientScript->registerScript('createJtree','

            var jsTreeLoaded=false;


            $("#'.$this->id.'")
	        .jstree({
            "plugins" : [ "themes", "json_data", "ui", "crrm", "cookies", "dnd", "types", "hotkeys", "contextmenu" ],
            "core" : {
                "animation" : 300
            },

            "ui": {
                "select_limit": 1 //no multiselect
            },

            "json_data" : {
                "ajax" : {
                    // the URL to fetch the data
                    "url" : "'.$this->ajaxUrl.'",
                    "data" : function (n) { 
                        return {
                            '.$this->getEnvironment().
                            ($this->singleRoot?'"singleRoot":"1",':'').'
                            "operation" : "get_children", 
                            "id" : n.attr ? n.attr("id").replace("node_","") : '.$this->modelId.'

                        }; 
                    }
                }
                
            },
            "themes" : {
                "theme" : "'.$this->theme.'",
                "url" : "'.$this->baseUrl.'/themes/'.$this->theme.'/style.css"
            },
            "types" : {
                "valid_children" : [],
                "max_depth" : '.$this->max_depth.',
                "max_children" : '.$this->max_children.',
                "types" : {
                    "readonly" : {
                        "delete_node" : false,
                        "icon" : {"image" : "'.$this->baseUrl.'/themes/readonly.png"}
                    }
                }
            },
            "contextmenu" : {
                items : {
                    //"rename" : false,
                    //"remove" : false,//Delete
                    "ccp" : false,
                    "create":{
                        "label"             : "Создать"
                         ,"action"            : function (obj) { this.create(obj); }
                    },
                    "remove":{
                        "label"             : "Удалить"
                         ,"action"            : function (obj) { this.remove(obj); }
                    },
                    "rename" : {
                        "label"             : "Переименовать"
                        ,"action"            : function (obj) { this.rename(obj); }
                        // All below are optional
                        ,"_disabled"         : false
                        ,"_class"            : "class"  // class is applied to the item LI node
                        ,"separator_before"  : false    // Insert a separator before the item
                        ,"separator_after"   : true     // Insert a separator after the item
                        // false or string - if does not contain `/` - used as classname
                        ,"icon"              : false
                        //,"submenu"           : {}
                    }
                }
            }
        })
        .bind("create.jstree", function (e, data) {
            var pid=data.rslt.parent.attr("id");
            if (typeof pid === "undefined")
                pid="node_0";//my fix, errors for root node
                
            $.post(
                "'.$this->ajaxUrl.'", 
                {
                    '.$csrfParam.$this->getEnvironment().'
                    "simpletree" : 1,
                    "operation" : "create_node", 
                    "id" : pid.replace("node_",""),
                    "position" : data.rslt.position,
                    "title" : data.rslt.name,
                    "type" : data.rslt.obj.attr("rel")

                }, 
                function (r) {
                    //alert("create.jstree GOT RESPONSE!");
                    if(r.status) {
                        $(data.rslt.obj).attr("id", "node_" + r.id);
                        '.$this->onCreate.'
                    }
                    else
                    {
                        if(r.errors)
                        {
                          '.$this->processErrors.'
                        }
                        $.jstree.rollback(data.rlbk);
                    }
                }
            );
        })
        .bind("remove.jstree", function (e, data) {
            data.rslt.obj.each(function () {
                if (data.inst._get_type(this) == "readonly")
                    return; 
                $.ajax({
                    async : false,
                    type: \'POST\',
                    url: "'.$this->ajaxUrl.'",
                    data : {
                        '.$csrfParam.$this->getEnvironment().'
                        "simpletree" : 1,
                        "operation" : "remove_node", 
                        "id" : this.id.replace("node_","")

                    }, 
                    success : function (r) {
                        if(!r.status)
                        {
                            data.inst.refresh();
                             '.$this->processErrors.'
                        }
                        else{
                        
                            '.$this->onRemove.'
                        }
                    }
                });
            });
        })
        .bind("rename.jstree", function (e, data) {

            if (data.inst._get_type(data.rslt.obj)=="readonly")//added 28.11.2011
            {
                $.jstree.rollback(data.rlbk);
                return;
            }

            $.post(
                "'.$this->ajaxUrl.'", 
                {
                    '.$csrfParam.$this->getEnvironment().'
                    "simpletree" : 1,
                    "operation" : "rename_node", 
                    "id" : data.rslt.obj.attr("id").replace("node_",""),
                    "title" : data.rslt.new_name

                }, 
                function (r) {
                    if(!r.status) {
                        $.jstree.rollback(data.rlbk);
                        '.$this->processErrors.'
                    }else{
                        '.$this->onRename.'
                    }
                }
            );
        })
        .bind("move_node.jstree", function (e, data) {
            data.rslt.o.each(function (i) {
                $.ajax({
                    async : false,
                    type: \'POST\',
                    url: "'.$this->ajaxUrl.'",
                    data : {
                        '.$csrfParam.$this->getEnvironment().'
                        "simpletree" : 1,
                        "operation" : "move_node", 
                        "id" : $(this).attr("id").replace("node_",""), 
                        "ref" :  data.rslt.np.attr("id") ? data.rslt.np.attr("id").replace("node_","") : 0,
                        "position" : data.rslt.cp + i,
                        "title" : data.rslt.name,
                        "copy" : data.rslt.cy ? 1 : 0

                    },
                    success : function (r) {
                        if(!r.status) {
                            $.jstree.rollback(data.rlbk);
                            '.$this->processErrors.'
                        }
                        else {
                            $(data.rslt.oc).attr("id", "node_" + r.id);
                            if(data.rslt.cy) {
                                data.inst.refresh(data.inst._get_parent(data.rslt.oc));
                            }
                            '.$this->onMove.'
                        }
                    }
                });
            });
        })

        .bind("select_node.jstree", function (e, data){
            '.$this->onSelect.'
        });', CClientScript::POS_END);
        
    }

    public function getEnvironment()
    {
        $model = is_string($this->model) ? $this->model : get_class($this->model);

        return '
            "model" : "'.$model.'",
            "modelPropertyId" : "'.$this->modelPropertyId.'",
            "modelPropertyParentId" : "'.$this->modelPropertyParentId.'",
            "modelPropertyPosition" : "'.$this->modelPropertyPosition.'",
            "modelPropertyName" : "'.$this->modelPropertyName.'",
        ';
        /*
        return '
            "model" : "'.$model.'",
        ';
        */
    }
    
    
    public function getModelId()
    {
        if (is_object($this->model))
            return($this->model->{$this->modelPropertyId});
        else
            return 0;
    }
    
    public function _get_children()
    {

        $children = array();
        $Model = new $_REQUEST['model'];
        //$reqID=$_REQUEST['id'];

        $modelPropertyParentId=$_REQUEST['modelPropertyParentId'];
        $modelPropertyId=$_REQUEST['modelPropertyId'];
        $modelPropertyPosition=$_REQUEST['modelPropertyPosition'];
        $modelPropertyName=$_REQUEST['modelPropertyName'];

        //$_REQUEST['modelPropertyParentId']
        //if (isset($_GET['rootCondition']))
        if (isset($_GET['singleRoot']))
        {
            //$rootCondition=$_GET['rootCondition'];
            $rootCondition=$modelPropertyParentId."='0'";
            $rootNode = $Model->find($rootCondition);
            if (!$rootNode)
            {
                //die(json_encode(array('status'=>0,'errors'=>array('incorrect root condition!'))));
                die('ERROR: incorrect root condition:'.$rootCondition);
            }
        }
        else
            $rootNode=false;


        $reqID = isset($_GET['id'])?intval($_GET['id']):0;

        if ($reqID==0)
        {
            //$rootPage  =  ClientPage::model()->findByUriAndCid('index',$this->owner->id);
            //$pid=$rootPage->id;

            if ($rootNode)
                $pid=$rootNode->{$modelPropertyId};
            else
                $pid=0;
        }
        else
        {
            //$rootPage=null;
            $pid=$reqID;
        }


        $tableName=$Model->tableName();

        $dbCmd=Yii::app()->db->createCommand()
                                       ->select('pages.'.$modelPropertyId.',pages.'.$modelPropertyName.',pages.'.$modelPropertyParentId.', (subpages.'.$modelPropertyId.' IS NOT NULL) AS hasChildren')
                                       ->from($tableName.' AS pages')
                                       ->leftJoin($tableName.' AS subpages', 'subpages.'.$modelPropertyParentId.'=pages.'.$modelPropertyId);


        $childs = $dbCmd->where('pages.'.$modelPropertyParentId.'=:pid', array(':pid'=>$pid))
                   ->group('pages.'.$modelPropertyId)
                   ->order('pages.'.$modelPropertyPosition)
                   ->queryAll();


        foreach ($childs  as $k => $row)
        {
            $children[$k]["data"] = $row['header'];
            $children[$k]["attr"]["id"] = "node_".$row[$modelPropertyId];
            $children[$k]["attr"]["rel"] = "default";
            if ($row['hasChildren'])
                $children[$k]["state"] = "closed";
        }

        //if (!$_REQUEST['id'])
        if (!$reqID)
        {
            if ($rootNode)
            {
                $children = array(
                    "data" => $rootNode->{$modelPropertyName},
                    "attr" => array('id'=>$rootNode->{$modelPropertyId},"rel"=>"readonly"),
                    "children" => $children,
                    "state" => $children ? 'open' : ''
                    //,"attr" => array()//my tst

                 );
            }
            else
            {
                $children = array(
                    "data" => "Корень",
                    "attr" => array('id'=>0),
                    "children" => $children,
                    "state" => $children ? 'open' : '',
                    "attr" => array("rel"=>"readonly")//my tst
                 );
            }
        }
        
        echo json_encode($children);
        Yii::app()->end();
    }
    

    
    static function performAjax()
    {
        //Yii::import('application.models.'.$_REQUEST['model']); //my tst
        Yii::import('application.modules.admin.models.'.$_REQUEST['model']); //my tst
        $Model = new $_REQUEST['model'];
        $method = '_'.$_REQUEST['operation'];
        
        header("HTTP/1.0 200 OK");
        header('Content-type: text/json; charset=utf-8');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Pragma: no-cache");


        self::$method($_POST);
        
    }
    
    static function _create_node($params)
    {
        $Model = new $params['model'];
        $Model->$params['modelPropertyParentId'] = $params['id'];
        $Model->$params['modelPropertyName'] = $params['title'];
        @$Model->$params['modelPropertyPosition'] = $params['position'];
        
        if($Model->save())
            echo json_encode(array('status'=>1, 'id'=>$Model->$params['modelPropertyId']));
        else//my
        {
            $errors=$Model->getErrors();
            echo json_encode(array('status'=>0,'errors'=>$errors));//print_r($Model->getErrors());
        }

    }
    
    static function _remove_node($params)
    {   
        self::_removeModelRecursively($params);
        echo json_encode(array('status'=>1));
    }
    
    static function _rename_node($params)
    {
        $Model = new $params['model'];
        $Model = $Model->findByPk($params['id']);
        $Model->$params['modelPropertyName'] = $params['title'];
        if($Model->save())
            echo json_encode(array('status'=>1));
        else//my
        {
            $errors=$Model->getErrors();
            echo json_encode(array('status'=>0,'errors'=>$errors));//print_r($Model->getErrors());
        }
    }
    
    
    static function _move_node($params)
    {   
        $params['ref'] = (int)$params['ref'];
        
        //get model
        $Model = new $params['model'];
        $Model = $Model->findByPk($params['id']);
        
        //copy and die?
        if($params['copy'])
        {
            $Model = self::_copy_node($Model, $params);
        }
        
        //get new siblings
        $criteria=new CDbCriteria;
        $criteria->select ='t.*';
        $criteria->order='t.'.$params['modelPropertyPosition'];
        $criteria->condition=$params['modelPropertyParentId'].'='.$params['ref'] . ' AND ' . $params['modelPropertyId'] . '!='.$params['id'];
        $siblings=$Model->findAll($criteria);       
        
        //if item is moved to a higher position ID in it's current folder, make sure to substract it's old position as the item only exists once
        if ($Model->$params['modelPropertyParentId'] == $params['ref'] && $Model->$params['modelPropertyPosition'] < $params['position'])
            $params['position']--;
        
        //save model
        $Model->$params['modelPropertyPosition'] = $params['position'];
        $Model->$params['modelPropertyParentId'] = $params['ref'];
        $Model->save();
        
        //assign positions to siblings
        $i = 0;
        foreach ($siblings AS $Sibling)
        {
            //params position is reserved, so iterate by it
            if($i == $params['position'])
                $i++;
            $Sibling->$params['modelPropertyPosition'] = $i;
            $Sibling->save();
            $i++;
        }
        
        echo json_encode(array('status'=>1));
    }
    
    static function _copy_node($Model, $params, $inheritPosition = false)
    {
        $NewModel = new $params['model'];
        $NewModel->attributes = $Model->attributes;
        
        //copy these, even if they're unsafe values
        $NewModel->{$params['modelPropertyName']} = $Model->{$params['modelPropertyName']};
        $NewModel->{$params['modelPropertyParentId']} = $params['ref'];
        $NewModel->{$params['modelPropertyPosition']} = $inheritPosition? $Model->{$params['modelPropertyPosition']} : $params['position'];
        
        if ($NewModel->save())
        {
            //copy children
            foreach ($NewModel->findAllByAttributes(array($params['modelPropertyParentId']=>$Model->{$params['modelPropertyId']})) as $Child)
            {
                $params['ref'] = $NewModel->{$params['modelPropertyId']};
                self::_copy_node($Child, $params, true);
            }
            return $NewModel;//@todo check
        }
        else//my
        {
            $errors=$Model->getErrors();
            echo json_encode(array('status'=>0,'errors'=>$errors));//print_r($Model->getErrors());
        }

    }
    
    static function _removeModelRecursively($params)
    {   
        if (!$params['id'] || preg_match('/[^\d]/',$params['id']))
            return json_encode(array('status' => 0,'errors'=>'Incorrect node id'));//@todo errors should be array?
        
        $Model = new $params['model'];
        $Model = $Model->findByPk($params['id']);
        foreach($Model->findAllByAttributes(array($params['modelPropertyParentId'] => $params['id'])) AS $Child)
        {
            $params['id'] = $Child->$params['modelPropertyId'];
            self::_removeModelRecursively($params);
        }
        $Model->delete();
    }
}


?>