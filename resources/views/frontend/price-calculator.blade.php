@extends('vwFrontMaster')

@section('content')

<style type="text/css">

  /*CSS For Result*/
  .promo.promo-light {
      background-color: #f5f5f5;
      border-radius: 3px;
      padding-left: 30px;
  }

  .promo-full {
    border-radius: 0 !important;
    border-left: 0 !important;
    border-right: 0 !important;
    padding: 40px 0 !important;
  }

  .promo-uppercase {
      text-transform: uppercase;
  }
  .promo {
      position: relative;
      padding: 30px 200px 30px 0;
  }

  .tset {
      display: inline-block;
      position: relative;
      box-shadow: 0 0 0 1px #ddd;
      line-height: 30px;
      padding: 0 15px;
      border-radius: 4px;
      font-size: 14px;
      margin: 5px 5px 15px 0;
      font-weight: 400;
      min-height: 30px;
      max-height: 30px;
      white-space: nowrap;
  }
  .tsethead {
      color: #000;
  }

  .tsetvalue {
/*      color: rgb(255, 102, 0);*/
      font-weight: 700;
      display: inline-block;
      padding: 0 3px 0 3px;
  }

  .button.button-mini {
      padding: 0 14px;
      font-size: 11px;
      height: 28px;
      line-height: 28px;
  }

  .button:hover {
      background-color: #444;
      color: #fff;
      text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
  }

  .button-mini i {
      margin-right: 3px;
  }
  .button i {
      position: relative;
      top: 1px;
      line-height: 1;
      margin-right: 5px;
  }
  .button-teal {
      background-color: #53777a;
  }

  .button {
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
      /*      background-color: #1abc9c;*/
      background-color: var(--green-color);
      color: #fff;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      border: none;
      text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
  }
  /*CSS For Result End*/

  section{
    background-size:cover !important;
  }

  .product-options div {
    margin-bottom: 5rem;
  }

  .cal-button {
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
      background-color: #1abc9c;
      color: #fff;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      border: none;
    /*      background-color: #002e6e;*/
      background-color: var(--green-color);
      text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
  }

  .sm-form-control {
    display: block;
    width: 100%;
    height: 38px;
    padding: 8px 14px;
    font-size: 15px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 2px solid #ddd;
    border-radius: 0 !important;
    -webkit-transition: border-color ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s;
    transition: border-color ease-in-out .15s;
  }

  .product-selection {
    display: flex;
    align-items: center;
    padding: 5rem 0;
  }

  #page-title-new {
    background-color: gray !important;
  }

  #page-title-new h1 {
    font-size: 26px;
    color: white;
  }

  .product-selection h2 {
    color: white;
    font-size: 18px;
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
    height:70vh;
  }
  .shop-detail .images-slider .slides li .img-responsive  {
    width:100%;
    height:100%;
    object-fit:cover;
  }
  @media (max-width:600px){
    .banner_btn{
      margin-top: 5%;
    }
  }
</style>

<!-- Popular Products -->
<section id="page-title-new" class="page-title-parallax title-center page-title-dark" style="background-color: #333;">
  <div class="container clearfix">
    <h1>Price Calculator</h1>
  </div>
</section>

