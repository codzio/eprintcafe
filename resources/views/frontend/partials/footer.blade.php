<!--======= FOOTER =========-->
<style>
  footer p {color: white;}
  .about-footer p a { color: white; }
  .about-footer .footer-desc {margin-bottom: 0px;}
  footer li a {color: white !important}
</style>
<footer>
  <div class="container"> 
    
    <!-- ABOUT Location -->
    <div class="col-md-5">
      <div class="about-footer"> <img class="margin-bottom-30" src="{{ asset('public/frontend') }}/images/logo-foot-new.png" alt="" >
        <p><i class="icon-pointer"></i> Shyam Electrosatat, 11, Vikas Marg,
          Veer Savarkar Block, Dayanand, <br> Colony, Shakarpur, Delhi, 110092</p>
        <p class="footer-desc"><strong>Call Us :</strong> (Monday – Saturday: 10:00 AM to 07:00 PM)</p>

        <p class="footer-desc"><strong>Customer Care & WhatsApp:</strong> <a href="tel:+918448193391">+91 8448193391</a></p>
        <p class="footer-desc"><strong>Bulk Orders or Corporate Inquiries:</strong> <a href="tel:+918448193390">+91 8448193390</a></p>
        <p class="footer-desc"><strong>Emails Orders or Support:</strong> <a href="mailto:mail@eprintcafe.com">mail@eprintcafe.com</a></p>

        <!-- <p><i class="icon-call-end"></i> <a href="tel:+91 8448193390">+91 8448193390</a></p>
        <p><i class="icon-envelope"></i> <a href="mailto:mail@Eprintcafe.com">mail@Eprintcafe.com</a></p> -->
        <!-- <section class="small-about">
              <ul class="social_icons">
                <li><a href="#." style="margin-left:0;"><i class="icon-social-facebook"></i></a></li>
                <li><a href="#."><i class="icon-social-twitter"></i></a></li>
                <li><a href="#."><i class="icon-social-tumblr"></i></a></li>
                <li><a href="#."><i class="icon-social-youtube"></i></a></li>
                <li><a href="#."><i class="icon-social-dribbble"></i></a></li>
              </ul>
        </section> -->
      </div>
    </div>
    
    <!-- HELPFUL LINKS -->
   <!--  <div class="col-md-3">
      <h6>HELPFUL LINKS</h6>
      <ul class="link">
        <li><a href="shop_01.html"> Products</a></li>
        <li><a href="#"> Find a Store</a></li>
        <li><a href="#"> Features</a></li>
        <li><a href="#"> Privacy Policy</a></li>
        <li><a href="blog-list_03.html"> Blog</a></li>
        <li><a href="#"> Press Kit </a></li>
      </ul>
    </div> -->
    
    <!-- SHOP -->
    <div class="col-md-2">
      <h6>SHOP</h6>
      <ul class="link">
        <li><a href="{{ route('aboutPage') }}"> About Us</a></li>
        <li><a href="{{ route('priceCalc') }}"> Price Calculator</a></li>
        <li><a href="{{ route('contactPage') }}"> Contact</a></li>
        <li><a href="{{ route('termsAndConditionPage') }}"> Terms & Conditions</a></li>
        <li><a href="{{ route('privacyPolicyPage') }}"> Privacy Policy</a></li>
        <li><a href="{{ route('returnPolicyPage') }}"> Return Policy</a></li>
        <li><a href="{{ route('shippingPolicyPage') }}"> Shipping Policy</a></li>
        <li><a href="{{ route('cancellationPolicyPage') }}">Cancellation Policy</a></li>
      </ul>
    </div>
    
    <!-- MY ACCOUNT -->
    <div class="col-md-2">
      <h6>MY ACCOUNT</h6>
      <ul class="link">
        <li><a href="{{ route('loginPage') }}"> Login</a></li>
        <li><a href="{{ route('registerPage') }}"> Register</a></li>
        <!-- <li><a href="#"> My Account</a></li> -->
        <li><a href="{{ route('cartPage') }}"> My Cart</a></li>
      </ul>
    </div>

    <div class="col-md-3">
      <h6>Delivery Time</h6>
      <p class="footer-desc"><strong>Delhi NCR:</strong> 2–3 working days (Same-day pickup and delivery available also through porter / we fast ) 
      <br><br>
            <strong>Other States of India:</strong> 5–10 working days (Delivery times may vary depending on your address.) <br>
            Please note, delivery time starts the next working day after placing your order.</p>
    </div>
    
    <!-- Rights -->

    <script type="text/javascript">
      jQuery(document).ready(function($) {
        
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $("#leadingForm").submit(function(event) {
          event.preventDefault();

          url = $(this).attr('action');
          formData = $(this).serialize();

          $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            beforeSend: function() {
              
              $(".text-danger").html('');
              $('#leadingFormBtn').html('Please Wait...');
              $("#leadingFormMsg").html('');

            }, success: function(res) {

              if(res.error == true) {
                if(res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                     $("#"+index+"Err").html(val);
                  });
                } else {
                  $("#leadingFormMsg").html(res.msg).css('color', 'red');
                }
              } else {
                window.location.href = res.redirect
              }

              $('#leadingFormBtn').html('Enquire Now');

            }
          })          

        });

      });
    </script>
    
  <div class="rights">
      <p>©  2024 eprintcafe All right reserved. </p>
      <div class="scroll"> <a href="#wrap" class="go-up"><i class="lnr lnr-arrow-up"></i></a> </div>-->
    </div>
  </div>
</footer>
<!--======= RIGHTS =========--> 