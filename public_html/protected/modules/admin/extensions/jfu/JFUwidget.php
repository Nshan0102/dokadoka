<?php

class JFUWidget extends CWidget
{

    //https://github.com/blueimp/jQuery-File-Upload

    /**
     * the url to the upload handler
     * @var string
     */
    public $url;
    public $primaryKeyVal=0;
    public $inputName='JFUmodel[file]';


    /**
     * Publishes the required assets
     */
    public function init()
    {
        parent::init();
        //$this->publishAssets();
    }

    public function run()
    {
        if(empty($this->url))
            die('no url specified');

        $text=' <!-- <input id="fileupload" type="file" name="JFUmodel[file]"> -->
              <!--  <button id="fileupload" title="загрузить картинку">загрузить картинку</button> -->
                <div class="upload-file-container">
                   <input id="fileupload" type="file" name="'.$this->inputName.'">
                </div>
               <div id="uploadlog"></div>
               <script type="text/javascript">';
        $text.="$(function () {
                $('#fileupload').fileupload({
                    dataType: 'json',
                    url: '".$this->url."',
                    paramName: '".$this->inputName."',
                    formData:[{name: 'primaryKeyValue', value: '".$this->primaryKeyVal."'},{name: '".Yii::app()->request->csrfTokenName."', value: '".Yii::app()->request->csrfToken."'}],
                    done: function (e, data) {
                        $.each(data.result, function (index, file) {
                            $('#uploadlog').append('Картинка загружена: <br><img src=\"'+file.name+'\" /><br/>');
                            //$('#fileupload').fileupload('destroy');
                            $('div.upload-file-container').hide();
                        });
                    }
                });

                $('#fileupload').bind('fileuploadfail', function (e, data) {
                    $('#uploadlog').append('<b style=\"color: red;\">Ошибка при загрузке</b><br/>').append('<small>'+data.jqXHR.responseText+'</small>');
                    //console.dir(data);
                    //console.dir(data.jqXHR);
                    //console.log(data.jqXHR.responseText);
                });
            });";
        $text.='</script>';
        print $text;
    }

    /**
     * Publises and registers the required CSS and Javascript
     * @throws CHttpException if the assets folder was not found
     */
    public static function publishAssets()
    {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets))
        {
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fileupload.js', CClientScript::POS_END);
            //Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.fileupload-ui.js', CClientScript::POS_END);
            //Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.fileupload-ui.css');
        }
        else
        {
            throw new CHttpException(500, 'JFUWidget - Error: Couldn\'t find assets to publish.');
        }
    }

}