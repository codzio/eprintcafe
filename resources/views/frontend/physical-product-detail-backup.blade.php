@extends('vwFrontMaster')



@section('content')

<style>
  .qty-input {
	 color: #000;
	 background: #fff;
	 display: flex;
	 align-items: center;
	 overflow: hidden;
}
 .qty-input .product-qty, .qty-input .qty-count {
	 background: transparent;
	 color: inherit;
	 font-weight: bold;
	 font-size: inherit;
	 border: none;
	 display: inline-block;
	 min-width: 0;
	 height: 2.5rem;
	 line-height: 1;
}
 .qty-input .product-qty:focus, .qty-input .qty-count:focus {
	 outline: none;
}
 .qty-input .product-qty {
	 width: 50px;
	 min-width: 0;
	 display: inline-block;
	 text-align: center;
	 appearance: textfield;
}
 .qty-input .product-qty::-webkit-outer-spin-button, .qty-input .product-qty::-webkit-inner-spin-button {
	 appearance: none;
	 margin: 0;
}
 .qty-input .qty-count {
	 padding: 0;
	 cursor: pointer;
	 width: 2.5rem;
	 font-size: 1.25em;
	 text-indent: -100px;
	 overflow: hidden;
	 position: relative;
}
 .qty-input .qty-count:before, .qty-input .qty-count:after {
	 content: "";
	 height: 2px;
	 width: 10px;
	 position: absolute;
	 display: block;
	 background: #000;
	 top: 0;
	 bottom: 0;
	 left: 0;
	 right: 0;
	 margin: auto;
}
 .qty-input .qty-count--minus {
	 border-right: 1px solid #e2e2e2;
}
 .qty-input .qty-count--add {
	 border-left: 1px solid #e2e2e2;
}
 .qty-input .qty-count--add:after {
	 transform: rotate(90deg);
}
 .qty-input .qty-count:disabled {
	 color: #ccc;
	 background: #f2f2f2;
	 cursor: not-allowed;
	 border-color: transparent;
}
 .qty-input .qty-count:disabled:before, .qty-input .qty-count:disabled:after {
	 background: #ccc;
}
 .qty-input {
	 border-radius: 4px;
	 box-shadow: 0 1em 2em -0.9em rgba(0, 0, 0, 0.7);
	 transform: scale(1.5);
}
</style>

