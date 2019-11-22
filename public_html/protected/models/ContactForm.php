<?php
/**
 * @property array $attributes
 *
 **/
class ContactForm extends CFormModel
{
	public $name;
	public $email;
	public $subject;
	public $body;
	public $phone;
	public $verifyCode;



	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, body', 'required'),
			// email has to be a valid email address
			//array('email', 'email'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>false),
            array('name,phone,body,body,email', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Код',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'body' => 'Сообщение',
            'email' => 'Email',
		);
	}


}