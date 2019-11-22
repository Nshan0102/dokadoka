<?php
class StreeController extends CommonTreeDataController//Controller
{

    protected $modelName = 'Page';


	/**
	 * @return array action filters
	 */
    /*
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
    */

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */

    /*
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    */


    /*
    public function actionSave()
    {
        $model=$this->loadModel();


        $this->renderPartial('_form',array('model'=>$model,), false, true);

    }
    */




    //public function actionGetForm()
    public function actionEdit()
    {
        //if(!Yii::app()->request->isAjaxRequest)
        //    return;

        //Avoiding duplicate script download when using CActiveForm on Ajax calls
        //http://www.yiiframework.com/wiki/231/avoiding-duplicate-script-download-when-using-cactiveform-on-ajax-calls/
        Yii::app()->clientScript->corePackages = array();

        
        $model=$this->loadModel();

        if(isset($_POST['Page']))
        {
            //print 'tttt';
            $model->attributes=$_POST['Page'];
            if($model->save())
                echo json_encode(array('status'=>1, 'id'=>$model->id));//$this->redirect(array('edit','id'=>$model->id));
            else
            {
                $errors=$model->getErrors();
                echo json_encode(array('status'=>0,'errors'=>$errors));//print_r($Model->getErrors());
                //print_r($model->getErrors());//@todo!
            }
            Yii::app()->end();
        }

        //print_r($page);

        $this->renderPartial('_form', array('model'=>$model), false, true);
        //$this->renderPartial('_form', array('model'=>$model));
    }




}
?>