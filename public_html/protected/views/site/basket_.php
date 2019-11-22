<?php
$this->pageTitle='Корзина';
?>

<div style="float:left; width:700px; background-color:#f1faff;">

<?php 

if ($isOrderReceived)
{
    ?>
    <div style=" margin:10px 10px 10px 20px; padding:10px;"><b>Спасибо, ваш заказ принят</b></div>
    <?php
}
elseif (Yii::app()->shoppingCart->isEmpty())
{
    ?>
    <div style=" margin:10px 10px 10px 20px; padding:10px;"><b>В корзине нет товаров</b></div>
    <?php
}
else
{
?>

<table border="0" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; border-bottom: #c1bba7 dotted 1px;">
  <tr>
    <td colspan="3"><div class="korzina">Корзина</div></td></tr>
  <tr>
    <td>

        <table cellpadding="0" cellspacing="0" class="basketin">
        <tr style="font-weight:bold; background-color:#dff4ff;">
            <td>№</td>
            <td>Код</td>
            <td>Наименование товара</td>
            <td width="80">Стоимость</td>
            <td width="70">Количество</td>
            <td width="80">Сумма</td>
            <td width="40">Удалить</td>
        </tr>

        <?php
         $positions = Yii::app()->shoppingCart->getPositions();
         $num=1;
         foreach ($positions as $position)
         {
        ?>
            <tr id="basketline_<?=$position->getId()?>">
                <td bgcolor="#b4e4ff"><?=$num++;?></td>
                <td><?=$position->getCode();?></td>
                <td><a href="<?=$position->buildHref();?>"><?=$position->header;?></a></td>
                <td><span class="summ" id="item_<?=$position->getId()?>_price"><?=number_format($position->getPrice(),0,'',' ')?></span> Br</td>
                <td>
                    <div style=" vertical-align: top;">
                        <span class="counterin">
                          <input class="btn" value="" onclick="decQty('iqty_<?=$position->getId()?>');updateBasketItem(<?=$position->getId()?>);" type="button" />
                          <input id="iqty_<?=$position->getId()?>" disabled="disabled" class="count" value="<?=$position->getQuantity()?>" type="text" />
                          <input class="btn" value="" onclick="incQty('iqty_<?=$position->getId()?>');updateBasketItem(<?=$position->getId()?>);" type="button" />
                        </span>
                    </div>
                </td>
                <td><span class="summ" id="item_<?=$position->getId()?>_subtotal"><?=number_format($position->getSumPrice(),0,'',' ')?></span> Br</td>
                <td><a href="#" onclick="removeBasketItem(<?=$position->getId()?>);return false;"><img src="/i/delete.png" width="16" height="16" /></a></td>
            </tr>
        <?php
         }
        ?>


        <tr>
            <td colspan="5"></td>
            <td colspan="2">
                <span class="ittogo">ИТОГО:</span>
                <span class="summa" id="basket_sum">
                    <?=number_format(Yii::app()->shoppingCart->getCost(),0,'',' ');?> Br
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="7">


                <?php echo CHtml::beginForm('/basket','post',array('id'=>'orderform')); ?>

                <?php echo CHtml::errorSummary($model); ?>

                <table border="0" class="info">
                <tr><td colspan="4">Информация для доставки:</td></tr>
                <tr>
                  <td>Имя:</td>
                  <td colspan="2">
                      <?php echo CHtml::activeTextField($model,'name',array('style'=>'width:245px;')) ?>
                  </td>
                  <td>Комментарии:</td>
                </tr>
                <tr>
                  <td>Номер телефона:</td>
                  <td>
                      <?php echo CHtml::activeDropDownList($model,'phone_prefix',
                            array('+375 29'=>'+375 29','+375 44'=>'+375 44','+375 33'=>'+375 33','+375 25'=>'+375 25'),array('size'=>1));?>
                  </td>
                  <td><?php echo CHtml::activeTextField($model,'phone') ?></td>
                  <td rowspan="3">
                      <?php echo CHtml::activeTextArea($model,'comment',array('style'=>'width:220px; height:120px;','cols'=>60,'rows'=>7)) ?>
                  </td>
                  </tr>
                <tr>
                  <td>Адрес доставки:</td>
                  <td>
                      <select name="OrderForm[city]" size="1">
                            <option>Минск</option>

                          <optgroup label="Брестская область">
                              <option>Барановичи</option>
                              <option>Береза</option>
                              <option>Брест</option>
                              <option>Дрогичин</option>
                              <option>Иваново</option>
                              <option>Ивацевичи</option>
                              <option>Кобрин</option>
                              <option>Лунинец</option>
                              <option>Пинск</option>
                          </optgroup>

                          <optgroup label="Витебская область">
                              <option>Витебск</option>
                              <option>Лепель</option>
                              <option>Новополоцк</option>
                              <option>Орша</option>
                              <option>Полоцк</option>
                              <option>Толочин</option>
                              <option>Ушачи</option>
                              <option>Шумилино</option>
                          </optgroup>

                          <optgroup label="Гомельская область">
                              <option>Гомель</option>
                              <option>Жлобин</option>
                              <option>Калинковичи</option>
                              <option>Микашевичи</option>
                              <option>Мозырь</option>
                              <option>Паричи</option>
                              <option>Речица</option>
                              <option>Рогачев</option>
                              <option>Светлогорск</option>
                          </optgroup>

                          <optgroup label="Гродненское область">
                              <option>Волковыск</option>
                              <option>Гродно</option>
                              <option>Зельва</option>
                              <option>Ивье</option>
                              <option>Лида</option>
                              <option>Мосты</option>
                              <option>Скидель</option>
                              <option>Слоним</option>
                              <option>Щучин</option>
                          </optgroup>
                          <optgroup label="Минская область">
                              <option>Березино</option>
                              <option>Борисов</option>
                              <option>Воложин</option>
                              <option>Дзержинск</option>
                              <option>Жодино</option>
                              <option>Заславль</option>
                              <option>Крупки</option>
                              <option>Логойск</option>
                              <option>Марьина Горка</option>
                              <option>Молодечно</option>
                              <option>Плещеницы</option>
                              <option>Слуцк</option>
                              <option>Смолевичи</option>
                              <option>Солигорск</option>
                              <option>Столбцы</option>
                          </optgroup>
                          <optgroup label="Могилевская область">
                              <option>Белыничи</option>
                              <option>Бобруйск</option>
                              <option>Могилев</option>
                              <option>Осиповичи</option>
                              <option>Шклов</option>
                          </optgroup>
                    </select>
                  </td>
                  <td><?php echo CHtml::activeTextField($model,'address')?></td>
                  </tr>
                <tr>
                  <td>Желаемое время доставки (промежуток не менее 2 часов)&nbsp;</td>
                  <td colspan="2"><?php echo CHtml::activeTextField($model,'time',array('style'=>'width:245px')) ?></td>
                </tr>
                </table>
                <?php echo CHtml::endForm(); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Обработка заказов:

                Дни обработки заказов:
                Понедельник
                Вторник
                Среда
                Четверг
                Пятница
                Суббота
                Воскресенье
                Время обработки заказов: c 9:00 до 18:00
            </td>
            <td colspan="3">
                <div class="itogo_cart" style="margin:0; padding:0; float:right;">
                    <a href="#" onclick="$('#orderform').submit();return false;" class="checkout" style="margin:0; padding:0;"><span>Оформить заказ</span></a>
                </div>
            </td>
        </tr>
    </table>
    </td>
</tr>
</table>
<?php
}
?>
</div>