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
 * @property Page $parent
 * @property boolean $isNewRecord
 */

class Page extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className
	 * @return Page the static model class
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
		return '{{pages}}';
	}

    public function primaryKey()
    {
        return 'id';
    }

    /**
     * @param string $attribute the name of the attribute to be validated
     * @param array $params options specified in the validation rule
     */
    public function uriValidator($attribute,$params)
    {
        //file_put_contents("D:/_projects/yiitest/_log.txt","uriValidator\n",FILE_APPEND);
        /*
        $keys = array_keys($this->attributeLabels());
        $ret='';
        foreach ($keys as $k)
            $ret.=''.$k.'='.$this->$k.'<br>';
        $this->addError($attribute,'params:'.$ret);
        */


        /*
        $exRec=self::model()->find('uri=:uri AND pid=:pid', array(':uri'=>$this->uri,':pid'=>$this->pid));
        if ($exRec && $exRec->id!==$this->id)
            $this->addError($attribute,'Страница с таким uri уже существует у этого родителя');
        */


        if ($this->isNewRecord && empty($this->uri))
        {

            $this->createUriByHeader();
            //file_put_contents("D:/_projects/yiitest/_log.txt"," new uri created:".$this->uri."\n",FILE_APPEND);
        }



        /*
        if ($this->isNewRecord)
            $exRec=self::model()->find('uri=:uri', array(':uri'=>$this->uri));
        else
            $exRec=self::model()->find('uri=:uri AND id<>:id', array(':uri'=>$this->uri,':id'=>$this->id));
        if ($exRec)
            $this->addError($attribute,'Страница с таким uri уже существует');
        */

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
                $exRec=$this->findByUri($this->uri);//self::model()->find('uri=:uri', array(':uri'=>$this->uri));
            }
            while ($exRec!==null);
        }
        else
        {
            //$exRec=self::model()->find('uri=:uri AND id<>:id', array(':uri'=>$this->uri,':id'=>$this->id));
            $exRec=$this->findByUri($this->uri);
            if (!is_null($exRec) && $exRec->id!=$this->id)
                $this->addError($attribute,'Страница с таким uri уже существует');
        }


    }


    public static function findByUri($uri)
    {
        return self::model()->find('uri=:uri ', array(':uri'=>$uri));
    }


    private function createUriByHeader()
    {
        $this->uri = Helpers::makeCorrectUri($this->header);
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
			array('header,pid,visible', 'required'),
			array('uri', 'length', 'max'=>100),
			array('header', 'length', 'max'=>255),
			array('html_title', 'length', 'max'=>255),
			array('uri', 'uriValidator'),
			array('text,meta_kw,meta_descr,html_title,upper_text', 'safe'),
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
			'id' => 'Id',
			'uri' => 'ЧПУ',
			'header' => 'Заголовок',
			'text' => 'Текст',
			'visible' => 'Отображать на сайте?',
			'update_time' => 'Дата-время изменения',
			'order' => 'Порядок',
			'pid' => 'Родительская страница',

            'meta_kw' => 'Meta-keywords',
            'meta_descr' => 'Meta-description',
            'html_title' => 'Html title',
            'upper_text' => 'Текст сверху страницы',

		);
	}


}