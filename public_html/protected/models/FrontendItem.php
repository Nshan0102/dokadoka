<?php

/**
 * This is the model class for table "{{items}}".
 *
 * The followings are the available columns in table '{{items}}':
 * @property integer $id
 * @property string $header
 * @property integer $visible
 * @property string $order
 * @property string $catid
 * @property string $image
 * @property string $upper_text
 * @property string $uri
 * @property string $text
 * @property string $imagebig
 * @property string $shorttext
 * @property string $meta_kw
 * @property string $meta_descr
 * @property string $html_title
 * @property integer $height
 * @property integer $worktime
 * @property integer $caliber
 * @property integer $zalps
 * @property string $price
 * @property string $dimensions
 * @property integer $ishit
 * @property integer $isbonus
 * @property integer $isrecommended
 * @property integer $showonindex
 * @property string $videocode
 * @property string $videocode2
 * @property string $rating
 * @property string $artikul
 * @property integer $unicode
 * @property integer $newprice
 * @property integer $oldprice
 * @property integer $promo_price
 * @property FrontendCategory $category
 */
class FrontendItem extends CActiveRecord implements IECartPosition
{

    public $category_header;
    public $header;

    /**
     * @static
     * @param string $className
     * @return FrontendItem
     */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getCode()
    {
        return $this->unicode;
    }

    public function buildHref()
    {
        return '/item/'.$this->uri;
    }

	public function tableName()
	{
		return '{{items}}';
	}

    public function primaryKey()
    {
        return 'id';
    }

	public function rules()
	{
		return array();
	}

    public function getId()
    {
        return $this->id;
    }

    public function getPrice()
    {
        /*
        if(!$this->price && $this->oldprice)
            return $this->oldprice;
        return $this->price;
        */
        return $this->getCurrentPrice();
    }

    public function catItems($catid,$ordr=null)
    {

        $criteria = new CDbCriteria();
        if (!$ordr)
            $criteria->order = $this->getTableAlias(false,false).".`order`";
        else
            $criteria->order = $ordr;
        $criteria->condition = 'catid=:catid AND visible=1';
        $criteria->params=array(':catid' => $catid);
        return $this->findAll($criteria);
    }

    public function getSearchResults($query)
    {
        //return $this->findAll("MATCH (header,text,artikul) AGAINST(:query)", array(':query' => $query));
        return $this->findAll(" `header` LIKE :query OR `text` LIKE :query OR artikul LIKE :query", array(':query' => '%'.$query.'%'));
    }

    public function findByUri($uri)
    {
        //return $this->find('uri=:uri AND visible=1', array(':uri' => $uri)); //visible=1 уже есть из-за defaultScope()
        return $this->find('uri=:uri', array(':uri' => $uri));
    }

    /**
     * Список товаров для индекса
     * @return array
     */
    public function getList4Index()
    {
        $criteria=new CDbCriteria;
        //$criteria->select='id,header';  // which columns to select
        $criteria->order='`order`';
        //$criteria->condition='visible=1 AND showonindex=1'; //visible=1 уже есть из-за defaultScope()
        $criteria->condition='showonindex=1';
        return $this->findAll($criteria);
    }


    public function defaultScope()
    {
        return array(
            'order'=>$this->getTableAlias(false,false).".`order`",
           // 'condition'=>$this->getTableAlias(false,false).'.visible=1',
        );
    }

	public function relations()
	{
		return array(
            'category'=>array(self::BELONGS_TO, 'FrontendCategory', 'catid','select'=>'id,header'),
            //more info on params and syntax in CActiveRecord.relations() phpdoc
		);
	}


    public function search()
   	{
        $criteria=new CDbCriteria;

        $sort = new CSort;
        $sort->attributes = array(
            //'catid',
            'defaultOrder'=>'t.header ASC',

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

        return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>$sort,
		));

    }

    public function hasOldPrice()
    {
        if ($this->oldprice && $this->price && $this->oldprice != $this->price) {
            return true;
        }
        return false;
    }

    public function getOldPriceFormatted()
    {
        return number_format($this->oldprice, 0, '', ' ');
    }

    public function hasDiscount()
    {
        return $this->getDiscountPercent()?true:false;
    }

    public function hasPersonalDiscount()
    {
        return self::getCurrentPromocode() && $this->promo_price;
    }

    public function getDiscountPercent()
    {
        if (!$this->oldprice) {
            return 0;
        }

        $actualPrice = $this->getCurrentPrice();

        $value = round(100*($this->oldprice-$actualPrice)/$this->oldprice);

        return $value;
    }

    const PROMO_PRICE_SESSION_KEY = 'promo_prices';

    public static function getCurrentPromocode()
    {
        if (isset($_COOKIE[self::PROMO_PRICE_SESSION_KEY]) && GlobalOrdersPhone::getByCode($_COOKIE[self::PROMO_PRICE_SESSION_KEY])) {
            return $_COOKIE[self::PROMO_PRICE_SESSION_KEY];
        }
        /*
        if ($code = Yii::app()->session[self::PROMO_PRICE_SESSION_KEY]) {
            return $code;
        }
        */
        return null;
    }

    public function getCurrentPriceFormatted()
    {
        return number_format($this->getCurrentPrice(), 0, '', ' ');
    }

    public function getCurrentPrice()
    {
       return (self::getCurrentPromocode() && $this->promo_price) ? $this->promo_price : $this->price;
    }

    public function getOrigPrice()
    {
        return $this->oldprice?$this->oldprice:$this->price;
    }
}