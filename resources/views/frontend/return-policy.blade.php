@extends('vwFrontMaster')

@section('content')

<style type="text/css">
  h3 {
      font-weight: bold !important;
      color: var(--secondary-color-3) !important;
      font-size: 26px !important;
      margin: 0px;
      margin-bottom: 20px !important;
      margin-top: 20px !important;
      letter-spacing: 5px;
      /* color: var(--secondary-color-3); */
  }

  .home_about_compnay h2 {
    line-height: 1.4;
    width: unset;
    font-weight: bold !important;
    color: var(--secondary-color-3) !important;
    font-size: 26px !important;
  }
</style>
    
<!--======= SUB BANNER =========-->
<section class="sub-bnr" data-stellar-background-ratio="0.5">
<div class="position-center-center">
  <div class="container">
    <h4>Return Policy</h4>
    <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula. -->
    <!--  Sed feugiat, tellus vel tristique posuere, diam</p>-->
    <ol class="breadcrumb">
      <li><a href="{{ route('homePage') }}">Home</a></li>
      <li class="active">Return Policy</li>
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
    <div class="heading home_about_compnay text-left">
      <h4 style="color:var(--secondary-color-3);">Return Policy</h4>

      <ul class="list-style-one pt-10 wow fadeInUp delay-0-3s animated" style="visibility: visible; animation-name: fadeInUp;">
                                <li>Cash on Delivery not available</li>
                                <li>Delivery charges depend on the location and weight of the order</li>
                <li>4-5 business days for deliveries to Metro-cities (Bangalore, Chennai, Delhi, Kolkata &amp; Mumbai)</li>
                <li>7-8 business days for deliveries to the rest of India</li>
                </ul><br>
                <h2>Details of our delivery service</h2>
                <h3>Refund Policy</h3>
<p>If an order is placed on our website and your payment is deducted, but your order is cancelled for any reason (Personal Request, Design Issue, ext.,) we will reimburse your money within 7-10 working days.</p>
                            <h3>Return</h3>
              <p>Only if the package is being damaged/Incorrect product being sent return is acceptable and the same has to be mentioned to the carrier while accepting the product</p>
              <h3>Damaged Product being received</h3>
              <p>If the product received has the exterior box/cover tampered with, PLEASE DO NOT ACCEPT IT. Instead, take a picture and share it with us while rejecting it, which may help us place a fresh order before the damaged one is returned to us for inquiry. If the product is received in fine condition but the inside product is damaged, we will assist you in managing it; however, if the unboxing video is available, we will assist you in sending the replacement order with no question asked.</p>
                            <h3>What if the status says delivered but the product hasn't arrived?</h3>
<p>If you haven't received your shipment but the status shows Delivered, don't worry; we can assist you in determining who has collected the goods by requesting Proof of Delivery (POD) or Delivery Receipt Sip (DRS Copy) from the Courier Company It will take 4-5 business days for the courier company to respond with the POD or DRS; once received, we will share the same with you via the message section.</p>

  <h3>Note</h3>
  <p>No claim will be entertained, if unboxing of parcel video not shared</p>

    </div>
  </div>
</section>

</div>

@endsection