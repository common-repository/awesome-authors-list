function handleCheck() {
	jQuery('#options > input').each(function() {
		check(this);
	});
}

function check(cb) {
	var short_code = jQuery('#short_code').val();
	var param = [' ', cb.value, '="1"'].join("");
	if (cb.checked) {
		if (short_code.indexOf(param) == -1) {
			short_code = short_code.replace(']', param);
			short_code += ']';
		}
	} else {
		if (short_code.indexOf(param) != -1) {
			short_code = short_code.replace(param, '');
		}
	}

	jQuery('#short_code').val(short_code);
	jQuery('#short_code').attr("size", short_code.length);
}

function showCopy() {
	jQuery("#copy").fadeIn(500);
}

function hideCopy() {
	jQuery("#copy").fadeOut(500);
}