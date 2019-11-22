<?php

class GlobalOrders 
{
    private $dbHost;
    private $dbUser;
    private $dbPass;
    private $dbName;
    private $dbh;
    private $source;
    private $discountPercentViaOrder;
    private $noDB = false;

    private function __construct($source/*$dbHost, $dbName, $dbUser, $dbPass */)
    {
        $this->discountPercentViaOrder = 0;
        $this->source = $source;
        /*
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbPass = $dbPass;
        $this->dbUser = $dbUser;
        */

        /*
        if (0 && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            //test mode on windows
            $this->dbHost = 'localhost';
            $this->dbName = 'fireworks';
            $this->dbPass = 'root';
            $this->dbUser = 'root';

        } else {
*/
        if($source=='fireworks.by' || $source=='369.by') {
            $this->dbHost = 'localhost';
        } else {
            $this->dbHost = '93.84.118.132';
        }

            $this->dbName = 'firew';
            $this->dbPass = '98wj2khds5';
            $this->dbUser = 'firew';
       // }

        $this->dbh = @mysqli_connect( $this->dbHost, $this->dbUser, $this->dbPass,$this->dbName);
        if(!$this->dbh) {
            //throw new Exception("db connect failed");
            $this->log('ERROR: mysql connect failed');
            $this->noDB = true;
        } else {
            mysqli_query($this->dbh,"SET names 'utf8'");
        }

    }

    private function log($msg)
    {
        @file_put_contents(dirname(__FILE__).'/gorders.log',date('[Y-m-d H:i:s]').$msg."\n", FILE_APPEND);
    }

    public static function getInstance($source)
    {
        return new self($source);
    }



    public function getSavedDiscount($phone)
    {
        if($this->noDB) {
            return 0;
        }
        $row = $this->dbGetRow('SELECT '.' * FROM go_phones WHERE phone="'.mysqli_real_escape_string($this->dbh,$phone).'"');
        if(!$row || !$row['discount']) {
            return 0;
        }
        if($row['discount']==25) {return 0;}//todo tmp, 25% отключена 28.01.2015

        return $row['discount'];
    }

    private function dbQuery($q)
    {
        $r = mysqli_query($this->dbh,$q);
        return $r;
    }

    private function dbGetRow($q)
    {
        $r = $this->dbQuery($q);
        $row = mysqli_fetch_assoc($r);
        mysqli_free_result($r);
        return $row;
    }

    private function dbInsert($table, $fields)
    {
        $query = "INSERT "." INTO `".$table."` ";
        $fnames  = array();
        $fvalues = array();
        foreach ($fields as $n=>$v)
        {
            $fnames[]  = '`'.$n.'`';
            if (is_null($v))
                $fvalues[] = "NULL";
            else
                $fvalues[] = "'".mysqli_real_escape_string($this->dbh, $v)."'";
        }
        $query .= "(".implode(', ',$fnames).") VALUES ";
        $query .= "(".implode(', ', $fvalues).")";
        $this->dbQuery($query);
        return mysqli_insert_id($this->dbh);
    }

    private function dbUpdate($table, $fields, $where)
    {
        $query = "UPDATE `".$table."` SET ";
        $setfields  = array();
        foreach ($fields as $n=>$v)
        {
            if (is_null($v))
                $setfields[]  = "`".$n."`=NULL";
            else
                $setfields[]  = "`".$n."`='".mysqli_real_escape_string($this->dbh,$v)."'";
        }
        $query .= implode(', ',$setfields)." WHERE ".$where;
        return $this->dbQuery($query);
    }

    private function dbGetList($q)
    {
        $r = $this->dbQuery($q);
        $res = array();
        while($row = mysqli_fetch_assoc($r)) {
            $res[] = $row;
        }
        mysqli_free_result($r);
        return $res;
    }

    private function addGlobalOrderPhone($phone, $name)
    {
        $promoCode = GlobalOrdersPhone::generatePromoCodeFromPhone($phone);
        return $this->dbInsert('go_phones', array('phone'=>$phone,'name'=>$name, 'promo_code'=>$promoCode));
    }

    /**
     * @param $phone
     * @param GlobalOrderItem[] $oitems
     * @param string $name
     */
    public function addOrder($phone, array $oitems, $name=null)
    {
        $phone = htmlspecialchars($phone);
        $this->discountPercentViaOrder = 0;
        if($this->noDB) {
            return;
        }
        $sum = 0;
        foreach($oitems as $itm) {
            $sum += $itm->price*$itm->qty;
        }

        $row = $this->dbGetRow('SELECT '.' * FROM go_phones WHERE phone="'.mysqli_escape_string($this->dbh,$phone).'"');
        if(!$row){
            $phID = $this->addGlobalOrderPhone($phone, $name);
            $hasStoredDiscount = false;
        } else {
            $phID = $row['id'];
            $hasStoredDiscount = $row['discount'];
        }

        $orderRec = array(
            'phone_id'=>$phID,
            'sum'=>intval($sum), //todo стоимость заказа в $
            'data'=>json_encode($oitems),
            'when'=>date('Y-m-d H:i:s'),
            'source' => $this->source,
        );
        $this->dbInsert('go_orders', $orderRec );

        //определение процента для след. скидки и сохранение его
        if(!$hasStoredDiscount) {
            $discSettings = $this->dbGetList('SELECT * FROM go_discounts_settings ORDER BY discount');
            $discountViaOrder = null;
            foreach($discSettings as $ds) {
                $minItems = $ds['min_items'];
                $minPrice = $ds['min_price'];
                $foundItems = 0;
                foreach($oitems as $oitem) {
                    //$itemSum = $oitem->price*$oitem->qty;
                    if($oitem->price<$minPrice) {
                        continue;
                    }
                    $foundItems+=$oitem->qty;
                }
                if($foundItems>=$minItems) {
                    $discountViaOrder = $ds['discount'];
                    break;
                }
            }
            //if($discountViaOrder) {
            if($discountViaOrder && $discountViaOrder!=25) { //todo tmp, 25% отключена 28.01.2015
                $this->discountPercentViaOrder = $discountViaOrder;
                //$this->dbUpdate('go_phones', array('discount'=>$discountViaOrder),'id='.$phID);
            }
        }

    }

    public function getDiscountPercentByLastOrder()
    {
        return $this->discountPercentViaOrder;
    }

}

class GlobalOrderItem
{
    public $price;
    public $name;
    public $qty;

    function __construct($name,$price, $qty)
    {
        $this->name = $name;
        $this->price = $price;
        $this->qty = $qty;
    }


}