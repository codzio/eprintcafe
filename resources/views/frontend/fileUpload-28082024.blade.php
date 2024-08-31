@extends('vwFrontMaster')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/5.0.7/sweetalert2.min.css" rel="stylesheet">

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/5.0.7/sweetalert2.min.js"></script>

<style type="text/css">
  /*Remove Image CSS*/

  #placeOrderBtn {
    background: var(--secondary-color-3) !important;
  }

  .shopping-cart .cart-ship-info label{
    color: #302f2e;
    font-weight:700;
  }

  .edit-order-row-new {
    padding:1% 2% 0%;
  }

  .edit-order-row-new p {
    color: red;
  }

  .checkbox-div label, .checkbox label {
    color: white;
  }

  .error {
    color: red;
  }

  .remark {
    resize: none;
    height: 36px;
  }
  .remark:focus{
    border: none;
    outline: none;
  }
  #checkbox-style input{
    margin:0;
    width:17px;
    height:17px!important;
  }
  #checkbox-style{
    display:flex;
    column-gap:50px;
  }
  #dropzone{
    height:100%;
    width:100%;
  }
  .checkbox-div{
    display: grid;
    grid-template-columns:1fr 1fr;
    column-gap: 10px;
  }
  .shopping-cart .cart-ship-info input {
    height: 38px;
  }

  #uploadedDocuments {
    margin: 20px 0;
    display: flex;
  }

  .image-area {
    border: 2px solid black;
    border-radius: 10px;
    margin-right: 20px;
  }

  .image-area img{
    max-width: 150px;
    height: 150px;
  }
  .remove-image {
  display: none;
  position: absolute;
  border-radius: 10em;
  padding: 2px 6px 3px;
  text-decoration: none;
  font: 700 21px/20px sans-serif;
  background: #555;
  border: 3px solid #fff;
  color: #FFF;
  box-shadow: 0 2px 6px rgba(0,0,0,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    -webkit-transition: background 0.5s;
    transition: background 0.5s;
  }
  .remove-image:hover {
   background: #E54E4E;
    padding: 3px 7px 5px;
  }
  .remove-image:active {
   background: #E54E4E;
  }
  .shopping-cart .order-place input {
    height: 44px !important;
  }

  .shopping-cart {
    margin-bottom: 5vh;
  }

  .shopping-cart .order-place input {
    margin: -20px;
  }

  .billing-address {
    display: none;
  }

  .shopping-cart .cart-ship-info {
    margin-top: 0;
  }

  .shopping-cart .cart-ship-info h6 {
    margin-bottom: 20px;
    font-size: 16px;
  }

  .shopping-cart .cart-ship-info h6:before {
    margin-top: 22px;
  }

  #content {
    margin-top: 3%;
  }

  .dropzone {
    width: 100%;
    margin: 2% 0;
    border: 1px solid !important;
    border-color: var(--secondary-color-3) !important;
    border-radius: 5px;
    transition: 0.2s;
  }

  .shopping-cart .order-place {
    border: 1px solid !important;
    border-color: var(--green-color) !important;
  }

  .shopping-cart .order-place .order-detail p {
    color: black !important;
  }

  .dropzone.dz-drag-hover {
    border: 2px solid #3498db !important;
  }

  .dz-message.needsclick img {
    width: 50px;
    display: block;
    margin: auto;
    opacity: 0.6;
    margin-bottom: 15px;
  }

  span.plus {
    display: none;
  }

  .dropzone .dz-message {
      display: block;
      text-align: center;
  }

  .card .card-header {
      border-bottom: none;
  }

/*14-08-2024 start*/

.tab-container-main #couponCodeFormBtn{
  font-size:10px;
  position: absolute;
  height: 100%;
}
.tab-container-main #couponCodeForm{
  position:relative ;
}
.tab-container-main .cart-ship-info h6{
/*  color: #fff;*/
}
.tab-container-main .cart-ship-info h6:before{
  display:none;
}
.shopping-cart .cart-ship-info .order-detail p{
  color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
}

/*14-08-2024 end*/

/*New Css*/

:root{
/*  --secondary-color-3: #e5097f;*/
  --primary-color-2: #dd1d26;
  --primary-color-3: #000;
  --secondary-color-1: #000;
  --secondary-color-2: #dd1d26;
  /*  --secondary-color-3: #dd1d26;*/
  --secondary-color-3: #376513;
  --green-color: #49c93e;
  --light-black:#3333;
  --grey-light:#f5f5f5;
  --grey-color:#2b2a29;
}

.tab-container-main #couponCodeFormBtn{
  font-size:10px;
  position: absolute;
  height: 100%;
}
.tab-container-main #couponCodeForm{
  position:relative ;
}
.tab-container-main .cart-ship-info h6:before{
  display:none;
}
.shopping-cart .cart-ship-info .order-detail p{
  color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
}
.rvdoc{
  color:var(--secondary-color-3);
  font-weight:bold;
  margin-bottom:15px;
  display:block;
}
.shipping-name{
  color: var(--secondary-color-3)!important;
  font-weight:700;
  text-transform:uppercase;
}
.shipping-datail-value-sec{
  background:transparent!important;
}
.shipping-datail-value-sec p{
  color: #fff;
  margin-bottom: 0;
  font-size:13px;
}
.rvmargbot0{
  line-height:1.5;
  font-size:10px;
}
.form-input-row{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:10px;
}
.drop-second-btn{
  background: var(--secondary-color-3)!important;
  border:1px solid var(--secondary-color-3)!important;
}
.drop-btn-sec button i{
  font-size:16px;
  color: #fff;
}
.drop-btn-sec button a{
  color:#fff;
  display:flex;
  align-items:center;
  column-gap:5px;
}
.faq-btn{
  font-size: 10px;
    border: 1px solid #000;
    padding: 5px 12px;
    outline: none;
    border-radius: 3px;
    background: #000;
    color: #fff;
    display: flex;
    align-items: center;
    column-gap: 5px;
    letter-spacing: 0.5px;
    text-transform: uppercase;  
}
.upload-file-result h2{
  font-size: 16px;
  color:#000;
  margin:0;
  font-weight:600;
}
.upload-result-heading{
  display:flex;
  align-items:center;
  column-gap:4px;
  width: 80%;
}

.upload-result-heading h2:nth-child(1) {
  width: 80%;
}

.upload-file-result{
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 3%;
    background: #fff;
    margin: 3% 0;
    border-radius: 5px;
    gap: 12px;
}
.tab-container-main label{
  margin-bottom: 0;
  font-size:11px;
}
.tab-container-main .input_field{
  display: grid;
    align-items: center;
    grid-template-columns: 25% 1fr;
    border:1px solid #000;
    padding-left: 12px;
    width:100%;
} 
.tab-container-main .input_field select{
  width:100%;
  border:none;
}
.tab-container-main .detail_fields{
  border-left:1px solid #000;
}
.tab-content-left::-webkit-scrollbar {
    width:4px; 
}
.tab-content-left::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 6px; 
}
.tab-content-left::-webkit-scrollbar-thumb {
    background: var(--secondary-color-3); 
    border-radius: 6px; 
}
.tab-content-left::-webkit-scrollbar-thumb:hover {
    background:var(--secondary-color-3);
}
.tab-container-main .label_input{
  border-left:1px solid #000;
  width:100%;
}
.tab-container-main .label_input input{
  border:none;
}
.tab-container-main .validate-code-link{
  position:relative;
}
.input-result-boxes-sec{
  display: flex;
    flex-direction: column;
    row-gap: 12px;
    position: absolute;
    overflow-y: auto;
    width: 100%;
    height:75%;
    left: 0;
    padding: 16px;

}
.result-box{
    padding: 5% 3%;
}
.delete{
  background: unset;
  border-bottom:1px solid #fff;
}
.delete-price{
  color:#fff000;
  background: transparent;
}
.delete-icon{
  color: red!important;
  margin-left: 15px;
}
.add-more-btn{
  font-size: 10px;
    border: 1px solid #c02942;
    padding: 5px 12px;
    outline: none;
    border-radius: 3px;
    background: #c02942;
    color: #fff;
    display: flex;
    align-items: center;
    column-gap: 5px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin: auto;
}
.tab-container-main .shopping-cart{
  margin-bottom:0;
}
.tab-container-main .shopping-cart .cart-ship-info label{
  margin-top:15px;
}

