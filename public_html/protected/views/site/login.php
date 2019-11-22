<h2>Вы можете войти на сайт, если вы зарегистрированы на одном из этих сервисов</h2>
<?php

    $this->widget('ext.eauth.EAuthWidget', array('action' => '/site/login'));


    if (!Yii::app()->user->isGuest)
    {
        print "<b>Авторизирован</b><br>";
        print " id:".Yii::app()->user->getId()."<br>";
        print " name:".Yii::app()->user->getName()."<br>";
        print " service:".Yii::app()->user->service."<br>";

        //print_r(Yii::app()->user);
        print "<br>";
    }

    $services=Yii::app()->eauth->getServices();
    foreach ($services as $serviceObj)
    {
        $service=$serviceObj->id;
        print "".$service."<br>";
        //print_r($service);
        try
        {
            $authIdentity = Yii::app()->eauth->getIdentity($service);
            //print_r($authIdentity);
            if (!$authIdentity->getIsAuthenticated())
                continue;
            print " auth!<br>";
            /*
            foreach ($authIdentity->attributes as $attr)
            {
                print " ".$attr."<br>";
            }
            */
        }
        catch (EAuthException $eae)
        {
            //Undefined service name: google

        }

    }




/*
SERVICES:

stdClass Object
(
    [id] => google
    [title] => Google
    [type] => OpenID
    [jsArguments] => Array
        (
            [popup] => Array
                (
                    [width] => 880
                    [height] => 520
                )

        )

)
stdClass Object
(
    [id] => twitter
    [title] => Twitter
    [type] => OAuth
    [jsArguments] => Array
        (
            [popup] => Array
                (
                    [width] => 900
                    [height] => 550
                )

        )

)
stdClass Object
(
    [id] => facebook
    [title] => Facebook
    [type] => OAuth
    [jsArguments] => Array
        (
            [popup] => Array
                (
                    [width] => 585
                    [height] => 290
                )

        )

)
stdClass Object
(
    [id] => mailru
    [title] => Mail.ru
    [type] => OAuth
    [jsArguments] => Array
        (
            [popup] => Array
                (
                    [width] => 580
                    [height] => 400
                )

        )

)
*/
?>