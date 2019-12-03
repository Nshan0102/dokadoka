function edit(ev)
{
    let elem = ev.target;
    elem.style = "display: none";
    elem.nextElementSibling.removeAttribute('style');
    let row = elem.parentElement.parentElement;
    let inputs = row.getElementsByTagName("input");
    let spans = row.getElementsByTagName("span");
    for (let i=0; i<inputs.length;i++){
        inputs[i].type = 'text';
        spans[i].style = "display: none";
    }
}

function save(ev,id)
{
    let elem = ev.target;
    elem.style = "display: none";
    elem.previousElementSibling.removeAttribute('style');
    let row = elem.parentElement.parentElement;
    let inputs = row.getElementsByTagName("input");
    let spans = row.getElementsByTagName("span");

    let name = $('#name'+id).val();
    let phone = $('#phone'+id).val();
    let promoCode = $('#promoCode'+id).val();
    let oneTime = $('#oneTime'+id).val();
    let used = $('#used'+id).val();
    $.ajax({
        type: "GET",
        url: "/admin/promoCodes/edit",
        data: {
            id: id,
            name: name,
            phone: phone,
            promo_code: promoCode,
            one_time: oneTime,
            used: used,
        },
        success: function (response) {
            if (response === "success"){
                for (let i=0; i<inputs.length;i++){
                    inputs[i].type = 'hidden';
                    let val = inputs[i].value;
                    if(val === '0'){
                        spans[i].innerHTML = "&#10060;";
                    }else if(val === '1'){
                        spans[i].innerHTML = "&#10004;";
                    }else{
                        spans[i].innerHTML = val;
                    }
                    spans[i].removeAttribute('style');
                }
            }else{
                for (let i=0; i<inputs.length;i++){
                    inputs[i].type = 'hidden';
                    spans[i].removeAttribute('style');
                }
                alert("Что то пошло не так!");
            }

        }
    });
}

function deletePromo(elem,id)
{
    let row = elem.parentElement.parentElement;
    Swal.fire({
        title: 'Вы уверены?',
        text: "Вы не сможете вернуть это!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Нет',
        confirmButtonText: 'Да, удалить!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "GET",
                url: "/admin/promoCodes/deletePromo",
                data: {
                    id: id,
                },
                success: function (response) {
                    if (response === "success"){
                        row.parentElement.removeChild(row);
                        Swal.fire(
                            'Удалено!',
                            '',
                            'success'
                        )
                    }else{
                        alert("Что то пошло не так!");
                    }
                }
            });
        }
    })
}

function filter(elem)
{
    let val = elem.value;
    if (val === 'endLess'){
        $('.endLess').show();
        $('.oneTime').hide();
        $('.noPromo').hide();
    }else if(val === 'oneTime'){
        $('.oneTime').show();
        $('.endLess').hide();
        $('.noPromo').hide();
    }else if(val === 'noPromo'){
        $('.noPromo').show();
        $('.oneTime').hide();
        $('.endLess').hide();
    }else{
        $('.oneTime').show();
        $('.endLess').show();
        $('.noPromo').show();
    }
}

function closeModal() {
    $(modal).css('display','none');
}

function seeOrder(el,id)
{
    let jsonData = document.getElementById(id).innerText;
    let data = JSON.parse(jsonData)["\u0000CActiveRecord\u0000_attributes"];
    let products = JSON.parse(data.data);
    let html = '';
    let subTotal = 0;
    let comment = data.comment;
    let time = data.time;
    console.log(data.time);
    $('#orderComment').html(comment);
    $('#orderTime').html(time);
    for (let i = 0; i < products.length; i++){
        let total = parseFloat(products[i]["price"]) * parseFloat(products[i]["qty"]);
        subTotal += total;
        html += "<tr><td>"+products[i]["name"]+"</td><td>"+products[i]["price"]+"</td><td>"+products[i]["qty"]+"</td><td>"+total+"</td></tr>";
    }
    html += "<tr style='background-color: #deffa6' class='promoCodeRowCell'><td></td><td></td><td></td><td><b>Всего </b>"+subTotal+" руб.</td></tr>";
    $("#modalBody").html(html);
    $(modal).css('display','block');
}

function deleteOrder(el,id)
{
    Swal.fire({
        title: 'Вы уверены?',
        text: "Вы не сможете вернуть это!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Нет',
        confirmButtonText: 'Да, удалить!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "GET",
                url: "/admin/globalOrders/delete",
                data: {
                    id: id,
                },
                success: function (response) {
                    if (response === "success"){
                        let row = el.parentElement.parentElement;
                        row.parentElement.removeChild(row);
                        Swal.fire('Удалено!');
                    }else{
                        Swal.fire({
                            title: 'Упс!',
                            text: "Что то пошло не так!",
                            icon: 'warning',
                        });
                    }
                }
            });
        }
    });
}

function orderSetPaid(el,id)
{
    $.ajax({
        type: "GET",
        url: "/admin/globalOrders/orderSetPaid",
        data: {
            id: id,
        },
        success: function (response) {
            if (response === "success"){
                let cell = el.parentElement;
                cell.removeChild(el);
                cell.innerHTML = "<span style='font-size: 20px;font-weight:900;color:#2ff32f'>&#10004;</span>"
                Swal.fire('Статус оплаты изменен!');
            }else{
                Swal.fire({
                    title: 'Упс!',
                    text: "Что то пошло не так!",
                    icon: 'warning',
                });
            }
        }
    });
}

function orderSetShipped(el,id)
{
    $.ajax({
        type: "GET",
        url: "/admin/globalOrders/orderSetShipped",
        data: {
            id: id,
        },
        success: function (response) {
            if (response === "success"){
                let cell = el.parentElement;
                cell.removeChild(el);
                cell.innerHTML = "<span style='font-size: 20px;font-weight:900;color:#2ff32f'>&#10004;</span>"
                Swal.fire('Статус доставки изменен!');
            }else{
                Swal.fire({
                    title: 'Упс!',
                    text: "Что то пошло не так!",
                    icon: 'warning',
                });
            }
        }
    });
}