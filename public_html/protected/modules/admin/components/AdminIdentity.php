<?php
class AdminIdentity extends CUserIdentity
{
    private $_id;
    public function authenticate()
    {

        $this->username=strtolower($this->username);
        //$user=Admin::model()->find('LOWER(login)=?',array($username));
        $user=Admin::model()->findByAttributes(array('login'=>$this->username));
        if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(!$user->validatePassword($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$user->id;
            $this->username=$user->login;
            $this->errorCode=self::ERROR_NONE;
        }
        return $this->errorCode==self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}
?>