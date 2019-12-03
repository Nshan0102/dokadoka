<?php


class PromoCode extends CActiveRecord
{
    public function tableName()
    {
        return 'go_promoCodes';
    }

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

    public static function getByCode($code)
    {
        return self::model()->find('promoCode=:code', array(':code'=>$code));
    }



}