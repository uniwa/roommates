$(function(){
    var dbgState = 0;

	$(document).keypress(function(e){
		if((e.which == '100')|(e.which == '68')|(e.which == '948')|(e.which == '916')){
	        if(dbgState == 0){
    	        dbgState = 1;
    	        return;
	        }
        }

		if((e.which == '101')|(e.which == '69')|(e.which == '949')|(e.which == '917')){
	        if(dbgState == 1){
	            dbgState = 2;
	            return;
            }
        }

		if((e.which == '98')|(e.which == '66')|(e.which == '944')|(e.which == '914')){
			if (dbgState == 2) {
				// debug on
				$('#debug').show();
				return;
			}
		}
		
		if(e.keyCode == '27'){
    		$('#debug').hide();
		}

		// debug off
		dbgState = 0;
	});

	function dbg(msg){
		var now = new Date();
		var strTime = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
        var old = $('#debug').html();
		var cur = "<span class='debugMsg'>[" + strTime + "] " + msg + " [js]</span><br />" + old;
		$('#debug').html(cur);
	}

    function dbgClear(){
        $('#debug').html('');
    }
});
