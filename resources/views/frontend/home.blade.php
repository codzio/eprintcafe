@extends('vwFrontMaster')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style type="text/css">
  .tp-caption {
    color: var(--secondary-color-3) !important;
  }
  .home_banner_slide{
      position:relative;
  }

  .home_banner_slide_content{
    position:absolute;
    width:100%;
    height:100%;
    top:16%;
    left:5%;
  }
  .home_banner a{
    background:#444;
    padding:10px 20px;
    color:#fff;
    transition:all .5s linear;
    border:1px solid #444;
  }
  
  .home_banner h1{
    margin:3% 0 5%;
    color:#000;
    font-family: sans-serif;
    font-weight:700;
    font-size:60px;
    line-height:1.4;
    width:65%;
  }
  .home_banner p{
    color:#000;
    width:80%;
    font-size:20px;
  }
  
  .arrival-bock .item-img img{
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

  .mbanner-img {
    display: none !important;
  }


  @media (max-width:575px) {
    
    .dbanner-img {
      display: none !important;
    }

    .mbanner-img {
      display: block !important;
      width: 100%;
    }

    .padding-top-100 {
      padding-top: 50px !important;
    }

    .for_card_listing {
      gap: 0px;
    }

    .home_banner_slide img {
/*      object-fit: contain !important;*/
    }

  }
</style>
  <div class="home_banner">
    <div class="home_banner_slider_main home_banner_slider">
      <div class="home_banner_slide">
        <img onclick="bannerRedirect()" class="dbanner-img" src="{{ asset('public/frontend') }}/img/banner.png">
        <img onclick="bannerRedirect()" class="mbanner-img" src="{{ asset('public/frontend') }}/img/mobile-banner.jpg">
        <!-- <div class="home_banner_slide_content">
          <p>Best Printing Company In Delhi</p>
          <h1>Your Ultimate Destination for <br> Hassle-Free Online Printing</h1>
          <a href="#">PRINT NOW</a>
        </div> -->
      </div>
    </div>
  </div>
  
  <!-- Content -->
  <div id="content"> 
    
    @if(!empty($categoryList) && $categoryList->count())
    <!-- New Arrival -->
    <section class="padding-top-100 home_page_card" style="padding-bottom:50px;">
      <div class="container"> 
        
        <!-- Main Heading -->
        <div class="heading text-center">
          <h4>Best Services For Printing</h4>
          <span>Welcome to Eprintcafe.com, An initiative of India Inttech Pvt. Ltd. ( Shyam Electrostat - Since 1990), your dedicated offline convenience printing store !</span> </div>
      </div>
      
      <div class="container">
        <!-- New Arrival -->
      <div class="arrival-bock"> 
        <div class="papular-block row single-img-demos for_card_listing"> 
          @foreach($categoryList as $category)
          <!-- Item --> 
          <div class="col-md-3">
            <div class="item"> 
              <!-- Item img -->
              <a href="{{ route('categoryPage', ['slug' => $category->category_slug]) }}">
                <div class="item-img"> <img class="img-1" src="{{ getImg($category->category_img) }}" alt=""> 
                  <!-- Overlay -->
                  <div class="overlay">
                    <!-- <div class="position-center-center">
                      <div class="inn">
                        <div href="images/categories/documents.png" data-lighter=""><i class="icon-magnifier"></i></div>
                        <div href="#."><i class="icon-basket"></i></div>
                        <div href="#."><i class="icon-heart"></i></div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </a>
              <!-- Item Name -->
              <div class="item-name"> <a href="{{ route('categoryPage', ['slug' => $category->category_slug]) }}">{{ $category->category_name; }}</a>
                <!--<p>Lorem ipsum dolor sit amet</p>-->
              </div>
              <!-- Price --> 
              <!-- <span class="price"><small>$</small>299</span>  -->
            </div>
          </div>
          @endforeach;
          
           
        </div>
      </div>
    </section>
    @endif
    
    @if(!empty($popularProds) && $popularProds->count())
    <section class="padding-top-50 home_page_card" style="padding-bottom:50px;">
      <div class="container"> 
        
        <!-- Main Heading -->
        <div class="heading text-center">
          <h4>Popular Products</h4>
          <!--<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula. -->
          <!--Sed feugiat, tellus vel tristique posuere, diam</span> </div>-->
        
        <!-- Popular Item Slide -->
        <div class="papular-block block-slide single-img-demos">
          
          @foreach($popularProds as $popProd)
          <div class="item"> 
            <!-- Item img -->
            <a href="{{ route('productPage', ['slug' => $popProd->slug]) }}">
                <div class="item-img"> <img class="img-1" src="{{ getImg($popProd->thumbnail_id) }}" alt=""> 
                  <!-- Overlay -->
                  <div class="overlay">
                    <!-- <div class="position-center-center">
                      <div class="inn">
                        <div href="images/categories/documents.png" data-lighter=""><i class="icon-magnifier"></i></div>
                        <div href="#."><i class="icon-basket"></i></div>
                        <div href="#."><i class="icon-heart"></i></div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </a>
            <!-- Item Name -->
            <div class="item-name"> <a href="{{ route('productPage', ['slug' => $popProd->slug]) }}">{{ $popProd->name }}</a>
              <!--<p>Lorem ipsum dolor sit amet</p>-->
            </div>
            <!-- Price --> 
            <!-- <span class="price"><small>$</small>299</span>  -->
          </div>
          @endforeach

        </div>
      </div>
    </section>
    @endif
    
    <!-- Knowledge Share -->
    <section class="light-gray-bg padding-top-50" style="padding-bottom:50px;">
      <div class="container"> 
        
        <!-- Main Heading -->
        <div class="heading home_about_compnay text-center">
          <h4 style="color:var(--secondary-color-3);">About Company</h4>
          <h2 style="margin:auto; width:72%;">Your Ultimate Destination For Hassle-Free Online Printing</h2>
          <div class="more_than_year">
            <h4>We Have More than <strong style="color:var(--secondary-color-3);">33</strong> Years Of Experience in Printing Services</h4>
          </div>
          <span style="width:100%; margin-bottom:35px;">Welcome to Eprintcafe.com, An initiative of India Inttech Pvt. Ltd. ( Shyam Electrostat - Since 1990), your dedicated offline convenience printing store ! We understand the value of your time and energy, which is why our platform is designed to provide you with easy access to high-quality online printing services. Say goodbye to the hassles of traditional printing – we're here to redefine your printing experience.
          </span> 
          <a href="{{ route('aboutPage'); }}" class="theme-btn mt-20 home_btn">Know More <i class="lnr lnr-arrow-right"></i></a>
        </div>
      </div>
    </section>

    <!-- home cards sec -->

    <section class="light-gray-bg padding-top-50" style="padding-bottom:50px;">
      <div class="container"> 
        
        <!-- Main Heading -->
        <div class="heading home_about_compnay text-center">
          <h4 style="color:var(--secondary-color-3)">Company Statistics</h4>
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
    <!-- <section class="testimonial" style="margin-top:40px;">
      <div class="container">
        <div class="row">
          <div class="col-12"> 
            <div class="single-slide"> 
              <div class="testi-in"> <i class="fa fa-quote-left"></i>
                <p>Great work, great communication and very flexible.</p>
                <h5>Ankit</h5>
              </div>
              <div class="testi-in"> <i class="fa fa-quote-left"></i>
                <p>Excellent team, will be working again for sure.</p>
                <h5>Suman</h5>
              </div>
              <div class="testi-in"> <i class="fa fa-quote-left"></i>
                <p>They always go extra mile to achieve the results.</p>
                <h5>Faizan</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    
    <!-- About -->
    <section class="small-about" style="padding-bottom:0px;">
      <div class="container"> 
        
        <!-- Main Heading -->
        <div class="headin-old text-center">
          <!-- <h4>about ecoshop</h4> -->
          <!-- <p>Phasellus lacinia fermentum bibendum. Interdum et malesuada fames ac ante ipsumien lacus, eu posuere odio luctus non. Nulla lacinia, -->
            <!-- eros vel fermentum consectetur, risus purus tempc, et iaculis odio dolor in ex. </p> -->
        </div>
        
        <!-- Social Icons -->
        <!--<ul class="social_icons">-->
        <!--  <li><a href="#."><i class="icon-social-facebook"></i></a></li>-->
        <!--  <li><a href="#."><i class="icon-social-twitter"></i></a></li>-->
        <!--  <li><a href="#."><i class="icon-social-tumblr"></i></a></li>-->
        <!--  <li><a href="#."><i class="icon-social-youtube"></i></a></li>-->
        <!--  <li><a href="#."><i class="icon-social-dribbble"></i></a></li>-->
        <!--</ul>-->
      </div>
    </section>
    <!--<section class="news-letter padding-top-150 padding-bottom-150">-->
    <!--  <div class="container">-->
    <!--    <div class="heading light-head text-center margin-bottom-30">-->
    <!--      <h4>NEWSLETTER</h4>-->
    <!--      <span>Phasellus lacinia fermentum bibendum. Interdum et malesuada fames ac ante ipsumien lacus, eu posuere odi </span> </div>-->
    <!--    <form>-->
    <!--      <input type="email" placeholder="Enter your email address" required>-->
    <!--      <button type="submit">SEND ME</button>-->
    <!--    </form>-->
    <!--  </div>-->
    <!--</section>-->
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script type="text/javascript">
    $(".home_banner_slider").slick({
      autoplay:true,
      autoplaySpeed:3000,
      arrows:false
    });

    function bannerRedirect() {
      window.location.href = "{{ url('category/document-printing'); }}"
    }

  </script>

@endsection