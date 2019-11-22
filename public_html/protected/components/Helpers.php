<?php
class Helpers
{
    public static function getWebRootPath()
    {
        return dirname(Yii::app()->request->scriptFile).'/';
    }

    //public static function ()

    public static function getUniqFilenameInDir($fullpath)
    {
        $ext=pathinfo($fullpath, PATHINFO_EXTENSION);
        $nameOnly =pathinfo($fullpath, PATHINFO_FILENAME);
        $dir =pathinfo($fullpath, PATHINFO_DIRNAME)."/";
        $i = 1;
        $newName=$nameOnly.".".$ext;
        while (file_exists($dir.$newName))
        {
            $newName = $nameOnly.($i++).".".$ext;
        }
        return $newName;
    }


    public static function adaptiveResizeImageAndThumb($srcPath,$destPath,$width,$height, $destThumbPath,$thumbWidth,$thumbHeight)
    {
        //https://github.com/masterexploder/PHPThumb/wiki/Basic-Usage
        //Yii::import('application.extensions.PhpThumbFactory');
        Yii::import('application.extensions.PhpThumbFactory');

        $thumb = PhpThumbFactory::create($srcPath);
        $dims = $thumb->getCurrentDimensions();
        if ($dims['width'] > $width || $dims['height'] > $height)
            $thumb->adaptiveResize($width, $height)->save($destPath);
        elseif ($srcPath!=$destPath)
            copy($srcPath,$destPath);

        if ($dims['width'] > $thumbWidth || $dims['height'] > $thumbHeight)
            $thumb->adaptiveResize($thumbWidth, $thumbHeight)->save($destThumbPath);
        else
            copy($srcPath, $destThumbPath);
    }

    public static function adaptiveResizeJPGImageAndThumb($srcPath,$destPath,$width,$height, $destThumbPath,$thumbWidth,$thumbHeight)
    {
        //https://github.com/masterexploder/PHPThumb/wiki/Basic-Usage
        //Yii::import('application.extensions.PhpThumbFactory');
        Yii::import('application.extensions.PhpThumbFactory');

        $thumb = PhpThumbFactory::create($srcPath);
        //$dims = $thumb->getCurrentDimensions();
        //if ($dims['width'] > $width || $dims['height'] > $height)
            $thumb->adaptiveResize($width, $height)->save($destPath,'JPG');
        //elseif ($srcPath!=$destPath)
        //    copy($srcPath,$destPath);

        //if ($dims['width'] > $thumbWidth || $dims['height'] > $thumbHeight)
            $thumb->adaptiveResize($thumbWidth, $thumbHeight)->save($destThumbPath,'JPG');
        //else
        //    copy($srcPath, $destThumbPath);
    }

    public static function validateImageExtension($path)
    {
        $ext = self::getExtensionFromPath($path);
        if (!self::isImage($ext))
            new CHttpException(403);
        return $ext;
    }

    public static function getExtensionFromPath($path)
    {
        return strtolower(CFileHelper::getExtension($path));
    }

    public static function getImageExtensions()
    {
        return array('jpg','jpeg','gif','png');
    }

    public static function isImage($ext)
    {
        if (!in_array($ext,self::getImageExtensions()))
            return false;
        else
            return true;
    }

    public static function makeCorrectUri($string)
    {
        Yii::import('application.extensions.UrlTransliterate.UrlTransliterate');
        return UrlTransliterate::cleanString($string);
    }

    public static function generateUID($length=16)
    {
        $ret='';
        $chars = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f');
        $chlength=count($chars);
        for ($i=0;$i<$length;$i++)
        {
            $ret.=$chars[mt_rand(0,$chlength-1)];
        }
        return $ret;
    }

    public static function sendEmail($toEmail, $subj,$body,$fromEmail=false)
    {
        //Yii::import('application.extensions.phpmailer');
        if (!$fromEmail)
        {
            //if (isset($_SERVER['HTTP_HOST']))
            //    $fromEmail="robot@".$_SERVER['HTTP_HOST'];//@todo uncomment
            $fromEmail="robot@fireworks.by";
        }
        try
        {
            Yii::log('sending mail to '.$toEmail.', from: '.$fromEmail.', subj:'.$subj."\nbody:\n".$body);//todo
            $mail = new PhpMailer(true);
            //$mail->IsMail();
            $mail->CharSet = "utf-8";
            $mail->IsHTML(true);
            //$mail->Host = 'smpt.163.com';
            //$mail->SMTPAuth = true;
            //$mail->Username = 'yourname@163.com';
            //$mail->Password = 'yourpassword';
            $mail->SetFrom($fromEmail, $fromEmail);
            $mail->Subject = $subj;
            $mail->AltBody = strip_tags($body);
            $mail->MsgHTML($body);
            $mail->AddAddress($toEmail);
            $mail->Send();
        }
        catch (Exception $e){
            Yii::log('error on sending mail: '.$e->getMessage());
        }
    }
}