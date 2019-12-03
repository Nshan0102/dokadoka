<?php

class SiteController extends SiteBaseController
{
    /** @var FrontendCategory[]  */
    public $cats = array();

    /** @var FrontendPage[] */
    public $topPages = array();

    public $promoCodeMessage;
    public $currentPromoCode;
    public $currentCategory=null;

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);

        $this->cats = FrontendCategory::model()->getList4Front();

        $this->topPages = FrontendPage::model()->findAll('pid='.intval(Yii::app()->params['idxPageID']));

        $this->currentPromoCode = FrontendItem::getCurrentPromocode();
        if (isset($_POST['promocode'])) {
            $goPhone = GlobalOrdersPhone::getByCode($_POST['promocode']);
            if ($goPhone && ($goPhone->used == '0' || $goPhone->oneTime == '0')) {
                $this->currentPromoCode = $_POST['promocode'];
                $this->promoCodeMessage = 'Промокод применён, цены обновлены';                
                setcookie(FrontendItem::PROMO_PRICE_SESSION_KEY, $goPhone->promo_code, time()+3600*8,"/");
                //Yii::app()->session[FrontendItem::PROMO_PRICE_SESSION_KEY] = $goPhone->promo_code;
                $goPhone->used = 1;
                $goPhone->save();
            } else {
                $this->promoCodeMessage = 'Введён некорректный промокод';
            }
        }

    }

    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'NumberCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),

        );
    }

    // social auth here
    public function actionLogin()
    {
        $this->pageTitle = 'Авторизация';
        $service = Yii::app()->request->getQuery('service');
        if (isset($service))
        {
            $authIdentity = Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl = $this->createAbsoluteUrl('site/login');//Yii::app()->user->returnUrl;
            $authIdentity->cancelUrl = $this->createAbsoluteUrl('site/login');

            if ($authIdentity->authenticate())
            {
                $identity = new EAuthUserIdentity($authIdentity);

                // successful authentication
                if ($identity->authenticate())
                {
                    Yii::app()->user->login($identity);

                    // special redirect with closing popup window
                    $authIdentity->redirect();
                }
                else
                {
                    // close popup window and redirect to cancelUrl
                    $authIdentity->cancel();
                }
            }

            // Something went wrong, redirect to login page
            $this->redirect(array('site/login'));
        }

        // default authorization code through login/password ..
        $this->render('login');
    }

    public function actionAjaxBasket()
    {
        $qty = intval(Yii::app()->request->getQuery('qty', ''));
        if ($qty < 1)
            $qty = 1;

        if (isset($_GET['upditemid']) && !empty($_GET['upditemid']))
        {

            $item = Yii::app()->shoppingCart->itemAt($_GET['upditemid']);
            if ($item)
                Yii::app()->shoppingCart->update($item, $qty);
        }
        elseif (isset($_GET['additemid']) && !empty($_GET['additemid']))
        { //добавление в корзину
            $iid = $_GET['additemid'];
            $item = FrontendItem::model()->findByPk($iid);
            if ($item)
                Yii::app()->shoppingCart->put($item, $qty);
            //если товар уже есть, кол-во обрабатывается корректно
        }
        elseif (isset($_GET['remove_pos']) && Yii::app()->shoppingCart->contains($_GET['remove_pos']))
        {
            Yii::app()->shoppingCart->remove($_GET['remove_pos']);
        }


        $response=array('total'=>Yii::app()->shoppingCart->getItemsCount(),'sum'=>floatval(Yii::app()->shoppingCart->getCost()));

        header('Content-type:application/json');
        echo CJSON::encode($response);
        Yii::app()->end();

        //echo $this->renderPartial('basket_sticker', null, true);
    }

    public function actionBasket()
    {
        //<META NAME="" CONTENT="NOINDEX, NOFOLLOW">
//        var_dump($_POST);die;
        Yii::app()->clientScript->registerMetaTag('NOINDEX, NOFOLLOW', 'robots');


        if(isset($_GET['additemid'])) {
            /** @var IECartPosition $item */
            $item = FrontendItem::model()->findByPk($_GET['additemid']);
            if ($item) {
                Yii::app()->shoppingCart->put($item, 1);
            }
            $this->redirect('basket');
        }
        if(Yii::app()->request->getPost('recalc')) {
            $qties = (array)Yii::app()->request->getPost('qty');
            foreach ($qties as $citemID=>$citemQty) {
                $item = Yii::app()->shoppingCart->itemAt($citemID);
                if ($item) {
                    Yii::app()->shoppingCart->update($item, $citemQty);
                }
            }
            $this->redirect('basket');
        }

        Yii::import('application.components.GlobalOrders');
        $go = GlobalOrders::getInstance(Yii::app()->params['goSiteUID']);
        $model = new OrderForm();
        $isOrderReceived = false;
        $sumWithDiscount = 0;
        $totalCost = Yii::app()->shoppingCart->getCost();
        $discount = 0;
        $orderNum = null;
        if (isset($_POST['OrderForm']) && !Yii::app()->shoppingCart->isEmpty())
        {
            $model->attributes = $_POST['OrderForm'];
            if ($model->validate()) {
                $fullPhone = str_replace(' ', '', $model->phone_prefix) . preg_replace('~([^\d]+)~', '', $model->phone);

                $promocode = FrontendItem::getCurrentPromocode();
                if (!$promocode) {
                    $discount = $go->getSavedDiscount($fullPhone);
                    if (!$discount) {
                        $discount = $go->getDiscountPercentByLastOrder();
                    }
                    if ($discount) {
                        $sumWithDiscount = round((1 - $discount / 100) * $totalCost, -4);
                    }
                } else {

                }


                $positions = Yii::app()->shoppingCart->getPositions();

                $goItems = array();
                foreach ($positions as $pos) {
                    /** @var $pos FrontendItem */
                    $goItems[] = new GlobalOrderItem($pos->header, $pos->getPrice(), $pos->getQuantity());
                }

                /** @author Nshan Vardanyan*/
                $sum = 0;
                foreach($goItems as $itm) {
                    $sum += $itm->price*$itm->qty;
                }
//                var_dump($sum);die;
                $order = new GlobalOrdersOrder();
                $order->name = $_POST["OrderForm"]["name"];
                $order->phone_prefix = $_POST["OrderForm"]["phone_prefix"];
                $order->city = $_POST["OrderForm"]["city"];
                $order->address = $_POST["OrderForm"]["address"];
                $order->comment = $_POST["OrderForm"]["comment"];
                $order->phone = $_POST["OrderForm"]["phone"];
                $order->time = $_POST["OrderForm"]["time"];
                $order->sum = $sum;
                $order->data = json_encode($goItems);
                if ($promocode){
                    $promoType = GlobalOrdersPhone::getByCode($promocode);
                    $promoType->oneTime == 1 ? $order->promo_code_type = 1 : $order->promo_code_type = 2;
                    $order->promo_code = $promocode;
                }else {
                    $order->promo_code = $promocode;
                    $order->promo_code_type = 0;
                }
                $order->when = date('Y-m-d H:i:s');
                $order->source = 'DoKaDoKa.pro';
                $order->save();
                /** @author Nshan Vardanyan*/


                $go->addOrder($fullPhone, $goItems, $model->name);



                $mailbody = '<html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                </head>
                <body>
                <h3>Состав заказа</h3>
                ';



                foreach ($positions as $pos) {
                    /** @var $pos FrontendItem */
                    $mailbody .= '['.$pos->getCode().']'.$pos->header . ' ' . $pos->getQuantity() . ' шт. ('.$pos->getSumPrice().' руб.)<br>';
                }

                $mailbody .= 'Итого: ' . $totalCost . ' руб.<br>';
                if ($sumWithDiscount) {
                    $mailbody.='С учётом скидки: '.$sumWithDiscount.' руб. (скидка- '.$discount.'%)<br>';
                }

                $mailbody .= '<h3>Клиент</h3>';
                if ($promocode) {
                    $mailbody .= '<div>Использован промокод '.$promocode.'</div>';
                }
                $attrLabels = $model->attributeLabels();
                foreach ($model->attributes as $k => $v) {
                    if (!$model->$k) {
                        continue;
                    }
                    if ($k=='phone_prefix') {
                        continue;
                    }
                    if ($k=='phone' && $model->phone_prefix) {
                        $v = $model->phone_prefix.' '.$v;
                    }
                    $mailbody .= $attrLabels[$k] . ': ' . htmlspecialchars($v) . '<br>';
                }

                $mailbody .= '</body></html>';


                $dborder = new DbOrder();
                $dborder->body=$mailbody;
                $dborder->save();
                $orderNum = $dborder->id;
                Helpers::sendEmail(Yii::app()->params['adminEmail'], "новый заказ на сайте ".Yii::app()->params['goSiteUID']." #".$orderNum, $mailbody);

                Yii::app()->shoppingCart->clear(); //очистка корзины
                $isOrderReceived = true;
            }
        }
        $this->breadcrumbs = array('Корзина' => '');
        $this->render('basket', array(
                'model' => $model,
                'discount'=>$discount,
                'sumWithDiscount'=>$sumWithDiscount,
                'totalCost'=>$totalCost,
                'orderNum'=>$orderNum,
                'isOrderReceived' => $isOrderReceived
            ));
    }

    public function throw404()
    {
        throw new CHttpException(404, "Страница не найдена");
    }

    public function actionSearch()
    {

        $query = Yii::app()->request->getPost('search_query', '');
        if (empty($query))
            $this->throw404();
        $results = FrontendItem::model()->getSearchResults($query);
        $this->render('search', array('results' => $results, 'query' => $query));
    }

    public function actionItem()
    {

        $uri = Yii::app()->request->getQuery('uri', '');
        /**
         * @var FrontendItem $itm
         */
        $itm = FrontendItem::model()->findByUri($uri);
        if (!$itm)
            $this->throw404();
        if ($itm->meta_descr)
            Yii::app()->clientScript->registerMetaTag($itm->meta_descr, 'description');
        if ($itm->meta_kw)
            Yii::app()->clientScript->registerMetaTag($itm->meta_kw, 'keywords');
        $this->pageTitle = $itm->html_title?$itm->html_title:$itm->header;

        /**
         * @var FrontendItem $cat
         */
        $cat = FrontendCategory::model()->findByPk($itm->catid);
        if ($cat)
        {
		$this->currentCategory = $itm->catid;
            $this->breadcrumbs = array($cat->header => $cat->buildHref(), $itm->header => '');
            $catitems = FrontendItem::model()->catItems($cat->id,"price");
        }
        else
        {
            $this->breadcrumbs = array($itm->header => '');
            $catitems = array();
        }
        $this->render('item', array('item' => $itm,'catitems'=>$catitems));
    }

    public function actionPage()
    {

        $uri = Yii::app()->request->getQuery('uri', '');
        /**
         * @var FrontendPage $model
         */
        $model = FrontendPage::model()->findByUri($uri);
        if (!$model)
            $this->throw404();
        if ($model->meta_descr)
            Yii::app()->clientScript->registerMetaTag($model->meta_descr, 'description');
        if ($model->meta_kw)
            Yii::app()->clientScript->registerMetaTag($model->meta_kw, 'keywords');
        $this->pageTitle = $model->html_title;
        $this->breadcrumbs = array($model->header => '');
        $this->render('page', array('page' => $model));
    }

    public function actionCategory()
    {
        $uri = Yii::app()->request->getQuery('uri', '');
        /**
         * @var FrontendCategory $model
         */
        $model = FrontendCategory::model()->findByUri($uri);
        if (!$model)
            $this->throw404();
        if ($model->meta_descr)
            Yii::app()->clientScript->registerMetaTag($model->meta_descr, 'description');
        if ($model->meta_kw)
            Yii::app()->clientScript->registerMetaTag($model->meta_kw, 'keywords');
        $this->pageTitle = $model->html_title?$model->html_title:$model->header;
        $items = FrontendItem::model()->catItems($model->id);
	$this->currentCategory = $model->id;
        $this->breadcrumbs = array($model->header => '');
        $this->render('category', array('category' => $model, 'items' => $items));
    }

    public function actionIndex()
    {
        /*
        if($_SERVER['REQUEST_URI'] != '/' ) {
            throw  new CHttpException(404, "страница не найдена");
        }
        */


        $idxPage = FrontendPage::model()->findByUri('index');
        if (!$idxPage)
            throw new CHttpException(404, "Главная страница не найдена");

        if ($idxPage->meta_descr)
            Yii::app()->clientScript->registerMetaTag($idxPage->meta_descr, 'description');
        if ($idxPage->meta_kw)
            Yii::app()->clientScript->registerMetaTag($idxPage->meta_kw, 'keywords');
        $this->pageTitle = $idxPage->html_title;


        $pparams = array(
            //'items' =>  FrontendItem::model()->getList4Index(),
            'idxPage'=>$idxPage
        );
        $this->render('index',$pparams );
    }

    public function actionError()
    {

        if ($error = Yii::app()->errorHandler->error)
        {
            $code = $error['code'];
            if($code!=404)
                Yii::log("SiteController error:".$error['message']);//
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionContact()
    {

        $model = new ContactForm;
        if (isset($_POST['ContactForm']))
        {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate())
            {
                $mailbody = '<html>
                              <head>
                                  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                              </head>
                              <body>
                              <h3>Форма</h3>
                              ';
                $attrLabels = $model->attributeLabels();
                foreach ($model->attributes as $k => $v)
                {
                    if ($k=='verifyCode')
                        continue;
                    if ($model->$k)
                        $mailbody .= $attrLabels[$k] . ': ' . htmlspecialchars($v) . '<br>';
                }

                $mailbody .= '</body>
                  </html>';
                Helpers::sendEmail(Yii::app()->params['adminEmail'], "контакт-форма", $mailbody);


                //mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
                Yii::app()->user->setFlash('contact','Спасибо за сообщение');
                $this->refresh();
            }
        }
        $this->breadcrumbs = array('Контакт' => '');
        $this->render('contact', array('model' => $model));

    }
}