.shipping-heading h2{
  font-size:18px;
  margin:0;
}
.shipping-tab-inner{
    border: 1px solid #ccc;
}
.tab-container-main .home_btn{
  border: 1px solid var(--secondary-color-3);
    display: flex;
    align-items: center;
    font-size: 14px;
    column-gap: 8px;
    background: var(--secondary-color-3);
    margin: auto;
    padding:9px 30px;
    font-size:12px;
}
.shipping-heading{
  display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1% 2%;
    border-bottom: 1px solid #ccc;
}
.shipping-content{
  padding:2% 3%;
  border-bottom:1px solid #ccc;
}
.shipping-content .t600{
  font-weight:bold;
}
.home_btn_main{
    position: absolute;
  bottom:4px;
    padding: 4% 0 2%;
    background: #2b2a29;
} 
.tab-content-left-for-btn{
  position:relative;
}
.biiling-submit-btn{
  text-align:center;
  margin-top:4%;
}
.order-confirmation h2{
  font-size:16px;
}
.order-confirmation p{
  font-weight:700;
  color:red;
  line-height:1.3;
}
.order-confirmation{
  padding:2% 3%;
}
.edit-order-row{
  padding:2% 3% 4%;
}
.edit-order-btns{
  display: flex;
    flex-wrap: wrap;
    gap: 8px;
} 
.edit-order-row a{
  font-size: 11px;
    padding: 5px 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.edit-order-row a span{
  color: #ff6600;
  font-weight:700;
}
.edit-order-row a.active{
  background:var(--secondary-color-3);
  color:#fff;
  font-weight:700;
}
.upload-file-heading{
  font-size:17px;
  text-align:center;
  line-height:1.5;
  margin-top:6px;
}
.tab-content-inner{
  overflow-y:hidden;
}


@media (min-width:1600px){
  .home_btn_main button{
    margin-left:75px!important;
  }
  .edit-setting-btn{
    bottom:34px;
  }
  .rvdoc{
    font-size:18px;
  }
  .tab-content-left p{
    font-size:14px!important;
  }
}

@media (max-width:600px){
  .tab-content-right-sec{
    padding:20px 20px 60px;
  }
}

</style>
<div class="tab-section-main">
  <div class="container">
  <div class="row">
    <div class="col-12">
      <div class="tab-container-main">
          <div class="tabs-container">
           <div class="tab-heading-sec">
                <a href="#tab1" class="tab tab-1 {{ (!$cartData->count() || !empty($action))? 'active':'' }}">
                    <h1>1</h1>
                    <p>Upload File</p>
                </a>
                <a href="#tab2" class="tab tab-2 {{ ($cartData->count() && empty($action))? 'active':'disabled' }}">
                    <h1>2</h1>
                    <p>Edit Setting</p>
                </a>
                <a href="#tab3" class="tab tab-3 {{ $cartData->count()? '':'disabled' }}">
                    <h1>3</h1>
                    <p>Delivery Details</p>
                </a>
                <a data-url="{{ route('getTab4Data') }}" onclick="getTab4Data(this)" href="#tab4" class="tab tab-4 {{ $cartData->count()? '':'disabled' }}">
                    <h1>4</h1>
                    <p>Review Order</p>
                </a>
           </div>
           <div class="tab-content-sec">

            <div class="tab-content tab-1-content {{ (!$cartData->count() || !empty($action))? 'active':'' }}" id="tab1">
                <div class="tab-content-inner">
                    <div class="tab-content-left">
                      <h2 style="color:#fff;" class="upload-file-heading">Upload File or Custom Design</h2>
                        <p style="text-align:center;">Supported File Extns: PDF, Docx, Doc, 
                          PPT, PPTX, JPG, PNG 
                          You can upload Multiple file 
                          File Size sholud be less than 100MB.| 
                          we Recommed: Upload a PDF for perfect print</p>
                          <p style="text-align:center;">File is Above 100MB if your file size is more than 100MB. than, you can share your file through a www.wetransfer.com along with Printing Settings, Address and contact details. After that we will send you the payment link to make payment and after payment we will process your order.</p>
                    </div>
                    <div class="tab-content-right-sec">
                      <div class="drop-btn-sec">
                        <button>TOTAL FILES <span>{{ $cartData->count() }}</span></button>
                        <button class="drop-second-btn"><span>+</span>ADD MORE FILES</button>
                        <button><a href="tel:8448193390"><i class="fa fa-cog" aria-hidden="true"></i>Technical Issue : 8448193390</a></button>
                      </div>
                       <div class="tab-content-right-sec-inner">
                         <div id="dropzone">
                            <form action="{{ route('doUploadFiles') }}" class="dropzone needsclick dz-clickable" id="upload">
                              <div class="dz-message needsclick">
                                <span class="text">
                                <!-- <img src="http://localhost/eprintcafe/public/frontend/img/upload.png" alt="Upload"> -->
                                <img src="{{ asset('public/frontend/img/upload.png') }}" alt="Upload">
                                  <p><strong>Drop files here or click to upload.</strong></p>
                                </span>
                                <button  type="button" class="btn btn-primary click-to-upload" style="margin-bottom:6px;">Click to Upload</button>
                                <p>you can also drag and drop them here</p>
                                <span class="plus">+</span>
                              </div>
                            </form>
                            <span id="dropzoneMsg"></span>
                          </div>
                       </div>
                    </div>
                </div>
            </div>
            <div class="tab-content tab-2-content {{ ($cartData->count() && empty($action))? 'active':'disabled' }}" id="tab2">
                <div class="tab-content-inner">
                      <div class="tab-content-left">
                        <input onkeyup="filterFiles(this)" placeholder="Filter Files" class="form-control" type="text" style="margin-top: 10px;">
                        <div class="input-result-boxes-sec">
                          @if(!empty($cartData))
                          @php $fileTab=1; @endphp
                          @foreach($cartData as $cart)
                            
                            @php
                              $price = 0;
                              $productId = $cart->product_id;

                              if(isset(productSinglePrice($productId)->price)) {
                                $price = productSinglePrice($productId)->price;
                              }

                              $productSpecNew = productSpecNew($cart->id);

                            @endphp

                            <a data-toggle="tab" href="#fileTab{{ $cart->id }}" class="fileTab-{{ $cart->id }} result-box fileTab-list">
                              <span class="rvdoc">{{ $cart->file_name }}</span>
                              <p class="rvmargbot0" style="text-transform: uppercase; font-size: 11px;">
                                <span>
                                  <span>Pages : {{ $cart->qty }}  | </span> 
                                  <span>Copies : {{ $cart->no_of_copies }} | </span>
                                </span>
                                <span> PAPER SIZE : {{ $productSpecNew->paperSize }} | </span>
                                <span> PAPER TYPE : {{ $productSpecNew->paperGsm }}gsm {{ $productSpecNew->paperType }} | </span>
                                <span> PRINTED COLOUR : {{ $productSpecNew->color }} | </span>
                                <span> PRINTING SIDES : {{ $productSpecNew->paperSide }} | </span>
                                <span> COVER OPTION : NO COVER | </span>
                                <span> BINDING OPTIONS : {{ $productSpecNew->binding? $productSpecNew->binding:'NO BINDING' }} | </span>
                                <span> LAMINATION OPTIONS : {{ $productSpecNew->lamination? $productSpecNew->lamination:'NO LAMINATION' }} | </span>
                              </p>
                              <p class="delete">
                                <b class="rvpric">
                                  <i class="icon-rupee"></i> 
                                  <span class="delete-price perSheetPrice{{ $cart->id }}">{{ $cart->amount }}</span> 
                                  <span data-url="{{ route('removeCartItem') }}" data-id="{{ $cart->id }}" onclick="deleteFile(this)" class="text-danger small delete-icon" title="{{ $cart->file_name }}"><i class="icon-trash"></i>Delete File</span>
                                </b>
                              </p>
                            </a>

                            @php $fileTab++; @endphp

                          @endforeach
                          @endif
                        </div>

                        <div class="home_btn_main edit-setting-btn">
                          <button class="home_btn proceed-to-next-btn-tab2" style="margin-left:23px;">Proceed to Next Step <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                        </div>

                      </div>

                      <div class="tab-content-right-sec">
                        <div class="drop-btn-sec">
                          <button>TOTAL FILES <span>{{ $cartData->count() }}</span></button>
                          <button class="drop-second-btn addMoreBtn"><span>+</span>ADD MORE FILES</button>
                          <button><a href="tel:8448193390"><i class="fa fa-cog" aria-hidden="true"></i>Technical Issue : 8448193390</a></button>
                        </div>
                        
                        <div class="fileTab-content">
                          @if(!empty($cartData))
                            @php $fileTab=1; @endphp
                            @foreach($cartData as $cart)

                            @php
                              $price = 0;
                              $productId = $cart->product_id;
                              $cartId = $cart->id;

                              if(isset(productSinglePrice($productId)->price)) {
                                $price = productSinglePrice($productId)->price;
                              }

                              $productSpecNew = productSpecNew($cart->id);

                              $paperSizeId = $productSpecNew->paperSizeId;
                              $paperGsmId = $productSpecNew->paperGsmId;
                              $paperTypeId = $productSpecNew->paperTypeId;
                              $paperSideId = $productSpecNew->paperSide;
                              $paperColorId = $productSpecNew->color;

                              $paperSizeList = getPaperSizeList($productId);
                              $gsmList = getPaperGsmList($productId, $paperSizeId);
                              $paperTypeList = getPaperTypeList($productId, $paperSizeId, $paperGsmId);
                              $paperSideList = getPaperSideList($productId, $paperSizeId, $paperGsmId, $paperTypeId);
                              $paperColorList = getPaperColorList($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSideId);

                              $bindingList = getBindingList($paperSizeId);
                              $laminationList = getLaminationList($paperSizeId);
                              $coverList = getCoverList();

                            @endphp

                          <div class="tab-content fileTab-content fileTab-content-{{ $cart->id }} {{ $fileTab == 1? 'active':'' }}" id="fileTab{{ $cart->id }}">
                            <div class="upload-file-result">
                              <div class="upload-result-heading">
                                <h2>{{ $cart->file_name }}</h2>
                                <!-- <button class="faq-btn" style="margin-left:10px;"><i class="fa fa-cog" aria-hidden="true"></i>FAQ</button> -->
                              </div>
                              <h2 class="perSheetPrice{{ $cartId }}">Rs {{ $cart->amount }}</h2>
                            </div>
                            <form data-id="{{ $cartId }}" id="cartForm{{ $cartId }}" onsubmit="return updateCartForm(event);" action="{{ route('doUpdateCart') }}" method="post" class="detail_page_form" style="margin-top:10px;">
                               <div class="form-input-row">
                                <div class="input_field">
                                    <label for="select">Product</label>
                                    <div class="detail_fields">
                                       <select id="product{{ $cartId }}" data-url="{{ route('getProductPricing') }}" data-id="{{ $cartId }}" onchange="getPaperSizeList(this)" name="product">
                                          <option value="">Select Product</option>
                                          @if(!empty($productList))
                                          @foreach($productList as $productL)
                                          <option {{ ($cart->product_id == $productL->id)? 'selected':'' }} value="{{ $productL->id }}">{{ $productL->name }}</option>
                                          @endforeach
                                          @endif
                                       </select>
                                       <span class="text-danger" id="product{{ $cartId }}Err"></span>
                                    </div>
                                </div>
                                <div class="input_field">
                                    <label for="select">Paper Size</label>
                                    <div class="detail_fields">
                                      <select id="paperSize{{ $cartId }}" data-url="{{ route('getProductPricing') }}" data-id="{{ $cartId }}" onchange="getPaperGsmList(this)" name="paperSize">
                                          <option value="">Select Paper Size</option>
                                          @if(!empty($paperSizeList))
                                          @foreach($paperSizeList as $paperSize)
                                          <option {{ ($paperSizeId == $paperSize->id)? 'selected':''; }} value="{{ $paperSize->id }}">{{ $paperSize->size }}</option>
                                          @endforeach
                                          @endif
                                      </select>
                                      <span class="text-danger" id="paperSize{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Paper GSM</label>
                                    <div class="detail_fields">
                                      <select id="paperGsm{{ $cartId }}" data-url="{{ route('getProductPricing') }}" data-id="{{ $cartId }}" onchange="getPaperTypeList(this)" name="paperGsm">
                                         <option value="">Select Paper GSM</option>
                                         @if(!empty($gsmList) && $gsmList->count())
                                            @foreach($gsmList as $gsm)
                                                <option {{ ($paperGsmId == $gsm->id)? 'selected':''; }} data-weight="{{ $gsm->per_sheet_weight }}" value="{{ $gsm->id }}">{{ $gsm->gsm }} GSM - {{ $gsm->paper_type_name }}</option>
                                            @endforeach
                                          @endif
                                       </select>
                                       <span class="text-danger" id="paperGsm{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Paper Type</label>
                                    <div class="detail_fields">
                                      <select id="paperType{{ $cartId }}" data-url="{{ route('getProductPricing') }}" data-id="{{ $cartId }}" onchange="getPaperSideList(this)" name="paperType">
                                          <option value="">Select Paper Type</option>
                                          @if(!empty($paperTypeList) && $paperTypeList->count())
                                              @foreach ($paperTypeList as $paperType)
                                                  <option 
                                                  {{ ($paperTypeId == $paperType->paper_type_id)? 'selected':''; }}
                                                  data-price="{{ $paperType->paper_type_price }}" value="{{ $paperType->paper_type_id }}">{{ $paperType->paper_type }}</option>
                                              @endforeach
                                          @endif
                                       </select>
                                       <span class="text-danger" id="paperType{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Print Sides</label>
                                    <div class="detail_fields">
                                      <select id="sides{{ $cartId }}" data-url="{{ route('getProductPricing') }}" data-id="{{ $cartId }}" onchange="getPaperColorList(this)" name="paperSides">
                                          <option value="">Select Print Sides</option>
                                          @if(!empty($paperSideList) && $paperSideList->count())
                                              @foreach($paperSideList as $paperSide)
                                                <option
                                                {{ ($paperSideId == $paperSide->side)? 'selected':''; }}
                                                value="{{ $paperSide->side }}">{{ $paperSide->side }}</option>
                                              @endforeach
                                          @endif
                                      </select>
                                      <span class="text-danger" id="paperSides{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Color</label>
                                    <div class="detail_fields">
                                      <select id="color{{ $cartId }}" onchange="calculatePrice('{{ $cartId }}')" name="color">
                                          <option value="">Select Color</option>
                                          @if(!empty($paperColorList) && $paperColorList->count())
                                              @foreach($paperColorList as $color)
                                                  <option 
                                                  {{ ($paperColorId == $color->color)? 'selected':''; }}
                                                  data-price="{{ $color->other_price }}" value="{{ $color->color }}">{{ $color->color }}</option>
                                              @endforeach
                                          @endif
                                       </select>
                                       <span class="text-danger" id="color{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Binding</label>
                                    <div class="detail_fields">
                                      <select id="binding{{ $cartId }}" onchange="calculatePrice('{{ $cartId }}')" name="binding">
                                          <option value="">Select Binding</option>
                                          @if(!empty($bindingList) && $bindingList->count())
                                              @foreach ($bindingList as $binding)
                                                  <option 
                                                  {{ ($cart->binding_id == $binding->id)? 'selected':''; }}
                                                  data-price="{{ $binding->price }}" data-split="{{ $binding->split }}" value="{{ $binding->id }}">{{ $binding->binding_name }}</option>
                                              @endforeach
                                          @endif
                                       </select>
                                       <span class="text-danger" id="binding{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Lamination</label>
                                    <div class="detail_fields">
                                      <select id="lamination{{ $cartId }}" onchange="calculatePrice('{{ $cartId }}')" name="lamination">
                                          <option value="">Select Lamination</option>
                                          @if(!empty($laminationList) && $laminationList->count())
                                              @foreach ($laminationList as $lamination) {
                                                  <option 
                                                  {{ ($cart->lamination_id == $binding->id)? 'selected':''; }}
                                                  data-price="{{ $lamination->price }}" value="{{ $lamination->id }}">{{ $lamination->lamination }} - {{ $lamination->lamination_type }}</option>
                                              @endforeach
                                          @endif
                                       </select>
                                       <span class="text-danger" id="lamination{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Cover</label>
                                    <div class="detail_fields">
                                      <select id="cover{{ $cartId }}" onchange="calculatePrice('{{ $cartId }}')" name="cover">
                                          <option value="">Select Cover</option>
                                          @if(!empty($coverList) && $coverList->count())
                                          @foreach($coverList as $cover)
                                          <option 
                                          {{ ($cart->cover_id == $cover->id)? 'selected':''; }}
                                          value="{{ $cover->id }}">{{ $cover->cover }}</option>
                                          @endforeach
                                          @endif
                                      </select>
                                      <span class="text-danger" id="cover{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">No of Pages</label>
                                    <div class="label_input choose">
                                       <input onchange="calculatePrice('{{ $cartId }}')" min="1" id="noOfPages{{ $cartId }}" name="noOfPages" type="text" style="width:100%;" placeholder="No of Pages" value="{{ $cart->qty }}">
                                       <span class="text-danger" id="noOfPages{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">No of Copies</label>
                                    <div class="label_input choose">
                                       <input onchange="calculatePrice('{{ $cartId }}')" min="1" id="noOfCopies{{ $cartId }}" name="noOfCopies" type="text" style="width:100%;" placeholder="No of Copies" value="{{ $cart->no_of_copies }}">
                                       <span class="text-danger" id="noOfCopies{{ $cartId }}Err"></span>
                                    </div>
                                 </div>
                                 <div class="input_field">
                                    <label for="select">Remark</label>
                                    <span class="label_input choose" style="background: #fff;">
                                       <textarea id="remark{{ $cartId }}" class="remark" name="remark" style="width:100%; padding-top: 10px; border:0; padding-left: 5px;" placeholder="Remark">{{ $cart->remark }}</textarea>
                                       <span class="text-danger" id="remark{{ $cartId }}Err"></span>
                                       <span class="text-danger" id="cartId{{ $cartId }}Err"></span>
                                    </span>
                                 </div>
                               </div>
                               <div style="display: flex; justify-content:flex-end;">
                                 <!-- <p><p>Choose a quantity between 1 - 1000 for instant ordering. For higher quantities, you will be allowed to request quotations from Sales Team.</p> -->
                                 <input id="cartId{{ $cartId }}" type="hidden" name="cartId" value="{{ $cartId }}">
                                 <button id="cartFormBtn{{ $cartId }}" type="submit" class="home_btn" style="display:block; text-align: right; margin: inherit; font-size: 17px;">Save</button>
                               </div>
                                
                            </form>
                          </div>

                            @php $fileTab++; @endphp
                            @endforeach
                          @endif
                        </div>
                          
                      </div>

                </div>
            </div>
            <div class="tab-content tab-3-content" id="tab3">
                <div class="tab-content-inner">
                    <div class="tab-content-left">
                      <div class="input-result-boxes-sec" style="margin-top:0;">
                         <a href="#" class="result-box shipping-datail-value-sec">
                            <span class="shipping-name" style="margin-bottom:4%; display:block;">Shipping Details</span>

                            @if(!empty($customerAddress))
                            <p class="shipping-datail-value-sec-inner" style="text-transform: uppercase; font-size: 11px;">
                              <p>{{ $customerAddress->shipping_name }}</p>
                              <p>Company: {{ $customerAddress->shipping_company_name ?? '-' }}</p>
                              <p>Address: {{ $customerAddress->shipping_address }}</p>
                              <p>City: {{ $customerAddress->shipping_city }}</p>
                              <p>State: {{ $customerAddress->shipping_state }}</p>
                              <p>Pincode: {{ $customerAddress->shipping_pincode }}</p>
                              <p>Email: {{ $customerAddress->shipping_email }}</p>
                              <p>Mobile: {{ $customerAddress->shipping_phone }}</p>
                              <p>GST Number: {{ $customerAddress->gst_number ?? '-' }}</p>
                            </p>

                            <span class="shipping-name" style="margin-top:4%;margin-bottom:4%; display:block;">Billing Details</span>
                            <p class="shipping-datail-value-sec-inner" style="text-transform: uppercase; font-size: 11px;">

                              @if(!$customerAddress->is_billing_same)
                              <p>{{ $customerAddress->billing_name }}</p>
                              <p>Company: {{ $customerAddress->billing_company_name ?? '-' }}</p>
                              <p>Address: {{ $customerAddress->billing_address }}</p>
                              <p>City: {{ $customerAddress->billing_city }}</p>
                              <p>State: {{ $customerAddress->billing_state }}</p>
                              <p>Pincode: {{ $customerAddress->billing_pincode }}</p>
                              <p>Email: {{ $customerAddress->billing_email }}</p>
                              <p>Mobile: {{ $customerAddress->billing_phone }}</p>
                              @else
                              <p>The billing address is same as shipping address.</p>
                              @endif
                            </p>
                            @else
                            <p class="shipping-datail-value-sec-inner" style="text-transform: uppercase; font-size: 11px;">
                              <p>Shipping address is not yet saved.</p>                              
                            </p>
                            @endif

                            <!-- <p class="delete">
                              <b class="rvpric">
                                <i class="icon-rupee"></i> <span class="delete-price">0.63</span> 
                                 <span class="text-danger small delete-icon" title="banner-1-newjpg-1723629445.jpg">
                                 <i class="icon-trash"></i>
                                  Delete File</span>
                                </b>
                            </p> -->
                          </a>
                       </div>
                       <div class="home_btn_main">
                        <button class="home_btn proceed-to-next-btn-tab3" style="margin-left:23px;">Proceed to Next Step <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <div class="tab-content-right-sec shopping-cart" style="overflow-y:auto;">
                       <div class="cart-ship-info">
                        <h6>SHIPPING DETAILS</h6>
                         <form id="customerAddressForm" method="post" action="{{ route('saveAddress') }}">
                            <ul class="row">
                              <li class="col-md-6">
                                <label>*NAME
                                  <input type="text" name="shippingName" value="{{ isset($customerAddress->shipping_name)? $customerAddress->shipping_name:$customerData->name }}" placeholder="">
                                </label>
                                <span class="error" id="shippingNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <label>COMPANY NAME
                                  <input type="text" name="shippingCompanyName" value="{{ isset($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:'' }}" placeholder="">
                                </label>
                                <span class="error" id="shippingCompanyNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <label>GST Number
                                  <input type="text" name="gstNumber" value="{{ isset($customerAddress->gst_number)? $customerAddress->gst_number:'' }}" placeholder="">
                                </label>
                                <span class="error" id="gstNumberErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <label>*ADDRESS
                                  <input type="text" name="shippingAddress" value="{{ isset($customerAddress->shipping_address)? $customerAddress->shipping_address:$customerData->address }}" placeholder="">
                                </label>
                                <span class="error" id="shippingAddressErr"></span>
                              </li>

                              <li class="col-md-6">
                                <label>*TOWN/CITY
                                  <input type="text" name="shippingCity" value="{{ isset($customerAddress->shipping_city)? $customerAddress->shipping_city:$customerData->city }}" placeholder="">
                                </label>
                                <span class="error" id="shippingCityErr"></span>
                              </li>
                            
                              <li class="col-md-6">
                                <label>*State
                                  <input type="text" name="shippingState" value="{{ isset($customerAddress->shipping_state)? $customerAddress->shipping_state:$customerData->state }}" placeholder="">
                                </label>
                                <span class="error" id="shippingStateErr"></span>
                              </li>

                              <li class="col-md-6">
                                <label>*Pincode
                                  <input type="number" name="shippingPincode" value="{{ isset($customerAddress->shipping_pincode)? $customerAddress->shipping_pincode:'' }}" placeholder="">
                                </label>
                                <span class="error" id="shippingPincodeErr"></span>
                              </li>
                              
                              <!-- EMAIL ADDRESS -->
                              <li class="col-md-6">
                                <label> *EMAIL ADDRESS
                                  <input type="text" name="shippingEmail" value="{{ isset($customerAddress->shipping_email)? $customerAddress->shipping_email:$customerData->email }}" placeholder="">
                                </label>
                                <span class="error" id="shippingEmailErr"></span>
                              </li>
                              <!-- PHONE -->
                              <li class="col-md-6">
                                <label> *PHONE
                                  <input type="text" name="shippingPhone" value="{{ isset($customerAddress->shipping_phone)? $customerAddress->shipping_phone:$customerData->phone }}" placeholder="">
                                </label>
                                <span class="error" id="shippingPhoneErr"></span>
                              </li>

                              @php

                                $isBillingAddressSame = true;
                                if(isset($customerAddress->is_billing_same)) {
                                  if(!$customerAddress->is_billing_same) {
                                    $isBillingAddressSame = false;
                                  }
                                } 

                              @endphp
                              
                              <!-- CREATE AN ACCOUNT -->
                              <li class="col-md-6">
                                <div class="checkbox margin-0 margin-top-20">
                                  <input {{ $isBillingAddressSame? 'checked':''; }} id="checkbox1" class="styled" type="checkbox" name="isBillingAddressSame" value="true">
                                  <label for="checkbox1"> Is Billing Address Same as Shipping Address </label>
                                </div>
                              </li>
                            </ul>
                          
                            <!-- SHIPPING info -->
                            <h6 style="{{ $isBillingAddressSame? '':'display:block'; }}" class="billing-address margin-top-20">Billing Details</h6>
                            <ul style="{{ $isBillingAddressSame? '':'display:block'; }}" class="billing-address row">
                              
                              <!-- Name -->
                              <li class="col-md-6">
                                <label> *NAME
                                  <input type="text" name="billingName" value="{{ isset($customerAddress->billing_name)? $customerAddress->billing_name:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <!-- COMPANY NAME -->
                                <label>COMPANY NAME
                                  <input type="text" name="billingCompanyName" value="{{ isset($customerAddress->billing_company_name)? $customerAddress->billing_company_name:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingCompanyNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <!-- ADDRESS -->
                                <label>*ADDRESS
                                  <input type="text" name="billingAddress" value="{{ isset($customerAddress->billing_address)? $customerAddress->billing_address:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingAddressErr"></span>
                              </li>
                              <!-- TOWN/CITY -->
                              <li class="col-md-6">
                                <label>*TOWN/CITY
                                  <input type="text" name="billingCity" value="{{ isset($customerAddress->billing_city)? $customerAddress->billing_city:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingCityErr"></span>
                              </li>
                              
                              <!-- COUNTRY -->
                              <li class="col-md-6">
                                <label>*State
                                  <input type="text" name="billingState" value="{{ isset($customerAddress->billing_state)? $customerAddress->billing_state:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingStateErr"></span>
                              </li>

                              <li class="col-md-6">
                                <label>*Pincode
                                  <input type="text" name="billingPincode" value="{{ isset($customerAddress->billing_pincode)? $customerAddress->billing_pincode:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingPincodeErr"></span>
                              </li>
                              
                              <!-- EMAIL ADDRESS -->
                              <li class="col-md-6">
                                <label> *EMAIL ADDRESS
                                  <input type="text" name="billingEmail" value="{{ isset($customerAddress->billing_email)? $customerAddress->billing_email:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingEmailErr"></span>
                              </li>
                              <!-- PHONE -->
                              <li class="col-md-6">
                                <label> *PHONE
                                  <input type="text" name="billingPhone" value="{{ isset($customerAddress->billing_phone)? $customerAddress->billing_phone:'' }}" placeholder="">
                                </label>
                                <span class="error" id="billingPhoneErr"></span>
                              </li>
                            </ul>

                            <div class="biiling-submit-btn">
                              <button id="customerAddressBtn" type="submit" class="btn">SUBMIT</button>
                            </div>
                            <div id="customerAddressFormMsg"></div>

                          </form>
                       </div>
                    </div>
                </div>
            </div>
            <div class="tab-content tab-4-content" id="tab4">
                <div class="tab-content-inner">
                    <div class="tab-content-left">
                      <div class="tab-content-left-for-btn">
                        <div class="shopping-cart small-cart">
                          <div class="cart-ship-info">
                            <div class="rows"> 
                              
                              <!-- DISCOUNT CODE -->
                              <div class="col-12">
                                <h6 style="color: #fff; font-size: 14px;" id="discount-code">DISCOUNT CODE</h6>
                                <form id="couponCodeForm" method="post" action="{{ route('applyUploadPromo') }}">
                                  <input id="couponCode" name="couponCode" type="text" value="" placeholder="ENTER YOUR CODE IF YOU HAVE ONE">
                                  <button id="couponCodeFormBtn" type="submit" class="btn btn-small btn-dark">APPLY CODE</button>
                                </form>
                                <span id="couponCodeErr" class="removeErr"></span>

                                <h6 style="margin-top: 2rem; color: #fff; font-size: 14px;" id="suggest-you-input">Suggest Your Inputs</h6>
                                <textarea id="remark" class="form-control" rows="3" name="remark"></textarea>

                                <div class="order-detail">
                                  <p>Weight <span id="cartWeight">0.0255</span></p>
                                  <p>Discount <span id="discountData">0</span></p>
                                  <p>Shipping Charge <span id="shippingData">0</span></p>
                                  <p>Sub Total <span id="subTotalData">2.67</span></p>
                                  <p>Packaging Charges ({{ setting('packaging_charges') }}%) <span id="packagingChargeData">0.1335</span></p>
                                  <p class="all-total">TOTAL<span id="totalData">2.8035</span></p>
                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                      <div class="home_btn_main {{ empty($cartData)? 'hide':'' }}" id="place-order" style="bottom: -14%; width:100%;">
                          
                          <ul>
                            <li>
                              <div class="courier-checkbox checkbox-design" id="checkbox-style">
                                <div class="checkbox-div">
                                  <label for="courier-dtdc">DTDC</label>
                                  <input class="courier-option" onchange="updateCourier(this)" style="" name="courier" value="DTDC" id="courier-dtdc" class="styled" type="checkbox" checked>
                                </div>
                                <div class="checkbox-div">
                                  <label for="sbi">India Post</label>
                                  <input class="courier-option" onchange="updateCourier(this)" style="" name="courier" value="India Post" id="courier-ip" class="styled" type="checkbox">
                                </div>
                              </div>
                            </li>

                            <li style="margin-top:10px">
                              <div class="courier-checkbox checkbox-design" id="checkbox-style">
                                <div class="checkbox-div">
                                  <label for="courier-dtdc">Phonepe</label>
                                  <input class="paymentMethod-option" onchange="updatePaymentMethod(this)" name="paymentMethod" value="phonepe" id="phonepe" class="styled" type="checkbox" checked>
                                </div>
                                <div class="checkbox-div">
                                  <label for="sbi">Payu</label>
                                  <input class="paymentMethod-option" onchange="updatePaymentMethod(this)" name="paymentMethod" value="payu" id="payu" class="styled" type="checkbox">
                                </div>
                              </div>
                            </li>

                            <li>
                              <div class="checkbox">
                                <input style="width:20px" name="acceptTermsCondition" value="true" id="checkbox3-4" class="styled" type="checkbox">
                                <label for="checkbox3-4"> I’VE READ AND ACCEPT THE <span onclick="redirect('{{ route('termsAndConditionPage') }}')" class="color"> TERMS & CONDITIONS </span> </label>

                                <span id="acceptTermsConditionErr" class="error text-danger"></span>
                              </div>
                            </li>
                          </ul>

                          <button id="placeOrderBtn" type="button" class="home_btn place-order-btn" >PLACE ORDER <i class="fa fa-arrow-right" aria-hidden="true"></i></button>

                      </div>
                    </div>
                    <div id="reviewOrderDetails" class="tab-content-right-sec" style="overflow-y: auto;">
                       <div class="shipping-tab-inner">
                         <div class="shipping-heading">
                           <h2>Shipping Address</h2>
                           <button class="home_btn edit-address" style="margin:unset;">EDIT ADDRESS</button>
                         </div>
                         <div class="shipping-content">
                           <p class="nobottommargin"><span class="t600">Contact Person: dd</span><br>Address: ddd<br>Landmark :<br>PinCode :282003<br>Mobile :8077796066<br></p>
                         </div>
                         <div class="order-confirmation">
                           <h2>Order confirmation email will sent to : <a href="mailto:faizan7017084@gmail.com">faizan7017084@gmail.com</a></h2>
                           <p>Expected Delivery Timeline - Delhi NCR (4 to 7 working Days) and in other states (7 to 10 Working Days).</p>
                         </div>
                       </div>
                       <div class="shipping-tab-inner" style="margin-top:5%;">
                         <div class="shipping-heading">
                           <h2>Printing Order</h2>
                           <button class="home_btn edit-order" style="margin:unset;">EDIT ORDER</button>
                         </div>
                          <div class="edit-order-row">
                            <p style="font-weight:700;">banner-02jpg-1723742722.jpg</p>
                            <div class="edit-order-btns">
                              <a href="#" class="active">PDF PRINT</a>
                              <a href="#">PRICE : <span>Rs 0.63</span></a>
                              <a href="#">PAGES : <span>1</span></a>
                              <a href="#">COPIES : <span>1</span></a>
                              <a href="#">PAPER TYPE : <span>75 GSM NORMAL PAPER</span></a>
                              <a href="#">PAPER SIZE : <span>A4</span></a>
                              <a href="#">PRINTED COLOUR : <span>BLACK & WHITE</span></a>
                              <a href="#">COVER OPTION : <span>NO COVER</span></a>
                              <a href="#">PRINTING SIDES : <span>DOUBLE SIDE PRINTING</span></a>
                              <a href="#">BINDING OPTIONS : <span>NO BINDING</span></a>
                            </div>
                          </div>
                       </div>
                    </div>
                </div>
            </div>
            
           </div>
          </div>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">

  //start new function to get the pricing data
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const editAddress = (el) => {
    $('.tab-3').trigger('click').addClass('active');
  }

  const editOrder = (el) => {
    $('.tab-2').trigger('click').addClass('active');
  }

  $('.fileTab-list').click(function(event) {
    $('.fileTab-content').removeClass('show');
  });

  const filterFiles = (el) => {
    const searchValue = $(el).val().toLowerCase();
    $('.input-result-boxes-sec a').each(function() {
      const text = $(this).find('.rvdoc').text().toLowerCase();
      if (text.includes(searchValue)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  }

  const getTab4Data = (el) => {

    url = $(el).data('url');

    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'json',
      data: {action: 'tab4'},
      success: function(res) {
        if (res.error == true) {
          swal('Error!', res.msg, 'error');
        } else {
          $("#reviewOrderDetails").html(res.data);
          $("#cartWeight").html(res.weight);
          $("#discountData").html(res.discount);
          $("#shippingData").html(res.shippingCharge);
          $("#subTotalData").html(res.subTotal);
          $("#packagingChargeData").html(res.packagingCharges);
          $("#totalData").html(res.paidAmount);

          if (!res.paidAmount) {
            $('.home_btn_main').addClass('hide');
          } else {
            $('.home_btn_main').removeClass('hide');
          }

        }
      }
    });

  }

  const updateCartForm = (event) => {
    event.preventDefault();
      
    let cartId = event.target.getAttribute('data-id');
    let formId = "#" + "cartForm" + cartId;
    const formBtn = "#" + "cartFormBtn" + cartId;  // Corrected string concatenation

    const formData = $(formId).serialize();
    const formUrl = $(formId).attr('action');

    $.ajax({
      url: formUrl,
      type: 'POST',
      dataType: 'json',
      data: formData,
      beforeSend: function() {
        $(formBtn).html('Please Wait...');
        $('.text-danger').text('');
      }, success: function(res) {

        if (res.error == true) {
          if (res.eType == 'field') {
            $.each(res.errors, function(index, error) {
               $(`#${index}${cartId}Err`).text(error);
            });
          } else {
            swal('Error!', res.msg, 'error');
          }
        } else {
          swal('Success!', res.msg, 'success');
        }

        $(formBtn).html('Save');
      }
    })
    

  }

  const getPaperSizeList = (el) => {
    url = $(el).data('url');
    cartId = $(el).data('id');
    productId = $(el).find(':selected').val();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
          productId: productId,
          cartId: cartId,
          action: 'size'
        },
        beforeSend: function() {

        }, success: function(res) {

          if (res.error == true) {
            swal('Error!', res.msg, 'error');
          }
            
            //unselect all
            $(`#paperSize${cartId}`).html(`<option value="">Select Paper Size</option>`);
            $(`#paperGsm${cartId}`).html(`<option value="">Select Paper GSM</option>`);
            $(`#paperType${cartId}`).html(`<option value="">Select Paper Type</option>`);
            $(`#sides${cartId}`).html(`<option value="">Select Print Sides</option>`);
            $(`#color${cartId}`).html(`<option value="">Select Color</option>`);
            $(`#binding${cartId}`).html(`<option value="">Select Binding</option>`);
            $(`#lamination${cartId}`).html(`<option value="">Select Lamination</option>`);
            $(`#cover${cartId}`).html(`<option value="">Select Cover</option>`);

            $(`#paperSize${cartId}`).html(res.paperSizeOptions);

        }
    })

  }

  const getPaperGsmList = (el) => {
    url = $(el).data('url');
    cartId = $(el).data('id');
    productId = $(`#product`+cartId).find(':selected').val();
    paperSizeId = $(el).find(':selected').val();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
          productId: productId,
          paperSize: paperSizeId,
          cartId: cartId,
          action: 'gsm'
        },
        beforeSend: function() {

        }, success: function(res) {

          if (res.error == true) {
            swal('Error!', res.msg, 'error');
          }
            
            //unselect all
            $(`#paperGsm${cartId}`).html(`<option value="">Select Paper GSM</option>`);
            $(`#paperType${cartId}`).html(`<option value="">Select Paper Type</option>`);
            $(`#sides${cartId}`).html(`<option value="">Select Print Sides</option>`);
            $(`#color${cartId}`).html(`<option value="">Select Color</option>`);
            $(`#binding${cartId}`).html(`<option value="">Select Binding</option>`);
            $(`#lamination${cartId}`).html(`<option value="">Select Lamination</option>`);
            $(`#cover${cartId}`).html(`<option value="">Select Cover</option>`);

            calculatePrice(cartId);

            $(`#paperGsm${cartId}`).html(res.gsmOptions);
            $(`#binding${cartId}`).html(res.bindingOptions);
            $(`#lamination${cartId}`).html(res.laminationOptions);

        }
    })

  }

  const getPaperTypeList = (el) => {
    url = $(el).data('url');
    cartId = $(el).data('id');
    productId = $(`#product`+cartId).find(':selected').val();
    paperSizeId = $(`#paperSize`+cartId).find(':selected').val();
    paperGsmId = $(el).find(':selected').val();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
          productId: productId,
          paperSize: paperSizeId,
          paperGsm: paperGsmId,
          cartId: cartId,
          action: 'paper_type'
        },
        beforeSend: function() {

        }, success: function(res) {

          if (res.error == true) {
            swal('Error!', res.msg, 'error');
          }
            
            //unselect all
            $(`#paperType${cartId}`).html(`<option value="">Select Paper Type</option>`);
            $(`#sides${cartId}`).html(`<option value="">Select Print Sides</option>`);
            $(`#color${cartId}`).html(`<option value="">Select Color</option>`);

            calculatePrice(cartId);
            $(`#paperType${cartId}`).html(res.paperOptions);

        }
    })

  }

  const getPaperSideList = (el) => {
    url = $(el).data('url');
    cartId = $(el).data('id');
    productId = $(`#product`+cartId).find(':selected').val();
    paperSizeId = $(`#paperSize`+cartId).find(':selected').val();
    paperGsmId = $(`#paperGsm`+cartId).find(':selected').val();
    paperTypeId = $(el).find(':selected').val();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
          productId: productId,
          paperSize: paperSizeId,
          paperGsm: paperGsmId,
          paperType: paperTypeId,
          cartId: cartId,
          action: 'paper_sides'
        },
        beforeSend: function() {

        }, success: function(res) {

          if (res.error == true) {
            swal('Error!', res.msg, 'error');
          }
            
            //unselect all
            $(`#sides${cartId}`).html(`<option value="">Select Print Sides</option>`);
            $(`#color${cartId}`).html(`<option value="">Select Color</option>`);

            calculatePrice(cartId);
            $(`#sides${cartId}`).html(res.paperSides);

        }
    })

  }

  const getPaperColorList = (el) => {
    url = $(el).data('url');
    cartId = $(el).data('id');
    productId = $(`#product`+cartId).find(':selected').val();
    paperSizeId = $(`#paperSize`+cartId).find(':selected').val();
    paperGsmId = $(`#paperGsm`+cartId).find(':selected').val();
    paperTypeId = $(`#paperType`+cartId).find(':selected').val();
    paperSideId = $(el).find(':selected').val();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
          productId: productId,
          paperSize: paperSizeId,
          paperGsm: paperGsmId,
          paperType: paperTypeId,
          paperSides: paperSideId,
          cartId: cartId,
          action: 'paper_color'
        },
        beforeSend: function() {

        }, success: function(res) {

          if (res.error == true) {
            swal('Error!', res.msg, 'error');
          }
            
            //unselect all
            $(`#color${cartId}`).html(`<option value="">Select Color</option>`);

            calculatePrice(cartId);
            $(`#color${cartId}`).html(res.paperColor);

        }
    })

  }

  function calculateBindingPrice(cartId) {
    bindingPrice = $(`#binding${cartId}`).find(':selected').attr('data-price');
    bindingSplit = $(`#binding${cartId}`).find(':selected').data('split');
    getPaperSide = $(`#sides${cartId}`).find(':selected').val();
    qty = ($(`#noOfPages${cartId}`).val() == '')? 0:$(`#noOfPages${cartId}`).val();

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

  function calculatePrice(cartId) {    

    paperGsmPrice = 0;
    paperTypePrice = 0;
    paperSidesPrice = 0;
    paperColorPrice = 0;
    bindingPrice = 0;
    laminationPrice = 0;

    qty = ($(`#noOfPages${cartId}`).val() == '')? 0:$(`#noOfPages${cartId}`).val();
    noOfCopies = ($(`#noOfCopies${cartId}`).val() == '')? 1:$(`#noOfCopies${cartId}`).val();

    // if ($("#paperGsm").find(':selected').val() != '') {
    //   paperGsmPrice = $("#paperGsm").find(':selected').attr('data-weight');
    // }

    if ($(`#paperType${cartId}`).find(':selected').val() != '') {
      paperTypePrice = $(`#paperType${cartId}`).find(':selected').attr('data-price');
    }

    // if ($("#sides").find(':selected').val() != '') {
    //   paperSidesPrice = $("#sides").find(':selected').attr('data-price');
    // }

    if ($(`#color${cartId}`).find(':selected').val() != '') {
      paperColorPrice = $(`#color${cartId}`).find(':selected').attr('data-price');
    }

    if ($(`#binding${cartId}`).find(':selected').val() != '') {
      bindingPrice = $(`#binding${cartId}`).find(':selected').attr('data-price');
    }

    if ($(`#lamination${cartId}`).find(':selected').val() != '') {
      laminationPrice = $(`#lamination${cartId}`).find(':selected').attr('data-price');
    }

    // totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice)+parseFloat(bindingPrice)+parseFloat(laminationPrice);

    totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice);

    console.log(paperGsmPrice, paperTypePrice, paperSidesPrice, paperColorPrice, totalPrice);


    split = calculateBindingPrice(cartId).split;
    bindingPrice = calculateBindingPrice(cartId).bindingPrice;

    if (bindingPrice) {
        $("#splitDetails").html(`<strong>Split:</strong> ${split}`);
    } else {
        $("#splitDetails").html('');
    }

    // if(paperSidesPrice != 0 && paperColorPrice != 0) {
    //   totalPrice = parseFloat(totalPrice) - parseFloat(paperSidesPrice);
    // }

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

    //$(`#perSheetPrice${cartId}`).html(`₹`+totalPrice)
    $(`.perSheetPrice${cartId}`).html(`Rs `+(finalPrice*noOfCopies))

    //console.log(totalPrice, parseInt(qty));

  }

  // end pricing data function

