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
      <h4 style="color:var(--secondary-color-3);">Shipping Policy</h4>

      <p>We use DTDC, DELHIVERY, etc for shipping the order. Customers have to Select the Courier Company at the time of placing the order.</p>
              <p> At Eprintcafe.com, We completely understand that once you order online, we address that worry by promising you “hustle-free shipping”. This means that once you have placed the order at our site, we take care of everything so you don't have to worry about anything! </p>
                            <p>Any date quoted for delivery of the work via the website is indicative only. Eprintcafe.com and its delivery partners will do their best to meet time scales however in this agreement time is not of the essence.</p>

<p>Delivery is deemed complete either when posted or, if by courier when it is collected. Enquiries about tracked deliveries can be made online or by emailing info@eprintcafe.com quoting your order number. We have partnered with the most efficient and reliable courier and logistics service providers to ensure you receive your order on time and safely with full courtesy and attention.</p>

<p>Please Note: The delivery timeframe mentioned in product details is estimated. Actual delivery time depends upon the address where the order needs to be delivered, courier issues, and other circumstances that might affect delivery. You can rest assured that you are completely protected as a customer at Eprintcafe.com.</p>

    </div>
  
  </div>
</section>

</div>

@endsection