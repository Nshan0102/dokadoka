<?php

class CatalogApiController extends CController
{
    public function getAllowedIPs()
    {
        return array(
            '127.0.0.1',
            '93.84.118.132',//fireworks.by vps
            '86.57.249.148',//my
        );
    }

    public function __construct($id, $module = null)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        Yii::log("CatalogApi __construct");
        if(!in_array($ip,$this->getAllowedIPs()))
        {
            Yii::log("CatalogApi __construct incorrect IP: ".$ip);
            throw new CHttpException(403,'should not pass');
        }
        parent::__construct($id, $module);
    }

    private function responseOut($data)
    {
        print json_encode($data);
        Yii::app()->end();
    }

    public function actionGetCatsList()
    {
        $cats = Yii::app()->db->createCommand("SELECT id, header,uri FROM ".FrontendCategory::model()->tableName()."")->queryAll();
        $this->responseOut($cats);
    }

    public function actionGetItem()
    {
        $id =intval(Yii::app()->request->getQuery('id'));
        $q = "SELECT items.*, cats.header AS catname FROM ".FrontendItem::model()->tableName()." AS items
        LEFT JOIN ".FrontendCategory::model()->tableName()." AS cats ON cats.id = items.catid
        WHERE items.id=:id";
        $item = Yii::app()->db->createCommand($q)->queryRow(true,array(":id"=>$id));
        if(!$item)
            throw new CHttpException(404,'not found');
        $this->responseOut($item);
    }
    public function actionGetItems()
    {
        /*
        if(isset($_GET['full']))
            $fields='*';
        else
            $fields='id,header,price,unicode';
        */
        $fields='*';
        $irecs = Yii::app()->db->createCommand("SELECT ".$fields." FROM ".FrontendItem::model()->tableName())->queryAll();
        $this->responseOut($irecs);
    }

    private function modelErrorsToString(CActiveRecord $model)
    {
        $errors = $model->getErrors();
        $allStrings = array();
        $attributes = $model->attributeLabels();
        foreach($errors as $field=>$strings)
        {
            if(isset($attributes[$field]))
                $fn = $attributes[$field];
            else
                $fn = $field;
            foreach($strings as $str)
            {
                $allStrings[]='['.$fn.'] '.$str;
            }
        }
        return implode("<br>",$allStrings);
    }

    public function actionUpdateItem()
    {
        //if(!Yii::app()->request->getIsPostRequest())
        if(!$_POST)
        {
            Yii::log("CatalogApi - UpdateItem not post");
            throw new CHttpException(400,'use post, luke');
        }
        Yii::import('application.modules.admin.models.Item');


        $id = intval(Yii::app()->request->getQuery('id'));
        if($id) {
            $item = Item::model()->findByPk($id);
            if (!$item) {
                Yii::log("CatalogApi - UpdateItem not found: ".$id);
                throw new CHttpException(404, 'not found');
            }
        } else {
            $item = new Item();
        }
        $item->attributes = $_POST;
        if($item->save())
            $this->responseOut(array('status'=>1,'itemID'=>$item->id));
        else
            $this->responseOut(array('status'=>0,'itemID'=>0, 'error'=>$this->modelErrorsToString($item)));
    }
} 