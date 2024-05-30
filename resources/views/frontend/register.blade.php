@extends('vwFrontMaster')

@section('content')

<style type="text/css">
  .form-btn_{
    display:flex;
    flex-direction: column;
  }
  .sign_up{
    margin-top:38px!important;
  }
  .shopping-cart .btn{
    margin-right:unset!important;
    margin:auto;
  }
  .shopping-cart .cart-ship-info {
    margin-top: 0;
  }

  .shopping-cart .cart-ship-info .errors {
    margin-bottom: 10px;
  }

  .errors {
    position: unset;
  }
  #registerForm{
    padding:0 12px;
  }
  .sign_up{
    margin-top:50px;
/*    display:flex;*/
/*    align-items:center;*/
  }
  .margin-bott{
    margin-bottom:38px;
  }
  label{
    margin-bottom:13px;
  }

  @media (max-width:600px){
    .mobile-width{
      width:80%;
    }
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
            <div class="row" style="display:flex; justify-content:center;"> 
              
              <!-- ESTIMATE SHIPPING & TAX -->
              <div class="col-sm-6 mobile-width">
                <h6>REGISTER</h6>
                <form id="registerForm" method="post">

                  <div class="checkbox margin-0  text-right">
                      <p id="registerFormMsg" class="fw-semibold">
                      </p>
                  </div>
                  
                  <ul class="row margin-bott">
                    
                    <!-- Name -->
                    <li class="col-12">
                      <label> *NAME
                        <input type="text" name="name" value="" placeholder="">
                      </label>
                      <span class="errors" id="nameErr"></span>
                    </li>
                    
                    <!-- EMAIL ADDRESS -->
                    <li class="col-12">
                      <label> *EMAIL ADDRESS
                        <input type="text" name="email" value="" placeholder="">
                      </label>
                      <span class="errors" id="emailErr"></span>
                    </li>

                    <!-- PHONE -->
                    <li class="col-12">
                      <label> *MOBILE NUMBER
                        <input type="text" name="phone" value="" placeholder="">
                      </label>
                      <span class="errors" id="phoneErr"></span>
                    </li>

                    <!-- <li class="col-12"> 
                      <label>*ADDRESS
                        <input type="text" name="address" value="" placeholder="">
                      </label>
                      <span class="errors" id="addressErr"></span>
                    </li> -->

                    <!-- TOWN/CITY -->
                    <!-- <li class="col-12">
                      <label>*TOWN/CITY
                        <input type="text" name="city" value="" placeholder="">
                      </label>
                      <span class="errors" id="cityErr"></span>
                    </li> -->

                    <!-- COUNTRY -->
                    <!-- <li class="col-12">
                      <label> STATE
                        <select class="selectpicker" name="state">
                          <option value="">Select state</option>
                          <option value="AN">Andaman and Nicobar Islands</option>
                          <option value="AP">Andhra Pradesh</option>
                          <option value="AR">Arunachal Pradesh</option>
                          <option value="AS">Assam</option>
                          <option value="BR">Bihar</option>
                          <option value="CH">Chandigarh</option>
                          <option value="CT">Chhattisgarh</option>
                          <option value="DN">Dadra and Nagar Haveli</option>
                          <option value="DD">Daman and Diu</option>
                          <option value="DL">Delhi</option>
                          <option value="GA">Goa</option>
                          <option value="GJ">Gujarat</option>
                          <option value="HR">Haryana</option>
                          <option value="HP">Himachal Pradesh</option>
                          <option value="JK">Jammu and Kashmir</option>
                          <option value="JH">Jharkhand</option>
                          <option value="KA">Karnataka</option>
                          <option value="KL">Kerala</option>
                          <option value="LA">Ladakh</option>
                          <option value="LD">Lakshadweep</option>
                          <option value="MP">Madhya Pradesh</option>
                          <option value="MH">Maharashtra</option>
                          <option value="MN">Manipur</option>
                          <option value="ML">Meghalaya</option>
                          <option value="MZ">Mizoram</option>
                          <option value="NL">Nagaland</option>
                          <option value="OR">Odisha</option>
                          <option value="PY">Puducherry</option>
                          <option value="PB">Punjab</option>
                          <option value="RJ">Rajasthan</option>
                          <option value="SK">Sikkim</option>
                          <option value="TN">Tamil Nadu</option>
                          <option value="TG">Telangana</option>
                          <option value="TR">Tripura</option>
                          <option value="UP">Uttar Pradesh</option>
                          <option value="UT">Uttarakhand</option>
                          <option value="WB">West Bengal</option>
                        </select>
                      </label>
                      <span class="errors" id="stateErr"></span>
                    </li> -->
                    
                    <!-- LAST NAME -->
                    <li class="col-12">
                      <label> *PASSWORD
                        <input type="password" name="password" value="" placeholder="">
                      </label>
                      <span class="errors" id="passwordErr"></span>
                    </li>

                    <!-- LAST NAME -->
                    <!-- <li class="col-md-6 li_margin">
                      <label> *CONFIRM PASSWORD
                        <input type="password" name="confirmPassword" value="" placeholder="">
                      </label>
                      <span class="errors" id="confirmPasswordErr"></span>
                    </li> -->

                    <input type="hidden" name="action" value="{{ $action }}">
                    
                  </ul>
                 <div class="form-btn_">
                    <button id="registerFormBtn" type="submit" class="btn">REGISTER NOW</button>
                    <p class="sign_up">Already have an Account? <a href="{{ $loginPageUrl }}">Login</a></p>
                 </div>

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

    $("#registerForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("doRegister") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#registerFormBtn").html('Please Wait...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#registerFormMsg').html(res.msg);
              }
          } else {
              // $("#registerForm")[0].reset();
              // $('#registerFormMsg').html(res.msg).show();
             window.location.href = res.redirect;
          }


          $("#registerFormBtn").html('Register Now');
        }
      })

    });
  
  });
</script>

@endsection

