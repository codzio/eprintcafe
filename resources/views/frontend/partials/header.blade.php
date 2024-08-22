<style type="text/css">

  .mobile {
    display: none !important;
  }

  .hover_mega_menu .tab button:hover {
    background-color: green;
  }

  @media(max-width:575px) {
    .desktop {
      display: none !important;
    }

    .mobile {
      display: block !important;
    }

    .mobile .dropdown-backdrop {
      z-index: -990;
    }

    .tabcontent h3 {
      font-size: 14px;
    }
  }
</style>
<!-- header -->
  <header>
    <div class="sticky">
      <div class="container"> 
        
        <!-- Logo -->
        <div class="logo"> <a href="{{ route('homePage') }}"><img class="img-responsive" src="{{ asset('public/frontend') }}/images/logo.png" alt="" ></a> </div>
        <nav class="navbar ownmenu">
         <div class="mobile_ham_flex">
           <div class="mobile_hamburger">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-open-btn" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"><i class="fa fa-navicon"></i></span> </button>
          </div>
          
         </div>  
         <!-- Nav Right -->
          <div class="nav-right for-mobile">
            <ul class="navbar-right">
            
              @if(customerId())
              <!-- USER INFO -->
              <li class="dropdown user-acc"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" ><i class="icon-user"></i> </a>
                <ul class="dropdown-menu">
                  <li>
                    <h6>HELLO! {{ customerData('name'); }}</h6>
                  </li>
                  <li><a href="{{ route('customerDashboard') }}">DASHBOARD</a></li>
                  <li><a href="{{ route('cartPage') }}">MY CART</a></li>
                  <li><a href="{{ route('customerLogout') }}">LOG OUT</a></li>
                </ul>
              </li>
              @else
              <li class="user-acc"> <a href="{{ route('loginPage') }}" role="button"><i class="icon-user"></i> </a>
              </li>
              @endif
              
              <!-- USER BASKET -->
              <li class="dropdown user-basket"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="icon-basket-loaded"></i> </a>
                @if(cartData())
                <ul class="dropdown-menu">
                  @foreach(cartData() as $cartD)
                  @php
                    $price = 0;

                    if(isset(productSinglePrice($cartD->product_id)->price)) {
                      $price = productSinglePrice($cartD->product_id)->price;
                    }
                  @endphp
                  <li>
                    <div class="media-left">
                      <div class="cart-img"> <a href="#"> <img class="media-object img-responsive" src="{{ getImg($cartD->thumbnail_id) }}" alt="..."> </a> </div>
                    </div>
                    <div class="media-body">
                      <h6 class="media-heading">{{ $cartD->name }}</h6>
                      <span class="price">Price: {{ $price }}</span> <span class="qty">QTY: {{ $cartD->qty }}</span> </div>
                  </li>
                  @endforeach
                  <li>
                    <h5 class="text-center">SUBTOTAL: Rs {{ productPriceMulti()->total }}</h5>
                  </li>
                  <li class="margin-0">
                    <div class="row">
                      <div class="col-xs-6"> <a href="{{ route('cartPage') }}" class="btn">VIEW CART</a></div>
                      <div class="col-xs-6 "> <a href="{{ route('checkoutPage') }}" class="btn">CHECK OUT</a></div>
                    </div>
                  </li>
                </ul>
                @endif
              </li>
              
              <!-- SEARCH BAR -->
              <!-- <li class="dropdown"> <a href="javascript:void(0);" class="search-open"><i class=" icon-magnifier"></i></a>
                <div class="search-inside animated bounceInUp"> <i class="icon-close search-close"></i>
                  <div class="search-overlay"></div>
                  <div class="position-center-center">
                    <div class="search">
                      <form>
                        <input type="search" placeholder="Search Shop">
                        <button type="submit"><i class="icon-check"></i></button>
                      </form>
                    </div>
                  </div>
                </div>
              </li> -->

            </ul>
          </div>
         </div>
          <!-- NAV -->
          <div class="collapse navbar-collapse" id="nav-open-btn">
            <ul class="nav">
              <li class="{{ $menu == 'home'? 'active':''; }}"> 
                <a href="{{ route('homePage') }}" class="dropdown-toggle">Home</a>
              </li>
              
              @if(getProductCatList())
              <li class="dropdown desktop"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">All Products</a>
                <ul class="dropdown-menu hover_mega_menu">
                    <!-- <a href="{{ route('homePage') }}">Index Default</a> -->
                    
                    <div class="tab">
                      @php $i=1; @endphp
                      @foreach(getProductCatList() as $prodCat)
                      @php
                        $catUrl = route('categoryPage', ['slug' => $prodCat->category_slug]);
                      @endphp
                      <button onclick="window.location.href='{{ $catUrl }}'" class="tablinks {{ $i==1? 'active':'' }}" onmouseover="openCity(event, '{{ $prodCat->category_slug }}')">{{ $prodCat->category_name }}</button>
                      @php $i++; @endphp
                      @endforeach

                      <button onmouseover="openCity(event, 'Visiting Cards')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/visiting-cards') }}'">Visiting cards</button>
                      <button onmouseover="openCity(event, 'Business Stationary')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/business-stationary') }}'">Business Stationary</button>
                      <button onmouseover="openCity(event, 'Business Material')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/business-material') }}'">Business Material</button>
                      <button onmouseover="openCity(event, 'Posters')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/posters') }}'">Posters</button>
                      <button onmouseover="openCity(event, 'Sticker Printing Services')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/sticker-printing-services') }}'">Sticker Printing Services</button>
                    </div>

                    @php $t=1; @endphp
                    @foreach(getProductCatList() as $prodCat)
                    <div id="{{ $prodCat->category_slug }}" class="tabcontent" style="{{ $t==1? 'display: block;':'' }}">
                      <h3>{{ $prodCat->category_name }}</h3>
                        
                        <!-- <p>London is the capital city of England.</p> -->
                        @php
                          $getProducts = getProductList($prodCat->id);
                        @endphp

                        @if(!empty($getProducts) && $getProducts->count())
                          @foreach($getProducts as $prod)
                          <li><a href="{{ route('productPage', ['slug' => $prod->slug]) }}">{{ $prod->name }}</a></li>
                          @endforeach
                        @else
                          <li><a href="javascript:void(0)">No Products Available</a></li>
                        @endif

                    </div>
                    @php $t++; @endphp
                    @endforeach

                    <div class="clearfix"></div>
                </ul>
              </li>
              @endif

              @if(getProductCatList())
              <li class="dropdown mobile"> <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">All Products</a>
                <ul class="dropdown-menu hover_mega_menu">
                    <!-- <a href="{{ route('homePage') }}">Index Default</a> -->
                    
                    <div class="tab">
                      @php $i=1; @endphp
                      @foreach(getProductCatList() as $prodCat)
                      @php
                        $catUrl = route('categoryPage', ['slug' => $prodCat->category_slug]);
                      @endphp
                      <button class="tablinks {{ $i==1? 'active':'' }}" onclick="openCityMobile(event, '{{ $prodCat->category_slug }}-mobile')">{{ $prodCat->category_name }}</button>
                      @php $i++; @endphp
                      @endforeach

                      <button onmouseover="openCity(event, 'Visiting Cards')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/visiting-cards') }}'">Visiting cards</button>
                      <button onmouseover="openCity(event, 'Business Stationary')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/business-stationary') }}'">Business Stationary</button>
                      <button onmouseover="openCity(event, 'Business Material')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/business-material') }}'">Business Material</button>
                      <button onmouseover="openCity(event, 'Posters')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/posters') }}'">Posters</button>
                      <button onmouseover="openCity(event, 'Sticker Printing Services')" class="custom-tab tablinks" onclick="window.location.href='{{ url('products/sticker-printing-services') }}'">Sticker Printing Services</button>
                    </div>

                    @php $t=1; @endphp
                    @foreach(getProductCatList() as $prodCat)
                    <div id="{{ $prodCat->category_slug }}-mobile" class="tabcontent" style="{{ $t==1? 'display: block;':'' }}">
                      <h3>{{ $prodCat->category_name }}</h3>
                        
                        <!-- <p>London is the capital city of England.</p> -->
                        @php
                          $getProducts = getProductList($prodCat->id);
                        @endphp

                        @if(!empty($getProducts) && $getProducts->count())
                          @foreach($getProducts as $prod)
                          <li><a href="{{ route('productPage', ['slug' => $prod->slug]) }}">{{ $prod->name }}</a></li>
                          @endforeach
                        @else
                          <li><a href="javascript:void(0)">No Products Available</a></li>
                        @endif

                    </div>
                    @php $t++; @endphp
                    @endforeach

                    <div class="clearfix"></div>
                </ul>
              </li>
              @endif

              <li class="{{ $menu == 'price-calculator'? 'active':''; }}"> <a href="{{ route('priceCalc') }}">Price Calculator </a> </li>

              <li class="{{ $menu == 'about'? 'active':''; }}"> <a href="{{ route('aboutPage') }}">About </a> </li>
              <li class="{{ $menu == 'contact'? 'active':''; }}"> <a href="{{ route('contactPage') }}"> contact</a> </li>

              @if(!customerId())
              <li class="{{ $menu == 'login'? 'active':''; }}"> <a href="{{ route('loginPage') }}"> Login</a> </li>
              <li class="{{ $menu == 'register'? 'active':''; }}"> <a href="{{ route('registerPage') }}"> Register</a> </li>
              @endif

              <li class="{{ $menu == 'track-orders'? 'active':''; }}"> <a href="{{ route('trackOrdersPage') }}">Track Order</a> </li>

            </ul>
          </div>  

          <!-- Nav Right -->
          <div class="nav-right">
            <ul class="navbar-right">
              
              @if(customerId())
              <!-- USER INFO -->
              <li class="dropdown user-acc"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" ><i class="icon-user"></i> </a>
                <ul class="dropdown-menu">
                  <li>
                    <h6>HELLO! {{ customerData('name'); }}</h6>
                  </li>
                  <li><a href="{{ route('customerDashboard') }}">DASHBOARD</a></li>
                  <li><a href="{{ route('cartPage') }}">MY CART</a></li>
                  <li><a href="{{ route('customerLogout') }}">LOG OUT</a></li>
                </ul>
              </li>
              @else
              <!-- <li class="user-acc"> <a href="{{ route('loginPage') }}" role="button"><i class="icon-user"></i> </a>
              </li> -->
              @endif
              
              <!-- USER BASKET -->
              <li class="dropdown user-basket"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="icon-basket-loaded"></i> </a>
                @if(cartData())
                <ul class="dropdown-menu">
                  @foreach(cartData() as $cartD)
                  @php
                    $price = 0;

                    if(isset(productSinglePrice($cartD->product_id)->price)) {
                      $price = productSinglePrice($cartD->product_id)->price;
                    }
                  @endphp
                  <li>
                    <div class="media-left">
                      <div class="cart-img"> <a href="#"> <img class="media-object img-responsive" src="{{ getImg($cartD->thumbnail_id) }}" alt="..."> </a> </div>
                    </div>
                    <div class="media-body">
                      <h6 class="media-heading">{{ $cartD->name }}</h6>
                      <span class="price">Price: {{ $price }}</span> <span class="qty">QTY: {{ $cartD->qty }}</span> </div>
                  </li>
                  @endforeach
                  <li>
                    <h5 class="text-center">SUBTOTAL: Rs {{ productPriceMulti()->total }}</h5>
                  </li>
                  <li class="margin-0">
                    <div class="row">
                      <div class="col-xs-6"> <a href="{{ route('cartPage') }}" class="btn">VIEW CART</a></div>
                      <div class="col-xs-6 "> <a href="{{ route('checkoutPage') }}" class="btn green">CHECK OUT</a></div>
                    </div>
                  </li>
                </ul>
                @endif
              </li>
              
              <!-- SEARCH BAR -->
              <!-- <li class="dropdown"> <a href="javascript:void(0);" class="search-open"><i class=" icon-magnifier"></i></a>
                <div class="search-inside animated bounceInUp"> <i class="icon-close search-close"></i>
                  <div class="search-overlay"></div>
                  <div class="position-center-center">
                    <div class="search">
                      <form>
                        <input type="search" placeholder="Search Shop">
                        <button type="submit"><i class="icon-check"></i></button>
                      </form>
                    </div>
                  </div>
                </div>
              </li> -->
            </ul>
          </div>
          
          
        </nav>
      </div>
    </div>
  </header>