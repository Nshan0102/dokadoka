<?php

class AdvController extends CController
{

    /**
     * @var Adv the currently loaded data model instance.
     */
    protected  $_model;

    /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column1';

	public function actionUpdate()
    {
        $this->loadModel();
        if(isset($_POST['Adv']))
        {
            $this->_model->attributes=$_POST['Adv'];
            if($this->_model->save())
                $this->redirect('/admin/adv');
            else
            {
                $errors=$this->_model->getErrors();
                echo json_encode(array('status'=>0,'errors'=>$errors));//print_r($Model->getErrors());
            }
            die();
        }

        $this->render('update');
    }

	public function actionView()
    {
        $this->loadModel();
        print "<div style='".$this->_model->preview_style."'>".$this->_model->text."</div>";
    }

	public function actionIndex()
	{

        $dataProvider=new CActiveDataProvider('Adv',
                array(
                    'criteria' => array(
                        'select' => 'code,name',
                       // 'order' => 'download_id DESC',
                        //'limit' => $count,
                    ),
                    'pagination' => array('pageSize' => Yii::app()->params['adminPerPage']),
                )
            );
		$this->render('index',array('dataProvider'=>$dataProvider));
	}

    /**
        * Returns the data model based on the primary key given in the GET variable.
        * If the data model is not found, an HTTP exception will be raised.
        * @return Adv
        */
       public function loadModel()
       {
           if($this->_model===null)
           {
               if(isset($_GET['id']))
                   $this->_model=Adv::model()->findByPk($_GET['id']);
               if($this->_model===null)
                   throw new CHttpException(404,'The requested page does not exist.');
           }
           return $this->_model;
       }
}