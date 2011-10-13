$(function(){
	$.url = function(url) {
		return $('base').attr('href')+url.substr(1);
	}

	$('.order-by').change(function(e){
		e.preventDefault();
		var sel = $(this).find('select').val();
		var dataString = {'selection':sel};
		if($(this).hasClass('profile-ord')){
			var url = $.url('/profiles');
		}else if($(this).hasClass('house-ord')){
			var url = $.url('/houses');
		}	
		postit(url, dataString);
	});
	
	function postit(url, data){
		$('body').append($('<form/>', {
		  id: 'jQueryPostItForm',
		  method: 'POST',
		  action: url
		}));

		for(var i in data){
		  $('#jQueryPostItForm').append($('<input/>', {
			type: 'hidden',
			name: i,
			value: data[i]
		  }));
		}

		$('#jQueryPostItForm').submit();
	}
})