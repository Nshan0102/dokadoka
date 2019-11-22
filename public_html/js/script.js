document.addEventListener("DOMContentLoaded", () => {
    let cookies = document.cookie;
    if (localStorage.getItem('promo') &&  cookies.indexOf('promo_prices') >= 0){
        localStorage.removeItem('promo');
        window.location.reload();
    }
});

function promoCodeFormSubmit(e) {
    e.preventDefault();
    localStorage.setItem('promo','activated');
    $('#promocode-form').submit();
}

function updateBasketSticker(json) {
    var btxt;
    if (json['total'] > 0) {
        $('div.menus').addClass('basket-active');
        btxtpop = 'В корзине товаров: ' + json['total'] + '. На сумму: ' + json['sum'] + ' Br';
        btxt = 'Товаров: ' + json['total'];
    }
    else {
        $('div.menus').removeClass('basket-active');
        btxt = '';
    }
    $('div.menus a.basket-items').text(btxt);
    $('div.pop div.inner a.basket-items').text(btxtpop);
}
function updateBasketItemQty(elemClicked) {
    var itemBlock = elemClicked.closest('.itemContainer');
    var iqty = +itemBlock.find('.counter-value').val();
    var itemid = itemBlock.attr('data-id');
    var priceElem = elemClicked.closest('.itemContainer').find('.price');
    var sumElem = elemClicked.closest('.itemContainer').find('.summ');
	sumElem.html((iqty*parseInt(priceElem.attr('data'))).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")+' руб.');
    var totalElem = $('#total_price_num');
    totalElem.text('...');
    $.getJSON('/ajaxbasket?upditemid=' + itemid + '&qty=' + iqty, function (json) {
        totalElem.text(json['sum']);
        updateBasketSticker(json);
    });
}
$(document).ready(function () {
    $('#basketPopup').find('.pop').show();

$(document).mouseup(function (e) {
    var container = $(".pop");
    if (container.has(e.target).length === 0){
        $('#basketPopup').hide();
	$('#basketPopup2').hide();
    }
});
/*$('.make-order').click(function(){
$.getJSON('/ajaxbasket?additemid=' + $(this).attr('data-id') + '&qty=' + $('#basketPopup').find('.counter-value').val(), function (json) {
            updateBasketSticker(json);
        });
});*/
	$('.add-to-basket-in-youtube').click(function(){
		$('#basketPopup2').hide();
		thiss= $(this);
		$('a.add-to-basket').each(function(){
			if($(this).attr('data-id')==thiss.attr('data-id'))
				$(this).trigger('click');
		});
	});
    $('a.add-to-basket').on('click', function (e) {
        e.preventDefault();
        var itemid = $(this).attr('data-id');
        var itemBlock = $(this).closest('.itemContainer');
        var imgSrc = itemBlock.find('.img-wrap img').attr('src');
        var aBuy = itemBlock.find('a.add-to-basket').attr('data-id');
        var bPopupEl = $('#basketPopup');
        var idata;
        var icounter2 = '<div class="counter" >'+
            +'<span class="minus"></span>'+
            +'<input type="text" id="basket_counter" value="1" class="counter-value" disabled="disabled">'+
            +'<span class="plus"></span>'+
            +'</div>';
        var itemName = itemBlock.find('a.title').html();
        var itemMoney = itemBlock.find('span.newPrice').html();
        if (imgSrc.length)
            idata = '<img src="' + imgSrc + '" />'; else
            idata = '';
        var iqty = itemBlock.find('.counter input.counter-value').val();
        var itext = 'Товар "' + itemName + '" добавлен к заказу в количестве 1 шт' + ' по цене за шт: ' + itemMoney;
        bPopupEl.find('div.img-wrap').html(idata);
        bPopupEl.find('div.text').html(itext);
	bPopupEl.find('.counter-value').val(1);
	//bPopupEl.find('.make-order').attr('data-id',itemid);
	bPopupEl.find('.add-to-basket').off('click').on('click',function(){
		$.getJSON('/ajaxbasket?additemid=' + itemid + '&qty=' + bPopupEl.find('.counter-value').val(), function (json) {
            		updateBasketSticker(json);
        	});
		
		return false;
	});
        bPopupEl.show();

	$.getJSON('/ajaxbasket?additemid=' + itemid + '&qty=' + iqty, function (json) {
            updateBasketSticker(json);
        });
	
    });
    $(document).on('click','#add-to-basket-youtube',function(){
        
        var itemid = $(this).attr('data-id');
        $.getJSON('/ajaxbasket?additemid=' + itemid + '&qty=' + 1, function (json) {
            updateBasketSticker(json);
		alert('Товар добавлен');
        });
    });

    $('.wrapper').css({paddingTop: $('.intro').height()});
    $(window).on('resize', function () {
        $('.wrapper').css({paddingTop: $('.intro').height()})
    });
    $('.submenu-select').styler({
        onSelectOpened: function () {
            $('.jq-selectbox li').off('click')
            $('.jq-selectbox li').on('click', function () {
                var index = $(this).index();
                window.location.href = $('.submenu li').eq(index).find('a').attr('href');
            })
        }, selectSearch: false, selectVisibleOptions: 100
    });
    $('.menu-button').on('click', function () {
        $('.main-menu').slideToggle();
    });
    $('.counter').on('mousedown', function () {
        return false;
    });
    $('.counter').on('selectstart', function () {
        return false;
    });
    $('.counter .plus').on('click', function () {
        var number = +$(this).siblings('.counter-value').val();
        $(this).siblings('.counter-value').val(number + 1);
    });
    $('.basket-wrap .counter .plus').on('click', function () {
        updateBasketItemQty($(this));
    });
    $('.counter .minus').on('click', function () {
        var number = +$(this).siblings('.counter-value').val();
        if (number == 1)return;
        $(this).siblings('.counter-value').val(number - 1);
    });
    $('.basket-wrap .counter .minus').on('click', function () {
        updateBasketItemQty($(this));
    });
    if ($('.fancybox')[0]) {
        $(".fancybox").attr('rel', 'gallery').fancybox({
            beforeShow: function () {
                if (this.title) {
                    this.title += '<br />';
                }
            }, afterShow: function () {
            }, helpers: {title: {type: 'inside'}}
        });
    }
    if ($('.watch-video.fancybox')[0]) {
        $(".watch-video.fancybox").click(function () {
            $dataid = $(this).attr('data-id');
            /*$.fancybox({
                'padding': 0,
                'autoScale': false,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'title': this.title +' '+ '<a href="#" id="add-to-basket-youtube" data-id="'+ $dataid + '">положить в корзину</a>',
                'width': 680,
                'height': 495,
                'href': this.href.replace("watch?v=","embed/"),
                'type': 'swf',
                'swf': {'wmode': 'transparent', 'allowfullscreen': 'true'},
                beforeShow: function () {
                    if (this.title) {
                        this.title += '<br />';
                    }
                },
                afterShow: function () {
                },
                helpers: {title: {type: 'inside'},media : {}}
            });*/
		$('#basketPopup2').find('.youtube').html('<iframe width="680" height="495" src="'+this.href.replace("watch?v=","embed/")+'" frameborder="0" allowfullscreen></iframe>');
		$('#basketPopup2').find('.text').html(this.title);
		$('#basketPopup2').show();
		$('#basketPopup2').find('.add-to-basket-in-youtube').attr('data-id',$dataid);
            return false;
        });
    }
    $('.basket-table .delete-button').on('click', function () {
        $(this).closest('li').remove();
        var itemid = $(this).attr('data-id');
        $.getJSON('/ajaxbasket?remove_pos=' + itemid, function (json) {
            updateBasketSticker(json);
        });
    });
    $('.pop .close-window').on('click', function () {
        $('#basketPopup').fadeOut();
        return false;
    });
    if ($(window).width() < 768) {
        $('.category-open').on('click', function () {
            $('.sidebar-fix-menu').slideToggle();
            $("body").css("overflow", "hidden");
        });
        $('.close-category').on('click', function () {
            $('.sidebar-fix-menu').slideToggle();
            $("body").css("overflow", "auto");
        });
        $('.menus>.menu-button').on('click', function () {
            $("body").css("overflow", "hidden");
        });
        $('.main-menu>.menu-button').on('click', function () {
            $("body").css("overflow", "auto");
        });
        var nav = $('.menus'),
            positionX = nav.offset(),
            screenPosition = $(window).scrollTop();
        if (nav.length > 0) {
            $(window).scroll(function () {
                if ($(this).scrollTop() > positionX.top) {
                    nav.addClass("fix-nav");
                } else {
                    nav.removeClass("fix-nav");
                }
            });
            if (screenPosition > positionX.top) {
                nav.addClass("fix-nav");
            }
        }
    };
});