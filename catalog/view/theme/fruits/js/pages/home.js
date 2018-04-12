var Home = {
	initsliders: function () {
		//init main banner
		var owl = $('#main_banner');


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


	},

	setScroll: function () {
		$('.about_us_container-content').slimScroll({
			height: '250px',
			position: 'right',
			color: '#4a88db',
			railColor: '#eae4fa',
			opacity: 1,
			railOpacity: 1,
			railVisible: true,
			alwaysVisible: true,
			railBorderRadius: '0',
			borderRadius : '0',
			size: '6px'
		});
	},
	init: function(){
		this.initsliders();

		this.setScroll();

		Main.initProductsList();
	}
};
$(document).ready(function(){


	Home.init();
});