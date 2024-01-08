var interval;

function countdown() {
  clearInterval(interval);
  interval = setInterval( function() {
      var timer = $('.js-timeout').html();
      timer = timer.split(':');
      var minutes = timer[0];
      var seconds = timer[1];
      seconds -= 1;
      if (minutes < 0) return;
      else if (seconds < 0 && minutes != 0) {
          minutes -= 1;
          seconds = 59;
      }
      else if (seconds < 10 && length.seconds != 2) seconds = '0' + seconds;

      $('.js-timeout').html(minutes + ':' + seconds);

      if (minutes == 0 && seconds == 0) {
        clearInterval(interval)
        $("#resendOtpTimeout").hide();
        $("#resendOtpBtn").show();
      }
  }, 1000);
}

toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": true,
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$("#kt_sign_in_form").submit(function(event) {
	event.preventDefault();

	url = $(this).attr('action');
	formData = $(this).serialize();

	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'json',
		data: formData,
		beforeSend: function() {
			$(".text-danger").html('');
            $("#kt_sign_in_submit .indicator-label").hide();
            $("#kt_sign_in_submit .indicator-progress").show();
		},
		success: function (res) {
            if (res.error == true) {
            	if (res.eType == 'field') {
            		$.each(res.errors, function(index, val) {
            			$("#"+index+"Err").html(val);
            		});
            	} else {
            		toastr.error(res.msg);
            	}
            } else {
                window.location.href = res.redirect;
            }

            $("#kt_sign_in_submit .indicator-label").show();
            $("#kt_sign_in_submit .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
	})

});

$("#kt_sing_in_two_factor_form").submit(function(event) {
    event.preventDefault();

    url = $(this).attr('action');
    formData = $(this).serialize();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_sing_in_two_factor_submit .indicator-label").hide();
            $("#kt_sing_in_two_factor_submit .indicator-progress").show();
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });
                } else {
                    toastr.error(res.msg);
                }
            } else {
                window.location.href = res.redirect;
            }

            $("#kt_sing_in_two_factor_submit .indicator-label").show();
            $("#kt_sing_in_two_factor_submit .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

});

$("#resendOtpBtn").click(function(event) {
    event.preventDefault();

    url = $(this).attr('data-url');

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: {action: 'resend'},
        beforeSend: function() {
            $("#resendOtpBtn").hide();
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });
                } else {
                    toastr.error(res.msg);
                }
            } else {
                toastr.success(res.msg);

                $("#resendOtpTimeout").show();
                $('.js-timeout').text("1:00");
                countdown();

            }

            $("#kt_sing_in_two_factor_submit .indicator-label").show();
            $("#kt_sing_in_two_factor_submit .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

});

$("#kt_password_reset_form").submit(function(event) {
    event.preventDefault();

    url = $(this).attr('action');
    formData = $(this).serialize();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_password_reset_submit .indicator-label").hide();
            $("#kt_password_reset_submit .indicator-progress").show();
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });
                } else {
                    toastr.error(res.msg);
                }
            } else {
                
                toastr.success(res.msg);

                timeout = setTimeout(function() {
                    window.location.href = res.redirect;
                    clearTimeout(timeout);
                }, 3000);
                
            }

            $("#kt_password_reset_submit .indicator-label").show();
            $("#kt_password_reset_submit .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

});

$("#kt_new_password_form").submit(function(event) {
    event.preventDefault();

    url = $(this).attr('action');
    formData = $(this).serialize();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_new_password_submit .indicator-label").hide();
            $("#kt_new_password_submit .indicator-progress").show();
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });
                } else {
                    toastr.error(res.msg);
                }
            } else {
                
                toastr.success(res.msg);

                timeout = setTimeout(function() {
                    window.location.href = res.redirect;
                    clearTimeout(timeout);
                }, 3000);
                
            }

            $("#kt_new_password_submit .indicator-label").show();
            $("#kt_new_password_submit .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

});