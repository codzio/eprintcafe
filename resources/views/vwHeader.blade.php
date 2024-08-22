<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="facebook-domain-verification" content="layzn4ks2zqdivl31x9n44t0hityh8" />
<meta name="csrf-token" content="{{ csrf_token() }}">  
<link rel="shortcut icon" href="{{ getImg(setting('favicon')); }}"/>
<title>{{ setting('website_name') }} | {{ $title }}</title>

<!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
<link rel="stylesheet" type="text/css" href="{{ asset('public/frontend') }}/rs-plugin/css/settings.css" media="screen" />

<!-- Bootstrap Core CSS -->
<link href="{{ asset('public/frontend') }}/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="{{ asset('public/frontend') }}/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="{{ asset('public/frontend') }}/css/ionicons.min.css" rel="stylesheet">
<link href="{{ asset('public/frontend') }}/css/main.css" rel="stylesheet">
<link href="{{ asset('public/frontend') }}/css/style.css?v=4" rel="stylesheet">
<link href="{{ asset('public/frontend') }}/css/responsive.css?v=4" rel="stylesheet">

<!-- JavaScripts -->
<script src="{{ asset('public/frontend') }}/js/modernizr.js"></script>

<!-- Online Fonts -->
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900' rel='stylesheet' type='text/css'>

<script src="{{ asset('public/frontend') }}/js/jquery-1.11.3.min.js"></script>

<style>

 div.ldr{
    border-right: 4px solid var(--primary-color-2);
 }
 div.ldr:before{
  border-left: 3px solid var(--primary-color-3);
 }
 div.ldr:after{
  border-right: 2px solid var(--primary-color-2);
 }
  .home-slider{
    height:80vh;
  }
  .testimonial .owl-dots{
    position:unset;
  }
  .small-about{
    border-top:0;
  }
  .testimonial p{
    width:100%;
    max-width:90%;
    text-align:center;
  }
  .testimonial .testi-in h5{
    text-align:center;
  }
  .testimonial .owl-dots{
    display:flex;
    justify-content:center;
    align-items:center;
  }
  .testimonial .testi-in i{
    display:block;
    text-align:center;
    position:unset;
  }
  .papular-block .item{
    margin-bottom:25px;
  }
</style>

{!! setting('header_scripts') !!}

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-11400170460">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11400170460');
</script>

@if(Request::get("transaction_id"))
  
  <!-- Event snippet for Purchase Order conversion page -->
  <script>
    gtag('event', 'conversion', {
        'send_to': 'AW-11400170460/l72SCOHX-aQZENyXg7wq',
        'value': {{ Request::get("amount") }},
        'currency': 'INR',
        'transaction_id': '{{ Request::get("transaction_id") }}'
    });
  </script>

@endif

@if($menu == 'cart')
  
  <!-- Event snippet for Add to cart conversion page -->
  <script>
    gtag('event', 'conversion', {'send_to': 'AW-11400170460/qdTiCIyc-qQZENyXg7wq'});
  </script>

@endif

</head>
<body>

  {!! setting('body_scripts') !!}

  <marquee style="height: unset; margin-top: 10px; font-size: 15px; font-weight:bold" width="100%%" direction="left" height="100px">For order status and enquiries, call <a style="color:var(--secondary-color-3)" href="tel:8448193391">8448193391</a>. For corporate and bulk enquiries, call <a style="color:var(--secondary-color-3)" href="tel:8448193390">8448193390</a>.</marquee>

<!-- LOADER -->
<div id="loader">
  <div class="position-center-center">
    <div class="ldr"></div>
  </div>
</div>