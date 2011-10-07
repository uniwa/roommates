$(function(){
	$('.order-by').change(function(e){
		e.preventDefault();
		var sel = $(this).find('select').val();
		var dataString = {'selection':sel};
		if($(this).hasClass('profile-ord')){
			var url = 'profiles';
		}else if($(this).hasClass('house-ord')){
			var url = 'houses';
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
});