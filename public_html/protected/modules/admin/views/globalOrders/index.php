<?php
require dirname(__FILE__).'/_header.php';
?>
<!--
/** @var GlobalOrdersController $this  */
/** @var GlobalOrdersController $this  */
/*
$dataProvider = new CArrayDataProvider(GlobalOrdersOrder::model()->findAll());
$dataProvider->setPagination([
        'pageSize' => 100
]);
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'items_grid',
        'dataProvider'=>$dataProvider,
        //'filter' => $model,
        'columns' => array(

            array(
                'name'=>'when',
                'header'=>'когда',
            ),
            array(
                'name'=>'sum',
                'header'=>'сумма',
            ),
            array(
                'name'=>'phone',
                'header'=>'телефон',
            ),
            array(
                'name'=>'name',
                'header'=>'имя',
            ),

            array(
                'name'=>'data',
                'header'=>'данные',
                'type'=>'raw',
                'value' => 'nl2br($data->getDataPretty())'
               // 'value'=>'"<pre>".print_r(json_decode($data->data,1),1)."</pre>"'
            ),

            array(
                'name'=>'source',
                'header'=>'источник',
            ),


            array(
                'class'=>'CButtonColumn',
                'template'=>'{delete}',
                //'template'=>'{update}{delete}',
                'buttons'=>array
                (

                    'update' => array
                    (
                        'label'=>'Редактировать',
                        'url'=>'Yii::app()->createUrl("admin/globalOrders/edit", array("id"=>$data->id))',
                    ),

                    'delete' => array
                    (
                        'label'=>'Удалить',
                        'url'=>'Yii::app()->createUrl("admin/globalOrders/delete", array("id"=>$data->id))',
                    ),
                ),
            ),
        ),
    ));
*/
-->
<?php
/** @author Nshan Vardanyan **/
?>
<div style="margin-bottom: 20px;margin-top: 20px;display: flex;flex-direction: row;justify-content: space-between;">
    <select style="height: 20px;" onchange="filter(this)" id="promo_type">
        <option value="all">Все</option>
        <option value="endLess">Бесконечный</option>
        <option value="oneTime">Одноразовый</option>
        <option value="noPromo">Без Промокода</option>
    </select>
</div>
<table>
    <thead>
        <tr>
            <td class="tableCell theadText">ID</td>
            <td class="tableCell theadText">Имя</td>
            <td class="tableCell theadText">Телефон</td>
            <td class="tableCell theadText">Промокод</td>
            <td class="tableCell theadText">Тип Промокода</td>
            <td class="tableCell theadText">Сумма (руб.)</td>
            <td class="tableCell theadText">Город</td>
            <td class="tableCell theadText">Адресс</td>
            <td class="tableCell theadText">Oплачено</td>
            <td class="tableCell theadText">Доставлено</td>
            <td class="tableCellLast theadText" style="align-items: center">Действия</td>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($models as $data) {
        ?>
                <tr class="<?php if($data['promo_code_type'] === "0"){echo 'noPromo';}else{echo $data['promo_code_type'] === "1" ? 'oneTime' : 'endLess';}?>" id="<?php echo $data['id'] ?>">
                    <td class="tableCell">
                        <span><?php echo $data['id'] ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['name'] ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo trim($data['phone_prefix']).trim($data['phone']); ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['promo_code'] ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php
                            if($data['promo_code_type'] === "0"){
                                echo 'Без Промокода';
                            }else{
                                echo $data['promo_code_type'] === "1" ? 'Одноразовый' : 'Бесконечный';
                            }
                            ?>
                        </span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['sum'] ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['city'] ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['address'] ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['paid'] === "0" ? "<button style='min-height: 27px;' onclick='orderSetPaid(this,".'"'.$data['id'].'"'.")'>Оплачено</button>" : "<span style='font-size: 20px;font-weight:900;color:#2ff32f'>&#10004;</span>" ?></span>
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['shipped'] === "0" ? "<button style='min-height: 27px;' onclick='orderSetShipped(this,".'"'.$data['id'].'"'.")'>Доставлено</button>" : "<span style='font-size: 20px;font-weight:900;color:#2ff32f'>&#10004;</span>" ?></span>
                    </td>
                    <td class="tableCellLast">
                        <span style="display: none;" id="data_<?php echo $data['id']; ?>"><?php echo json_encode((array)$data);?></span>
                        <button class="btn-primary" onclick="seeOrder(this,'<?php echo "data_".$data['id']; ?>')">&#128065;</button>
                        <button class="btn-danger" onclick="deleteOrder(this,'<?php echo $data['id']; ?>')">&#10060;</button>
                    </td>
                </tr>
        <?php
            };
        ?>
    </tbody>
</table>
<div class="pagination">
    <div>
        <?php $this->widget('CLinkPager', array(
            'pages' => $pages,
        )) ?>
    </div>
</div>
<div id="myModal" class="modal" onclick="closeModal()">
    <div class="modal-content" onclick="event.preventDefault()">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="orderCommentBlock">
            <b>Время доставки : <span id="orderTime"></span></b><br>
            <b>Комментария покупателя :</b><br>
            <span id="orderComment"></span></div>
        <div style="max-height: 50vh;overflow-y: auto;">
            <table>
                <thead>
                    <tr>
                        <td>Имя</td>
                        <td>Цена</td>
                        <td>Кол.</td>
                        <td>Сумма</td>
                    </tr>
                <tbody id="modalBody">

                </tbody>
                </thead>
            </table>
        </div>
    </div>
</div>
<script src="../../../../../js/admin.js"></script>
<script>var modal = $('#myModal')[0];</script>
<?php
/** @author Nshan Vardanyan **/
?>