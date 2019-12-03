<?php
class ItemsController extends CController
{

    public $layout='/layouts/column1';

    public $modelName='Item';

/*
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
*/

    public function actionSaveVisibility()
    {
        if (isset($_POST['ids']) && is_array($_POST['ids']))
        {
            //print_r($_POST);
            foreach($_POST['ids'] as $itemid=>$checked)
            {
                if ($checked!=1)
                    $checked=0;
                Yii::app()->db->createCommand("UPDATE ".Item::model()->tableName()." SET visible=".$checked." WHERE id=:id")->execute(array(":id"=>$itemid));
            }
        }
    }
    public function actionDelete()
    {
        if(!isset($_GET['id']))
            throw new CHttpException(404, 'Товар не найден');

       $model=CActiveRecord::model($this->modelName)->findByPk($_GET['id']);
       if (is_null($model))
           throw new CHttpException(404, 'Товар не найден');

       $model->delete();

    }

    //tests
    public function actionTtt()
    {

        /*
        $criteria=new CDbCriteria;

       // Do all joins in the same SQL query
       $criteria->together  =  true;
       // Join the 'category' table
       $criteria->with = array('category');

       //укажем какие поля нам нужны вообще
       $criteria->select = 'id,header,price,uri,catid';



        $items = Item::model()->findAll($criteria);
        foreach ($items as $item)
        {
            $item->dumpAttributes();
        }
        */
    }


    public function actionList()
    {

        $model = new Item('search');
        $model->unsetAttributes();  // clear any default values
        $model->visible = 1;

        if(isset($_GET[$this->modelName]))
            $model->attributes=$_GET[$this->modelName];

        $this->render('list',array('model'=>$model));
    }

    public function actionItemForm()
    {


        if(!isset($_GET['id']))
            $model = new $this->modelName;
        else
        {
            $model=CActiveRecord::model($this->modelName)->findByPk($_GET['id']);
            if (is_null($model))
                throw new CHttpException(404);
        }

        /* //не работает при enctype
        if(isset($_POST['ajax']) && $_POST['ajax']==='item-itemForm-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }*/



        if(isset($_POST[$this->modelName]))
        {
            if (isset($_POST['delimage'])) //удаление маленькой картинки
            {
                $imgField='image';
                if (!empty($model->$imgField))
                {
                    $imgPath = dirname(Yii::app()->request->scriptFile).$model->$imgField;
                    if (is_file($imgPath))
                        unlink($imgPath);
                    Yii::app()->db->createCommand("UPDATE `".$model->tableName()."` SET `".$imgField."`=NULL WHERE id=".$model->getPrimaryKey())->execute();
                    $model->$imgField = null;//чтобы снова не загружать AR из БД
                }
                Yii::app()->user->setFlash('success', "Малое изображение удалено");
                $this->redirect(array('/admin/items/itemForm/id/'.$model->id));
            }

            if (isset($_POST['delimagebig'])) //удаление большой картинки
            {
                $imgField='imagebig';
                if (!empty($model->$imgField))
                {
                    $imgPath = dirname(Yii::app()->request->scriptFile).$model->$imgField;
                    if (is_file($imgPath))
                        unlink($imgPath);
                    Yii::app()->db->createCommand("UPDATE `".$model->tableName()."` SET `".$imgField."`=NULL WHERE id=".$model->getPrimaryKey())->execute();
                    $model->$imgField = null;//чтобы снова не загружать AR из БД
                }
                Yii::app()->user->setFlash('success', "Большое изображение удалено");
                $this->redirect(array('/admin/items/itemForm/id/'.$model->id));
            }

            //должно быть ДО того, как начнём работать с картинкой
            $model->attributes=$_POST[$this->modelName];

            $relativeImagesPath='/media/';
            $webRoot = dirname(Yii::app()->request->scriptFile);


            //загрузка маленькой картинки
            $imgField1='image';
            $image1 = CUploadedFile::getInstance($model, $imgField1);
            if ($image1)
            {
                $fullPath = $webRoot.$relativeImagesPath.(string)$image1;
                $model->$imgField1 = $relativeImagesPath.Helpers::getUniqFilenameInDir($fullPath);
            }
            //загрузка большой картинки
            $imgField2='imagebig';
            $image2 = CUploadedFile::getInstance($model, $imgField2);
            if ($image2)
            {
                $fullPath = $webRoot.$relativeImagesPath.(string)$image2;
                $model->$imgField2 = $relativeImagesPath.Helpers::getUniqFilenameInDir($fullPath);
            }

            if($model->save())
            {
                if ($image1)
                    $image1->saveAs($webRoot.$model->$imgField1);
                if ($image2)
                    $image2->saveAs($webRoot.$model->$imgField2);

                //Yii::app()->user->setFlash('success',"webroot:".$webRoot.",".$model->$imgField1);

                Yii::app()->user->setFlash('success',"Товар сохранён");
//                $this->redirect(array('/admin/items/list'));
                $this->redirect(array('/admin/items/itemForm/id/'.$model->id));//tst id in new

            }
            else
            {
                //Yii::app()->user->setFlash('error', "Ошибка при сохранении: ".var_export($model->getErrors()));
                /*array ( 'header' => array ( 0 => 'Необходимо заполнить поле Название.', ), 'uri' => array ( 0 => 'Необходимо заполнить поле ЧПУ.', ), ) */
                Yii::app()->user->setFlash('error', "Ошибки при сохранении");//они выведутся на форме под нужными полями
            }
        }
        $this->render('itemForm',array('model'=>$model));
    }
}
?>