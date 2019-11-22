<?php
class Wyswyg extends CInputWidget
{
    public $kcFinderPath;
    public $height = '375px';
    public $width = '100%';
    //public $toolbarSet;
    public $config;
    public $filespath;
    public $filesurl;
    public $baseurl;
    //public $value;
    //public $name;
    public $type = 'ckeditor';
    //public $skipPublish = false;

    public function init()
    {
        if ($this->type == 'ckeditor')
        {
            $dir = dirname(__FILE__) . '/ckeditor/source';
            //if (!$this->skipPublish)
            $this->baseurl = Yii::app()->getAssetManager()->publish($dir);
            //$this->baseurl ~   /assets/566f9bbc
            $this->kcFinderPath = $this->baseurl . "/kcfinder/";
        }
        parent::init();
    }

    public function run()
    {
        $text='
        <script type="text/javascript" src="'.$this->baseurl.'/ckeditor/ckeditor.js"></script>
        <script type="text/javascript">
        window.CKEDITOR_BASEPATH="'.$this->baseurl.'/ckeditor/";
        function initCkeditor(taID)
        {
            var instance = CKEDITOR.instances[taID];
            if(instance)
            {
                CKEDITOR.remove(instance);
                //CKEDITOR.destroy(instance);
            }
            CKEDITOR.replace(taID, {
                "height": "'.$this->height.'", //400px
                "width": "'.$this->width.'",//95%
                "toolbar":
                    [
                    [ "Source", "Cut", "Copy", "Paste", "PasteText", "PasteFromWord" ],
                    [ "Undo", "Redo", "SelectAll", "RemoveFormat" ],
                    [ "Image", "Table", "HorizontalRule", "SpecialChar" ],
                    "\/",
                    [ "Bold", "Italic", "Underline", "Strike", "-", "Subscript", "Superscript" ],
                    [ "NumberedList", "BulletedList", "-", "Outdent", "Indent", "Blockquote", "CreateDiv" ],
                    [ "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock" ],
                    [ "Link", "Unlink" ],
                    "\/",
                    [ "Styles", "Format", "Font", "FontSize" ],
                    [ "TextColor", "BGColor" ]
                    ],
                    "language": "ru",
                    "filebrowserBrowseUrl": "'.$this->kcFinderPath.'browse.php?type=files",
                    "filebrowserImageBrowseUrl": "'.$this->kcFinderPath.'browse.php?type=images",
                    "filebrowserFlashBrowseUrl": "'.$this->kcFinderPath.'browse.php?type=flash",
                    "filebrowserUploadUrl": "'.$this->kcFinderPath.'upload.php?type=files",
                    "filebrowserImageUploadUrl": "'.$this->kcFinderPath.'upload.php?type=images",
                    "filebrowserFlashUploadUrl": "'.$this->kcFinderPath.'upload.php?type=flash"
            });

        }
        </script>
        ';


        //для KCFINDER
        $session = new CHttpSession;
        $session->open();
        $session['KCFINDER'] = array(
          'disabled' => false,
          'uploadURL' => Yii::app()->baseUrl."/media/",
          'uploadDir' => dirname(Yii::app()->request->scriptFile)."/media/",
        );
        print $text;
    }
}

?>