$(function(){
    var dbgState = false;
    
    $.ctrl = function(key, callback, args) {
        $(document).keydown(function(e) {
            if(!args) args=[]; // IE barks when args is null
            if(e.keyCode == key.charCodeAt(0) && e.ctrlKey) {
                callback.apply(this, args);
                return false;
            }
        });
    };

    $.ctrl('E', function() {
		if (dbgState) {
//				var msg = 'debug off';
//				dbg(msg);
			$('#debug').hide();
		} else {
//				var msg = 'debug on';
//				dbg(msg);
			$('#debug').show();
		}
		
		dbgState = !dbgState;
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
