_window_width = window.innerWidth;

// REMOVE DUPLICATEs ON MOBILE MODE
$(function(){
	if (_window_width < 993) {
		$('.form-search-desktop').remove();
		$('.content-aside').eq(0).remove();
	}
})

// SET ACTIVE MENU
$(function(){
	var found = false;
	$('.menu-item').each(function(i,item) {
		if ($(item).find('a').attr('href') == window.location.pathname) {
			$(item).addClass('active');
			found = true;
		}
	})
	if (!found && window.location.pathname != '/') {
		$('#menu-item-catalog').addClass('active');
	}
})

function initSwipers() {
	// SWIPER FOR HOME SLIDER
	const swiperSlider = new Swiper('.home_slider-in', {
		speed: 400,
		slidesPerView: 1,
		loop: false,
		pagination: {
			el: '.swiper-pagination',
		},
		navigation: {
			nextEl: '.home_slider-in .swiper-button_next',
			prevEl: '.home_slider-in .swiper-button_prev',
		},
		on: {
			beforeInit: function() {
				var homeSliderIn = $('.home_slider-in');
				if (homeSliderIn.find('.swiper-slide').length > 1) {
					homeSliderIn.find('.swiper-button_next').removeClass('d-none');
					homeSliderIn.find('.swiper-button_prev').removeClass('d-none');
					homeSliderIn.find('.swiper-pagination').removeClass('d-none');
				}
			}
		}
	});

	// SWIPER FOR PRODUCTS CAROUSEL
	if ($(window).width() > 576) {
		var swiperProducts = new Swiper('.products_carousel-swiper', {
			slidesPerView: 4,
			navigation: {
				nextEl: '.products_carousel-swiper .swiper-button_next',
				prevEl: '.products_carousel-swiper .swiper-button_prev',
			},
			breakpoints: {
				0: {
					slidesPerView: 1,
				},
				650: {
					slidesPerView: 2,
				},
				940: {
					slidesPerView: 3,
				},
				1245: {
					slidesPerView: 4,
				},
			}
		});
	}

	if ($('body .pm-image-small').length) {

		// SWIPER FOR PRODUCT
		var galleryThumbs = new Swiper('body .pm-image-small', {
			slidesPerView: 4,
			spaceBetween: 10,
			watchSlidesVisibility: true,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
		});
		var galleryTop = new Swiper('body .pm-image-top', {
			thumbs: {
				swiper: galleryThumbs
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
		});
	}
}

// SWIPER
$(function(){
	initSwipers();
	$('.theme-radio-option').eq(0).attr('checked','');
})

// FAQ ACCORDION
$(function(){
	$('body').on('click', '.hfli-main-question', function(){
		var thisAnswer = $(this).parent().find('.hfli-main-answer');
		$('.hfli-main-answer').not(thisAnswer).slideUp();
		thisAnswer.slideDown();

		var icon = $(this).parent().parent().find('.sprite');
		$('.home_faq-list').find('.sprite-arrow-down').not(icon).addClass('rotated');
		icon.removeClass('rotated');
	})
})

// CHECKBOX FLAG
$(function(){
	$('body').on('click','.theme-checkbox',function(){
		$(this).find('input:checked').next().toggleClass('checked');
	})
})

// SORTING BUTTONS
$(function(){
	$('.ct-sorting-buttons').on('click','.cts-buttons-btn',function(){
		$('.ct-sorting-buttons').find('.cts-buttons-btn').removeClass('active');
		$(this).addClass('active');
	})
})

// COUNTER
$(function(){

	$(document).on('click','.product-counter-less',function(){
		var key = $(this).data('key');

		var parent = $(this).parent();
		var value = parent.find('.product-counter-value');
		var current = parseInt(value.text());
		if (current <= 1) return;
		value.text(current - 1);

		quantityCart( parseInt(value.text()), parseInt(key) );

		parent.find('input').val(current - 1);
		if ($('.card_cart-counter').length) {
			var parent = $(this).parent().parent().parent();

			var replace_price = parent.find('.cart-item-price').text().replace(' ', '').replace('₸', '');
			var price = parseInt(replace_price);

			parent.find('.cart-item-total').text( ((price * (current - 1))).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ').replace('.00', '') + ' ₸' );
			// $('.cart-count').text( parseInt($('.cart-count').text()) - 1 );
			cartSum();
		}
	})

	$(document).on('click', '.product-counter-more', function(){
		var key = $(this).data('key');

		var parent = $(this).parent();
		var value = parent.find('.product-counter-value');
		var current = parseInt(value.text());
		value.text(current + 1);

		quantityCart( parseInt(value.text()), parseInt(key) );

		parent.find('input').val(current + 1);
		if ($('.card_cart-counter').length) {
			var parent = $(this).parent().parent().parent();

			var replace_price = parent.find('.cart-item-price').text().replace(' ', '').replace('₸', '');
			var price = parseInt(replace_price);

			parent.find('.cart-item-total').text( ((price * (current + 1))).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ').replace('.00', '') + ' ₸' );
			// $('.cart-count').text( parseInt($('.cart-count').text()) + 1 );
			cartSum();
		}
	})
})
//quantity cart


	function quantityCart(quantity, key) {
		if (quantity >= 1) {

			console.log(key);
			console.log(quantity);

			$.ajax({
				url: 'index.php?route=checkout/cart/edit',
				type: 'post',
				data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
				dataType: 'json',
				beforeSend: function() { 
					$(this).prop('disabled', true).attr('disabled', true).hide();
				},
				complete: function() {
					$(this).prop('disabled', false).attr('disabled', false).show();
				},
				success: function(json) {
					console.log(json)
					$('.sprite.sprite-cart-2').load('index.php?route=common/cart/info .cart-count');
					// console.log(json.response)
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr);
					console.log(ajaxOptions);
					console.log(thrownError);
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			}); 
		}
	}

// FEATURES TABS
$(function(){
	$(document).on('click', '.pf-tabs-item', function(){
		$('.pf-tabs-item').removeClass('active');
		$(this).addClass('active');
		$('.pf-main-block').addClass('d-none');
		$('#' + $(this).attr('data-for')).removeClass('d-none');
	})
})

// CART ADDITIONALS CHECKBOX
$(function(){
	$('body').on('click','.cmal-item-name .checkbox',function(){
		if ($(this).find('input').prop('checked')) {
			$(this).parent().parent().addClass('active')
		} else {
			$(this).parent().parent().removeClass('active')
		}
		
	})
})

// MY DROPDOWN
$(function(){ 
	$('.my_dropdown-main').on('click', function(){
		$('.my_dropdown-list').not( $(this).parent().find('.my_dropdown-list') ).hide();
		$('.my_dropdown-main').not( $(this) ).find('svg').removeClass('rotated');
		$(this).find('svg').toggleClass('rotated');
		$(this).parent().find('.my_dropdown-list').toggle();
		$(this).parent().toggleClass('active');
	})
	$(document).on('click', '.my_dropdown-item', function(){
		var text = $(this).text(),
			value = $(this).attr('data-value');
		$(this).parent().parent().find('.my_dropdown-input').val(value);
		$(this).parent().parent().find('.my_dropdown-selected').text(text);
		$(this).parent().hide();
		$(this).parent().parent().find('svg').removeClass('rotated');
		$(this).parent().parent().removeClass('active');

		var main = $(this).parent().parent().find('.my_dropdown-main');
		main.hasClass('my_dropdown-main_pc') ? main.removeClass('my_dropdown-main_pc') : null;
	});

	$('.my_dropdown-container').hover(function (){
		if (!$('.my_dropdown-container, .my_dropdown-list').is(":focus")){
				$('.my_dropdown-container').removeClass('active');
				$('.my_dropdown-container ul.my_dropdown-list').hide();
		}
    }, function (){
    	if (!$('.my_dropdown-container, .my_dropdown-list').is(":focus")){
				$('.my_dropdown-container').removeClass('active');
				$('.my_dropdown-container ul.my_dropdown-list').hide();
		}
    });
})

// SERVICE SLIDE DROPDOWN PLACEHOLDER
$(function(){
	$('.my_dropdown-main').on()
})

// CONFIRM CITY
$(function(){
	$('body').on('click','#confirm-city-yes',function(){
		$('.confirm_city').addClass('d-none');
	})
	$('body').on('click','#confirm-city-no',function(){
		$('.confirm_city').addClass('d-none');
		$('.your_city').removeClass('d-none');
	})
})

// CHOOSE CITY
$(function(){
	$('body').on('click','.ht-loc-link',function(e){
		e.preventDefault();
		$('.your_city').removeClass('d-none');
	})
	$('body').on('click','.your_city-btn',function(){
		$('.htt-link-value').text($(this).text());
		$('.your_city').addClass('d-none');
	})
})

// MAP FORM
$(function(){
	$('body').on('click','.mif-success-btn',function(){
		$('.mi-form-success').addClass('d-none');
	})
})

// LINK PREVENT DEFAULT
$(function(){
	$('body').on('click','a[href="#"]',function(e){
		e.preventDefault();
	})
})

function cartSum() {
	var _cart_sum = 0;
	$('.cart-item-total').each(function(index,item){
		// var price = $(item).text().replace(' ', '').replace('₸', '');
		// _cart_sum += parseInt(price);
		_cart_sum += parseInt($(item).text().replace('₸', '').replace(' ', ''));
	})

	$('.ci-main-sum').text(_cart_sum.toLocaleString() + ' ₸');
}

// CART SUM
$(function(){
	cartSum();
})

// MOBILE MENU
$(function(){
	if (_window_width < 993) {
		$('body').on('click','.ht-menu-burger',function(){
			$('body').addClass('overflow_hidden');
			$('.header-nav').show();
		})
		$('body').on('click','.hn-top-close',function(){
			$('.header-nav').hide();
			$('body').removeClass('overflow_hidden');
		})
		$(document).on('click','.menu-item-link',function(){
			var dropdown = $(this).parent().find('.menu-item-dropdown');
			if (dropdown.length) {
				dropdown.slideToggle();
			}
		})

		// prevent catalog opening
		$(document).on('click','#menu-item-catalog .menu-item-link',function(e){
			e.preventDefault();
		})
	}
})

// JQUERY MASK
$(function(){
	$('input[type=tel]').mask('+7 (000) 000-00-00');

	$('.pricefilter').mask('# ##0', {reverse: true});
})

// CHOOSE PRODUCT MODEL
$(function(){
	$('body').on('click','.product-model',function(){
		$('.pm-desc-name-model').text($(this).attr('data-model'));
	})
})

// FILTER SHOW AND HIDE ON MOBILE
$(function(){
	$(document).on('click','.catalog_filter',function(){
		if (_window_width < 993) {
			$('.catalog_filter-details_outer').css('display','flex');
		}
	})
	$(document).on('click','.cf-details-close',function(e){
		e.stopPropagation();
		if (_window_width < 993) {
			$('.catalog_filter-details_outer').css('display','none');
		}
	})
})

// SORTING DROPDOWN ON MOBILE
$(function(){
	$(document).on('click','.ct-sorting-title',function(){
		if (_window_width < 993) {
			$('.ct-sorting-buttons').toggle();
		}
	})
})

// DROPDOWN CATALOG HOVER
$(function(){
	$('.midi-link-dropdown').hover(function(){
		$(this).parent().find('.mid-item-link').addClass('hover');
	}, function(){
		$(this).parent().find('.mid-item-link').removeClass('hover');
	})
})

// CONTACTS PAGE CITY TABS
$(function(){
	$(document).on('click','*[data-for=data-almaty]', function(){
		$('.contacts-data').css('display','none');
		$('.contacts-tabs-btn').removeClass('active');
		$(this).addClass('active');
		$('#data-almaty').css('display','flex');
	})
	$(document).on('click','*[data-for=data-astana]', function(){
		$('.contacts-data').css('display','none');
		$('.contacts-tabs-btn').removeClass('active');
		$(this).addClass('active');
		$('#data-astana').css('display','flex');
	})
})

// remove error messages if the required fields are being filled
$(document).ready(function(){
	$('body').on('keydown', 'input', function(){
		$(this).removeClass('form-error');
	});
	$('body').on('keydown', 'textarea', function(){
		$(this).removeClass('form-error');
	});
})

function hideRecaptchaError() {
	$('.recaptcha-error').addClass('d-none');
}

// MODAL CALL
$(function(){
	$('body').on('click','.order-a-call',function(){
		$('.overlay_order_call').fadeIn();
	})
	$('body').on('click','.order_call-close',function(){
		$('.overlay_order_call').fadeOut();
	})
	$('body').on('click','.oc-success-btn',function(){
		$('.overlay').fadeOut();
	})
})

// SEND FORM CALL
$(function(){
	var form = $('.overlay_order_call').find('form').eq(0);
	form.submit(function(e) {
	    e.preventDefault();
	});
	function success(result) {
		$('.loading-css').addClass('d-none');
		dataLayer.push({'event': 'formsend'});
		$('.overlay_order_call').find('.order_call').empty().append(result);
	}
	function error(result) {
		console.log('error', result);
	}
	form.on('click', 'button', function() {

		var inputName = form.find('input[name=name]').eq(0),
		    inputTel = form.find('input[name=tel]').eq(0);

		var isValid = formValidateCall(inputName, inputTel);

		if (isValid) {
			$('.loading-css').removeClass('d-none');

			var inputs = form.find('input');
			var data = {};
			inputs.each(function(){
				var name = $(this).attr('name');
				var val = $(this).val();
				data[name] = val;
			});

			$.ajax({
				url: '/index.php?route=mail/callback',
				type: 'POST',
				data: data,
				success: success,
				error: error
			});
		}
	})
})

// SEND FORM CALL VALIDATION
function formValidateCall(name, tel) {
	name.removeClass('form-error');
	tel.removeClass('form-error');

	var result = true;

	if (!name.val()) {
       name.addClass('form-error error-shake');
       result = false;
    };

    if (tel.val().length != 18) {
       tel.addClass('form-error error-shake');
       result = false;
    };

    setTimeout(function(){
		name.removeClass('error-shake');
    	tel.removeClass('error-shake');
    }, 1000);

    return result;
}

// MODAL QUESTION
$(function(){
	$('body').on('click','.hf-buttons-btn',function(){
		$('.overlay_order_question').fadeIn();
	})
	$('body').on('click','.order_question-close',function(){
		$('.overlay_order_question').fadeOut();
	})
})

// SEND FORM QUESTION
$(function(){
	var form = $('.overlay_order_question').find('form').eq(0);
	form.submit(function(e) {
	    e.preventDefault();
	});
	function success(result) {
		$('.loading-css').addClass('d-none');
		dataLayer.push({'event': 'formsend'});
		$('.overlay_order_question').find('.order_call').empty().append(result);
	}
	function error(result) {
		console.log('error', result);
	}
	form.on('click', 'button', function() {

		var inputName = form.find('input[name=question_name]').eq(0),
		    inputTel = form.find('input[name=question_tel]').eq(0),
		    textMessage = form.find('textarea[name=question_text]').eq(0);

		var isValid = formValidateQuestion(inputName, inputTel, textMessage);

		var captcha = grecaptcha.getResponse(recaptchaQuestionId);

		if (!captcha.length) {
		  form.find('.recaptcha-error').text('Вы не прошли проверку "Я не робот"');
		  form.find('.recaptcha-error').removeClass('d-none');
		} else {
		  form.find('.recaptcha-error').addClass('d-none');
		  form.find('.recaptcha-error').text('');
		}

		if (isValid && captcha.length) {
			$('.loading-css').removeClass('d-none');

			var inputs = form.find('input');
			var texts = form.find('textarea');
			var data = {};
			inputs.each(function(){
				var name = $(this).attr('name');
				var val = $(this).val();
				data[name] = val;
			});
			texts.each(function(){
				var name = $(this).attr('name');
				var val = $(this).val();
				data[name] = val;
			});
			data['g-recaptcha-response'] = captcha;

			$.ajax({
				url: '/index.php?route=mail/callback/question',
				type: 'POST',
				data: data,
				success: success,
				error: error
			});
		}
	})
})

// SEND FORM QUESTION VALIDATION
function formValidateQuestion(name, tel, text) {
	name.removeClass('form-error');
	tel.removeClass('form-error');
	text.removeClass('form-error');

	var result = true;

	if (!name.val()) {
       name.addClass('form-error error-shake');
       result = false;
    };

    if (tel.val().length != 18) {
       tel.addClass('form-error error-shake');
       result = false;
    };

    if (!text.val()) {
       text.addClass('form-error error-shake');
       result = false;
    };

    setTimeout(function(){
		name.removeClass('error-shake');
    	tel.removeClass('error-shake');
    	text.removeClass('error-shake');
    }, 1000);

    return result;
}

// SEND FORM MAIN
$(function(){
	var form = $('.map-info-form').eq(0);
	form.submit(function(e) {
	    e.preventDefault();
	});
	function success(result) {
		$('.loading-css').addClass('d-none');
		dataLayer.push({'event': 'formsend'});
		form.append(result);
	}
	function error(result) {
		console.log('error', result);
	}
	form.on('click', 'button', function() {

		var inputName = form.find('input[name=your_name]').eq(0),
		    inputTel = form.find('input[name=your_phone]').eq(0),
		    inputMessage = form.find('input[name=your_message]').eq(0);

		var isValid = formValidateMain(inputName, inputTel);

		var captcha = grecaptcha.getResponse(recaptchaMainId);

		if (!captcha.length) {
		  form.find('.recaptcha-error').text('Вы не прошли проверку "Я не робот"');
		  form.find('.recaptcha-error').removeClass('d-none');
		} else {
		  form.find('.recaptcha-error').addClass('d-none');
		  form.find('.recaptcha-error').text('');
		}

		if (isValid && captcha.length) {
			$('.loading-css').removeClass('d-none');

			var inputs = form.find('input');
			var data = {};
			inputs.each(function(){
				var name = $(this).attr('name');
				var val = $(this).val();
				data[name] = val;
			});
			data['g-recaptcha-response'] = captcha;

			$.ajax({
				url: '/index.php?route=mail/callback/main',
				type: 'POST',
				data: data,
				success: success,
				error: error
			});
		}
	})
})

// SEND FORM MAIN VALIDATION
function formValidateMain(name, tel) {
	name.removeClass('form-error');
	tel.removeClass('form-error');

	var result = true;

	if (!name.val()) {
       name.addClass('form-error error-shake');
       result = false;
    };

    if (tel.val().length != 18) {
       tel.addClass('form-error error-shake');
       result = false;
    };

    setTimeout(function(){
		name.removeClass('error-shake');
    	tel.removeClass('error-shake');
    }, 1000);

    return result;
}

// MODAL SERVICE
$(function(){
	$('body').on('click','.sst-form-button',function(e){
		e.preventDefault();
		var selectedText = $('.my_dropdown-selected').text();
		if (selectedText) {
			$('input[name=service_type]').val($('.my_dropdown-selected').text());
		} else {
			$('.my_dropdown-selected').parent().addClass('selected-error');
			return;
		}
		$('.overlay_order_service').fadeIn();
	})
	$('body').on('click','.order_service-close',function(){
		$('.overlay_order_service').fadeOut();
	})
	$(document).on('click','.ss-text-form .my_dropdown-item',function(){
		$('.my_dropdown-selected').parent().removeClass('selected-error');
	})
})

// SEND FORM SERVICE
$(function(){
	var form = $('.overlay_order_service').find('form').eq(0);
	form.submit(function(e) {
	    e.preventDefault();
	});
	function success(result) {
		$('.loading-css').addClass('d-none');
		dataLayer.push({'event': 'formsend'});
		$('.overlay_order_service').find('.order_call').empty().append(result);
	}
	function error(result) {
		console.log('error', result);
	}
	form.on('click', 'button', function() {

		var inputName = form.find('input[name=service_name]').eq(0),
		    inputTel = form.find('input[name=service_tel]').eq(0),
		    inputService = form.find('input[name=service_type]').eq(0);

		var isValid = formValidateService(inputName, inputTel);

		var captcha = grecaptcha.getResponse(recaptchaServiceId);

		if (!captcha.length) {
		  form.find('.recaptcha-error').text('Вы не прошли проверку "Я не робот"');
		  form.find('.recaptcha-error').removeClass('d-none');
		} else {
		  form.find('.recaptcha-error').addClass('d-none');
		  form.find('.recaptcha-error').text('');
		}

		if (isValid && captcha.length) {
			$('.loading-css').removeClass('d-none');

			var inputs = form.find('input');
			var data = {};
			inputs.each(function(){
				var name = $(this).attr('name');
				var val = $(this).val();
				data[name] = val;
			});
			data['g-recaptcha-response'] = captcha;

			$.ajax({
				url: '/index.php?route=mail/callback/service',
				type: 'POST',
				data: data,
				success: success,
				error: error
			});
		}
	})
})

// SEND FORM SERVICE VALIDATION
function formValidateService(name, tel) {
	name.removeClass('form-error');
	tel.removeClass('form-error');

	var result = true;

	if (!name.val()) {
       name.addClass('form-error error-shake');
       result = false;
    };

    if (tel.val().length != 18) {
       tel.addClass('form-error error-shake');
       result = false;
    };

    setTimeout(function(){
		name.removeClass('error-shake');
    	tel.removeClass('error-shake');
    }, 1000);

    return result;
}