<?php

class MainController extends CController
{

    /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column1';

	public function actionIndex()
	{
		$this->render('index');
	}


    public function actionService()
    {
        //print_r(Yii::app()->user);die();
        if (isset($_POST['clear_cache']))
        {
            Yii::app()->cache->flush();
            Yii::app()->user->setFlash('success',"Кэш очищен");
        }
        if (isset($_POST['change_pass']) && isset($_POST['admin']))
        {

            $model = Admin::model()->findbyPk(Yii::app()->adminUser->id);
            $model->scenario='changeAdminPass';
            $model->attributes=$_POST['admin'];
            if ($model->save())
                Yii::app()->user->setFlash('success',"Пароль изменён");
            else
            {
                $errStrings = array();
                foreach ($model->getErrors() as $ferrs)
                {
                    foreach ($ferrs as $ferr)
                        $errStrings[]=$ferr;
                }
                Yii::app()->user->setFlash('error',implode('<br/>',$errStrings));
            }
        }
        $this->render('service');
    }

    public function actionLogin()
    {

        $model=new AdminLoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['AdminLoginForm']))
        {
            $model->attributes=$_POST['AdminLoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
            {
                //

                /*
                file_put_contents("D:/_projects/yiitest/_log.txt",date("[H:i:s] ")."logged, returnurl:".Yii::app()->user->returnUrl."\n",FILE_APPEND);

                if (Yii::app()->user->returnUrl=='/index.php')
                    $this->redirect(Yii::app()->controller->module->returnUrl);
                else
                    $this->redirect(Yii::app()->user->returnUrl);

                Yii::app()->request->redirect(Yii::app()->createUrl(Yii::app()->user->returnUrl));//$this->redirect(Yii::app()->user->returnUrl);
                */
                Yii::app()->request->redirect(Yii::app()->createUrl('admin/main/'));
            }
        }
        // display the login form
        $this->render('login',array('model'=>$model));

    }
    
    public function actionLogout()
    {
        if (count($_POST)>0)
        {
            Yii::app()->adminUser->logout(false);
            $this->redirect(Yii::app()->createUrl('admin/main/login'));
        }
        else
            $this->redirect(Yii::app()->createUrl('admin/main'));
    }
}