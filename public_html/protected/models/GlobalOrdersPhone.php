<?php

/**
 * Class GlobalOrdersPhone
 * @property $id
 * @property $name
 * @property $phone
 * @property $discount
 * @property $promo_code
 * @property $oneTime
 * @property $used
 */
class GlobalOrdersPhone extends CActiveRecord
{
    public function tableName()
    {
        return 'go_phones';
    }

    /**
     * @param $code
     * @return GlobalOrdersPhone
     */
    public static function getByCode($code)
    {
        return self::model()->find('promo_code=:code', array(':code'=>$code));
    }


    /**
     * @param $id
     * @return GlobalOrdersPhone
     */
    public static function getById($id)
    {
        return self::model()->find('id=:id', array(':id'=>$id));
    }

    /**
     * @param string $className
     * @return GlobalOrdersPhone
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function defaultScope()
    {
        return array(
            'order'=>$this->getTableAlias(false,false).".`id` DESC",
        );
    }

    public static function generatePromoCodeFromPhone($phone)
    {

        $pcode = preg_replace('~^80~', '', $phone); // 8029....
        $pcode = preg_replace('~^\+375~', '', $pcode); // +375...
        $pcode = preg_replace('~([^\d]+)~', '', $pcode);
        if (strlen($pcode) == 12 && substr($pcode,0,3)=='375') {
            $pcode = substr($pcode, 3);
        } // 375 29 6 00 00 00
        return $pcode;
    }

    protected function beforeSave()
    {
        if (!$this->promo_code && $this->phone) {
            $this->promo_code = self::generatePromoCodeFromPhone($this->phone);
        }
        return parent::beforeSave();
    }

} 