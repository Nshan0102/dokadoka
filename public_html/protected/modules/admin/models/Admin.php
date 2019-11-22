<?php

/**
 * This is the model class for table "admins".
 *
 * The followings are the available columns in table 'admins':
 * @property string $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property integer $type
 */
class Admin extends CActiveRecord
{
    public $password_repeat;//for changing
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admins}}';
	}


    private function getPasswordHash($password)
    {
        return md5($password);
    }

    public function validatePassword($password)
    {
        return $this->getPasswordHash($password)===$this->password;
    }

    public function beforeSave()
    {
        $this->password = $this->getPasswordHash($this->password);
        return parent::beforeSave();
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('login', 'unique','on'=>'createNewAdmin,saveAdmin'),

			array('login, password, email', 'required','on'=>'createNewAdmin'),
			array('login', 'unique','on'=>'createNewAdmin,saveAdmin'),
			array('login, password', 'required','on'=>'loginAdmin'),
			array('password, password_repeat', 'required','on'=>'changeAdminPass'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('login, password', 'length','min'=>5, 'max'=>100),
			array('email', 'length', 'max'=>200),
			array('email', 'email', 'on'=>'createNewAdmin,saveAdmin'), // CEmailValidator

            // when in register scenario, password must match password2
            array('password', 'compare', 'on'=>'createNewAdmin,saveAdmin,changeAdminPass'),





            /*
            array('password', 'length', 'min'=>6, 'max'=>64, 'on'=>'changeAdminPass,createNewAdmin'),
            array('password', 'compare','on'=>'changeAdminPass,createNewAdmin'),
            //From API page:
            //    The value being compared with can be another attribute value
            //    .....
            //    If neither is specified, the attribute will be compared
            //   with another attribute whose name is by appending "_repeat" to the source attribute name.
            //
            //array('password', 'compare','compareAttribute'=>'password_repeat','on'=>'changeAdminPass,createNewAdmin', 'message'=>'пароли то не совпадают!'),
            array('password,password_repeat', 'safe'),
            */
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Логин',
			'password' => 'Пароль',
			'password2' => 'Повторите пароль',
			'email' => 'Email',
			'type' => 'Type',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}