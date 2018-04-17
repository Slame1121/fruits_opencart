var Category = {

	init: function(){
		$('#category_changer').on('change',function(){
			var link = $(this).val();
			if(typeof link != 'undefined' && link != ''){
				location.href = link;
			}

		})
		$('#ills_select').on('change',function(){
			$(this).closest('form').submit();

		})
		$('#sort_select').on('change',function(){
			var order = $(this).find('option:selected').data('order');
			$(this).closest('form').find('input[name=order]').val(order);
			$(this).closest('form').submit();

		})

	}
};
$(document).ready(function(){


	Category.init();
});