<?php


class AdminLoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Запомнить',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new AdminIdentity($this->username,$this->password);
		if(!$this->_identity->authenticate())
        {
            switch ($this->_identity->errorCode)
            {
                case AdminIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('password','Неправильный пароль');
                    break;
                case AdminIdentity::ERROR_USERNAME_INVALID:
                    $this->addError('password','Неправильный логин');
                    break;
                default:
                    $this->addError('password','Неизвестная ошибка');
                    break;

            }
			//$this->addError('password','Неправильный логин или пароль');
        }
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new AdminIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===AdminIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			//Yii::app()->user->login($this->_identity,$duration);
			//Yii::app()->getModule('admin')->user->login($this->_identity,$duration);

            //CWebUser::AUTH_TIMEOUT_VAR;

            Yii::app()->adminUser->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
