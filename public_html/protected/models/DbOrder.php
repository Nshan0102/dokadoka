<?php
/**
 * @property string $id
 * @property string $body
 */
class DbOrder extends CActiveRecord
{
    /**
    * @static
    * @param string $className
    * @return DbOrder
    */
  	public static function model($className=__CLASS__)
  	{
  		return parent::model($className);
  	}

    public function tableName()
	{
		return '{{orders}}';
	}

    public function primaryKey()
    {
        return 'id';
    }

    public function rules()
   	{
   		return array();
   	}
}
?>