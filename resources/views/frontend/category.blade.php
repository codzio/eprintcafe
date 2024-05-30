@extends('vwFrontMaster')

@section('content')

<style type="text/css">
  
  .sub-bnr{
    min-height:36vh;
    background-position:center;
  }

  @media(max-width:575px) {
    .sub-bnr {
      min-height: 18vh;
      background-size: cover;
      background-position: 35% 100%;
    }

    .sub-bnr h4 {
        font-size: 15px;
    }
  }

  @if($backgroundImage)
    .sub-bnr {
      background-image: url({{ $backgroundImage }})
    }
  @endif
</style>
  
<!--======= SUB BANNER =========-->
<section class="sub-bnr">
  <div class="position-center-center">
    <div class="container">
      <!-- <h4>{{ $category->category_name }}</h4> -->
      <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula. -->
      <!--  Sed feugiat, tellus vel tristique posuere, diam</p>-->
      <!-- <ol class="breadcrumb">
        <li><a href="{{ route('homePage') }}">Home</a></li>
        <li class="active">{{ $category->category_name }}</li>
      </ol> -->
    </div>
  </div>
</section>

<!-- Content -->
<div id="content"> 
  
  <!-- Popular Products -->
  <section class="shop-page padding-top-100 padding-bottom-100">
    <div class="container">
      <div class="item-display">
        <div class="row">
          <div class="col-xs-6"> <span class="product-num">Showing {{ $products->count() }} products</span> </div>
          
          <!-- Products Select -->
          <div class="col-xs-6">
            <div class="pull-right"> 
              
              <!-- Short By -->
              <!-- <select class="selectpicker">
                <option>Short By</option>
                <option>Short By</option>
                <option>Short By</option>
              </select> -->
              <!-- Filter By -->
              <!-- <select class="selectpicker">
                <option>Filter By</option>
                <option>Short By</option>
                <option>Short By</option>
              </select>
               -->
              <!-- GRID & LIST --> 
              <!-- <a href="product-detail_01.html" class="grid-style"><i class="icon-grid"></i></a> <a href="#." class="list-style"><i class="icon-list"></i></a> </div> -->
          </div>
        </div>
      </div>
      
      <!-- Popular Item Slide -->
      @if(!empty($products) && $products->count())
      <div class="papular-block row single-img-demos">
          @foreach($products as $product)
          <div class="col-md-4">
            <div class="item"> 
              <a href="{{ route('productPage', ['slug' => $product->slug]) }}">
                <div class="item-img"> <img class="img-1" src="{{ getImg($product->thumbnail_id); }}" alt=""> 
                  <div class="overlay">
                    <!-- <div class="position-center-center">
                      <div class="inn">
                        <div href="images/new-arrival-img.png" data-lighter=""><i class="icon-magnifier"></i></div>
                        <div href="#."><i class="icon-basket"></i></div>
                        <div href="#."><i class="icon-heart"></i></div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </a>
              <!-- Item Name -->
              <div class="item-name"> <a href="{{ route('productPage', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                <!--<p>Lorem ipsum dolor sit amet</p>-->
              </div>
              <!-- Price --> 
              <!--<span class="price"><small>$</small>299</span> -->
              </div>
          </div>
          @endforeach
      </div>
      @endif
    </div>
  </section>
  
  <!-- About -->
  <!--<section class="small-about padding-top-150 padding-bottom-150">-->
  <!--  <div class="container"> -->
      
      <!-- Main Heading -->
  <!--    <div class="heading text-center">-->
  <!--      <h4>about ecoshop</h4>-->
  <!--      <p>Phasellus lacinia fermentum bibendum. Interdum et malesuada fames ac ante ipsumien lacus, eu posuere odio luctus non. Nulla lacinia,-->
  <!--        eros vel fermentum consectetur, risus purus tempc, et iaculis odio dolor in ex. </p>-->
  <!--    </div>-->
      
      <!-- Social Icons -->
  <!--    <ul class="social_icons">-->
  <!--      <li><a href="#."><i class="icon-social-facebook"></i></a></li>-->
  <!--      <li><a href="#."><i class="icon-social-twitter"></i></a></li>-->
  <!--      <li><a href="#."><i class="icon-social-tumblr"></i></a></li>-->
  <!--      <li><a href="#."><i class="icon-social-youtube"></i></a></li>-->
  <!--      <li><a href="#."><i class="icon-social-dribbble"></i></a></li>-->
  <!--    </ul>-->
  <!--  </div>-->
  <!--</section>-->
  
  <!-- News Letter -->
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

@endsection