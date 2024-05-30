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
    <h4>Terms & Conditions</h4>
    <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula. -->
    <!--  Sed feugiat, tellus vel tristique posuere, diam</p>-->
    <ol class="breadcrumb">
      <li><a href="{{ route('homePage') }}">Home</a></li>
      <li class="active">Terms & Conditions</li>
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
      <h4 style="color:var(--secondary-color-3);">Terms & Conditions</h4>

      <p>Welcome to Eprintcafe.com, your digital haven for personalized print solutions. To ensure a seamless experience, it's crucial to familiarize yourself with our Terms and Conditions (T&amp;C). Let's embark on a journey to understand the dynamics of this digital relationship.</p>
                <h3>Acknowledging Acceptance</h3>
                
<p>Before you dive into the myriad offerings on Eprintcafe.com, take a moment to absorb our T&amp;C. Your use of the site signifies your acceptance of these terms. If you disagree, we kindly ask you to refrain from exploring further.</p>
                            <h3>The Eprintcafe.com Landscape</h3>
              <p>Eprintcafe.com, owned by India inttech Pvt. Ltd., holds the right to evolve this digital relationship. We reserve the authority to modify these T&amp;C, shaping the landscape of our interaction. Regular users should periodically check for updates, as continued use implies agreement with any revisions.</p>
              <h3>Violations Have Consequences</h3>
              <p>Let's be clear—violating these T&amp;C comes with consequences. Users found in breach may face access cancellation, and in severe cases, a permanent ban from our digital space. Stay informed, stay compliant.</p>
                            <h3>Protecting Your Digital Footprint: Cookies</h3>
<p>In our digital realm, cookies play a pivotal role. These small encrypted files enhance your shopping experience by keeping track of your cart and saving your password. Rest assured, our use of cookies aligns with safeguarding your privacy. Learn more about our approach in our Privacy Statement.</p>

    </div>
  </div>
</section>

</div>

@endsection