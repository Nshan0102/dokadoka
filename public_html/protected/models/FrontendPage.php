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

class FrontendPage extends CActiveRecord
{
    /**
     * @static
     * @param string $className
     * @return FrontendPage
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{pages}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function buildHref()
    {
        //return '/page/' . $this->uri;
        return '/' . $this->uri;
    }

    /**
     * @param $uri
     * @return FrontendPage
     */
    public function findByUri($uri)
    {
        //return $this->find('uri=:uri AND visible=1', array(':uri' => $uri)); //visible=1 уже есть из-за defaultScope()
        return $this->find('uri=:uri', array(':uri' => $uri));
    }

    public function rules()
    {
        return array(

        );
    }

    public function defaultScope()
    {
        return array(
            'order' => $this->getTableAlias(false, false) . ".`order`",
            'condition' => $this->getTableAlias(false, false) . '.visible=1',
        );
    }

}