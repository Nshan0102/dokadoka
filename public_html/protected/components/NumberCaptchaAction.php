<?php
class NumberCaptchaAction extends CCaptchaAction
{
    /*
    public function __construct($controller, $id)
    {
        parent::__construct($controller, $id);
    }
    */


    /**
     * Generates a new verification code.
     * @return string the generated verification code
     */
    protected function generateVerifyCode()
    {
        if($this->minLength < 3)
            $this->minLength = 3;
        if($this->maxLength > 10)
            $this->maxLength = 10;
        if($this->minLength > $this->maxLength)
            $this->maxLength = $this->minLength;
        $length = mt_rand($this->minLength,$this->maxLength);

        $code = '';
        for($i = 0; $i < $length; ++$i)
        {
            $code.=mt_rand(0,9);
        }

        return $code;
    }
}
?>