@extends('vwFrontMaster')

@section('content')

<style type="text/css">

/*popup css start */
  .btns-row {
    display: flex;
    column-gap: 14px;
    justify-content: center;
    align-items: center;
  }
/*popup css end */

  #content {
    z-index: unset;
  }
  .cart-head {
      display: grid;
      /* grid-template-columns: 1fr 1fr 1fr 1fr; */
      grid-template-columns: 29% 16% 30% 1fr;
  }

  .detail_card_row .cart-ship-info {
    height: fit-content;
    padding-bottom: 5%;
  }
  .main-content-div {
    margin-left:-8px;
  }
  .main-content-div p{
    font-size:12px;
  }
  .main-content-div span{
    font-size:12px;
  }

  .media3 input {
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #212529;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #ced4da;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      border-radius: 0.25rem;
      transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  }

  .hidden {
      display: none !important;
  }

  main {
      text-align: center;
  }

  .buttons {
      /*display: flex;
      flex-flow: row wrap;
      justify-content: center;
      margin: 3rem 0;
      gap: 1rem 2rem;*/
  }
  .buttons button {
      border: none;
      height: 3.6rem;
      border-radius: 1.8rem;
      font-family: inherit;
      font-size: inherit;
      color: #FFF;
      padding: 0 2rem;
      background-color: rgb(134, 185, 216);
      cursor: pointer;
  }
  .buttons button:hover {
      background-color: rgb(112, 163, 194);
  }
  .buttons button.btn-cookie {
      background-color: rgb(234, 194, 145);
  }
  .buttons button.btn-cookie:hover {
      background-color: rgb(198, 156, 106);
  }
  .buttons button.btn-exclamation {
      background-color: rgb(226, 119, 84);
  }
  .buttons button.btn-exclamation:hover {
      background-color: rgb(180, 89, 59);
  }
  .buttons button.btn-medal {
      background-color: rgb(91, 24, 24);
  }
  .buttons button.btn-medal:hover {
      background-color: rgb(70, 14, 14);
  }


  /* POPUPS */
  .popup {
      position: fixed;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,.5);
      display: grid;
      place-items: center;
      transition: opacity .2s;
  }
  .popup-content {
      position: relative;
      width: 62rem;
      max-width: 95vw;
/*      min-height: 40rem;*/
      border-radius: .5rem;
      background-color: #FFF;
      box-shadow: 0 0 3rem rgba(0,0,0,.2);
      animation: popup .2s linear forwards;
  }
  .popup-content-hide {
      animation: hide .2s linear forwards !important;
  }
  .popup-header {
      min-height: 10.5rem;
      display: grid;
      place-items: center;
      margin-bottom: 6rem;
  }
  .popup-text {
      margin: 7rem 4rem;
      text-align: center;
      color: #999;
      padding:0 18px;
  }
  .popup-text h1 {
      /*      font-size: 2.5rem;*/
      font-size: 1.8rem;
      color: rgb(140, 165, 183);
      margin-bottom: 2rem;
  }
  .popup-text p {
/*      margin-bottom: 2rem;*/
    margin-bottom: 1.5rem;
  }
  .popup-text button {
      width: 100%;
      border: none;
      height: 3.6rem;
      margin-top: 2rem;
      border-radius: 1.8rem;
      font-family: inherit;
      font-size: inherit;
      color: #FFF;
      background-color: rgb(134, 185, 216);
      cursor: pointer;
  }
  .popup-text button:hover {
      background-color: rgb(112, 163, 194);
  }
  .popup-text button.btn-grey {
    /*      background-color: rgb(203, 203, 203);*/
    background-color: green;
  }
  .popup-text button.btn-grey:hover {
    /*      background-color: rgb(178, 178, 178);*/
    background-color: black;

  }
  .popup-close {
      position: absolute;
      top: 1rem;
      right: 2rem;
      font-size: 4rem;
      color: #999;
      cursor: pointer;
  }

  /* COOKIE POPUP */
  .popup-cookie .cookie {
      transform: rotate(40deg) translateY(-1rem);
  }
  .popup-cookie h1 {
    /*      color: rgb(234, 194, 145);*/
    color: black;
  }
  .popup-cookie button {
      background-color: black;
      width: auto;
      padding: 10px 28px;
      line-height: 0;
      border-radius: 5px;
      height: 52px;
  }
  .popup-cookie button:hover {
      background-color: green;
  }

  /* EXCLAMATION POPUP */
  .popup-exclamation .exclamation {
      transform: rotate(-10deg) translateY(-2rem);
  }
  .popup-exclamation h1 {
      color: rgb(226, 119, 84);
  }
  .popup-exclamation button {
      background-color: rgb(226, 119, 84);
  }
  .popup-exclamation button:hover {
      background-color: rgb(180, 89, 59);
  }

  /* MEDAL POPUP */
  .popup-medal .medal {
      transform: rotate(5deg) translateY(-2rem);
  }
  .popup-medal h1 {
      color: rgb(91, 24, 24);
  }
  .popup-medal button {
      background-color: rgb(91, 24, 24);
  }
  .popup-medal button:hover {
      background-color: rgb(70, 14, 14);
  }


  /* ENVELOPE */
  .mail {
      position: relative;
      transform: rotate(-10deg);
      width: 15rem;
  } 

  .envelope {
      position: relative;
      background: transparent;
      width: 15rem;
      height: 10rem;
      border-radius: 0 0 1rem 1rem;
      overflow: hidden;
      z-index: 5;
      box-shadow: 1rem 1rem rgba(25, 40, 43, 0.4);
  }
  .envelope-back {
      position: absolute;
      bottom: 0;
      background-color: rgb(134, 185, 216);
      width: 15rem;
      height: 10rem;
      border-radius: 0 0 1rem 1rem;
      overflow: hidden;
      z-index: 1;
  }
  .envelope::after {
      position: absolute;
      content: '';
      width: 100%;
      height: 100%;
      border-left: 1rem solid rgba(219, 235, 245, 0.576);
      border-top: 1rem solid transparent;
  }
  .envelope-left, .envelope-right {
      position: absolute;
      left: 0;
      top: 0;
      background-color: rgb(178, 214, 236);
      width: 10rem;
      height: 10rem;
      transform-origin: top left;
      transform: rotate(45deg);
      box-shadow: -.5rem -.5rem 0 rgba(127, 163, 186, 0.5);
  }
  .envelope-right {
      left: auto;
      right: 0;
      transform-origin: top right;
      transform: rotate(-45deg);
      background-color: rgb(178, 214, 236);
  }
  .envelope-bottom {
      position: absolute;
      left: 50%;
      bottom: -55%;
      transform: translateX(-50%) rotate(45deg);
      background-color: rgb(178, 214, 236);
      border-radius: 1rem;
      width: 10rem;
      height: 10rem;
      box-shadow: -.5rem -.5rem 0 rgba(127, 163, 186, .5);
  }
  .envelope-top {
      position: absolute;
      left: 50%;
      bottom: 0;
      transform: translateX(-50%) translateY(-50%) rotate(45deg);
      background-color: rgb(134, 185, 216);
      border-radius: 1rem 0 0 0;
      width: 10rem;
      height: 10rem;
      outline: .3rem solid rgb(134, 185, 216);
      z-index: 1;
  }

  .letter {
      position: absolute;
      bottom: 4rem;
      left: 50%;
      transform: translateX(-50%);
      width: 11rem;
      height: 10rem;
      background-color: #FFF;
      z-index: 2;
      border: .5rem solid rgba(127, 163, 186, 0.5);
      display: grid;
      place-items: center;
  }
  .face {
      position: relative;
      transform: translateY(-1rem);
      width: 6rem;
      height: 2rem;
  }
  .eye-left, .eye-right {
      position: absolute;
      top: 0;
      left: 0;
      width: 1rem;
      height: 1rem;
      border-radius: 50%;
      background-color: rgb(110, 133, 148);
  }
  .eye-right {
      right: 0;
      left: auto;
  }
  .mouth {
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 1.5rem;
      height: 2rem;
      border-radius: 50%;
      background: linear-gradient(to top, rgb(110, 133, 148),  rgb(110, 133, 148) 50%, rgba(0,0,0,0) 50%, rgba(0,0,0,0));
  }

  /* COOKIE */
  .cookie {
      position: relative;
  }
  .cookie-body {
      position: relative;
      width: 15rem;
      height: 15rem;
      border-radius: 50%;
      background-color: rgb(234, 194, 145);
      overflow: hidden;
      box-shadow: .5rem .5rem 0 rgba(0,0,0,.2);
  }
  .cookie-bite {
      content: '';
      position: absolute;
      height: 4rem;
      width: 4rem;
      border-radius: 50%;
      background-color: #FFF;
      right: 2rem;
      top: .8rem;
      box-shadow: 3rem .5rem 0 #FFF,
          -2rem -2rem 0 #FFF;
  }

  .cookie-body .face {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-20deg);
  }
  .cookie-body .mouth { background: linear-gradient(to top, rgb(55, 38, 18),  rgb(55, 38, 18) 50%, rgba(0,0,0,0) 50%, rgba(0,0,0,0)); }
  .cookie-body .eye-left, .cookie-body .eye-right { background-color: rgb(55, 38, 18); }

  .cookie-body::before {
      content: '';
      position: absolute;
      height: 110%;
      width: 110%;
      border-radius: 50%;
      border: 1rem solid rgb(255, 216, 168);
      transform: translate(-.4rem, -.2rem)
  }
  .cookie-body::after {
      content: '';
      position: absolute;
      height: 110%;
      width: 110%;
      bottom: -.4rem;
      right: -.4rem;
      border-radius: 50%;
      border: .8rem solid rgb(183, 144, 114);
      transform: translate(-.3rem, -.2rem)
  }

  .chocolate-chip {
      background: rgb(179, 133, 98);
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      position: absolute;
      top: 3.5rem;
      left: 3.5rem;
      box-shadow: 6rem 6rem 0 -.2rem rgb(179, 133, 98),
          4rem 8rem 0 .1rem rgb(179, 133, 98),
          8rem 2rem 0 -.4rem rgb(179, 133, 98),
          -1rem 6rem 0 -.2rem rgb(179, 133, 98);
  }

  /* EXCLAMATION MARK */
  .exclamation {
      position: relative;
      min-height: 17rem;
  }
  .exclamation-main {
      position: relative;
      clip-path: polygon(0 0, 100% 0, 90% 100%, 10% 100%);
      border-radius: 1rem;
      width: 7rem;
      height: 10rem;
      background-color: rgb(226, 119, 84);
  }
  .exclamation-shadow {
      position: absolute;
      top: 1rem;
      left: 1rem;
      clip-path: polygon(0 0, 100% 0, 90% 100%, 10% 100%);
      border-radius: 1rem;
      width: 7rem;
      height: 10rem;
      background-color: rgba(0,0,0,.2);
  }
  .exclamation-dot {
      position: absolute;
      transform: translateX(-50%);
      left: 50%;
      bottom: 0;
      width: 5rem;
      height: 5rem;
      border-radius: 50%;
      margin-top: 2rem;
      background-color: rgb(226, 119, 84);
      box-shadow: 1rem 1rem rgba(0,0,0,.2);
  }

  .exclamation .face {
      position: absolute;
      top: 20%;
      left: 50%;
      transform: translate(-50%, -50%);
  }
  .exclamation .mouth { background: linear-gradient(to top, rgb(55, 38, 18),  rgb(55, 38, 18) 50%, rgba(0,0,0,0) 50%, rgba(0,0,0,0)); }
  .exclamation .eye-left, .exclamation .eye-right { background-color: rgb(55, 38, 18); }

  /* MEDAL */
  .medal {
      position: relative;
      height: 15rem;
  }
  .medal-body {
      position: relative;
      width: 12rem;
      height: 12rem;
      border-radius: 50%;
      background-color: rgb(255, 230, 0);
      border: 2rem solid rgb(236, 185, 0);
      overflow: hidden;
      box-shadow: .5rem .5rem 0 rgba(0,0,0,.2);
  }
  .medal-body::after {
      content: '';
      position: absolute;
      width: 10rem;
      height: 10rem;
      border: 1rem solid rgb(255, 244, 144);
      top: -.5rem;
      left: -.5rem;
      border-radius: 50%;
  }
  .medal-ribbon {
      position: absolute;
      bottom: -6rem;
      left: 50%;
      width: 9rem;
      height: 10rem;
      clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);
      background: linear-gradient(to right, rgb(91, 24, 24) 50%, rgb(177, 52, 52) 50%);
      transform: translate(-50%, -50%);
      z-index: -1;
  }
  .medal-ribbon-shadow {
      position: absolute;
      bottom: -6rem;
      left: 50%;
      width: 9rem;
      height: 10rem;
      clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);
      background: rgba(0,0,0,.2);
      transform: translate(-50%, -50%) translate(.5rem, .5rem);
      z-index: -1;
  }

  .medal .face {
      position: absolute;
      top: 45%;
      left: 50%;
      transform: translate(-50%, -50%);
  }
  .medal .mouth { background: linear-gradient(to top, rgb(114, 85, 13),  rgb(114, 85, 13) 50%, rgba(0,0,0,0) 50%, rgba(0,0,0,0)); }
  .medal .eye-left, .medal .eye-right { background-color: rgb(114, 85, 13); }


  @keyframes hide {
      0% { transform: scale(1); opacity: 1; }
      100% { transform: scale(0); opacity: 0; }
  }
  @keyframes popup {
      0% { transform: scale(0); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
  }
  @media (max-width:575px){
    .main-content-div {
      margin-left: 0;
    }
  }
</style>

<!-- <section class="sub-bnr" data-stellar-background-ratio="0.5">
  <div class="position-center-center">
    <div class="container">
      <h4>SHOPPING CART</h4>
      <ol class="breadcrumb">
        <li><a href="{{ route('homePage') }}">Home</a></li>
        <li class="active">SHOPPING CART</li>
      </ol>
    </div>
  </div>
</section>
 -->
<!-- Content -->
<div id="content"> 
  
  <div id="popupContainer" style="position: fixed; top: 0; left: 0; z-index: 9999;">
  </div>

  <!-- Popup End -->
  
  <!--======= PAGES INNER =========-->
  <section class="padding-top-100 padding-bottom-100 pages-in chart-page">
    <div class="container"> 
      
      <!-- Payments Steps -->
      <div class="shopping-cart text-center">
        <div class="cart-head">
          <h6>PRODUCTS</h6>
              <h6>PRICE</h6>
              <h6>No Of Pages/Copies</h6>
              <h6>TOTAL</h6>
        </div>
        
          <div class="detail_card_row  shopping-cart small-cart">
              <div class="cart-items">
                <form id="cartForm" method="post">
                  @foreach($cartData as $cart)
                  @php
                    $price = 0;
                    $productId = $cart->product_id;

                    if(isset(productSinglePrice($productId)->price)) {
                      $price = productSinglePrice($productId)->price;
                    }
                  @endphp

                  <div class="row cart-details">



                    <div class="media media1"> 
                      <!-- Media Image -->
                        <div class="card_detail">
                          <h5>{{ $cart->name }}</h5>
                          <!-- <p><strong>Document Link:</strong>{{ $cart->document_link }}</p> -->
                        </div>
                      </div>
                    
                    <!-- PRICE -->
                        <div class="media2"> <span class="price">{{ $price }}</span> </div>
                    
                    <!-- QTY -->
                      <div class="media3">
                          <input min="1" id="qty" type="number" style="width:95px; text-align: center;" name="qty[{{ $cart->id }}]" value="{{ $cart->qty }}" placeholder="No of Pages">
                          <input min="1" id="noOfCopies" type="number" style="width:95px; text-align: center;" name="noOfCopies[{{ $cart->id }}]" value="{{ $cart->no_of_copies }}" placeholder="No of Copies">
                      </div>            
                    <!-- TOTAL PRICE -->
                        <div class="media4"> 
                          <!-- <span class="price">{{ (($price*$cart->qty)*$cart->no_of_copies) }}</span> -->
                          <span class="price">{{ productSinglePrice($productId)->total }}</span>
                        </div>
                    
                    <!-- REMOVE -->
                    <div class="media5"> <a class="remove-cart-item" data-id="{{ $cart->id }}" href="javascript:void(0)"><i class="icon-close"></i></a> </div>
                  </div>
                  <div class="main-content-div">
                    <div id="cart-item-{{$cart->id}}" class="product-desc" style="display:none">
                      {!! productSpec($cart->id) !!}
                      <p><strong>Binding:</strong> {{ productSinglePrice($productId)->binding }}</p>

                      @if(productSinglePrice($productId)->split)
                        <p><strong>Split:</strong> {{ productSinglePrice($productId)->split }}</p>
                      @endif

                      <p><strong>Lamination:</strong> {{ productSinglePrice($productId)->lamination }}</p>
                      <p><strong>Cover:</strong> {{ productSinglePrice($productId)->cover }}</p>
                    </div>
                    <span onclick="toggleDetail(this, '{{ $cart->id }}')" id="view-detail-{{$cart->id}}" style="cursor: pointer;">View Details</span>
                  </div>
                  @endforeach
                </form>
              </div>
            
            <div class="cart-ship-info">
              <div class="rows"> 
                
                <!-- DISCOUNT CODE -->
                <!-- <div class="col-12">
                  <h6>DISCOUNT CODE</h6>
                  <form id="couponCodeForm" method="post" action="{{ route('applyPromo') }}">
                    <input id="couponCode" name="couponCode" type="text" value="" placeholder="ENTER YOUR CODE IF YOU HAVE ONE">
                    <button id="couponCodeFormBtn" type="submit" class="btn btn-small btn-dark">APPLY CODE</button>
                    <p id="couponCodeErr" class="removeErr"></p>
                  </form>
                </div> -->
                
                <!-- SUB TOTAL -->
                <div class="col-12">
                  <h6>grand total</h6>
                  <div class="grand-total">
                    <div class="order-detail">
                      <p>Weight <span id="totalWeight">{{ cartWeightMulti() }}</span></p>
                      <!-- <p>Binding <span>{{ productPrice()->binding }}</span></p>
                      <p>Lamination <span>{{ productPrice()->lamination }}</span></p>
                      <p>Cover <span>{{ productPrice()->cover }}</span></p> -->
                      <p>Discount <span id="totalDiscount">0</span></p>
                      <!-- <p>Shipping <span>0</span></p> -->
                      <p class="all-total">TOTAL COST <span id="totalCost"> {{ productPriceMulti()->total }}</span></p>
                    </div>
                  </div>
                </div>
                <div class="coupn-btn"> 
                    <a id="updatecart" href="javascript:void(0)" class="btn">Update Cart</a> 
                    
                    <!-- <a href="{{ route('checkoutPage') }}"  style="background:#49c93e;"  class="btn" style="background: var(--secondary-color-3); color: #fff!important;">Go To Checkout</a>                    -->

                    <button style="background:#49c93e;" style="background: var(--secondary-color-3); color: #fff!important;" class="btn buttons btn-cookie popup-btn" data-popup="cookie">
                      Go to Checkout
                    </button>

                  </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </section>
  
  <!--======= PAGES INNER =========-->
  
</div>

<script type="text/javascript">

  function toggleDetail(el, id) {
    $("#cart-item-"+id).slideToggle().show();
    if ($(el).text() == "View Details") {
      $(el).text("Hide Details")
    } else {
      $(el).text("View Details")
    }
  }

  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.remove-cart-item').click(function (e) {
      cartId = $(this).attr('data-id');

      $.ajax({
        url: '{{ route("removeCartItem") }}',
        type: 'POST',
        dataType: 'json',
        data: {cartId: cartId},
        success: function(res) {
          location.reload();
        }
      })
      
    });

    $("#updatecart").click(function (e) {
      
      // qty = $("#qty").val();
      // noOfCopies = $("#noOfCopies").val();

      formData = $("#cartForm").serialize();

      $.ajax({
        url: '{{ route("updateCartItem") }}',
        type: 'POST',
        dataType: 'json',
        // data: {qty: qty, noOfCopies:noOfCopies},
        data: formData,
        beforeSend: function() {
          $("#updatecart").html('Updating...')
        },
        success: function(res) {
          $("#updatecart").html('Update Cart');
          location.reload();
        }
      })
      

    });

    $("#couponCodeForm").submit(function(event) {
      event.preventDefault();
      
      formData = $(this).serialize();
      url = $(this).attr('action');

      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#couponCodeFormBtn").html('Please Wait...');
          $("#couponCodeErr").html('');
        }, success: function(res) {

          if (res.error == true) {
            if (res.eType == 'field') {
              $.each(res.errors, function(index, val) {
                 $("#couponCodeErr").html(val).css('color', 'red');
              });
            } else {
              $("#couponCodeErr").html(res.msg).css('color', 'red');
            }
          } else {
            $("#totalDiscount").html(res.discount);
            $("#totalCost").html(res.grandTotal);
            $("#couponCodeErr").html(res.msg).css('color', 'green');
          }

          $("#couponCodeFormBtn").html('Apply Code');
        } 
      })

    });


  });

  'use strict';

  const illustrations = {
    cookie: {
        heading: 'Would you like to print more products?',
        button1: 'Add More Products',
        button2: 'Go to Checkout',
        img: `
            <div class="cookie">
                <div class="cookie-body">
                    <div class="chocolate-chip"></div>
                    <div class="face">
                        <div class="eye-left"></div>
                        <div class="eye-right"></div>
                        <div class="mouth"></div>
                    </div>
                </div>
                <div class="cookie-bite"></div>
            </div>
    `}
  }

  const btnContainer = document.querySelector('.buttons');
  const popupContainer = document.querySelector('#popupContainer');

  btnContainer.addEventListener('click', function(e) {
      const btn = e.target.closest('.popup-btn');
      if(!btn) return;
      const type = btn.dataset.popup;
      createPopup(type);
  });

  popupContainer.addEventListener('click', function(e) {
      if(!e.target.closest('button') && !e.target.closest('.popup-close') && !e.target.classList.contains('popup')) return;
      hidePopup();
  })

  function createPopup(type = 'mail') {
      const html = `
          <div class="popup popup-${type}">
              <div class="popup-content">
                  <div class="popup-close">&times;</div>
                  <!--<div class="popup-header">
                      ${illustrations[type].img}
                  </div>-->
                  <div class="popup-text">
                      <h1>${illustrations[type].heading}</h1>
                      <!-- <p>
                          Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quia enim repudiandae amet vero nobis labore exercitationem provident! Quibusdam, nisi odit quos, natus expedita obcaecati quas quod recusandae adipisci temporibus atque?
                      </p> -->
                      <div class="btns-row">
                        <button onclick="window.location.href='{{ url('category/document-printing'); }}'">${illustrations[type].button1}</button>
                        <button class="btn-grey" onclick="window.location.href='{{ route('checkoutPage') }}'">${illustrations[type].button2}</button>
                      </div>
                  </div>
              </div>
          </div>
      `;

      popupContainer.insertAdjacentHTML('afterbegin', html);
  }

  function hidePopup() {
      const popup = document.querySelector('.popup');
      const popupContent = popup.querySelector('.popup-content');
      
      popupContent.style.animation = 'hide .2s linear forwards';
      popupContent.addEventListener('animationend', function() {
          popupContainer.innerHTML = '';
      });
  }

  //createPopup('mail');
</script>

@endsection