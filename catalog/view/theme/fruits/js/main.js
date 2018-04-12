var Main = {

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
			if(contaienr.find('.product_card_container_reviews__addreview').css('display') ==  'none'){
				contaienr.find('.owl-carousel').hide();
				contaienr.find('.product_card_container_reviews-pagination').hide();
				contaienr.find('.product_card_container_reviews__addreview').fadeIn('slow');
			}else{

				//SEND REVIEW FORM (VALIDATION)
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
		all_containers.find('.tabs_list a').on('click', function(e){
			e.stopPropagation();
			e.preventDefault();
			$(this).parent().parent().find('a').removeClass('active');
			$(this).addClass('active');
			var href =  $(this).attr('href');
			$(this).closest('.tabs_container').find('.tabs_items >div').hide();
			$(this).closest('.tabs_container').find(href).fadeIn('slow');
		})
	},
	init: function(){
		this.initCatalog();
		this.initReviews();
		this.initReviews2();
		this.initNews();

		this.initTabs();
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
		$(e.target).parent().find('.owl-custom-pagination').append('<div class="navigation"><img src="/catalog/view/theme/fruits/images/arrow-left.png" /><img src="/catalog/view/theme/fruits/images/arrow-right.png" /></div><div class="count"></div>');
		$(e.target).parent().find('.owl-custom-pagination .navigation img:first-child').on('click', function(){
			($(e.target).trigger('prev.owl.carousel'));
		});
		$(e.target).parent().find('.owl-custom-pagination .navigation img:nth-child(2)').on('click', function(){
			($(e.target).trigger('next.owl.carousel'));
		});
		$(e.target).parent().find('.owl-custom-pagination').find('.navigation');
		$(e.target).parent().find('.owl-custom-pagination .count').text(index + '/' + e.item.count)
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
		})


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
			var container = $(this).closest('.catalog_block_container-list__item');
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
					$.each(json, function(key, val){
						content.append(
							' <div class="catalog_block_container-subcategories__content-item">\
									<a href="'+val.href+'">'+val.name+'</a>\
								</div>'
						);
					});
					$('.alert-dismissible, .text-danger').remove();

					if (json['redirect']) {
						location = json['redirect'];
					}

					if (json['success']) {
						$('#content').parent().before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						// Need to set timeout otherwise it wont update the total
						setTimeout(function () {
							$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
						}, 100);

						$('html, body').animate({ scrollTop: 0 }, 'slow');

						$('#cart > ul').load('index.php?route=common/cart/info ul li');
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
				//bordered div
				$(val).find('>div').addClass('without_border');
				img.attr('src',img.data('image-active'));
			});
		});
		$('.catalog_block_container').on('click','.catalog_block_container-subcategories__close',function(e){
			e.stopPropagation();
			e.preventDefault();
			$(this).parent().removeClass('opened');

			$.each($('.catalog_block_container-list__item'),function(key, val){
				var img =	$(val).find('>img');
				$(val).find('>div').removeClass('without_border');
				img.attr('src',img.data('image'));

			});
			setTimeout(function(){
				opened = false;
			}, 300);

		})

		//remove subcategories if click on body

		$('body').on('click', function(e){
			if(opened){
				if($(e.target).closest('.catalog_block_container-subcategories.opened').length == 0){
					$.each($('.catalog_block_container-list__item'),function(key, val){
						var img =	$(val).find('>img');
						img.attr('src',img.data('image'));
						$(val).find('>div').removeClass('without_border');
					});
					if($(e.target).parent().hasClass('catalog_block_container-list__item-wrapper-text')){
						var position = $(e.target).parent().parent().parent().data('position');
						$.each($('div[data-position='+position+']'),function(key, val){
							var img =	$(val).find('>img');
							img.attr('src',img.data('image-active'));
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


		$('.product_list_container-content .raty, .cart_block .raty, .product_card .raty, #feature_banner .raty, .category_block .raty').raty({
			starType: 'i',
			readOnly: true,
			score: 3.5,/*function() {
				return $(this).attr('data-score');
			}*/
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
		return false;
	});
	$('#number .plus').click(function () {
		var $input = $(this).parent().find('input');
		$input.val(parseInt($input.val()) + 1);
		$input.change();
		return false;
	});

/* delete product */
	$( "a.remove" ).each(function(index) {
		$(this).click(function() {
		  $(this).next('.cart_block_container-item-wrap').remove();
		  $('.cart_block_container p').remove();
		  $('.cart_block_container').prepend('<p><strong>Почему вы удалили товар ? :(</strong></p>');
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