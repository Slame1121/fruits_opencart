var Account = {
	initOrderToggle: function () {
		$('.profile_data_tabs-container__item-orders__item-container__order').on('click', function(){
			$(this).parent().find('.profile_data_tabs-container__item-orders__item-container__dropdown').slideToggle();
		})
	},
	init: function(){
		this.initOrderToggle();
	}
};

$(document).ready(function(){
	Account.init();
});