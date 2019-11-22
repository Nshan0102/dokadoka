<?php
/**
 * @property array $attributes
 *
 **/
class OrderForm extends CFormModel
{

    public $name;
    public $phone;
    public $phone_prefix;
    public $comment;
    public $time;
    public $address;
    public $city;
    public function rules()
   	{
   		return array(
   			array('name, phone', 'required'),
   			//array('name', 'required'),
   			//array('comment', 'safe'),
            array('name,phone_prefix,phone,address,city,time,comment', 'safe'),
   		);
   	}


    public function attributeLabels()
   	{
   		return array(
   			'name' => 'Имя',
   			'phone' => 'Телефон',
   			'phone_prefix' => 'префикс', //префикс
   			'address' => 'Адрес', //
   			'city' => 'Город', //
   			'time' => 'Желаемое время доставки', //
   			'comment' => 'Комментарий', //
        );
    }
}
?>