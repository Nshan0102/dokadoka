<?php
/** @var $isOrderReceived boolean */
/** @var $model OrderForm */
/** @var FrontendItem[] $positions */
/** @var string $totalCost */
/** @var int $discount */
/** @var int $orderNum */
/** @var int $sumWithDiscount */
$this->pageTitle='Корзина';
$positions = Yii::app()->shoppingCart->getPositions();
$num=1;

$currentPromoCode = FrontendItem::getCurrentPromocode();
?>

<div class="content basket">
<p>
    <?if ($isOrderReceived):?>

        <b>СПАСИБО, <?=CHtml::encode($model->name)?>, ВАШ ЗАКАЗ ПРИНЯТ.<br>


            Номер вашего заказа: <?=$orderNum?><br>
            Сумма заказа: <?=floatval($totalCost)?> руб.<br>
            <?if($discount):?>

                Ваша скидка: <?=$discount?>%<br>
                Сумма к оплате со скидкой: <?=floatval($sumWithDiscount)?> руб.<br>
            <?endif?>


            В БЛИЖАЙШЕЕ ВРЕМЯ МЫ СВЯЖЕМСЯ С ВАМИ ДЛЯ УТОЧНЕНИЯ ДЕТАЛЕЙ.<br>
            Напоминаем что ДЛЯ БЕСПЛАТНОЙ ДОСТАВКИ вашего заказа его стоимость должна быть не менее 400 000 рублей!<br>
            Если сумма вашего заказа меньше - доставка платная 30 000 руб!<br>


        </b></p>
    <?else:?>


        <?if(Yii::app()->shoppingCart->isEmpty()):?>
             <p class="basket-empty">В вашей корзине нет товаров</p>
        <?else:?>

    <?=CHtml::beginForm('/basket','post',array('id'=>'info-form')); ?>
    <?=CHtml::errorSummary($model); ?>
    <div class="basket-wrap">
        <p class="title">Корзина</p>

        <ul class="basket-table">
            <li class='table-head '>
                <p class="number hidden-sm">№</p>
                <p class="code hidden-sm">Код</p>
                <p class="name">Наименование товара</p>
               <!-- <p class="name">Наименование</p>-->
                <p class="price hidden-sm">Стоимость</p>
                <p class="count">Количество</p>
                <p class="summ">Сумма</p>
                <p class="delete">Удалить</p>
            </li>

            <?foreach ($positions as $position):?>
            <li class="itemContainer" data-id="<?=$position->getId()?>">
                <p class="number hidden-sm"><?=$num++?></p>
                <p class="code hidden-sm"><?=$position->getCode()?></p>
                <p class="name"><a href="<?=$position->buildHref()?>"><?=$position->header?></a></p>
                <p class="price hidden-sm" data="<?=$currentPromoCode?$position->getOrigPrice():$position->getPrice()?>">
			<?=$currentPromoCode?floatval($position->getOrigPrice()):floatval($position->getPrice())?> руб.</p>
                <p class="count">
							<span class="counter">
								<span class='minus'></span>
								<input type="text" value="<?=$position->getQuantity()?>" class='counter-value' disabled="disabled">
								<span class='plus'></span>
							</span>
                </p>
                <p class="summ"><?=$currentPromoCode?floatval($position->getOrigSumPrice()):floatval($position->getSumPrice())?> руб.</p>
                <p class="delete">
                    <span class="delete-button" data-id="<?=$position->getId()?>"></span>
                </p>
            </li>
            <?endforeach?>



            <li class='last' style="height: 80px;">
                <p class="all-summ">

                    <? if($currentPromoCode):?>

                        <?
                            $origCost = Yii::app()->shoppingCart->getOrigCost();
                            $currentCost = Yii::app()->shoppingCart->getCost();
                            $discountSum = $currentCost - $origCost;
                        ?>
                        СУММА: <span><?=floatval($origCost);?> руб.</span><br>
                        СКИДКА: <span style="color: red;font-weight: bold;"><?=floatval($discountSum);?> руб.</span><br>
                        ИТОГО СО СКИДКОЙ: <span id="total_price_num"><?=floatval($currentCost);?> руб.</span><br>
                    <?else:?>
                        ИТОГО: <span><span id="total_price_num"><?=floatval(Yii::app()->shoppingCart->getCost());?></span> руб.</span>
                    <?endif?>
                </p>
            </li>
        </ul>

        <form action="/info-form/" method='GET' id='info-form'>
            <p class="form-title">Информация для доставки:</p>

            <div class="form-left">
                <p class="input-wrap">
                    <span style="font-weight: bold;color: #607D8B;">Имя:</span>
                    <?=CHtml::activeTextField($model,'name',array('id'=>'name-query','class'=>'info-field','placeholder'=>'Введите имя')) ?>
                </p>

                <p class="input-wrap">
                    <span style="font-weight: bold;color: #607D8B;">Телефон:</span>
                    <?=CHtml::activeDropDownList($model,'phone_prefix',
                        array('+375 29'=>'+375 29','+375 44'=>'+375 44','+375 33'=>'+375 33','+375 25'=>'+375 25'),
                        array('class'=>'info-field','id'=>'code-query'));?>
                    <?=CHtml::activeTextField($model,'phone',array('class'=>'info-field','id'=>'number-query','placeholder'=>'Введите телефон')) ?>
                </p>

                <p class="input-wrap">
                    <span style="font-weight: bold;color:#607D8B;">Адрес:</span>

                    <select id='city-query' class='info-field' name='OrderForm[city]'>
                        <option selected="selected">Минск</option>
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

                    <?=CHtml::activeTextField($model,'address',array('id'=>'address-query','class'=>'info-field','placeholder'=>'Введите адрес'))?>
                </p>

                <p class="input-wrap">
                    <span class="big-div-shop" style="width: 130px;font-weight: bold;color: #607D8B;">Время доставки:</span>
                    <?=CHtml::activeTextField($model,'time',array('class'=>'info-field','id'=>'time-query','placeholder'=>'Введите время доставки')) ?>
                </p>
                <p style="font-style: italic;">Время доставки (промежуток не менее 2 часов)</p>
            </div>

            <div class="form-right">
                <p class="input-wrap">
                    <span style="font-weight: bold;color: #607D8B;">Комментарии:</span>
                    <?=CHtml::activeTextArea($model,'comment',array('id'=>'comment-query','class'=>'info-field')) ?>
                </p>
            </div>

            <div class="form-bottom">
                <div class="text">
                    Напоминаем что ДЛЯ БЕСПЛАТНОЙ ДОСТАВКИ вашего заказа его стоимость должна быть не менее <span style="color:red;">40 рублей!</span> <br>
                    Если сумма вашего заказа меньше, доставка платная 3 руб.!
                </div>

                <a id='info-button'  value='Оформить заказ' href="#" onclick="return submitOrder();">Оформить заказ</a>
            </div>
        <?=CHtml::endForm(); ?>
    </div>
    <?endif?>
    <?endif?>
</div>

<script>
    var minskMinPrice =  <?=Yii::app()->params['minskMinPrice']?>;
    var regionsMinPrice =  <?=Yii::app()->params['regionsMinPrice']?>;
    function submitOrder() {
        var currentTotalPrice;
        currentTotalPrice = $('#total_price_num').text();
        currentTotalPrice = currentTotalPrice.replace(/ /g, '');
        currentTotalPrice = parseInt(currentTotalPrice);
        var citySelected = $('#city-query').find('option:selected').val();
        var minPrice;
        if (citySelected == 'Минск') {
            minPrice = minskMinPrice;
        } else {
            minPrice = regionsMinPrice;
        }


        if (currentTotalPrice < minPrice) {

            alert('Минимальная сумма заказа: '+minPrice+' руб');
            return false;
        }




        $('#info-form').submit();
        return false;
    }
</script>