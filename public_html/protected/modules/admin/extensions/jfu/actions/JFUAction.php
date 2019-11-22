<?php
Yii::import("application.modules.admin.extensions.jfu.models.JFUmodel");

class JFUAction extends CAction
{
    public $path;
    //public $inputName='file';
    public $modelName='Unknown';
    public $modelAttributeName='unknown';
    public $allowedExtensions=array('jpg','jpeg','gif','png');

    private $_subfolder;


    /**
     * Initialize the propeties of this action, if they are not set.
     */
    public function init()
    {
        if (!isset($this->path))
        {
            $this->path = "media";
        }


        if (!is_dir($this->path))
        {
            throw new CHttpException(500, "{$this->path} does not exists.");
        }
        else if (!is_writable($this->path))
        {
            throw new CHttpException(500, "{$this->path} is not writable.");
        }

        /*
		if($this->subfolderVar !== false){
			$this->_subfolder = Yii::app()->request->getQuery($this->subfolderVar, date("mdY"));
		}else{
			$this->_subfolder = date("Y/m");
		}
        */
        $this->_subfolder = date("Y/m");
    }

    /**
     * The main action that handles the file upload request.
     * @since 0.1
     * @author Asgaroth
     */
    public function run()
    {
        $this->init();
        //$model = new JFUmodel();

        /**
         * @var CActiveRecord $model
         */
        //$model = new $this->modelName;

        $pkVal=Yii::app()->request->getPost('primaryKeyValue', '');



        //print "_files: ";print_r($_FILES); print "request:";print_r($_REQUEST);print "post:";print_r($_POST);die();

        $model=CActiveRecord::model($this->modelName)->findByPk($pkVal);
        if (is_null($model))
            throw new CHttpException(404,"Record not found: ".$pkVal);

        $cfile=CUploadedFile::getInstance($model, $this->modelAttributeName);
        if (is_null($cfile))
            throw new CHttpException(500,"Incorrect input or model name");

        $fileName=$cfile->getName();
        $mime_type = $cfile->getType();
        //$filesize = $cfile->getSize();

        $model->setAttribute($this->modelAttributeName, $fileName);
        if ($model->validate())
        {

            $savePath = dirname(Yii::app()->request->scriptFile).'/'.$this->path;
            $path = $savePath . "/" . $this->_subfolder . "/";
            if (!is_dir($path))
            {
                $sfchunks = explode("/", $this->_subfolder);
                $path = $savePath . "/";
                foreach ($sfchunks as $sfchunk)
                {
                    $path .= $sfchunk . "/";
                    if (!is_dir($path))
                        mkdir($path);
                }
            }
            $saveName = Helpers::getUniqFilenameInDir($path . $fileName);
            $webpath = "/" . $this->path . "/" . $this->_subfolder . "/" . $saveName;


            if ($cfile->saveAs($path.$saveName))
            {
                //второй раз установим аттрибут, уже с реально сохранённым файлом
                $model->setAttribute($this->modelAttributeName, $webpath);
                $model->save(false);
            }
            else
                throw new CHttpException(500, "Error saving file");

            echo json_encode(array(array("name" => $webpath, "type" => $mime_type)));
        }
        else
        {
            echo CVarDumper::dumpAsString($model->getErrors());
            //Yii::log("JFUAction: " . CVarDumper::dumpAsString($model->getErrors()), CLogger::LEVEL_ERROR, "application.modules.admin.jfu.actions.XUploadAction");
            throw new CHttpException(500, "Could not upload file:".var_export($model->getErrors($this->modelAttributeName),true));
        }
    }
}