<style type="text/css">
  section{
    background-size:cover !important;
  }
  .ng-scope h1{
    color:#fff;
    font-weight:500;
  }
  .ng-scope{
    text-align:center;
  }
  .banner_btn a{
    display: inline-block;
    position: relative;
    cursor: pointer;
    outline: 0;
    white-space: nowrap;
    margin: 3px 3px;
    padding: 0 22px;
    font-size: 14px;
    height: 40px;
    line-height: 40px;
    background-color: #002e6e;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: none;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
  }
  .ng-scope span{
    color:#fff;
    font-size:22px;
    margin-bottom:10px;
    display:block;
  }
  .price-desktop .home_btn {
    padding: 10px 16px;
  }

  #addToCartFormBtn {
    margin-top: 15px;
    float: right;
    background-color: var(--green-color);
    color: black;
    width: fit-content;
    border:1px solid var(--green-color);
    display: flex;
    column-gap: 16px;
  }

  .detail_page_disc{  
    margin-top:8%;
  }
  .iconlist{
  }
  .iconlist li{
    padding-left: 26px;
    position:relative;
    }
    .iconlist li::before{
      position: absolute;
      content: '';
      width:6px;
      height:6px;
      border-radius:100%;
      background:#000;
      top:40%;
      left:6px;
    }

    .detail_page_disc ul li::before {
      position: absolute;
      content: '';
      width:6px;
      height:6px;
      border-radius:100%;
      background:#000;
      top:40%;
      left:6px;
    }

    .detail_page_disc ul li{
       padding-left: 26px;
       position:relative;
    }

    .detail_page_disc h3{
      text-transform: capitalize;
    }
    .detail_page_disc h5{
      text-transform: capitalize;
    }
  .validate-code-link-main .validate-code-link{
    display: flex;
  }
  .validate-code-link-main .validate-code-link button{
      background: var(--primary-color-2);
      color: #fff;
      border: 0;
      padding: 0 12px;
      border-radius: 0 4px 4px 0;
  }
  .validate-code-link-main .validate-code-link button:hover{
    background: #000;
  }
  .validate-code-link-main .val-err{
    color: var(--primary-color-2);
  }

  .validate-code-link-main .val-succ{
    color: green;
  }
  .detail_fields{
    width: 65%;
  }
  .detail_fields select{
    width: 100%;
  }
  .shop-detail .images-slider .slides li{
    /* height:70vh; */
    height:100%;
  }
  .shop-detail .images-slider .slides li .img-responsive  {
    width:100%;
    height:100%;
    object-fit:cover;
  }
  .image-tab-content .slides{
    display:none;
  }
  .image-tab-content .slides.active{
    display:block;
  }
  .image-tab-heading a{
    display:block;
    height:130px;
    width:100%;
    cursor:pointer;
    border-radius:6px;
  }
  .image-tab-heading a img{
    width:100%;
    border-radius:6px;
    height:100%;
    object-fit:cover;
    object-position:left;
  }
  .image-tab-content{
    display:flex;
    height:100%;
    overflow: hidden;
    column-gap:15px;
    padding:2%;
    border-radius:6px;
    border:1px solid var(--green-color)
  }
  .image-tab-content-row{
    height:600px;
  }
  .image-tab-heading{
    row-gap:15px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
  }

  .image-tab-col{
    height:100%;
  }
  .images-slider{
    height:100%;
  }
  .images-slider:before{
    position:unset;
  }
  .image-tab-content-sec{
    height:100%;
    width:100%;
  } 
  .image-tab-content-sec .slidess{
    height:100%;
    width:100%;
  }
  .image-tab-content-sec .slidess li{
    height:100%;
  }
  .image-tab-content-sec .slidess li img{
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
    border-radius:6px;
  }
  body{
    scroll-behavior: smooth;
  }
  .tabs_cards{
    display:none;
  }
  .tabs_cards.active{
    display:block;
  }
  .number-counter span{
    border: 1px solid green;
    font-size:24px;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 2px;
  }
  .input_field{
    align-items: center;
    column-gap: 5%;
  }
 .number-counter{
  display:flex;
  justify-content:space-between;
  align-itmes:center;
  width:34%;
 }
  @media (max-width:600px){
    .banner_btn{
      margin-top: 5%;
    }
  }
</style>

<!-- Popular Products -->
<section id="page-title-new" class="page-title-parallax title-center page-title-dark" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ $bannerImg }}) center center; padding: 64px 0px;">
  <div class="container clearfix ng-scope">
    <h1>{{ $product->name }}</h1>
    
    @if(!empty($product->short_description))
      <span>{{ $product->short_description }}</span>
    @endif

    <div class="banner_btn">
        <a class="button" href="#">Buy Now</a>
    </div>
  </div>
</section>

