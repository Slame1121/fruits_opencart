var Main = {

	initHeader: function () {
        //fixed
        $(window).bind('scroll', function () {
            if ($(window).scrollTop() > 0) {
                $('.fixed_navbar').addClass('fixed');

            } else {
                $('.fixed_navbar').removeClass('fixed');
            }
        });
	},
	initReviews: function () {
		var owl = $('.reviews_block_container-wrapper .owl-carousel');
		owl.on('changed.owl.carousel', function(e) {
			Main.setOwlPagination(e);
		}).on('initialized.owl.carousel',function(e){
			Main.InitOwlPagination(e);
		});
		owl.owlCarousel({
			margin: 0,
			nav: false,
			dots:false,
			loop: true,
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 1
				},
				1000: {
					items: 1
				}
			}
		});


		//add review button
		$('.reviews_block_container-wrapper__button').on('click', 'button', function(e){
			var contaienr = $(this).parent().parent();
			if(contaienr.find('.reviews_block_container-wrapper__addreview').css('display') ==  'none'){
				contaienr.find('.owl-carousel').hide();
				contaienr.find('.reviews_block_container-wrapper-pagination').hide();
				contaienr.find('.reviews_block_container-wrapper__addreview').fadeIn('slow');
			}else{

                var name_field = $('#name_input');
                var comment_field = $('#comment_textarea');

                var name = $('#name_input').val();
                var comment = $('#comment_textarea').val();

                if(name == ''){
                    $('.name_error').remove();
                    $('<p class="name_error" style="color: red; margin-top: 3px;"> Error name </p>').insertAfter($(name_field));
                }

                if(comment == ''){
                    $('.comment_error').remove();
                    $('<p class="comment_error" style="color: red; margin-top: 3px;"> Error comment </p>').insertAfter($(comment_field));
                }

				if($('.name_error').length > 0 || $('.comment_error').length > 0){
                	return false;
				}else{
                    $.post( "index.php?route=common/reviews_form/addReview", { name: name, comment: comment })
                        .done(function() {
                            var container = $('.reviews_block_container-wrapper');
                            container.find('.reviews_block_container-wrapper__addreview').hide();
                            container.find('.owl-carousel').fadeIn('slow');
                            container.find('.reviews_block_container-wrapper-pagination').fadeIn('slow');
                        });
				}

				//SEND REVIEW FORM (VALIDATION)
			}
		});

        $('.reviews_block_container-wrapper').on('focus', '#name_input', function(e){
        	$('.name_error').remove();
		});

        $('.reviews_block_container-wrapper').on('focus', '#comment_textarea', function(e){
            $('.comment_error').remove();
        });

		//close adding review block
		$('.reviews_block_container-wrapper__addreview__header-close').on('click', 'a', function(e){
			e.stopPropagation();
			e.preventDefault();
			var container = $(this).closest('.reviews_block_container-wrapper');
			container.find('.reviews_block_container-wrapper__addreview').hide();
			container.find('.owl-carousel').fadeIn('slow');
			container.find('.reviews_block_container-wrapper-pagination').fadeIn('slow');
		});
	},

    validateFeedback: function () {
        function validateEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

		$('.contacts_container-content_left button.feedback_button').click(function(){
            var name = $('#contacts-name').val();
            var email = $('#contacts-email').val();
            var message = $('#contacts-message').val();

            if(name == ''){
                $('#contacts-name').addClass('error-block');
            }

            if(!validateEmail(email)){
                $('#contacts-email').addClass('error-block');
            }

            if(message == ''){
                $('#contacts-message').addClass('error-block');
            }

            if($('.error-block').length > 0){
                return false;
            }else{
                $.post( "index.php?route=common/contact_form/addFeedback", { name: name, email: email, message: message })
				.done(function() {
                    $('#contacts-name, #contacts-email, #contacts-message').val('');
					swal('Поздравляем, ' + name, "Ваше сообщение отправлено !", "success");
				});
            }
		});

		$('#contacts-name, #contacts-email, #contacts-message').change(function(){
			$(this).removeClass('error-block');
		});
	},

	initReviews2: function () {
		
		var owl = $('.product_card_container_reviews .owl-carousel');
		owl.on('changed.owl.carousel', function(e) {
			Main.setOwlPagination(e);
		}).on('initialized.owl.carousel',function(e){
			Main.InitOwlPagination(e);
		});
		//var owl = $('#pagereview');
		owl.owlCarousel({
			margin: 20,
			nav: false,
			dots:false,
			loop: true,
			responsive: {
				0: {
					items: 1
				},
				786: {
					items: 1
				},
				1200: {
					items: 1
				}
			}
		});


		//add review button
		$('.product_card_container_reviews__button').on('click', 'button', function(e){
			var contaienr = $(this).parent().parent();
			contaienr.find('.error').text('');
			if(contaienr.find('.product_card_container_reviews__addreview').css('display') ==  'none'){
				contaienr.find('.owl-carousel').hide();
				contaienr.find('.product_card_container_reviews-pagination').hide();
				contaienr.find('.product_card_container_reviews__addreview').fadeIn('slow');
			}else{
				var name = contaienr.find('input[name=name]').val();
				var text = contaienr.find('textarea[name=comment]').val();
				var  rating = contaienr.find('#customer_rating').raty('score');
				var product_id= $(this).attr('data-product-id');
				$.ajax({
					url: 'index.php?route=product/product/write&product_id='+product_id,
					type: 'post',
					data: 'name=' + name+'&text='+text +'&rating=' + rating,
					dataType: 'json',
					beforeSend: function() {
					},
					complete: function() {
					},
					success: function(json) {
						if(typeof json.error != 'undefined'){
							contaienr.find('.error').text(json.error);
						}else{
							contaienr.find('.product_card_container_reviews__addreview__content').empty().append('<h3>'+json.success+'</h3>')
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});
		//close adding review block
		$('.product_card_container_reviews__addreview__header-close').on('click', 'a', function(e){
			e.stopPropagation();
			e.preventDefault();
			var container = $(this).closest('.product_card_container_reviews');
			container.find('.product_card_container_reviews__addreview').hide();
			container.find('.owl-carousel').fadeIn('slow');
			container.find('.product_card_container_reviews-pagination').fadeIn('slow');
		});
		
	},
	initNews: function () {
		var owl = $('.news_container-content__list');
		owl.on('changed.owl.carousel', function(e) {
			Main.setOwlPagination(e);
		}).on('initialized.owl.carousel',function(e){
			Main.InitOwlPagination(e);
		});
		owl.owlCarousel({
			margin: 0,
			nav: false,
			dots:false,
			loop: true,
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 2
				},
				1000: {
					items: 2
				}
			}
		});
	},
	initTabs: function () {
		var all_containers = $('.tabs_container');
		$('body').on('click', '.tabs_container .tabs_list a', function(e){
			e.stopPropagation();
			e.preventDefault();
			$(this).parent().parent().find('a').removeClass('active');
			$(this).addClass('active');

			var href =  $(this).attr('href');
			$(this).closest('.tabs_container').find('.tabs_items >div').hide();
			$(this).closest('.tabs_container').find(href).fadeIn('slow');
		})
	},
	initAddTocartButton: function () {
		$('body').on('click','.button-add-to-cart', function(e){
			e.stopPropagation();
			e.preventDefault();
			var product_id = $(this).data('product-id');
			var options = {};
			var options_objects = $('.product_option_value.active');
			if(options_objects.length > 0){
				$.each(options_objects, function(key, val){
					options[$(val).data('product-option-id')] = $(val).data('product-option-value-id');
				});
			}
			cart.add(product_id, 1, JSON.stringify(options));
		})
	},
	initRaty: function () {
		$('.raty').raty({
			starType: 'i',
			readOnly: function() {
				return !($(this).attr('data-changer') != 'undefined' && $(this).attr('data-changer') == 1);
			},
			size  : 25,
			score:
				function() {
					return $(this).attr('data-score');
				}
		});
	},
	initLikeButton: function () {
		$('.button_like').on('click', function(){
			if($(this).hasClass('liked')){
				wishlist.remove($(this).data('product-id'));
				$(this).removeClass('liked')
			}else{
				wishlist.add($(this).data('product-id'));
				$(this).addClass('liked')
			}
		})
	},
	initLoginModal: function () {

		$('.login_modal-content__tabs').on('click', 'a', function(){
			$(this).closest('div.login_modal').find('.login_modal-header span').text($(this).text());
			$(this).closest('div.login_modal').find('.login_modal-content__submit button span').text($(this).text());
			$(this).closest('div.login_modal').find('.login_modal-content__submit button').attr('form', $(this).data('form-id'));
		});

		$('.login_modal-content__submit').on('click', 'button' ,function(e){
			e.stopPropagation();
			e.preventDefault();
			var form = $('#' + $(this).attr('form'));
			var action = form.attr('action');
			form.find('p.error').remove();
			form.find('input.error').removeClass('error');
			$.ajax({
				url: action,
				type: 'post',
				data: form.serialize(),
				dataType: 'json',
				beforeSend: function() {
				},
				complete: function() {
				},
				success: function(json) {
					if(json.warning){
						form.append('<p class="error">'+json.warning+'</p>');
					}
					if(json.success){
						if(json.success.redirect){
							location.href = json.success.redirect;
						}
					}
				}
				,
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});

		});
	},
	initParalax: function () {
			$("#leaf_paralax").paroller({ factor: '0.8', type: 'foreground', direction: 'vertical' });


	},
	initSearch: function () {
		$('.top_navbar-container__item-menu__search').on('click', function(e){

			$(this).addClass('active');
		}).on('keyup','input',function(){
			var self = $(this);
			$.post( "index.php?route=common/header/searchProducts", { search: $(this).val(),dataType: 'json' })
				.done(function(json) {
					self.parent().find('ul').empty();
					$.each(json, function(key, val){
							self.parent().find('ul').append('<li><a href="'+val.link+'">'+val.name+'</a></li>')
					})
				});
		})
		$('body').on('click', function(e){
			if($(e.target).closest('.top_navbar-container__item-menu__search').length == 0 && $('.top_navbar-container__item-menu__search').hasClass('active')){
				$('.top_navbar-container__item-menu__search').removeClass('active');
			}else{

			}
		});
	},
	init: function(){
		this.initHeader();
		this.initCatalog();
        this.validateFeedback();
		this.initReviews();
		this.initReviews2();
		this.initNews();

		this.initTabs();

		this.initAddTocartButton();

		this.initRaty();

		this.initLikeButton();

		this.initLoginModal();

		this.initParalax();

		this.initSearch();
	},

	InitOwlPagination: function(e){
		if (e.item) {
			var index = e.item.index - 1;
			var count = e.item.count;
			if (index > count) {
				index -= count;
			}
			if (index <= 0) {
				index += count;
			}
		}

		if(e.item.count > 1){
			$(e.target).parent().find('.owl-custom-pagination').append('<div class="navigation"><img src="/catalog/view/theme/fruits/images/arrow-left.png" /><img src="/catalog/view/theme/fruits/images/arrow-right.png" /></div><div class="count"></div>');
			$(e.target).parent().find('.owl-custom-pagination .navigation img:first-child').on('click', function(){
				($(e.target).trigger('prev.owl.carousel'));
			});
			$(e.target).parent().find('.owl-custom-pagination .navigation img:nth-child(2)').on('click', function(){
				($(e.target).trigger('next.owl.carousel'));
			});
			$(e.target).parent().find('.owl-custom-pagination').find('.navigation');
			$(e.target).parent().find('.owl-custom-pagination .count').text(index + '/' + e.item.count)
		}

	},
	setOwlPagination: function(e){

		if (!e.namespace || e.property.name != 'position') return ;

		if (e.item) {
			var index = e.item.index - 1;
			var count = e.item.count;
			if (index > count) {
				index -= count;
			}
			if (index <= 0) {
				index += count;
			}
		}

		$(e.target).parent().find('.owl-custom-pagination .count').text(index + '/' + e.item.count)
	},

	initCatalog: function(){

		//main categories slider
		var owl = $('.catalog_block_container-list');
		$.each(owl.find('>div'),function(key, val){
			$(this).attr( 'data-position', $(val).index() );
			var background_color = $(val).find('button').addClass('button-background-'+$(val).index()).data('color');
			$('body').append('<style>.button-background-'+$(val).index()+':before{background:'+background_color+' !important;}</style>');
		})
		owl = $('.catalog_block_container-list:not([data-carousel=0])');

		owl.on('changed.owl.carousel', function(e) {
			Main.setOwlPagination(e);
		}).on('initialized.owl.carousel',function(e){
			Main.InitOwlPagination(e);
		});
		owl.owlCarousel({
			margin: 0,
			nav: false,
			dots:false,
			loop: true,
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 2
				},
				1000: {
					items: 3
				}
			}
		});


		var opened = false;

		//init subcategories
		$('.catalog_block_container-list__item-wrapper-text').on('click', 'button', function(){
			$(this).closest('.owl-carousel').find('.active').removeClass('active');
			var catalog_container = $(this).closest('.catalog_block_container-list');

			var isCarousel = catalog_container.data('carousel') == 1;
			var container = $(this).closest('.catalog_block_container-list__item');
			if(!isCarousel){
				container.addClass('to_left');
				var index = container.index();
				container.before('<div class="spawn" style="width:400px;height:340px;"></div>');
				container.css({
					top: (340 * (Math.floor(index/3) )  +(Math.floor(index/3))*10) + 'px'
				})
				catalog_container.parent().find('.catalog_block_container-subcategories').css({
					top: (340 * (parseInt(Math.floor(index/3) )) + 60 +(Math.floor(index/3))*10) + 'px'
				});
			}

			var n = container.data('position');
			var category_id = $(this).data('category-id');
			owl.trigger('to.owl.carousel', n);
			$.ajax({
				url: 'index.php?route=common/category_catalog/getChilds',
				type: 'post',
				data: 'category_id=' + category_id ,
				dataType: 'json',
				beforeSend: function() {
				},
				complete: function() {
				},
				success: function(json) {
					var content = $('.catalog_block_container-subcategories__content');
					content.empty();
					$.each(json['childs'], function(key, val){
						content.append(
							' <div class="catalog_block_container-subcategories__content-item">\
									<a href="'+val.href+'">'+val.name+'</a>\
								</div>'
						);
					});
					$('.catalog_block_container-subcategories__header a').attr('href',json.link).html('<span>СМОТРЕТЬ ВСЕ ТОВАРЫ</span> /'+json.name);
					$('.alert-dismissible, .text-danger').remove();

					if (json['redirect']) {
						location = json['redirect'];
					}

					if (json['success']) {
				}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
			container.closest('.catalog_block_container').find('.catalog_block_container-subcategories').addClass('opened');
			setTimeout(function(){
				opened = true;
			}, 300);
			var position = $(this).closest('.catalog_block_container-list__item').data('position');

			$.each($('div[data-position='+position+']'),function(key, val){
				var img =	$(val).find('>img');
				$(val).addClass('active');
				//bordered div
				$(val).find('>div').addClass('without_border');
				//img.attr('src',img.data('image-active'));

			});
		});
		$('.catalog_block_container').on('click','.catalog_block_container-subcategories__close',function(e){
			e.stopPropagation();
			e.preventDefault();
			$('.spawn').remove();
			$(this).parent().removeClass('opened');
			$('.catalog_block_container-list[data-carousel=0]').find('>div').removeClass('to_left').css('top','auto');

			$.each($('.catalog_block_container-list__item'),function(key, val){
			//	var img =	$(val).find('>img');
				$(val).find('>div').removeClass('without_border');
				$(val).removeClass('active');
			//	img.attr('src',img.data('image'));

			});
			setTimeout(function(){
				opened = false;
			}, 300);

		})

		//remove subcategories if click on body

		$('body').on('click', function(e){
			if(opened){
				if($(e.target).closest('.catalog_block_container-subcategories.opened').length == 0){
					$('.spawn').remove();
					$.each($('.catalog_block_container-list__item'),function(key, val){
						//var img =	$(val).find('>img');
						//img.attr('src',img.data('image'));
						$(val).find('>div').removeClass('without_border');
						$(val).removeClass('active');
					});
					$('.catalog_block_container-list[data-carousel=0]').find('>div').removeClass('to_left').css('top','auto');
					if($(e.target).parent().hasClass('catalog_block_container-list__item-wrapper-text')){
						var position = $(e.target).parent().parent().parent().data('position');
						$.each($('div[data-position='+position+']'),function(key, val){
						//	var img =	$(val).find('>img');
							//img.attr('src',img.data('image-active'));
							$(val).find('>div').addClass('without_border');
						});


						return ;
					}

					setTimeout(function(){
						opened = false;
					}, 300);
					$('.catalog_block_container-subcategories.opened').removeClass('opened');
				}
			}

		})
	},

	initProductsList: function(){
		$('.product_list_container-content').find('.owl-carousel').owlCarousel({
			margin: 0,
			nav: false,
			dots:false,
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 2
				},
				1000: {
					items: 4
				}
			}
		});
		var owl = $('#feature_banner');
		owl.owlCarousel({
			margin: 0,
			nav: true,
			dots:false,
			loop: false,
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 2
				},
				1000: {
					items: 4
				}
			}
		});



	}
};