// add more btn js
 $(".addMoreBtn").click(function(event) {
    event.preventDefault();
   $('.tab-heading-sec a').removeClass('active');
    $(".tab-1").toggleClass('active');
    $('.tab-content').removeClass('active');
    $(".tab-1-content").addClass('active');

  });

// edit order btn js
  $(".edit-order").click(function(event) {
    event.preventDefault();
   $('.tab-heading-sec a').removeClass('active');
    $(".tab-2").toggleClass('active');
    $('.tab-content').removeClass('active');
    $(".tab-2-content").addClass('active');
  });

  // edit address btn js
  $(".edit-address").click(function(event) {
    event.preventDefault();
   $('.tab-heading-sec a').removeClass('active');
    $(".tab-3").toggleClass('active');
    $('.tab-content').removeClass('active');
    $(".tab-3-content").addClass('active');
  });

 // proceed to next btn js for tab 2
  $(".proceed-to-next-btn-tab2").click(function(event) {
    event.preventDefault();
   $('.tab-heading-sec a').removeClass('active');
    $(".tab-3").toggleClass('active');
    $('.tab-content').removeClass('active');
    $(".tab-3-content").addClass('active');
  });

  // proceed to next btn js for tab 3
  $(".proceed-to-next-btn-tab3").click(function(event) {
    event.preventDefault();
   $('.tab-heading-sec a').removeClass('active');
    $(".tab-4").toggleClass('active');
    $('.tab-content').removeClass('active');
    $(".tab-4-content").addClass('active');
  });