<section class="page-title-parallax title-center page-title-dark" style="background-color: #333;">
  <div class="container clearfix product-selection">
    <div class="col-md-4">
      <h2>Select Product Type</h2>
    </div>
    <div class="col-md-4">
      <select id="category" name="category" class="form-control">
        @if(!empty($categoryList) && $categoryList->count())
          @foreach($categoryList as $category)
            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
          @endforeach
        @endif
      </select>
    </div>
    <div class="col-md-4">
      <select id="productId" name="productId" class="form-control">
        @if(!empty($productList) && $productList->count())
          @foreach($productList as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
          @endforeach
        @endif
      </select>
    </div>
  </div>
</section>

<section class="page-title-parallax title-center page-title-dark" style="padding: 3rem">
  <div class="container clearfix">
    <div class="row product-options">
        <div class="col-sm-2">
          <label for="login-form-username">PAGES: </label>
          <input onchange="noOfPages()" id="noOfPages" name="noOfPages" class="required sm-form-control input-block-level" type="number" min="1" value="1">
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">COPIES: </label>
          <input onchange="noOfCopy()" id="noOfCopies" name="noOfCopies" class="required sm-form-control input-block-level" type="number" min="1" value="1">
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">PAPER SIZE: </label>
          <select onchange="updatePaperSize(this)" id="paperSize" name="paperSize" class="required sm-form-control input-block-level">
            <option value="">Select Paper Size</option>
            @if(!empty($paperSize))
            @foreach($paperSize as $paperSize)
            <option {{ ($defPaperSize->id == $paperSize->id)? 'selected':''; }} value="{{ $paperSize->id }}">{{ $paperSize->size }}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">PAPER GSM: </label>
          <select onchange="updatePaperGsm(this)" id="paperGsm" name="paperGsm" class="required sm-form-control input-block-level">
            @if(!empty($defGsmOpt))
              {!! $defGsmOpt !!}
            @else
              <option value="">Select Paper GSM</option>
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">PAPER TYPE: </label>
          <select onchange="updatePaperType(this)" id="paperType" name="paperType" class="required sm-form-control input-block-level">
            @if(!empty($defPaperTypeOpt))
              {!! $defPaperTypeOpt !!}
            @else
              <option value="">Select Paper Type</option>
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">PRINT SIDE: </label>
          <select onchange="updatePaperSides(this)" id="paperSides" name="paperSides" class="required sm-form-control input-block-level">
            @if(!empty($defPaperSidesOpt))
              {!! $defPaperSidesOpt !!}
            @else
              <option value="">Select Sides</option>
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">COLOR: </label>
          <select onchange="updateColor(this)" id="color" name="color" class="required sm-form-control input-block-level">
            @if(!empty($defPaperColorOpt))
              {!! $defPaperColorOpt !!}
            @else
              <option value="">Select Color</option>
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">BINDING: </label>
          <select onchange="updateBinding(this)" id="binding" name="binding" class="required sm-form-control input-block-level">
            @if(!empty($defBindingOpt))
              {!! $defBindingOpt !!}
            @else
              <option value="">Select Binding</option>
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">LAMINATION: </label>
          <select onchange="updateLamination(this)" id="lamination" name="lamination" class="required sm-form-control input-block-level">
            @if(!empty($defLaminationOpt))
              {!! $defLaminationOpt !!}
            @else
              <option value="">Select Lamination</option>
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">COVER: </label>
          <select id="cover" name="cover" class="required sm-form-control input-block-level">
            <option value="">Select Cover</option>
            @if(!empty($covers))
            @foreach($covers as $cover)
            <option value="{{ $cover->id }}">{{ $cover->cover }}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="col-sm-2">
          <label for="login-form-username">&nbsp;</label>
          <button onclick="calculatePrice(this)" type="button" class="cal-button">Calculate Price</button>
        </div>
    </div>
  </div>
</section>

<div id="result" class="promo promo-light promo-full promo-uppercase p-4 p-md-5 mb-5" style="display: none;">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-12">

        <h5 class="text-center" style="margin-bottom:3rem">The calculated prices are exclusive of discounts.</h5>

        <div class="tset"><span> 
          <span class="tsethead">PAGES :</span> 
          <span id="pagesRes" class="tsetvalue">-</span></span>
        </div>
        <div class="tset">
          <span> 
            <span class="tsethead">COPIES :</span> 
            <span id="copiesRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">PAPER SIZE :</span>
            <span id="paperSizeRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">PAPER GSM :</span>
            <span id="paperGsmRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">PAPER TYPE :</span>
            <span id="paperTypeRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">PRINT SIDE :</span>
            <span id="printSideRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">COLOR :</span>
            <span id="colorRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">BINDING :</span>
            <span id="bindingRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">LAMINATION :</span>
            <span id="laminationRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
        <div class="tset">
          <span>
            <span class="tsethead">COVER :</span>
            <span id="coverRes" class="tsetvalue ng-binding">-</span>
          </span>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="button  button-mini button-green ng-binding">Printing Cost : 
          <span id="printingCostRes">-</span>
        </div>

        <div style="display: none;" id="bindingSplitDiv" class="button button-mini button-green ng-binding">Split Into : 
          <span id="bindingSplitRes">-</span>
        </div>

        <div class="button  button-mini button-green ng-binding">Binding Cost : 
          <span id="bindingCostRes">-</span>
        </div>
        <div class="button  button-mini button-green ng-binding">Lamination Cost : 
          <span id="laminationCostRes">-</span>
        </div>
        <div class="button  button-mini button-green ng-binding">Total Cost : 
          <span id="totalCostRes">-</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  function updatePaperSize(el) {

    paperSize = $(el).find(':selected').val();
    productId = $("#productId").find(':selected').val();    

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
          
          // calculatePrice();

          $("#paperGsm").html(res.gsmOptions);
          $("#binding").html(res.bindingOptions);
          $("#lamination").html(res.laminationOptions);

      }
    });

  }

  function updatePaperGsm(el) {
    
    productId = $("#productId").find(':selected').val();
    paperSize = $("#paperSize").find(':selected').val();
    paperGsm = $(el).find(':selected').val();

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
          
          // calculatePrice();
          $("#paperType").html(res.paperOptions);

      }
    });

  }

  function updatePaperType(el) {
    productId = $("#productId").find(':selected').val();
    paperSize = $("#paperSize").find(':selected').val();
    paperGsm = $("#paperGsm").find(':selected').val();
    paperType = $(el).find(':selected').val();

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
          
          // calculatePrice();
          $("#sides").html(res.paperSides);

      }
    })
  }

  function updatePaperSides(el) {

    productId = $("#productId").find(':selected').val();
    paperSize = $("#paperSize").find(':selected').val();
    paperGsm = $("#paperGsm").find(':selected').val();
    paperType = $("#paperType").find(':selected').val();
    paperSides = $(el).find(':selected').val();

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
          
          // calculatePrice();
          $("#color").html(res.paperColor);

      }
    })
  }

  function updateColor() {
    //calculatePrice();
  }

  function updateBinding() {
    //calculatePrice();
  }

  function updateLamination() {
    //calculatePrice();
  }

  function noOfCopy() {
    //calculatePrice();
  }

  function noOfPages() {
    //calculatePrice();
  }

  function calculatePrice(el) {

    $(el).html('Please Wait...');

    $("#bindingSplitDiv").hide();

    paperGsmPrice = 0;
    paperTypePrice = 0;
    paperSidesPrice = 0;
    paperColorPrice = 0;
    
    bindingPrice = 0;
    bindingSplit = 0;
    totalSplit = 0;

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

    getPaperSide = $("#paperSides").find(':selected').val();

    if ($("#binding").find(':selected').val() != '') {
      bindingPrice = $("#binding").find(':selected').attr('data-price');
      bindingSplit = $("#binding").find(':selected').data('split');

      if(bindingSplit > 0) {
        
        //proceed to calculate
        multiple = (getPaperSide == 'Double Side')? 2:1;
        bindingSplit = bindingSplit*multiple;

        if (qty > bindingSplit) {
          totalSplit = Math.floor(qty/bindingSplit); 
        }

      }

    }

    if ($("#lamination").find(':selected').val() != '') {
      laminationPrice = $("#lamination").find(':selected').attr('data-price');
    }

    // totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice)+parseFloat(bindingPrice)+parseFloat(laminationPrice);

    totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice);

    // if(paperSidesPrice != 0 && paperColorPrice != 0) {
    //   totalPrice = parseFloat(totalPrice) - parseFloat(paperSidesPrice);
    // }

    if (totalSplit > 0) {
      $("#bindingSplitRes").html(totalSplit);
      $("#bindingSplitDiv").show();
      newSplitPrice = totalSplit+1;
      $("#bindingCostRes").html(parseFloat(bindingPrice)*newSplitPrice);
      bindingPrice = parseFloat(bindingPrice)*newSplitPrice;
    } else {
      $("#bindingCostRes").html(parseFloat(bindingPrice));  
      bindingPrice = parseFloat(bindingPrice);
    }

    finalPrice = (parseFloat(totalPrice)*parseInt(qty))+parseFloat(bindingPrice)+parseFloat(laminationPrice);
    
    if(qty != 0) {
        
        // if(noOfCopies != 0) {

        //   noOfCopies = parseInt(noOfCopies)+1;

        //   $('.price-widget-sezzle').html(`₹`+(finalPrice*noOfCopies));
        // } else {
        //   $('.price-widget-sezzle').html(`₹`+finalPrice);
        // }

      $('.price-widget-sezzle').html(`₹`+(finalPrice*noOfCopies));

    }

    $("#perSheetPrice").html(`₹`+totalPrice);

    $("#pagesRes").html(qty);
    $("#copiesRes").html(noOfCopies);
    $("#paperSizeRes").html($("#paperSize").find(':selected').html());
    $("#paperGsmRes").html($("#paperGsm").find(':selected').html());
    $("#paperTypeRes").html($("#paperType").find(':selected').html());
    $("#printSideRes").html($("#paperSides").find(':selected').html());
    $("#colorRes").html($("#color").find(':selected').html());

    getBindingValue = $("#binding").find(':selected').html();
    if (getBindingValue == 'Select Binding') {
      getBindingValue = 'NO BINDING';
    }
    $("#bindingRes").html(getBindingValue);

    getLaminationValue = $("#lamination").find(':selected').html();
    if (getLaminationValue == 'Select Lamination') {
      getLaminationValue = 'NO LAMINATION';
    }
    $("#laminationRes").html(getLaminationValue);

    getCoverValue = $("#cover").find(':selected').html();
    if (getCoverValue == 'Select Cover') {
      getCoverValue = 'NO COVER';
    }
    $("#coverRes").html(getCoverValue);
    $("#printingCostRes").html(`(${totalPrice} * ${qty}) * ${noOfCopies}`);

    $("#laminationCostRes").html(parseFloat(laminationPrice));
    $("#totalCostRes").html(finalPrice*noOfCopies);

    $("#result").show();
    $(el).html('CALCULATE PRICE');

    console.log(totalPrice, parseInt(qty));

  }

  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $("#category").change(function(event) {
      
      categoryId = $(this).find(':selected').val();
      $("#result").hide();

      $.ajax({
        url: '{{ route("getOptionsByCat") }}',
        type: 'POST',
        dataType: 'json',
        data: {categoryId: categoryId},
        success: function(res) {
          $("#productId").html(res.productList);
          $(".product-options").html(res.optionTemplate);
        }
      }) 

    });

    $("#productId").change(function(event) {
      
      productId = $(this).find(':selected').val();
      $("#result").hide();

      $.ajax({
        url: '{{ route("getOptionsByProduct") }}',
        type: 'POST',
        dataType: 'json',
        data: {productId: productId},
        success: function(res) {
          $(".product-options").html(res.optionTemplate);
        }
      }) 

    });

  });
</script>

@endsection