<section class="padding-top-100 padding-bottom-100">
  <div class="container"> 
    
    <!-- SHOP DETAIL -->
    <div class="shop-detail">

      <div class="row image-tab-content-row"> 
        
        <!-- Popular Images Slider -->
        <div class="col-md-6 image-tab-col"> 
            
          <!-- Images Slider -->
          <!-- <div class="images-slider">
            <div class="image-tab-content">
              <div class="image-tab-heading">
                <a href="#tab1" data="http://localhost/epcafe/public/media/2024/02/05/study.jpg"><img src="http://localhost/epcafe/public/media/2024/02/05/study.jpg" alt=""></a>
                <a href="#tab2" data="http://localhost/epcafe/public/media/2024/02/05/study.jpg"><img src="http://localhost/epcafe/public/media/2024/02/05/study.jpg" alt=""></a>
                <a href="#tab3" data="http://localhost/epcafe/public/media/2024/02/05/study.jpg"><img src="http://localhost/epcafe/public/media/2024/02/05/study.jpg" alt=""></a>
              </div>
              <div class="image-tab-content-sec">
                <ul id="tab" class="slidess tabs_cards active">
                  <li data-thumb="{{ getImg($product->thumbnail_id) }}"> <img class="img-responsive" src="{{ getImg($product->thumbnail_id) }}"  alt=""> </li>
                </ul> 
              </div>
            </div>
          </div> -->
        </div>
        
        <!-- Content -->
        <div class="col-md-6 detail_ul">
          <h4 style="color:var(--primary-color-1);">{{ $product->name }}</h4>
          
          <form method="post" id="addToCartForm" class="detail_page_form" style="margin-top:25px;">
            <div class="input_field">

              <!-- <div class="number-counter">
                <label for="select">No of Pages</label>
                <div class="qty-input">
                  <button class="qty-count qty-count--minus" data-action="minus" type="button">-</button>
                  <input class="product-qty" type="number" name="product-qty" min="0" max="10" value="1">
                  <button class="qty-count qty-count--add" data-action="add" type="button">+</button>
                </div>
              </div> -->

              <div class="qty-input">
                <button class="qty-count qty-count--minus" data-action="minus" type="button">-</button>
                <input class="product-qty" type="number" name="product-qty" min="1" max="10" value="1">
                <button class="qty-count qty-count--add" data-action="add" type="button">+</button>
              </div>

                <div class="label_input choose">
                  <input min="1" id="noOfPages" name="noOfPages" type="text" style="width:100%;" placeholder="No of Pages">
                  <span class="text-danger" id="noOfPagesErr"></span>
                </div>
            </div>

          <input type="hidden" name="productId" value="{{ $product->id }}">
          <button id="addToCartFormBtn" class="theme-btn mt-20 home_btn"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="upload" class="svg-inline--fa fa-upload fa-w-16 mr-3 ml-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 16px;"><path fill="currentColor" d="M296 384h-80c-13.3 0-24-10.7-24-24V192h-87.7c-17.8 0-26.7-21.5-14.1-34.1L242.3 5.7c7.5-7.5 19.8-7.5 27.3 0l152.2 152.2c12.6 12.6 3.7 34.1-14.1 34.1H320v168c0 13.3-10.7 24-24 24zm216-8v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h136v8c0 30.9 25.1 56 56 56h80c30.9 0 56-25.1 56-56v-8h136c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path></svg><span id="addToCartFormTxt">Add to Cart</span></button>
          <p>my name is faizan</p>
          <div class="price-desktop" style="margin-bottom: 5px;">

             <div class="input_field" style="display:block;">
                <label for="select">Estimated Delivery</label>
                <div class="label_input validate-code-link-main" style="width:100%;">
                  <div class="validate-code-link">
                    <input id="pincode" name="pincode" type="number" style="width:100%;" placeholder="Pincode">
                    <button type="button" id="estimatedDeliveryBtn">Check</button>
                  </div>
                  <span id="deliveryErr" class="val-err"></span>
                </div>

                <!-- <label for="select">Document Link</label>
                <div class="label_input validate-code-link-main" style="width:100%;">
                  <div class="validate-code-link">
                    <input id="documentLink" name="documentLink" type="text" style="width:100%;" placeholder="Document Link">
                    <button type="button" id="documentLinkBtn">Update</button>
                  </div>
                  <span id="documentLinkErr" class="val-err"></span>
                </div> -->


            </div>                

          </div>
        </form>
        <!-- <div class="input_field" style="display:block;">
              <label for="select">Estimated Delivery</label>
              <div class="label_input validate-code-link-main" style="width:50%;">
                <div class="validate-code-link">
                  <input id="pincode" name="pincode" type="number" style="width:100%;" placeholder="Pincode">
                  <button type="button" id="estimatedDeliveryBtn">Check</button>
                </div>
                <span id="deliveryErr" class="val-err"></span>
              </div>

              <label for="select">Document Link</label>
              <div class="label_input validate-code-link-main" style="width:50%;">
                <div class="validate-code-link">
                  <input id="documentLink" name="documentLink" type="text" style="width:100%;" placeholder="Document Link">
                  <button type="button" id="documentLinkBtn">Update</button>
                </div>
                <span id="documentLinkErr" class="val-err"></span>
              </div>
          </div> -->
        </div>
      </div>

      <div class="detail_page_disc">
        {!! $product->description !!}

        <div class="banner_btn" style="text-align:center;">
            <a class="button" href="Buy Now" style="margin-top: 50px;">BUY NOW</a>
        </div>

      </div>
    </div>
  </div>
</section>
    
