<div style="margin-bottom: 20px;display: flex;flex-direction: row;justify-content: space-between;">
    <select style="height: 20px;" onchange="filter(this)" id="promo_type">
        <option value="all">Все</option>
        <option value="endLess">Бесконечный</option>
        <option value="oneTime">Одноразовый</option>
    </select>
    <div style="background-color: #bcdbf7; padding: 10px 15px 20px 5px">
        <h2 style="margin-bottom: 10px; color: chocolate;">Добавить ПромоКоды</h2>
        <form style="margin-bottom: 10px" method="POST" action="/admin/promoCodes/uploadPromo" enctype="multipart/form-data">
            <div style="margin-bottom: 5px">
                <label for="isOneTime">Одноразовый</label>
                <input type="checkbox" id="isOneTime" name="isOneTime"><br>
            </div>
            <input type="hidden" name="<?=Yii::app()->request->csrfTokenName?>" value="<?=Yii::app()->request->csrfToken ?>">
            <input type="file" id="fileInput" name="file" style="display: none;">
            <button onclick="event.preventDefault();document.getElementById('fileInput').click()">Выберите файл</button>
            <button type="submit">Загрузить</button>
        </form>
        <?php
        if ($uploaded !== ''){
            echo "<span style='padding: 5px;background: bisque;color: forestgreen;font-weight: 700;'>$uploaded</span>";
        }
        ?>
    </div>
</div>
<div class="pagination" style="margin-bottom: 20px">
    <div>
        <?php $this->widget('CLinkPager', array(
            'pages' => $pages,
        )) ?>
    </div>
</div>
<table>
    <thead>
        <tr>
            <td class="tableCell theadText">Имя</td>
            <td class="tableCell theadText">Телефон</td>
            <td class="tableCell theadText">Промо Код</td>
            <td class="tableCell theadText">Одноразовый <br> 0 - да / 1 - нет</td>
            <td class="tableCell theadText">Использован <br> 1 - да / 0 - нет</td>
            <td class="tableCellLast theadText" style="align-items: center">Действия</td>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($models as $data) {
        ?>
                <tr class="<?php echo $data['oneTime'] ? 'oneTime' : 'endLess';?>" id="<?php echo $data['id'] ?>">
                    <td class="tableCell">
                        <span><?php echo $data['name'] ?></span>
                        <input type="hidden" id="name<?php echo $data['id'] ?>" value="<?php echo $data['name'] ?>">
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['phone'] ?></span>
                        <input type="hidden" id="phone<?php echo $data['id'] ?>" value="<?php echo $data['phone'] ?>">
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['promo_code'] ?></span>
                        <input type="hidden" id="promoCode<?php echo $data['id'] ?>" value="<?php echo $data['promo_code']?>">
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['oneTime'] ? "&#10004;" : "&#10060;" ?></span>
                        <input type="hidden" id="oneTime<?php echo $data['id'] ?>" value="<?php echo $data['oneTime']?>">
                    </td>
                    <td class="tableCell">
                        <span><?php echo $data['used'] ? "&#10004;" : "&#10060;" ?></span>
                        <input type="hidden" id="used<?php echo $data['id'] ?>" value="<?php echo $data['used'] ?>">
                    </td>
                    <td class="tableCellLast">
                        <button class="btn-primary" onclick="edit(event)">Edit</button>
                        <button class="btn-primary" style="display: none;" onclick="save(event,<?php echo $data['id'] ?>)">Save</button>
                        <button class="btn-danger" onclick="deletePromo(this,<?php echo $data['id'] ?>)">Delete</button>
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
<script src="../../../../../js/admin.js"></script>