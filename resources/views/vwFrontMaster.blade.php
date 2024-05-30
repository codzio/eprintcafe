@include('vwHeader')

<div id="wrap"> 
  
  @include('frontend.partials.header')
  
    @yield('content')
  
  @include('frontend.partials.footer')
  
</div>

@include('vwFooter')