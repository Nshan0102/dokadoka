<?php

class PromoCodesController extends CController
{
    public $layout='/layouts/column1';

    public function actionIndex($uploaded = '')
    {
        $criteria=new CDbCriteria();
        $count=GlobalOrdersPhone::model()->count($criteria);
        $pages=new CPagination($count);

        // results per page
        $pages->pageSize=50;
        $pages->applyLimit($criteria);
        $models=GlobalOrdersPhone::model()->findAll($criteria);
        $this->render('index', array(
            'uploaded' => $uploaded,
            'models' => $models,
            'pages' => $pages
        ));
    }

    public function actionUploadPromo()
    {
        $uploadedMessage = '';
        if (count($_FILES) > 0){
            $ext = substr($_FILES['file']['name'],-3);
            if ($ext === 'txt'){
                try {
                    move_uploaded_file($_FILES['file']["tmp_name"],'protected/uploads/promoCode.' . $ext);
                    $content = file_get_contents('protected/uploads/promoCode.' . $ext);
                    $data = explode("\n", $content);
                    $failedCodes = [];
                    $newPromoCount = 0;
                    foreach ($data as $promo){
                        $promoCode = trim($promo);
                        $promoCode = htmlspecialchars($promoCode);
                        if (GlobalOrdersPhone::getByCode($promoCode)){
                            array_push($failedCodes,$promoCode);
                        }else{
                            $promoCodeObj = new GlobalOrdersPhone();
                            $promoCodeObj->phone = $promoCode;
                            $promoCodeObj->promo_code = $promoCode;
                            $_POST['isOneTime'] === 'on' ? $promoCodeObj->oneTime = 1 : $promoCodeObj->oneTime = 0;
                            $promoCodeObj->save();
                            $newPromoCount++;
                        }
                    }
                    $uploadedMessage = "Добавлено ".$newPromoCount." промокодов. <span style='color: red;font-weight: 700;'>Неудавшийся(".count($failedCodes).")</span>";
                }catch (exception $e) {
                    $uploadedMessage = $e;
                }
            }else{
                $uploadedMessage =  "<span style='color: red;font-weight: 700;'>Файл должен быть в формате '.txt'</span>";
            }
            unset($_FILES['file']);
            $_POST = [];
        }
        return $this->actionIndex($uploadedMessage);
    }

    public function actionDeletePromo()
    {
        $go_phone = GlobalOrdersPhone::getById($_GET['id']);
        if ($go_phone->delete()){
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function actionEdit()
    {
        $go_phone = GlobalOrdersPhone::getById($_GET['id']);
        $go_phone->name = $_GET['name'];
        $go_phone->phone = $_GET['phone'];
        $go_phone->promo_code = $_GET['promo_code'];
        $go_phone->oneTime = $_GET['one_time'];
        $go_phone->used = $_GET['used'];
        if($go_phone->save()){
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function actionDelete()
    {

    }
}