@if(!empty($relProducts) && $relProducts->count())
<section class="light-gray-bg padding-top-150 padding-bottom-150">
  <div class="container"> 
    
    <!-- Main Heading -->
    <div class="heading text-center">
      <h4>RELATED PRODUCTS</h4>
      <!--<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec faucibus maximus vehicula. -->
      <!--Sed feugiat, tellus vel tristique posuere, diam</span>-->
      </div>
    
    <!-- Popular Item Slide -->
    <div class="papular-block block-slide single-img-demos">
        @foreach($relProducts as $relProd)
        <div class="item"> 
          <a href="{{ route('productPage', ['slug' => $relProd->slug]) }}">
              <div class="item-img"> 
                <img class="img-1" src="{{ getImg($relProd->thumbnail_id); }}" alt="">
                <!-- Overlay -->
                <div class="overlay">
                  <!-- <div class="position-center-center">
                    <div class="inn">
                      <div href="{{ asset('public/frontend') }}/images/new-arrival-img.png" data-lighter=""><i class="icon-magnifier"></i></div>
                      <div href="#."><i class="icon-basket"></i></div>
                      <div href="#."><i class="icon-heart"></i></div>
                    </div>
                  </div> -->
                </div>
              </div>
            </a>
          <!-- Item Name -->
          <div class="item-name"> <a href="{{ route('productPage', ['slug' => $relProd->slug]) }}">{{ $relProd->name }}</a>
            <!--<p>Lorem ipsum dolor sit amet</p>-->
          </div>
          <!-- Price --> 
          <!--<span class="price"><small>$</small>299</span> -->
        </div>
        @endforeach
    </div>
  </div>
</section>
@endif

<script type="text/javascript">




