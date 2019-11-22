<?php

class AdminModule extends CWebModule
{
    static private $_isAdmin;

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));


        //via http://www.yiiframework.com/wiki/89/module-based-login/
        $this->setComponents(array(
            'errorHandler' => array('errorAction' => 'admin/main/error'),
            'user' => array(
                'class' => 'CWebUser',
                'loginUrl' => Yii::app()->createUrl('admin/main/login'),
            )
        ));
        Yii::app()->user->setStateKeyPrefix('admin_');

	}


    protected function isAdminLogged()
    {
        //Yii::app()->getSession()->remove('model');
       // return Yii::app()->getSession()->get('currAdmin');
        return Yii::app()->adminUser->isGuest;
    }

	public function beforeControllerAction($controller, $action)
	{
        if (parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            $route = $controller->id . '/' . $action->id;
            // echo $route;
            $publicPages = array(
                'main/login',
                'main/error',
            );

            if ($this->isAdminLogged() && !in_array($route, $publicPages))
            {
                /*
                $returnUrl='/admin/'.$controller->getId().'/'.$action->getId();
                file_put_contents("D:/_projects/yiitest/_log.txt",date("[H:i:s] ")."not logged, returnurl:".$returnUrl."\n",FILE_APPEND);
                Yii::app()->getModule('admin')->user->setReturnUrl($returnUrl);
                */
                //Yii::app()->getModule('admin')->user->loginRequired();
                Yii::app()->adminUser->loginRequired();
            }
            else
                return true;
        }
        else
            return false;
    }
}
