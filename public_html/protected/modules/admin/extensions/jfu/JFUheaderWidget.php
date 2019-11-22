<?php

class JFUheaderWidget extends CWidget
{

    //https://github.com/blueimp/jQuery-File-Upload
    //хидер для JFU виджета - скрипты, css



    public function run()
    {

        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery-ui-1.8.16.custom.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fileupload.js', CClientScript::POS_END);
        //Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fileupload-ui.js', CClientScript::POS_END);
        //Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.fileupload-ui.css');


        print '<style type="text/css">
            div.upload-file-container {
               background: url('.$baseUrl.'/upload.png) no-repeat;
                z-index: 1000;
            }
            div.upload-file-container :hover{cursor: pointer;}
            div.upload-file-container input {
               opacity: 0;
               filter: alpha(opacity = 50);
               z-index: 0;
            }
        </style>';
    }


    public function publishAssets()
    {

    }

}