var QtyInput = (function () {
	var $qtyInputs = $(".qty-input");

	if (!$qtyInputs.length) {
		return;
	}

	var $inputs = $qtyInputs.find(".product-qty");
	var $countBtn = $qtyInputs.find(".qty-count");
	var qtyMin = parseInt($inputs.attr("min"));
	var qtyMax = parseInt($inputs.attr("max"));

	$inputs.change(function () {
    alert(1);
		var $this = $(this);
		var $minusBtn = $this.siblings(".qty-count--minus");
		var $addBtn = $this.siblings(".qty-count--add");
		var qty = parseInt($this.val());

		if (isNaN(qty) || qty <= qtyMin) {
			$this.val(qtyMin);
			$minusBtn.attr("disabled", true);
		} else {
			$minusBtn.attr("disabled", false);

			if (qty >= qtyMax) {
				$this.val(qtyMax);
				$addBtn.attr("disabled", true);
			} else {
				$this.val(qty);
				$addBtn.attr("disabled", false);
			}
		}
	});

	$countBtn.click(function () {
		var operator = this.dataset.action;
		var $this = $(this);
		var $input = $this.siblings(".product-qty");
		var qty = parseInt($input.val());

		if (operator == "add") {
			qty += 1;
			if (qty >= qtyMin + 1) {
				$this.siblings(".qty-count--minus").attr("disabled", false);
			}

			if (qty >= qtyMax) {
				$this.attr("disabled", true);
			}
		} else {
			qty = qty <= qtyMin ? qtyMin : (qty -= 1);

			if (qty == qtyMin) {
				$this.attr("disabled", true);
			}

			if (qty < qtyMax) {
				$this.siblings(".qty-count--add").attr("disabled", false);
			}
		}

		$input.val(qty);
	});
})();

  
  // Pause YouTube Video on slide change
  // $mainviewer.on('beforeChange', function(slick, currentSlide, nextSlide) {

  //     $('iframe').each(function() {
  //       $(this)[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
  //     });

  // });
  
  
  // Play YouTube Video if it is the current slide
  // $mainviewer.on('afterChange', function(slick, currentSlide, nextSlide) {
  //   if($('.slick-current .slide').hasClass('youtube')) {
  //     $('.slick-current iframe')[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
  //   }
  // });


  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.image-tab-heading a').click(function(e) {
      e.preventDefault(); // Prevent default anchor behavior

      // Get the image source from data-image attribute
      var imgSrc = $(this).data('image');

      // Update the image src in .tabs_cards li img
      $('.tabs_cards li img').attr('src', imgSrc);
    });
  //});

   
  //});
    
      $("#paperSize").change(function(event) {
        
        paperSize = $(this).find(':selected').val();
        productId = {{ $product->id }}

        $.ajax({
          url: '{{ route("getPricing") }}',
          type: 'POST',
          dataType: 'json',
          data: {
            productId: productId,
            paperSize: paperSize,
            action: 'gsm'
          },
          beforeSend: function() {

          }, success: function(res) {
              
              calculatePrice();

              $("#paperGsm").html(res.gsmOptions);
              $("#binding").html(res.bindingOptions);
              $("#lamination").html(res.laminationOptions);

          }
        })

      });

      $("#paperGsm").change(function (e) {
        
        productId = {{ $product->id }}
        paperSize = $("#paperSize").find(':selected').val();
        paperGsm = $(this).find(':selected').val();

        $.ajax({
          url: '{{ route("getPricing") }}',
          type: 'POST',
          dataType: 'json',
          data: {
            productId: productId,
            paperSize: paperSize,
            paperGsm: paperGsm,
            action: 'paper_type'
          },
          beforeSend: function() {

          }, success: function(res) {
              
              calculatePrice();
              $("#paperType").html(res.paperOptions);

          }
        })

      });

      $("#paperType").change(function (e) {
        
        productId = {{ $product->id }}
        paperSize = $("#paperSize").find(':selected').val();
        paperGsm = $("#paperGsm").find(':selected').val();
        paperType = $(this).find(':selected').val();

        $.ajax({
          url: '{{ route("getPricing") }}',
          type: 'POST',
          dataType: 'json',
          data: {
            productId: productId,
            paperSize: paperSize,
            paperGsm: paperGsm,
            paperType: paperType,
            action: 'paper_sides'
          },
          beforeSend: function() {

          }, success: function(res) {
              
              calculatePrice();
              $("#sides").html(res.paperSides);

          }
        })

      });

      $("#sides").change(function (e) {
        
        productId = {{ $product->id }}
        paperSize = $("#paperSize").find(':selected').val();
        paperGsm = $("#paperGsm").find(':selected').val();
        paperType = $("#paperType").find(':selected').val();
        paperSides = $(this).find(':selected').val();

        $.ajax({
          url: '{{ route("getPricing") }}',
          type: 'POST',
          dataType: 'json',
          data: {
            productId: productId,
            paperSize: paperSize,
            paperGsm: paperGsm,
            paperType: paperType,
            paperSides: paperSides,
            action: 'paper_color'
          },
          beforeSend: function() {

          }, success: function(res) {
              
              calculatePrice();
              $("#color").html(res.paperColor);

          }
        })

      });

      $("#color").change(function (e) {
        calculatePrice();
      });

      $("#binding").change(function (e) {
        calculatePrice();
      });

      $("#lamination").change(function (e) {
        calculatePrice();
      });

      $("#noOfCopies").change(function (e) {
        calculatePrice();
      });

      $("#noOfPages").change(function (e) {
        calculatePrice();
      });

      function calculateBindingPrice() {

        bindingPrice = $("#binding").find(':selected').attr('data-price');
        bindingSplit = $("#binding").find(':selected').data('split');
        getPaperSide = $("#sides").find(':selected').val();
        qty = ($("#noOfPages").val() == '')? 0:$("#noOfPages").val();

        totalSplit = 1;

        if (bindingPrice) {
          
          if(bindingSplit > 0) {
        
            //proceed to calculate
            multiple = (getPaperSide == 'Double Side')? 2:1;
            bindingSplit = bindingSplit*multiple;

            if (qty > bindingSplit) {
              totalSplit = Math.ceil(qty/bindingSplit); 
            }
          }

        } else {
          bindingPrice = 0;
        }

        if (totalSplit > 1) {
          newSplitPrice = totalSplit;
          bindingPrice = parseFloat(bindingPrice)*newSplitPrice;
        } else {
          newSplitPrice = totalSplit;
          bindingPrice = parseFloat(bindingPrice);
        }

        return {
          split: newSplitPrice,
          bindingPrice: bindingPrice,
        }

      }

      function calculatePrice() {

        paperGsmPrice = 0;
        paperTypePrice = 0;
        paperSidesPrice = 0;
        paperColorPrice = 0;
        bindingPrice = 0;
        laminationPrice = 0;

        qty = ($("#noOfPages").val() == '')? 0:$("#noOfPages").val();
        noOfCopies = ($("#noOfCopies").val() == '')? 1:$("#noOfCopies").val();

        // if ($("#paperGsm").find(':selected').val() != '') {
        //   paperGsmPrice = $("#paperGsm").find(':selected').attr('data-weight');
        // }

        if ($("#paperType").find(':selected').val() != '') {
          paperTypePrice = $("#paperType").find(':selected').attr('data-price');
        }

        // if ($("#sides").find(':selected').val() != '') {
        //   paperSidesPrice = $("#sides").find(':selected').attr('data-price');
        // }

        if ($("#color").find(':selected').val() != '') {
          paperColorPrice = $("#color").find(':selected').attr('data-price');
        }

        if ($("#binding").find(':selected').val() != '') {
          bindingPrice = $("#binding").find(':selected').attr('data-price');
        }

        if ($("#lamination").find(':selected').val() != '') {
          laminationPrice = $("#lamination").find(':selected').attr('data-price');
        }

        // totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice)+parseFloat(bindingPrice)+parseFloat(laminationPrice);

        totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice);

        // if(paperSidesPrice != 0 && paperColorPrice != 0) {
        //   totalPrice = parseFloat(totalPrice) - parseFloat(paperSidesPrice);
        // }

        split = calculateBindingPrice().split;
        bindingPrice = calculateBindingPrice().bindingPrice;

        if (bindingPrice) {
          $("#splitDetails").html(`Split: ${split}`);
        } else {
          $("#splitDetails").html('');
        }

        finalPrice = (parseFloat(totalPrice)*parseInt(qty))+parseFloat(bindingPrice)+parseFloat(laminationPrice);;
        
        if(qty != 0) {
            
            // if(noOfCopies != 0) {

            //   noOfCopies = parseInt(noOfCopies)+1;

            //   $('.price-widget-sezzle').html(`₹`+(finalPrice*noOfCopies));
            // } else {
            //   $('.price-widget-sezzle').html(`₹`+finalPrice);
            // }

          $('.price-widget-sezzle').html(`₹`+(finalPrice*noOfCopies));

        }

        $("#perSheetPrice").html(`₹`+totalPrice)

        //console.log(totalPrice, parseInt(qty));

      }

    $("#estimatedDeliveryBtn").click(function(event) {
      
      pincode = $("#pincode").val();

      $.ajax({
        url: '{{ route("checkPincode") }}',
        type: 'POST',
        dataType: 'json',
        data: {pincode: pincode},
        beforeSend: function() {
          $("#deliveryErr").html('').removeClass('val-err val-succ');
           $("#estimatedDeliveryBtn").html('Checking...');
        },
        success: function(res) {
          
          if (res.error == true) {
            if (res.eType == 'field') {
              $("#deliveryErr").html(res.errors.pincode).addClass('val-err');
            } else if(res.eType == 'final') {
              $("#deliveryErr").html(res.msg).addClass('val-err');
            }
          } else {
            $("#deliveryErr").html(res.msg).addClass('val-succ');
          }

          $("#estimatedDeliveryBtn").html('Check');
        }
      })      

    });

    $("#documentLinkBtn").click(function(event) {
      
      documentLink = $("#documentLink").val();

      $.ajax({
        url: '{{ route("checkDocumentLink") }}',
        type: 'POST',
        dataType: 'json',
        data: {documentLink: documentLink},
        beforeSend: function() {
          $("#documentLinkErr").html('').removeClass('val-err val-succ');
           $("#documentLinkBtn").html('Checking...');
        },
        success: function(res) {
          
          if (res.error == true) {
            if (res.eType == 'field') {
              $("#documentLinkErr").html(res.errors.documentLink).addClass('val-err');
            } else if(res.eType == 'final') {
              $("#documentLinkErr").html(res.msg).addClass('val-err');
            }
          } else {
            $("#documentLinkErr").html(res.msg).addClass('val-succ');
          }

          $("#documentLinkBtn").html('Update');
        }
      })      

    });

    $("#addToCartForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();
      documentLink = $("#documentLink").val();
      formData += "&documentLink="+documentLink;

      $.ajax({
        url: '{{ route("addToCart") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $('.text-danger').html('');
          $("#addToCartFormTxt").html('Adding...');
        },
        success: function(res) {
          
          if (res.error == true) {
            if (res.eType == 'field') {
              
              $.each(res.errors, function(index, val) {
                 $("#"+index+"Err").html(val);
              });

            } else if(res.eType == 'final') {
              $("#documentLinkErr").html(res.msg).addClass('val-err');
            }
          } else {
            $("#documentLinkErr").html(res.msg).addClass('val-succ');
            window.location.href = "{{ route('cartPage') }}";
          }

          $("#addToCartFormTxt").html('Add To Cart');
        }
      })  
      

    });

  });
</script>

@endsection