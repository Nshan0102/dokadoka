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

class FrontendCategory extends CActiveRecord
{
    /**
     * @static
     * @param string $className
     * @return FrontendCategory
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

    public function buildHref()
    {
        return '/category/'.$this->uri;
    }

    /**
     * Список только видимых категорий для фронтенда (только айди + название + uri + image)
     * @return array
     */
    public function getList4Front()
    {
        $criteria=new CDbCriteria;
        $criteria->select='id,header,uri,image';  // which columns to select
        $criteria->order='`order`';
        //$criteria->condition='visible=1';//visible=1 уже есть из-за defaultScope()
        return $this->findAll($criteria);
    }


    public function findByUri($uri)
    {
        //return $this->find('uri=:uri AND visible=1', array(':uri' => $uri)); //visible=1 уже есть из-за defaultScope()
        return $this->find('uri=:uri', array(':uri' => $uri));
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array();
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
            'order'=>$this->getTableAlias(false,false).".`order`",
            'condition'=>$this->getTableAlias(false,false).'.visible=1',
        );
    }


}