// tabs js
 $('.tab-heading-sec a').click(function(e){
    e.preventDefault();

    if ($(this).hasClass("disabled")) {
      return false;
    }

   $('.tab-heading-sec a').removeClass('active');
   $(this).addClass('active')
   var targetTab = $(this).attr('href');

   if (targetTab == '#tab2') {
    //The first screen get displayed by default
    $('.input-result-boxes-sec a:first').trigger('click').addClass('active');
    const getId = $('.input-result-boxes-sec a:first').attr('href');
    $(getId).addClass('active show');
   }

   $('.tab-content').removeClass('active')
   $(`.tab-content${targetTab}`).addClass('active')

});

// delete icon js 
  // $(".delete-icon").click(function(event) {
  //     event.preventDefault();
  //     var box = $(this).parent('.rvpric').parent('.delete').parent('.result-box');
  //     box.hide();    
  //     // console.log(box)
  // });

 const deleteFile = (el) => {
  const cartId = $(el).data('id');
  const url = $(el).data('url');

   swal({
      title: 'Are you sure?',
      text: "It will permanently deleted !",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then(function(isConfirmed) {

      if(isConfirmed) {
        $.ajax({
          url: url,
          type: 'POST',
          dataType: 'JSON',
          data: {cartId: cartId},
          success: function(res) {
            location.reload();
          }
        })
      }
      
    })

 }



  function redirect(url) {
    window.location.href = url
  }

  function updateCourier(el) {
    getVal = $(el).val();
    if(getVal == 'India Post') {
      $("#courier-dtdc").prop('checked', false);
      $("#courier-ip").prop('checked', true);
    } else {
      $("#courier-dtdc").prop('checked', true);
      $("#courier-ip").prop('checked', false);
    }
  }

  function updatePaymentMethod(el) {
    getVal = $(el).val();
    if(getVal == 'phonepe') {
      $("#payu").prop('checked', false);
      $("#phonepe").prop('checked', true);
    } else {
      $("#payu").prop('checked', true);
      $("#phonepe").prop('checked', false);
    }
  }

  function removeDoc(el) {

    url = $(el).attr('data-url');
    id = $(el).attr('data-id');

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {id: id},
        beforeSend: function() {
          $("#dropzoneMsg").html("");
        }, success: function(res) {
          if(res.error == true) {
            if(res.eType == 'field') {
              $.each(res.errors, function(index, val) {
                 $("#"+index+"Err").html(val);
              });
            } else {
              $("#dropzoneMsg").html(res.msg).css('color', 'red');
            }
          } else {
            $("#dropzoneMsg").html(res.msg).css('color', 'green');
            $("#uploadedDocuments").html(res.docTemplate);
          }

          timeout = setTimeout(function() {
              //$this.removeAllFiles();
              $("#dropzoneMsg").html('');
              clearTimeout(timeout);
          }, 3000);

        }
      });

  }

  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#checkbox1").change(function (e) {
      if ($(this).is(':checked')) {
        $('.billing-address').hide();
      } else {
        $('.billing-address').show();
      }
    });

    $("#customerAddressForm").submit(function(event) {
      event.preventDefault();
      
      formData = $(this).serialize();
      url = $(this).attr('action');

      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $(".error").html('');
          $("#customerAddressBtn").html('Please Wait...');
          $("#customerAddressFormMsg").html('');
        }, success: function(res) {
          if(res.error == true) {
            if(res.eType == 'field') {
              $.each(res.errors, function(index, val) {
                 $("#"+index+"Err").html(val);
              });
            } else {
              swal('Error!', res.msg, 'error');
            }
          } else {
            //$("#customerAddressFormMsg").html(res.msg);
            swal('Success!', res.msg, 'success');

            $(".shipping-datail-value-sec").html(res.deliveryDetailTemp);

            subTotal = ((res.priceData.total-res.priceData.shipping));
            $("#discountData").html(res.priceData.discount)
            $("#shippingData").html(res.priceData.shipping)
            $("#subTotalData").html(res.priceData.subTotal);
            $("#packagingChargeData").html(res.packagingCharges);
            $("#totalData").html(res.paidAmount);
          }

          $("#customerAddressBtn").html('Submit')

        }
      });
      

    });

    $("#placeOrderBtn").click(function (e) {
      
      acceptTerms = '';
      if ($("[name=acceptTermsCondition]").is(':checked')) {
        acceptTerms = 'true';
      }

      remark = $("#remark").val();
      // wetransferLink = $("#wetransferLink").val();
      wetransferLink = "";
      courier = $(".courier-option:checkbox:checked").val();
      paymentMethod = $(".paymentMethod-option:checkbox:checked").val();

      formData = $("#customerAddressForm").serialize()+"&acceptTermsCondition="+acceptTerms+"&remark="+remark+"&wetransferLink="+wetransferLink+"&courier="+courier+"&paymentMethod="+paymentMethod;

      url = "{{ route('uploadPlaceOrder') }}"

      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $(".error").html('');
          $("#placeOrderBtn").html('Please Wait...');
          $("#placeOrderMsg").html('');
        }, success: function(res) {
          if(res.error == true) {
            if(res.eType == 'field') {
              $.each(res.errors, function(index, val) {
                 $("#"+index+"Err").html(val);
              });

              swal('Error!', res.msg, 'error');

            } else {
              $("#placeOrderMsg").html(res.msg).css('color', 'red');
              swal('Error!', res.msg, 'error');
            }
          } else {
            $("#placeOrderMsg").html(res.msg).css('color', 'green');
            window.location.href = res.redirect;
          }

          $("#placeOrderBtn").html('Place Order')

        }
      });

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
              //swal('Error!', res.msg, 'error');
            }
          } else {

            subTotal = ((res.priceData.total-res.priceData.shipping));

            $("#discountData").html(res.priceData.discount)
            $("#shippingData").html(res.priceData.shipping)
            $("#subTotalData").html(res.priceData.subTotal);
            $("#packagingChargeData").html(res.packagingCharges);
            $("#totalData").html(res.paidAmount);
            $("#couponCodeErr").html(res.msg).css('color', 'white');
            //swal('Success!', res.msg, 'success');

          }

          $("#couponCodeFormBtn").html('Apply Code');
        } 
      })

    });

    // document.getElementById('openDropzoneButton').addEventListener('click', function() {
    //     $("#upload").trigger('click');
    // });

    Dropzone.autoDiscover = false;
    $("#upload").dropzone({
        url: "{{ route('doUploadFiles') }}",
        params: {
          'slug': '{{ $slug }}'
        },
        method: 'POST',
        parallelUploads: 30,
        uploadMultiple: true,
        maxFilesize: 1000, //MB
        maxFiles: 30, //Cannot upload more than 30 files
        acceptedFiles: ".jpg, .jpeg, .png, .zip, application/pdf, application/zip",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function() {
            this.on("error", function(file, res) {
                // Handle errors if the file size exceeds the limit or for other reasons
                if (res) {
                    console.log(res);
                }
            }),
            this.on("success", function(file, res) {
                // Handle errors if the file size exceeds the limit or for other reasons
        
                res = JSON.parse(res);
                $this = this;

                if (res.error) {
                    
                    if (res.eType == 'final') {
                        //toastr.error(res.msg);
                        $("#dropzoneMsg").html(res.msg).css('color', 'red');
                    } else {
                        $.each(res.errors, function(index, val) {
                           //toastr.error(val);  
                          $("#dropzoneMsg").html(val).css('color', 'red');
                        });
                    }

                } else {

                    // toastr.success(res.msg);

                    $("#dropzoneMsg").html(res.msg).css('color', 'green');
                    // $("#uploadedDocuments").html(res.docTemplate);

                    timeout = setTimeout(function() {
                        $this.removeAllFiles();
                        $("#dropzoneMsg").hide();
                        window.location.href = res.redirect;
                        clearTimeout(timeout);
                    }, 3000);

                }

            })
        },
    });

  });
</script>

@endsection