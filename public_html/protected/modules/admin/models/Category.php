<?php

/**
 * @property integer $id
 * @property string $uri
 * @property string $header
 * @property string $text
 * @property integer $visible
 * @property string $update_time
 * @property integer $order
 * @property string $pid
 * @property string $meta_kw
 * @property string $meta_descr
 * @property string $html_title
 * @property string $upper_text
 * @property string $image
 * @property Category $parent
 * @property boolean $isNewRecord
 */

class Category extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className
	 * @return Category the static model class
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
		return '{{categories}}';
	}

    public function primaryKey()
    {
        return 'id';
    }


    /** Список всех категорий для селектов (только айди + название)
     * @return array
     */
    public function getList4Selects()
    {
        $criteria=new CDbCriteria;
        $criteria->select='id,header';  // which columns to select
        $criteria->order='`order`';
        //$criteria->condition='postID=:postID';
        //$criteria->params=array(':postID'=>10);
        return $this->findAll($criteria);
    }

    /**
     * @param string $attribute the name of the attribute to be validated
     * @param array $params options specified in the validation rule
     */
    public function uriValidator($attribute,$params)
    {
        //if ($this->isNewRecord && empty($this->uri))
        if (empty($this->uri))
            $this->createUriByHeader();

        $origUri = $this->uri;
        $i=1;
        do
        {
            $this->uri = $origUri;
            if ($i!=1)
                $this->uri.=$i;
            $i++;
            if ($this->isNewRecord)
                $exRec=self::model()->find('uri=:uri', array(':uri'=>$this->uri));
            else
                $exRec=self::model()->find('uri=:uri AND id<>:id', array(':uri'=>$this->uri,':id'=>$this->id));
        }
        while ($exRec!==null);
        /*
        if ($this->isNewRecord)
        {
            $origUri = $this->uri;
            $i=1;
            do
            {
                $this->uri = $origUri;
                if ($i!=1)
                    $this->uri.=$i;
                $i++;
                $exRec=self::model()->find('uri=:uri', array(':uri'=>$this->uri));
            }
            while ($exRec!==null);
        }
        else
        {
            $exRec=self::model()->find('uri=:uri AND id<>:id', array(':uri'=>$this->uri,':id'=>$this->id));
            if ($exRec)
                $this->addError($attribute,'Категория с таким uri уже существует');
        }
        */
    }


    public static function findByUri($uri)
    {
        return self::model()->find('uri=:uri ', array(':uri'=>$uri));
    }


    private function createUriByHeader()
    {
        Yii::import('application.extensions.UrlTransliterate.UrlTransliterate');
        $this->uri = UrlTransliterate::cleanString($this->header);
    }

    public function delete()
    {
        parent::delete();
        if (!empty($this->image))
            @unlink(dirname(Yii::app()->request->scriptFile).'/'.$this->image);
        Yii::app()->db->createCommand("UPDATE ".Yii::app()->db->tablePrefix."items SET catid=NULL WHERE catid=".$this->id)->execute();
    }

    public function beforeSave()
    {
        $this->update_time = new CDbExpression('NOW()');
        return parent::beforeSave();
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.


        //http://www.yiiframework.com/wiki/10/how-to-automate-timestamps-in-activerecord-models/


		return array(
			//array('uri,visible,order,pid,header', 'required'),
			array('header,pid,header,visible', 'required'),
            /*
			array('parent_id, position, visible', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>25),
			array('tooltip, options', 'length', 'max'=>100),
            */
			array('uri', 'length', 'max'=>100),
			array('header', 'length', 'max'=>255),
			array('html_title', 'length', 'max'=>255),
			array('uri', 'uriValidator'),
			array('text,upper_text,meta_kw, meta_descr,html_title', 'safe'),

			array('image', 'file','types'=>'jpg,jpeg,gif,png',"allowEmpty"=>TRUE),//без allowEmpty не даёт сохранить форму без изображения
            //array('image', 'unsafe'),//By making the image unsafe the input values for the image field wont be set by $model->attributes=$_POST['Category'];
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, parent_id, title, position, tooltip, url, icon, visible, task, options', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'items'=>array(self::HAS_MANY, 'Item', 'catid'),
		);
	}


    public function defaultScope()
    {
        return array(
            //'order'=>$this->getTableAlias(false,false).".`order`",
            //'condition'=>$this->getTableAlias(false,false).'.visible=1', //тогда и редактирование в админке не находит
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'uri' => 'ЧПУ',
			'header' => 'Название',
			'text' => 'Описание категории',
			'visible' => 'Отображать на сайте?',
			'update_time' => 'Дата-время изменения',
			'order' => 'Порядок',
			'pid' => 'Родительская категория',

            'meta_kw' => 'Meta-keywords',
            'meta_descr' => 'Meta-description',
            'html_title' => 'Html title',
            'upper_text' => 'Текст сверху страницы',
            'image' => 'Изображение',

		);
	}


}