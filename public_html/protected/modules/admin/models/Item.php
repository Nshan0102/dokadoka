<?php

/**
 * This is the model class for table "{{items}}".
 *
 * The followings are the available columns in table '{{items}}':
 * @property string $id
 * @property string $header
 * @property integer $visible
 * @property string $order
 * @property string $catid
 * @property string $image
 * @property string $imagebig
 * @property string $upper_text
 * @property string $uri
 * @property string $text
 * @property string $shorttext
 * @property string $meta_kw
 * @property string $meta_descr
 * @property string $html_title
 * @property integer $height
 * @property integer $worktime
 * @property integer $caliber
 * @property integer $zalps
 * @property float $price
 * @property string $dimensions
 * @property integer $ishit
 * @property integer $isbonus
 * @property integer $isrecommended
 * @property integer $showonindex
 * @property string $videocode
 * @property string $videocode2
 * @property string $rating
 * @property string $artikul
 * @property Category $category
 * @property integer $unicode
 * @property float $promo_price
 */
class Item extends CActiveRecord
{

    public $category_header=null;
    public $header;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{items}}';
	}

    public function primaryKey()
    {
        return 'id';
    }

    /**
     * @param string $attribute the name of the attribute to be validated
     * @param array $params options specified in the validation rule
     */
    public function uriValidator($attribute, $params)
    {
        if ($this->getIsNewRecord())
            $exRec=self::model()->find('uri=:uri', array(':uri'=>$this->uri));
        else
            $exRec=self::model()->find('uri=:uri AND id<>:id', array(':uri'=>$this->uri,':id'=>$this->id));
        if ($exRec)
            $this->addError($attribute,'Товар с таким ЧПУ уже существует');
    }

	public function rules()
	{
		return array(
			array('header, catid, uri', 'required'),
            //array('colX','default','setOnEmpty'=>true,'value'=>0),//This way Yii will set the attribute colX to 0 if it's empty
            array('price,newprice,oldprice,rating,unicode,promo_price','default','setOnEmpty'=>true,'value'=>null),//This way Yii will set the attributes to null if it's empty
			array('order,visible, height, worktime, caliber, zalps, ishit, isbonus, isrecommended,showonindex', 'numerical', 'integerOnly'=>true),
			array('price,newprice,oldprice,promo_price', 'numerical'),
			array('header, image, imagebig, upper_text,artikul', 'length', 'max'=>255),
			array('uri', 'length', 'max'=>100),
			array('unicode', 'length', 'max'=>100),
			array('html_title', 'length', 'max'=>500),
			array('dimensions', 'length', 'max'=>30),
			array('rating', 'length', 'max'=>4),
            array('uri', 'uriValidator'),
            array('unicode', 'unique','allowEmpty'=>true),
			array('text, shorttext, meta_kw, meta_descr, videocode,videocode2', 'safe'),
            array('image,imagebig', 'file','types'=>'jpg,jpeg,gif,png',"allowEmpty"=>TRUE),//без allowEmpty не даёт сохранить форму без изображения
			//array('id, header, visible, order, catid, upper_text, uri, text, shorttext, height, worktime, caliber, zalps, price, dimensions, ishit, isbonus, isrecommended, rating', 'safe', 'on'=>'search'),
            array('category_header,uri,price,newprice,oldprice,header,catid,visible', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
            'category'=>array(self::BELONGS_TO, 'Category', 'catid','select'=>'id,header'),
            //more info on params and syntax in CActiveRecord.relations() phpdoc
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'header' => 'Название',
			'unicode' => 'Уник.код',
			'artikul' => 'Артикул',
			'visible' => 'Отображать на сайте?',
			'order' => 'Порядок',
			'catid' => 'Категория',
			//'newprice' => 'новая цена',
			'oldprice' => 'старая цена',
			'image' => 'Картинка маленькая',
			'imagebig' => 'Картинка большая',
			'upper_text' => 'Текст сверху страницы',
			'uri' => 'ЧПУ',
			'text' => 'Полное описание',
			'shorttext' => 'Короткое описание',
			'meta_kw' => 'Meta Keywords',
			'meta_descr' => 'Meta Descr',
			'html_title' => 'Html Title',
			'height' => 'Высота выстрела (м)',
			'worktime' => 'Время работы (сек)',
			'caliber' => 'Калибр',
			'zalps' => 'Кол-во залпов',
			'price' => 'новая цена',
			'dimensions' => 'Размеры (ШхДхВ см)',
			'ishit' => 'Хит?',
			'isbonus' => 'Бонус?',
			'isrecommended' => 'Рекомендовано?',
			'videocode' => 'youtube ссылка (вида http://www.youtube.com/watch?v=w_J-9syHC7o)',
			'videocode2' => 'youtube embed',
			'rating' => 'Рейтинг',
            'showonindex' => 'Выводить на главной?',
            'category_header ' => 'наззвание категории',
            'promo_price' => 'промо-цена',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
        //via http://www.yiiframework.com/forum/index.php?/topic/24280-gridview-filtering-of-relational-data/
        //simpler: http://www.mrsoundless.com/post/2011/05/09/Searching-and-sorting-a-column-from-a-related-table-in-a-CGridView.aspx

		$criteria=new CDbCriteria;

        // Do all joins in the same SQL query
        $criteria->together  =  true;
        // Join the 'category' table
        $criteria->with = array('category');

        //укажем какие поля нам нужны вообще
        $criteria->select = 'id,header,newprice,oldprice, price,uri,catid,order,unicode,visible,promo_price';

        /*
		$criteria->compare('id',$this->id,true);

		$criteria->compare('visible',$this->visible);
		$criteria->compare('order',$this->order,true);
        */
		$criteria->compare('catid',$this->catid,true);
        $criteria->compare('uri',$this->uri,true);
		if (strlen($this->visible)) {
			$criteria->compare('t.visible', $this->visible);
		}
        $criteria->compare('t.header',$this->header,true);

        if($this->category_header)
            $criteria->compare('catid',$this->category_header);
            //$criteria->compare('category.header',$this->category_header,true);


        // Create a custom sort
        $sort = new CSort;
        $sort->attributes = array(
            //'catid',
            'defaultOrder'=>'t.header ASC
            ',
            // For each relational attribute, create a 'virtual attribute' using the public variable name
            'category_header' => array(
                'asc' => 'category.header',
                'desc' => 'category.header DESC',
                'label' => 'Название категории',
            ),
            /*
            'location_city_title' => array(
                'asc' => 'location.city_title',
                'desc' => 'location.city_title DESC',
                'label' => 'City',
            ),
            'location_airport_code' => array(
                'asc' => 'location.airport_code',
                'desc' => 'location.airport_code DESC',
                'label' => 'Airport Code',
            ),*/
            '*',
        );


        $adp=new CActiveDataProvider($this, array(
        			'criteria'=>$criteria,
                    'sort'=>$sort,
        		));
        //$adp->pagination->pageSize=2; //works fine
        return $adp;
	}
}