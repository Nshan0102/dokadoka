<?php

class GlobalOrdersController extends CController
{
    public $layout='/layouts/column1';
    public function actionDiscountSettings()
    {
        $model = new GlobalOrdersDiscount();
        if(isset($_POST['GlobalOrdersDiscount'])) {

            $model->attributes = $_POST['GlobalOrdersDiscount'];
            if($model->save(false)) {
                Yii::app()->user->setFlash('success', "Добавлено");
                $this->redirect('discountSettings');
            }
        }

        $this->render('dsettings', array('model'=>$model));
    }

    public function actionClients()
    {
        if (isset($_POST['multi_add'])) {
            $lines = explode("\n", trim($_POST['multi_add']));
            $totalAdded = 0;
            foreach ($lines as $line) {
                $line = trim($line);
                list($phone, $name) = explode(';', $line) + array('','');
                $phone = trim($phone);
                if (!strlen($phone)) {
                    continue;
                }
                $client = new GlobalOrdersPhone();
                $client->phone = $phone;
                $client->name = $name;
                $client->save(false);
                $totalAdded++;
            }
            Yii::app()->user->setFlash('success', "Клиенты добавлены (".$totalAdded.")");
            $this->redirect('clients');
        }
        $this->render('clients');
    }

    public function actionIndex()
    {
        $criteria=new CDbCriteria();
        $count=GlobalOrdersOrder::model()->count($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=20;
        $pages->applyLimit($criteria);
        $models=GlobalOrdersOrder::model()->findAll($criteria);
        $this->render('index', array(
            'models' => $models,
            'pages' => $pages
        ));
    }

    public function actionDeleteDS()
    {
        $model=GlobalOrdersDiscount::model()->findByPk(Yii::app()->request->getQuery('id'));
        if (!$model)
            throw new CHttpException(404, 'Элемент не найден');
        $model->delete();

    }

    public function actionDelete()
    {
        $model=GlobalOrdersOrder::model()->findByPk(Yii::app()->request->getQuery('id'));
        if (!$model || !$model->delete()){
            echo 'Элемент не найден';
        }
        echo "success";
    }

    public function actionOrderSetPaid()
    {
        $model=GlobalOrdersOrder::model()->findByPk(Yii::app()->request->getQuery('id'));
        if (!$model){
            echo 'Элемент не найден';
        }
        $model->paid = 1;
        $model->save();
        echo "success";
    }

    public function actionOrderSetShipped()
    {
        $model=GlobalOrdersOrder::model()->findByPk(Yii::app()->request->getQuery('id'));
        if (!$model){
            echo 'Элемент не найден';
        }
        $model->shipped = 1;
        $model->save();
        echo "success";
    }
} 