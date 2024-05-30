@extends('vwFrontMaster')

@section('content')
  
<style type="text/css">
  .shopping-cart .cart-ship-info {
    margin-top: 0;
  }

  .errors {
    position: unset;
  }
</style>

  <!--======= SUB BANNER =========-->
  <!-- <section class="sub-bnr" data-stellar-background-ratio="0.5">
    <div class="position-center-center">
      <div class="container">
        <h4>LOGIN</h4>
        <ol class="breadcrumb">
          <li><a href="{{ route('homePage') }}">Home</a></li>
          <li class="active">LOGIN</li>
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
          <div class="cart-ship-info">
            <div class="row"> 
              
              <!-- ESTIMATE SHIPPING & TAX -->
              <div class="col-sm-6" style="float:unset;margin:auto;">
                <h6>LOGIN YOUR ACCOUNT</h6>
                <form id="loginForm" method="post">

                  <div class="checkbox margin-0  text-right">
                      <p id="loginFormMsg" class="fw-semibold text-danger"></p>
                  </div>


                  <ul class="row">
                    
                    <!-- Name -->
                    <li class="col-md-12">
                      <label> Email/Phone Number
                        <input type="text" name="phoneNumber" value="" placeholder="">
                      </label>
                      <span class="errors" id="phoneNumberErr"></span>
                      <span class="errors" id="emailErr"></span>
                    </li>
                    <!-- LAST NAME -->
                    <li class="col-md-12 li_margin">
                      <label> PASSWORD
                        <input type="password" name="password" value="" placeholder="">
                      </label>
                      <span class="errors" id="passwordErr"></span>
                    </li>

                    <input type="hidden" name="action" value="{{ $action }}">
                    
                    <!-- LOGIN -->
                    <div class="rows">
                      <li class="col-md-4">
                        <button id="loginFormBtn" type="submit" class="btn">LOGIN</button>
                      </li>
                      
                      <!-- FORGET PASS -->
                      <li class="col-md-4">
                        <div class="checkbox margin-0 margin-top-20 text-right">
                          <a href="{{ route('forgotPasswordPage') }}">Forgot Password?</a>
                        </div>
                      </li>
                    </div>
                  </ul>
                    <p class="sign_up">Don't have an Account? <a href="{{ $registerPageUrl }}">Sign up</a></p>

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
    
  </div>

<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#loginForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("doLogin") }}',
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
                  $('#loginFormMsg').html(res.msg);
              }
          } else {
              $("#loginForm")[0].reset();
              $('#loginFormMsg').html(res.msg).show();
              window.location.href = res.redirect;
          }


          $("#loginFormBtn").html('Login');
        }
      })

    });
  
  });
</script>

@endsection

