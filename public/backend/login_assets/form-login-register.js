$(document).ready(function(){
	'use strict';


	// Remember checkbox
	if($('.chk-remember').length){
		$('.chk-remember').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
		});
	}

	$('#remember-me').on('ifChecked', function (event) {
	    $("#chkrememberme").val("1");
	}).on('ifUnchecked', function (event) {
	    $("#chkrememberme").val("0");
	});
});


