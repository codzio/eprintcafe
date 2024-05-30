@extends('vwFrontMaster')

@section('content')

<style type="text/css">
  .vision-text h2 {
    font-family: inherit;
  }

  .heading span {
    font-family: inherit;
  }
</style>
    
<!--======= SUB BANNER =========-->
<section class="sub-bnr" data-stellar-background-ratio="0.5">
<div class="position-center-center">
  <div class="container">
    <h4>ABOUT eprintcafe</h4>
    <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula. -->
    <!--  Sed feugiat, tellus vel tristique posuere, diam</p>-->
    <ol class="breadcrumb">
      <li><a href="{{ route('homePage') }}">Home</a></li>
      <li class="active">ABOUT</li>
    </ol>
  </div>
</div>
</section>

<!-- Content -->
<div id="content"> 

<!-- Knowledge Share -->
<section class="light-gray-bg padding-top-50" style="background:#fff!important;">
  <div class="container"> 
    
    <!-- Main Heading -->
    <div class="heading home_about_compnay text-center">
      <h4 style="color:var(--secondary-color-3);">About Company</h4>
      <h2 style="margin:auto; width:72%;">Your Ultimate Destination For Hassle-Free Online Printing</h2>
      <!--<div class="more_than_year">-->
      <!--  <h4>We Have More than <strong style="color:var(--primary-color-1);">33</strong> Years Of Experience in Printing Services</h4>-->
      <!--</div>-->
      <span style="width:100%; margin-bottom:35px; margin-top: 1rem;">Welcome to Eprintcafe.com, An initiative of India Inttech Pvt. Ltd. ( Shyam Electrostat - Since 1990), your dedicated offline convenience printing store ! We understand the value of your time and energy, which is why our platform is designed to provide you with easy access to high-quality online printing services. Say goodbye to the hassles of traditional printing – we're here to redefine your printing experience.
      </span> 
    </div>
  </div>
</section>

<!-- History -->
<section class="history-block padding-bottom-20">
  <div class="container">
    <div class="row">
      <div class="col-xs-10 center-block">            
        <!-- IMG --> 
        <!--<img class="img-responsive margin-top-80 margin-bottom-80" src="images/about-img.jpg" alt="">-->
        <div class="vision-text">
          <div class="col-lg-12">
            <h5 class="text-left">our vision</h5>
            <span>Our promise to you is simple - unique, affordable, and easy online printing solutions.</span>
          </div>
          <div class="col-lg-12" style="height: 32px;"></div>
          <div class="col-lg-12">
            <h5 class="text-left">our mission</h5>
            <span>Our commitment is to offer you quality products and online printing services without breaking the bank.</span>
          </div>
          <div class="col-lg-12" style="height: 32px;"></div>
          <div class="col-lg-12">
            <h5 class="text-left">We are impaneled with</h5>
            <div class="heading">
                <span>SUPREME COURT OF INDIA,<br>
                ELECTION COMMISSION,<br>
                INDIAN INSTITUTE OF CHARTERED ACCOUNTANTS OF INDIA (ICAI),<br>
                DELHI METRO RAIL CORPORATION (DMRC),<br>
                CENTRAL POLLUTION CONTROL BOARD (CPCB),<br>
                NATIONAL SCHEDULED CAST DEVELOPMENT & FINANCE CORPORATION (NSFDC),<br>
                SATLUJ JAL VIKAS NIGAM (SJVN),<br>
                TATA PROJECTS LTD,<br>
                TATA MOTORS LTD,<br>
                SHAPOORJI PALLONJI CO. PVT LTD,<br>
                AFCONS,<br>
                LARSON & TUBRO (L&T),<br>
                BHARAT ELECTRONICS LTD. (BEL),<br>
                CENTRAL WAREHOUSING CORPORATION,<br>
                INDRAPRASTH DENTAL COLLAGE,<br>
                SOUTH ASIAN UNIVERSITY,<br>
                JMC PROJECTS LTD.,<br>
                NATIONAL BOARD OF EXAMINATION IN MEDICAL SCIENCES (NBEMS),<br>
                MINERAL EXPLORATION OF INDIA LTD,<br>
                JETKING INFOTRAIN LTD,<br>
                UNACADEMY,<br>
                INDIAN AIRFORCE<br>
                INSTITUTE OF HIGHWAY ENGINEERINGING<br>
                NATIONAL HIGHWAY AUTHORITY OF INDIA ETC.<br>
                Now We introduce our self as e-commerce online store in the name of Eprintcafe.com</span>
            </div>
          </div>
          <!--<div class="col-lg-7">-->
          <!--  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam volutpat dui at lacus aliquet, a consequat enim aliquet. Integer molestie sit amet sem et faucibus. Nunc ornare pharetra dui, vitae auctor orci fringilla eget. Pellentesque in placerat felis. Etiam mollis venenatis luctus. <br>-->
          <!--    <br>-->
          <!--    Morbi ac scelerisque mauris. Etiam sodales a nulla ornare viverra. Nunc at blandit neque, bibendum varius purus. Nam sit amet sapien vitae enim vehicula tincidunt. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc faucibus imperdiet vulputate. </p>-->
          <!--</div>-->
        </div>
      </div>
    </div>
  </div>
</section>

<!-- home cards sec -->
<section class="light-gray-bg padding-top-50" style="">
  <div class="container"> 
    
    <!-- Main Heading -->
    <div class="heading home_about_compnay text-center">
      <h4 style="color:var(--secondary-color-3);">Company Statistics</h4>
      <h2 style="width:72%; margin:auto;">See Our Statistics That We Record To Achieve Our Clients</h2>
    </div>
    <div class="home_counter_card_sec padding-top-50">           
      <div class="counter-container">
        <!-- <i class="fab fa-twitter fax-3x"></i> -->
        <p>+</p>
        <span>On-Time Delivery </span>
        <div class="counter" data-target="100"></div>
      </div>
      <div class="counter-container">
        <!-- <i class="fab fa-youtube fax-3x"></i> -->
        <p>+</p>
        <span>Project We Completed Along the Way</span>
        <div class="counter" data-target="900"></div>
      </div>
      <div class="counter-container">
        <!-- <i class="fab fa-facebook fax-3x"></i> -->
        <p>+</p>
        <span>Error-Free Print Percentage</span>
        <div class="counter three" data-target="100"></div>
      </div>
      <div class="counter-container">
        <!-- <i class="fab fa-facebook fax-3x"></i> -->
        <p>+</p>
        <span>We Have Many Years Of Experience</span>
        <div class="counter" data-target="33"></div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonial -->
<section class="testimonial padding-top-50" style="margin-bottom:50px;">
  <div class="container">
    <div class="row">
      <div class="col-12"> 
        
        <!-- SLide -->
        <div class="single-slide">
          <!-- Slide -->
          <div class="testi-in"> <i class="fa fa-quote-left"></i>
            <p>Great work, great communication and very flexible.</p>
            <h5>Ankit</h5>
          </div>
          
          <!-- Slide -->
          <div class="testi-in"> <i class="fa fa-quote-left"></i>
            <p>Excellent team, will be working again for sure.</p>
            <h5>Suman</h5>
          </div>
          
          <!-- Slide -->
          <div class="testi-in"> <i class="fa fa-quote-left"></i>
            <p>They always go extra mile to achieve the results.</p>
            <h5>Faizan</h5>
          </div>
        </div>
      </div>
      
      <!-- Img -->
      <!-- <div class="col-sm-6"> <img class="img-responsive" src="images/testi-avatar.jpg" alt=""> </div> -->
    </div>
  </div>
</section>

</div>

@endsection