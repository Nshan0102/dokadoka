<?php
class CtreeController extends CommonTreeDataController //Controller
{
    protected $modelName = 'Category';

    public function actions()
    {
        return array(
            'upload' => array(
                'class' => 'application.modules.admin.extensions.jfu.actions.JFUAction',
                //'subfolderVar' => 'parent_id',
                'path' => "media",
                'modelName' => $this->modelName,
                'modelAttributeName' => 'image',
            ),
        );
    }

    public function actionIndex()
    {
        //Yii::import('ext.jfu.JFUWidget');
        //JFUWidget::publishAssets();
        $this->render('index');
    }

    /**
     * @param Category $model
     */
    /*
    protected function saveModel($model)
    {

        if (isset($_POST['delimage'])) //удаление картинки
        {
            $pathFull = Helpers::getWebRootPath()."media/".$model->image;
            if (file_exists($pathFull))
                @unlink($pathFull);

            Yii::app()->user->setFlash('success', "Изображение удалено");
            Yii::app()->db->createCommand("UPDATE " . $model->tableName()." SET `image`=NULL WHERE id=".$model->id)->execute();
            $this->redirect(array('admin/ctree/edit/id/'.$model->id));//@todo tst
        }

        //http://www.yiiframework.com/wiki/2/   (details in comments)
        $savedMainImgName = false;
        $image = CUploadedFile::getInstance($model, 'image');
        if ((is_object($image) && get_class($image) === 'CUploadedFile'))
        {
            //die((string)$image);
            $ext=strtolower(CFileHelper::getExtension((string)$image));
            if (Helpers::isImage($ext))
            {
                $savedMainImgName = $model->id . '.' .$ext ;
                //@todo get uniq filename in dir method here!
                $model->image = $savedMainImgName;
            }
        }


        $model->attributes = $_POST[$this->modelName];
        if ($model->save())
        {

            if ($savedMainImgName)
            {
                $image->saveAs(Helpers::getWebRootPath()."media/".$savedMainImgName);
                $this->redirect(array('edit','id'=>$model->id));//@todo tst
            }
            echo json_encode(array('status' => 1, 'id' => $model->id)); //$this->redirect(array('edit','id'=>$model->id));
        }
        else
        {
            $errors = $model->getErrors();
            echo json_encode(array('status' => 0, 'errors' => $errors)); //print_r($Model->getErrors());
        }
        die();
    }
    */
    public function actionDeleteimage()
    {
        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(403,'Access denied - not a POST request');
        $model=$this->loadModel();

        $pathFull = dirname(Yii::app()->request->scriptFile).$model->image;
        if (file_exists($pathFull))
            @unlink($pathFull);
        Yii::app()->db->createCommand("UPDATE " . $model->tableName()." SET `image`=NULL WHERE id=".$model->id)->execute();
        echo CJSON::encode(array('status'=>1, 'id'=>$model->id));
    }

    /*
    public function actionUploadimage()
    {
        $rootPath=Helpers::getWebRootPath();
        //file_put_contents(Helpers::getWebRootPath()."log.log","uploadimageAction",FILE_APPEND);

        //[{"name":"picture1.jpg","size":902604,"url":"\/\/example.org\/files\/picture1.jpg","thumbnail_url":"\/\/example.org\/thumbnails\/picture1.jpg","delete_url":"\/\/example.org\/upload-handler?file=picture1.jpg","delete_type":"DELETE"},{"name":"picture2.jpg","size":841946,"url":"\/\/example.org\/files\/picture2.jpg","thumbnail_url":"\/\/example.org\/thumbnails\/picture2.jpg","delete_url":"\/\/example.org\/upload-handler?file=picture2.jpg","delete_type":"DELETE"}]

        $resp = array("name"=>"pic1.jpg",
            "size"=>12345,
            "url"=>"/media/img.jpg",
            "thumbnail_url"=>"/media/img.jpg",
            "delete_url"=>"/admin/ctree/deleteimage",
            "delete_type"=>"DELETE"
        );
        //header('Content-type: application/json');

        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            header('Content-type: application/json');
        else
            header('Content-type: text/plain');

        print json_encode(array($resp));
    }
    */

}

?>