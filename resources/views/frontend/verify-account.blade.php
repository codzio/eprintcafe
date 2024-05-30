@extends('vwFrontMaster')

@section('content')

<style type="text/css">
  .shopping-cart .cart-ship-info {
    margin-top: 0;
  }

  .shopping-cart .cart-ship-info .errors {
    margin-bottom: 10px;
  }

  .errors {
    position: unset;
  }
</style>
  

<!--======= SUB BANNER =========-->
  <!-- <section class="sub-bnr" data-stellar-background-ratio="0.5">
    <div class="position-center-center">
      <div class="container">
        <h4>REGISTER</h4>
        <ol class="breadcrumb">
          <li><a href="{{ route('homePage') }}">Home</a></li>
          <li class="active">REGISTER</li>
        </ol>
      </div>
    </div>
  </section> -->
  
  <!-- Content -->
  <div id="content"> 
    
    <!--======= PAGES INNER =========-->
    <section class="chart-page charge_padding">
      <div class="container"> 
        
        <!-- Payments Steps -->
        <div class="shopping-cart"> 
          
          <!-- SHOPPING INFORMATION -->
          <div class="cart-ship-info register">
            <div class="row"> 
              
              <!-- ESTIMATE SHIPPING & TAX -->
              <div class="col-sm-6" style="float:unset;margin:auto;">
                <h6>Verify Your Account</h6>
                <form id="loginForm" method="post">

                  <div class="checkbox margin-0  text-right">
                      <p id="loginFormMsg" class="fw-semibold">
                      </p>
                  </div>


                  <ul class="row">
                    
                    <!-- Name -->
                    <li class="col-md-12">
                      <label> OTP
                        <input type="text" name="otp" value="" placeholder="">
                      </label>
                    </li>

                    <li class="col-md-12">
                      <span class="errors" id="otpErr"></span>
                    </li>



                    <div class="rows">
                      <li class="col-md-4">
                        <button id="loginFormBtn" type="submit" class="btn">Verify</button>
                      </li>
                      
                      <!-- FORGET PASS -->
                      <li class="col-md-4">
                        <div class="checkbox margin-0 margin-top-20 text-right">
                          <a id="resendOTP" href="javascript:void(0)">Resend OTP</a>
                        </div>
                      </li>
                    </div>
                  </ul>
                    <p class="sign_up">Don't have an Account? <a href="{{ route('registerPage') }}">Sign up</a></p>

                </form>
                
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<script type="text/javascript">

  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function gtag_report_conversion(url) {
      var callback = function () {
        if (typeof(url) != 'undefined') {
          window.location = url;
        }
      };
      gtag('event', 'conversion', {
          'send_to': 'AW-11400170460/5jSNCKXIkKcZENyXg7wq',
          'event_callback': callback
      });
      return false;
    }

    $("#loginForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("doVerifyAccount") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#loginFormBtn").html('Please Wait...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#loginFormMsg').html(res.msg).css('color', 'red');
              }
          } else {
              // $("#loginForm")[0].reset();
              $('#loginFormMsg').html(res.msg).css('color', 'green');

              gtag_report_conversion(res.redirect);
              
              setTimeout(function() {
                window.location.href = res.redirect;
              }, 3000);

          }


          $("#loginFormBtn").html('Verify');
        }
      })

    });

    $("#resendOTP").click(function (e) {
      $.ajax({
        url: '{{ route("doResendRegisOtp") }}',
        type: 'POST',
        dataType: 'json',
        data: {action: 'resend'},
        beforeSend: function() {
          $("#resendOTP").html('Please Wait...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#loginFormMsg').html(res.msg).css('color', 'red');
              }
          } else {
              // $("#loginForm")[0].reset();
              $('#loginFormMsg').html(res.msg).css('color', 'green');
          }
          
          $("#resendOTP").html('Resend OTP');
        }
      })
    });
  
  });
</script>

@endsection

