@extends('vwFrontMaster')

@section('content')
  
  <!--======= SUB BANNER =========-->
  <!-- <section class="sub-bnr" data-stellar-background-ratio="0.5">
    <div class="position-center-center">
      <div class="container">
        <h4>Forgot Password</h4>
        <ol class="breadcrumb">
          <li><a href="{{ route('homePage') }}">Home</a></li>
          <li class="active">Forgot Password</li>
        </ol>
      </div>
    </div>
  </section> -->
  
  <!-- Content -->
  <div id="content"> 
    
    <!--======= PAGES INNER =========-->
    <section class="chart-page padding-top-150 padding-bottom-100">
      <div class="container"> 
        
        <!-- Payments Steps -->
        <div class="shopping-cart"> 
          
          <!-- SHOPPING INFORMATION -->
          <div class="cart-ship-info">
            <div class="row"> 
              
              <!-- ESTIMATE SHIPPING & TAX -->
              <div class="col-sm-6" style="float:unset;margin:auto;">
                <h6>Forgot Password</h6>
                <form id="forgetPass" method="post">
                  <ul class="row">
                    
                    <!-- Name -->
                    <li class="col-md-12">
                      <label> Phone Number
                        <input type="text" name="phoneNumber" value="" placeholder="">
                      </label>
                    </li>

                    <span class="col-md-12 text-danger" id="phoneNumberErr"></span>
                    <div class="col-md-12" id="forgetPassMsg"></div>
                    
                    <!-- LOGIN -->
                    <li class="col-md-4">
                      <button id="forgetPassBtn" type="submit" class="btn">Submit</button>
                    </li>
                    
                    <!-- CREATE AN ACCOUNT -->
                    <li class="col-md-4">
                      <div class="checkbox margin-0 margin-top-20">
                        <input id="checkbox1" class="styled" type="checkbox">
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
  </div>

<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#forgetPass").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("doForgotPassword") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#forgetPassBtn").html('Please Wait...');
          $(".text-danger").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#forgetPassMsg').html(res.msg).css('color', 'red');
              }
          } else {
              $("#forgetPass")[0].reset();
              $('#forgetPassMsg').html(res.msg).css('color', 'green').show();
              setTimeout(function() {
                window.location.href = res.redirect;
              }, 2000);
          }


          $("#forgetPassBtn").html('Submit');
        }
      })

    });
  
  });
</script>

@endsection

