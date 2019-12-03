<?php

/**
 * Class GlobalOrdersOrder
 * @property $id
 * @property $name
 * @property $phone
 * @property $phone_prefix
 * @property $sum
 * @property $data
 * @property $source
 * @property $when
 * @property $time
 * @property $city
 * @property $address
 * @property $comment
 * @property $promo_code
 * @property $promo_code_type
 * @property $paid
 * @property $shipped
 */
class GlobalOrdersOrder extends CActiveRecord
{
    /**
     * @param string $className
     * @return GlobalOrdersOrder
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDataPretty()
    {
        $data = json_decode($this->data,1);
        $ret = "";
        foreach($data as $d) {
            $ret.=$d['name'].' - '.$d['qty']."шт.\n";
        }
        return $ret;
    }

    public function relations()
    {
        return array(
            'phone' => array(self::BELONGS_TO, 'GlobalOrdersPhone', 'phone'),
        );
    }

    public function tableName()
    {
        return 'go_orders';
    }

    public function attributeLabels()
    {
        return array(
            'sum'=>'сумма',
            'phone'=>'клиент',
            'source'=>'источник',
            'data'=>'данные',
            'when'=>'когда',
            'comment'=>'комментария',
            'time'=>'время',
        );
    }


} 