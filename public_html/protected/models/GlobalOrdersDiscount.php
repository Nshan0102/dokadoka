<?php

/**
 * Class GlobalOrdersDiscount
 * @property $id
 * @property $min_items
 * @property $min_price
 * @property $discount
 */
class GlobalOrdersDiscount extends CActiveRecord
{
    /**
     * @param string $className
     * @return GlobalOrdersDiscount
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array(array('min_items','min_price','discount'),'numerical','integerOnly'=>true),
        );
    }


    public function tableName()
    {
        return 'go_discounts_settings';
    }

    public function attributeLabels()
    {
        return array(
            'min_items'=>'мин. кол-во товаров',
            'min_price'=>'мин.цена товара',
            'discount'=>'размер скидки',
        );
    }


    public function defaultScope()
    {
        return array(
            'order'=>$this->getTableAlias(false,false).".`discount` DESC",
        );
    }
} 