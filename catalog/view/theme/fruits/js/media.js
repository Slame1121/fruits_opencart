var Media = {
	initHeaderNav: function () {
		$('.top_navbar-container__item-mobile-nav').on('click','.top_navbar-container__item-mobile-nav__button button', function(){
			var container = $('.top_navbar-container__item-mobile-nav__container');
			container.show();
			setTimeout(function(){
				container.addClass('open');
			}, 50)
		}).on('click','.top_navbar-container__item-mobile-nav__container-close', function(){
			var self = $(this);
			$(this).parent().removeClass('open');
			setTimeout(function(){
				self.parent().hide();
			}, 400)
		});
	},
	init: function(){
		this.initHeaderNav();
	}
};

$(document).ready(function(){
	Media.init();
});