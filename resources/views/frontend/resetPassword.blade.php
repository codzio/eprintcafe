@extends('vwFrontMaster')

@section('content')

<style type="text/css">
  .errors {
    position: unset;
  }
</style>
  
  <!--======= SUB BANNER =========-->
  <section class="sub-bnr" data-stellar-background-ratio="0.5">
    <div class="position-center-center">
      <div class="container">
        <h4>RESET PASSWORD</h4>
        <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula.  -->
          <!-- Sed feugiat, tellus vel tristique posuere, diam</p> -->
        <ol class="breadcrumb">
          <li><a href="{{ route('homePage') }}">Home</a></li>
          <!-- <li><a href="#">PAGES</a></li> -->
          <li class="active">RESET PASSWORD</li>
        </ol>
      </div>
    </div>
  </section>
  
  <!-- Content -->
  <div id="content"> 
    
    <!--======= PAGES INNER =========-->
    <section class="chart-page padding-top-100 padding-bottom-100">
      <div class="container"> 
        
        <!-- Payments Steps -->
        <div class="shopping-cart"> 
          
          <!-- SHOPPING INFORMATION -->
          <div class="cart-ship-info">
            <div class="row"> 
              
              <!-- ESTIMATE SHIPPING & TAX -->
              <div class="col-sm-6">
                <h6>RESET YOUR PASSWORD</h6>
                <form id="resetPassword" method="post">

                <div class="checkbox margin-0  text-right">
                    <p id="resetPasswordMsg" class="fw-semibold">
                    </p>
                </div>

                  <div class="checkbox margin-0  text-right">
                    <p class="fw-semibold">
                        Have you already reset the password ?  
                        <a href="{{ route('loginPage') }}">SIGN IN</a>
                    </p>
                  </div>


                  <ul class="row">
                    
                    <li class="col-md-12">
                      <label> OTP
                        <input type="text" name="otp" value="" placeholder="">
                      </label>
                      <span class="errors" id="otpErr"></span>
                    </li>

                    <li class="col-md-12">
                      <label> PASSWORD
                        <input type="password" name="password" value="" placeholder="">
                      </label>
                      <span class="errors" id="passwordErr"></span>
                    </li>
                    <li class="col-md-12">
                      <label> CONFIRM PASSWORD
                        <input type="password" name="confirmPass" value="" placeholder="">
                      </label>
                      <span class="errors" id="confirmPassErr"></span>
                    </li>
                    
                    <li class="col-md-4">
                      <button id="resetPasswordBtn" type="submit" class="btn">RESET PASSWORD</button>
                    </li>
                    
                    <li class="col-md-4">
                      <div class="checkbox margin-0 margin-top-20">
                        <input id="checkbox1" class="styled" type="checkbox">
                      </div>
                    </li>
                    
                    <!-- FORGET PASS -->
                    <li class="col-md-4">
                      <div class="checkbox margin-0 margin-top-20 text-right">
                        <a id="resendOTP" href="javascript:void(0)">Resend OTP</a>
                      </div>
                    </li>
                  </ul>
                </form>
                
              </div>
              
              <!-- SUB TOTAL -->
              <!-- <div class="col-sm-5">
                <h6>LOGIN WITH</h6>
                
                <ul class="login-with">
                  <li>
                      <a href="#."><i class="fa fa-facebook"></i>FACEBOOK</a>
                    
                    </li>
                    
                    <li>
                      <a href="#."><i class="fa fa-google"></i>GOOGLE</a>
                    
                    </li>
                    
                    <li>
                      <a href="#."><i class="fa fa-twitter"></i>TWITTER</a>
                    
                    </li>
                
                </ul>
                
                
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </section>
    
    
    <!-- News Letter -->
    <section class="news-letter padding-top-150 padding-bottom-150">
      <div class="container">
        <div class="heading light-head text-center margin-bottom-30">
          <h4>NEWSLETTER</h4>
          <span>Phasellus lacinia fermentum bibendum. Interdum et malesuada fames ac ante ipsumien lacus, eu posuere odi </span> </div>
        <form>
          <input type="email" placeholder="Enter your email address" required>
          <button type="submit">SEND ME</button>
        </form>
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

    $("#resetPassword").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("customerDoResetPassword") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#resetPasswordBtn").html('Processing...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#resetPasswordMsg').html(res.msg).css('color', 'red');
              }
          } else {
              $("#resetPassword")[0].reset();
              $('#resetPasswordMsg').html(res.msg).show().css('color', 'green');
              window.location.href = res.redirect;
          }

          $("#resetPasswordBtn").html('RESET PASSWORD');
        }
      })

    });

    $("#resendOTP").click(function (e) {
      $.ajax({
        url: '{{ route("doResendForgotPassOtp") }}',
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
                  $('#resetPasswordMsg').html(res.msg).css('color', 'red');
              }
          } else {
              // $("#loginForm")[0].reset();
              $('#resetPasswordMsg').html(res.msg).css('color', 'green');
          }
          
          $("#resendOTP").html('Resend OTP');
        }
      })
    });
  
  });
</script>

@endsection

