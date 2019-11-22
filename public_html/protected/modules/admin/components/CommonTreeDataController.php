<?php
class CommonTreeDataController extends CController
{
    /**
	 * @var string the default layout for the views.
     * - "//layouts/main" указывает на «protected/views/layouts/main.php» (если «protected» — это базовая директория приложения)
     *
     *  - "/layouts/main" указывает на «protected/modules/abc/views/layouts/main.php» (если «abc» — это текущий рабочий модуль. Если нет, то это тоже самое, что и "//layouts/main")
	 */
	public $layout='/layouts/column1';

    protected $modelName='Unknown';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    protected  $_model;


    public function actionAjaxTree()
    {
        $this->renderPartial('ajaxtree',null,false,false);
        //$this->renderPartial('ajaxtree');
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    //ajaxController
    public function actionsimpletree()
    {
        Yii::import('application.modules.admin.extensions.SimpleTreeWidget');
        SimpleTreeWidget::performAjax();
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @return CActiveRecord
     */
    public function loadModel()
    {
        if($this->_model===null)
        {
            if(isset($_GET['id']))
            {
                /*
                if(Yii::app()->user->isGuest)
                    $condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
                else*/
                    $condition='';

                //$new
                //$this->_model=->findByPk($_GET['id'], $condition);

                $this->_model=CActiveRecord::model($this->modelName)->findByPk($_GET['id'], $condition);
            }
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }


    //public function actionGetForm()
    public function actionEdit()
    {
        //if(!Yii::app()->request->isAjaxRequest)
        //    return;

        //Avoiding duplicate script download when using CActiveForm on Ajax calls
        //http://www.yiiframework.com/wiki/231/avoiding-duplicate-script-download-when-using-cactiveform-on-ajax-calls/
        Yii::app()->clientScript->corePackages = array();


        $model=$this->loadModel();

        if(isset($_POST[$this->modelName]))
        {
            $this->saveModel($model);
        }


        $this->renderPartial('_form', array('model'=>$model), false, true);
        //$this->renderPartial('_form', array('model'=>$model));
    }

    /**
     * @param CActiveRecord $model
     */
    protected function saveModel($model)
    {
        $model->attributes=$_POST[$this->modelName];
        if($model->save())
            echo CJSON::encode(array('status'=>1, 'id'=>$model->id));//$this->redirect(array('edit','id'=>$model->id));
        else
        {
            $errors=$model->getErrors();
            echo CJSON::encode(array('status'=>0,'errors'=>$errors));//print_r($Model->getErrors());
        }
        die();
    }


}
?>