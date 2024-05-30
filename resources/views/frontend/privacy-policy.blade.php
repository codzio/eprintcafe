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
    <h4>Privacy Policy</h4>
    <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula. -->
    <!--  Sed feugiat, tellus vel tristique posuere, diam</p>-->
    <ol class="breadcrumb">
      <li><a href="{{ route('homePage') }}">Home</a></li>
      <li class="active">Privacy Policy</li>
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
      <h4 style="color:var(--secondary-color-3);">Privacy Policy</h4>

      <p>Protecting your online privacy is important to us, not just from a business perspective, but from an ethical one as well. We therefore are proud to share with you our honest, open and 100% understandable privacy and security policy. We do not sell our database of information about you. That is NOT our revenue model. </p>
              <p>Your privacy isn't just a checkbox; it's a community principle we uphold earnestly. We neither sell nor rent your personal information for third-party marketing without explicit consent. Our revenue model isn't built on exploiting your data—it's built on trust.</p>
                            <h3>Fortified Security Measures</h3>
<p>Rest assured, your information is in safe hands. We house and process your data in servers fortified with both physical and technological security measures. To validate our privacy principles, we engage third-party entities for verification and certification.</p>
                        </div>
            <h3>Your Information Matters</h3>
            <p>Your information is a pivotal asset, and we treat it as such. Whether it's the details you provide during a purchase or when signing up for our newsletter, we handle your personal information responsibly. It's used internally for order processing and to keep you informed.</p>
            <h3>No Trading, No Sharing</h3>
            <p>Under no circumstances do we trade, rent, or share your Personal Information without your explicit consent. Legal requests aside, your name and address remain confidential. We may collect general demographic data, but it's never linked to your personal identity.</p>
            <h3>Managing Your Preferences</h3>
            <p>If your inbox needs a breather from our emails or you want information removed, it's as simple as dropping an email to care@eprintcafe.com. Your preferences matter, and we respect your choices.</p>
            <h3>Cookies: Your Digital Trail</h3>
            <p>We use cookies and technologies like pixel tags and clear gifs strategically. These tools help us tailor your web experience, ensuring you see what's relevant to your professional needs. While cookies enhance your interaction, the choice to accept them rests with you.</p>
            <h3>Third-Party Advertisements</h3>
            <p>To serve ads across the Internet, we employ third-party service providers. Rest assured, it's anonymous information they collect—no personally identifiable details. Your privacy remains intact during this standard industry process.</p>
            <h3>Empowering You: Requesting Data Erasure</h3>
            <p>You have the right to control your data. If you wish to delete or remove Personal Data, the process is simple:</p>
            <p>A) Send an email request to care@eprintcafe.com  from your registered email id.</p>

    </div>
  </div>
</section>

</div>

@endsection