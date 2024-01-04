// SERVICE
$(function(){
	form = $('.overlay_order_service').find('form').eq(0);
	form.submit(function(e) {
	    e.preventDefault();
	});
	function success(result) {
		console.log(result);
	}
	form.on('click', 'button', function() {

		var inputName = form.find('input[name=service_name]').eq(0),
		    inputTel = form.find('input[name=service_tel]').eq(0),
		    inputService = form.find('input[name=service_type]').eq(0);

		var isValid = formValidate(inputName, inputTel);

		if (isValid) {
			var inputs = form.find('input');
			var data = {};
			inputs.each(function(){
				var name = $(this).attr('name');
				var val = $(this).val();
				data[name] = val;
			});
			$.ajax({
				url: '/send-service.php',
				type: 'POST',
				data: data,
				success: success
			});
		}
	})
})

// SERVICE VALIDATION
function formValidate(name, tel) {
	name.removeClass('form-error');
	tel.removeClass('form-error');

    var telVal = tel.val(),
	result = true;

	if (name) {
       name.addClass('form-error error-shake');
       result = false;
    };

    var checktel = /^\+?\d+$/.test(telVal);

    if (!checktel) {
       tel.addClass('form-error error-shake');
       result = false;
    };

    setTimeout(function(){
    	tel.removeClass('error-shake');
    }, 1000);

    return result;

}