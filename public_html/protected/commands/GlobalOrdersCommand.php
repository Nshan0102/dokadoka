<?php

class GlobalOrdersCommand extends CConsoleCommand
{
    public function actionSendSms()
    {

    }

    public function actionGeneratePromoCodes()
    {
        /** @var GlobalOrdersPhone[] $allPhones */
        //$allPhones = GlobalOrdersPhone::model()->findAll(' promo_code is null');
        $allPhones = GlobalOrdersPhone::model()->findAll();
        print 'total:'.count($allPhones)."\n";
        foreach ($allPhones as $phoneRecord) {


            $pcode = GlobalOrdersPhone::generatePromoCodeFromPhone($phoneRecord->phone);

            $phoneRecord->promo_code = $pcode;
            $phoneRecord->save(false);
           // $allOrders->update(['promo_code' => $pcode]);
        }
    }
}