$(document).ready(function(){


	Main.init();
});


/* -dp- number product cart -dp- */

$(document).ready(function() {
	$('#number .minus').click(function () {
		var $input = $(this).parent().find('input');
		var count = parseInt($input.val()) - 1;
		count = count < 1 ? 1 : count;
		$input.val(count);
		$input.change();
		var cart_id = $(this).parent().data('cart-id');
		cart.update(cart_id, $(this).parent().find('input').val());
		return false;
	});
	$('#number .plus').click(function () {
		var $input = $(this).parent().find('input');
		$input.val(parseInt($input.val()) + 1);
		$input.change();
		var cart_id = $(this).parent().data('cart-id');
		cart.update(cart_id, $(this).parent().find('input').val());
		return false;
	});

/* delete product */
	$( "a.remove" ).each(function(index) {
		$(this).click(function(e) {
			e.preventDefault();
			e.stopPropagation();
		  $(this).next('.cart_block_container-item-wrap').remove();
		  $('.cart_block_container p').remove();
			cart.remove($(this).data('cart-id'));
		  $(this).fadeOut(1);

		});
	});

	//category grid/list
	$('#grid').on('click',function(e) {
		$('#list-content').removeClass('active').hide();
		$('#grid-content').addClass('active').show();
	});
	$('#list').on('click',function(e) {
		$('#grid-content').removeClass('active').hide();
		$('#list-content').addClass('active').show();
	});
	

	//category sorting
	$("#sorting").click(function(e) {
	  e.preventDefault();
	  $(this).toggleClass('active');
	})

	//category type-amount
	$(".type-amount").click(function() {
      $('button').removeClass('active');
      $(this).addClass('active');
	});


	$(".delivery, .paid").click(function() {
      $.ajax('/forward');
      $('button').removeClass('active');
      $(this).addClass('active');
	});

	//page about, owl carousel, reward
	$('.owl-carousel.reward').owlCarousel({
			loop: true,
			dots: false,
			margin: 3,
			mouseDrag: false,
			nav: true,
			navText: ['<img src="/catalog/view/theme/fruits/images/arrow-l.png">', '<img src="/catalog/view/theme/fruits/images/arrow-r.png">'],
			responsiveClass: true,
			responsive: {
				0: {
					items: 1,
					dots: true,
					nav: false
				},
				768: {
					items: 2,
					dots: true,
					nav: false
				},
				1200: {
					items: 4
				}
			}
	})


	// this faq 
	$(".faq_block_container-content_wrapper__item").click(function () {
	   if($(this).hasClass('open')){
			$(this).removeClass("open");
			$(this).children(".faq_block_container-content_wrapper__item-content").slideUp("slow");
			$(this).children(".yon").text("+");
	   }
	    else{
			$(this).addClass("open");
			$(this).children(".faq_block_container-content_wrapper__item-content").slideDown("slow");
			$(this).children(".yon").text("-");
		}
	});


        jQuery('table').addClass('responsive');
        jQuery('table').wrap('<div class="res"></div>');

        /*$('ul.tabs__caption').on('click', 'li:not(.active)', function() {
			$(this)
			.addClass('active').siblings().removeClass('active')
			.closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
		});*/

        

});

jQuery(document).ready(function($) {
    var width = $('#tabs-head').outerWidth();    
    $('#tabs-content').css({width: width+"px", display: "block"});
})