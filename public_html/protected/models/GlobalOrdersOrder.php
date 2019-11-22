<?php

/**
 * Class GlobalOrdersOrder
 * @property $id
 * @property $phone_id
 * @property $sum
 * @property $data
 * @property $source
 * @property $when
 * @property GlobalOrdersPhone $phone
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
            'phone' => array(self::BELONGS_TO, 'GlobalOrdersPhone', 'phone_id'),
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
            'phone_id'=>'клиент',
            'source'=>'источник',
            'data'=>'данные',
            'when'=>'когда',
        );
    }


} 