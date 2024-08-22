@extends('vwFrontMaster')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<style type="text/css">
  /*Remove Image CSS*/
  .shopping-cart .cart-ship-info label{
    color: #302f2e;
    font-weight:700;
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
  font-size:20px;
  color:#000;
  margin:0;
  font-weight:600;
}
.upload-result-heading{
  display:flex;
  align-items:center;
  column-gap:4px;
}
.upload-file-result{
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5%;
    background: #fff;
    margin:6% 0;
    border-radius: 5px;
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
                <a href="#tab1" class="tab tab-1 active">
                    <h1>1</h1>
                    <p>Upload File</p>
                </a>
                <a href="#tab2" class="tab tab-2">
                    <h1>2</h1>
                    <p>Edit Setting</p>
                </a>
                <a href="#tab3" class="tab tab-3">
                    <h1>3</h1>
                    <p>Delivery Details</p>
                </a>
                <a href="#tab4" class="tab tab-4">
                    <h1>4</h1>
                    <p>Review Order</p>
                </a>
           </div>
           <div class="tab-content-sec">

            <div class="tab-content tab-1-content active" id="tab1">
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
                        <button>TOTAL FILES <span>0</span></button>
                        <button class="drop-second-btn"><span>+</span>ADD MORE FILES</button>
                        <button><a href="tel:9643230482"><i class="fa fa-cog" aria-hidden="true"></i>Technical Issue : 9643230482</a></button>
                      </div>
                       <div class="tab-content-right-sec-inner">
                         <div id="dropzone">
                            <form action="http://localhost/eprintcafe-new/checkout/doUploadDropbox" class="dropzone needsclick dz-clickable" id="upload">
                              <div class="dz-message needsclick">
                                <span class="text">
                                <!-- <img src="http://localhost/eprintcafe/public/frontend/img/upload.png" alt="Upload"> -->
                                <img src="http://localhost/epcafe/public/frontend/img/upload.png" alt="Upload">
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
            <div class="tab-content tab-2-content" id="tab2">
                <div class="tab-content-inner">
                      <div class="tab-content-left">
                         <input placeholder="Filter Files" class="form-control" type="text" style="margin-top: 10px;">
                         <div class="input-result-boxes-sec">
                           <a href="#" class="result-box">
                              <span class="rvdoc">banner-01jpg-1723629437.jpg-PDF Print</span>
                              <p class="rvmargbot0" style="text-transform: uppercase; font-size: 11px;"><span><span>Pages : 1| </span> <span>Copies : 1| </span></span><span> PAPER TYPE : 75gsm Normal Paper| </span><span> PAPER SIZE : A4| </span><span> PRINTED COLOUR : BLACK &amp; WHITE| </span><span> COVER OPTION : NO COVER| </span><span> PRINTING SIDES : Double Side Printing| </span><span> BINDING OPTIONS : NO BINDING| </span></p>
                              <p class="delete">
                                <b class="rvpric">
                                  <i class="icon-rupee"></i> <span class="delete-price">0.63</span> 
                                   <span javascript:(0) class="text-danger small delete-icon" title="banner-1-newjpg-1723629445.jpg">
                                   <i class="icon-trash"></i>
                                    Delete File</span>
                                  </b>
                                </p>
                            </a>
                            <a href="#" class="result-box">
                              <span class="rvdoc">banner-01jpg-1723629437.jpg-PDF Print</span>
                              <p class="rvmargbot0" style="text-transform: uppercase; font-size: 11px;"><span><span>Pages : 1| </span> <span>Copies : 1| </span></span><span> PAPER TYPE : 75gsm Normal Paper| </span><span> PAPER SIZE : A4| </span><span> PRINTED COLOUR : BLACK &amp; WHITE| </span><span> COVER OPTION : NO COVER| </span><span> PRINTING SIDES : Double Side Printing| </span><span> BINDING OPTIONS : NO BINDING| </span></p>
                              <p class="delete">
                                <b class="rvpric">
                                  <i class="icon-rupee"></i> <span class="delete-price">0.63</span> 
                                   <span javascript:(0) class="text-danger small delete-icon" title="banner-1-newjpg-1723629445.jpg">
                                   <i class="icon-trash"></i>
                                    Delete File</span>
                                  </b>
                                </p>
                            </a>
                            <a href="#" class="result-box">
                              <span class="rvdoc">banner-01jpg-1723629437.jpg-PDF Print</span>
                              <p class="rvmargbot0" style="text-transform: uppercase; font-size: 11px;"><span><span>Pages : 1| </span> <span>Copies : 1| </span></span><span> PAPER TYPE : 75gsm Normal Paper| </span><span> PAPER SIZE : A4| </span><span> PRINTED COLOUR : BLACK &amp; WHITE| </span><span> COVER OPTION : NO COVER| </span><span> PRINTING SIDES : Double Side Printing| </span><span> BINDING OPTIONS : NO BINDING| </span></p>
                              <p class="delete">
                                <b class="rvpric">
                                  <i class="icon-rupee"></i> <span class="delete-price">0.63</span> 
                                   <span javascript:(0) class="text-danger small delete-icon" title="banner-1-newjpg-1723629445.jpg">
                                   <i class="icon-trash"></i>
                                    Delete File</span>
                                  </b>
                                </p>
                            </a>
                            <a href="#" class="result-box">
                              <span class="rvdoc">banner-01jpg-1723629437.jpg-PDF Print</span>
                              <p class="rvmargbot0" style="text-transform: uppercase; font-size: 11px;"><span><span>Pages : 1| </span> <span>Copies : 1| </span></span><span> PAPER TYPE : 75gsm Normal Paper| </span><span> PAPER SIZE : A4| </span><span> PRINTED COLOUR : BLACK &amp; WHITE| </span><span> COVER OPTION : NO COVER| </span><span> PRINTING SIDES : Double Side Printing| </span><span> BINDING OPTIONS : NO BINDING| </span></p>
                              <p class="delete">
                                <b class="rvpric">
                                  <i class="icon-rupee"></i> <span class="delete-price">0.63</span> 
                                   <span javascript:(0) class="text-danger small delete-icon" title="banner-1-newjpg-1723629445.jpg">
                                   <i class="icon-trash"></i>
                                    Delete File</span>
                                  </b>
                                </p>
                            </a>
                         </div>
                         <!-- <button class="home_btn">Proceed to Next Step <i class="fa fa-arrow-right" aria-hidden="true"></i></button> -->
                        <div class="home_btn_main edit-setting-btn">
                          <button class="home_btn proceed-to-next-btn-tab2" style="margin-left:23px;">Proceed to Next Step <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                        </div>
                      </div>
                    <div class="tab-content-right-sec">
                      <div class="drop-btn-sec">
                        <button>TOTAL FILES <span>0</span></button>
                        <button class="drop-second-btn addMoreBtn"><span>+</span>ADD MORE FILES</button>
                        <button><a href="tel:9643230482"><i class="fa fa-cog" aria-hidden="true"></i>Technical Issue : 9643230482</a></button>
                      </div>
                      <div class="upload-file-result">
                        <div class="upload-result-heading">
                          <h2>banner-01jpg-1723621102.jpg</h2>
                          <button class="faq-btn" style="margin-left:10px;"><i class="fa fa-cog" aria-hidden="true"></i>FAQ</button>
                        </div>
                        <h2>Rs 0.63</h2>
                      </div>
                      <form method="post" id="addToCartForm" class="detail_page_form" style="margin-top:25px;">
                         <div class="form-input-row">
                           <div class="input_field">
                              <label for="select">Paper Size</label>
                              <div class="detail_fields">
                                 <select id="paperSize" name="paperSize">
                                    <option value="">Select Paper Size</option>
                                    <option value="8">A5</option>
                                    <option selected="" value="9">A4</option>
                                    <option value="10">B5</option>
                                    <option value="11">A3</option>
                                 </select>
                                 <span class="text-danger" id="paperSizeErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">Paper GSM</label>
                              <div class="detail_fields">
                                 <select id="paperGsm" name="paperGsm">
                                    <option value="">Select Paper GSM</option>
                                    <option selected="" data-weight="0.0043" value="46">68 GSM - Normal Paper</option>
                                    <option selected="" data-weight="0.0085" value="30">75 GSM - Normal Paper</option>
                                    <option data-weight="0.0047" value="36">75 GSM - Fine/Premium Paper</option>
                                    <option data-weight="0.0056" value="47">90 GSM - JK Excel Bond</option>
                                    <option data-weight="0.0056" value="29">90 GSM - Duo Paper</option>
                                    <option data-weight="0.0063" value="98">100 GSM - Bond Paper - Letterhead - Edge</option>
                                    <option data-weight="0.0063" value="48">100 GSM - JK Excel Bond</option>
                                    <option data-weight="0.0063" value="31">100 GSM - Bond Paper</option>
                                    <option data-weight="0.0063" value="37">100 GSM - Duo Paper</option>
                                    <option data-weight="0.0063" value="97">100 GSM - Bond Paper - Letterhead</option>
                                    <option data-weight="0.0063" value="96">100 GSM - Duo Paper - Letterhead</option>
                                    <option data-weight="0.0081" value="34">130 GSM - Matte Paper</option>
                                    <option data-weight="0.0081" value="45">130 GSM - Glossy Paper</option>
                                    <option data-weight="0.0106" value="32">170 GSM - Matte Paper</option>
                                    <option data-weight="0.0106" value="38">170 GSM - Glossy Paper</option>
                                    <option data-weight="0.0131" value="44">210 GSM - White Gumming Sheet</option>
                                    <option data-weight="0.0138" value="39">220 GSM - Matte Paper</option>
                                    <option data-weight="0.0141" value="40">225 GSM - Glossy Paper</option>
                                    <option data-weight="0.0156" value="41">250 GSM - Glossy Paper</option>
                                    <option data-weight="0.0156" value="35">250 GSM - Matte Paper</option>
                                    <option data-weight="0.0169" value="95">270 GSM - Matte Paper</option>
                                    <option data-weight="0.0169" value="42">270 GSM - Glossy Paper</option>
                                    <option data-weight="0.0188" value="43">300 GSM - Glossy Paper</option>
                                    <option data-weight="0.0188" value="33">300 GSM - Matte Paper</option>
                                    <option data-weight="0.0188" value="49">300 GSM - Golden Sheet</option>
                                 </select>
                                 <span class="text-danger" id="paperGsmErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">Paper Type</label>
                              <div class="detail_fields">
                                 <select id="paperType" name="paperType">
                                    <option value="">Select Paper Type</option>
                                    <option selected="" data-price="0" value="10">Normal Paper</option>
                                    <option data-price="1500" value="10">Normal Paper</option>
                                 </select>
                                 <span class="text-danger" id="paperTypeErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">Print Sides</label>
                              <div class="detail_fields">
                                 <select id="sides" name="paperSides">
                                    <option value="">Select Print Sides</option>
                                    <option selected="" value="Single Side">Single Side</option>
                                    <option value="Double Side">Double Side</option>
                                 </select>
                                 <span class="text-danger" id="paperSidesErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">Color</label>
                              <div class="detail_fields">
                                 <select id="color" name="color">
                                    <option value="">Select Color</option>
                                    <option selected="" data-price="0.89" value="Black and White">Black and White</option>
                                    <option data-price="4.11" value="Color">Color</option>
                                 </select>
                                 <span class="text-danger" id="colorErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">Binding</label>
                              <div class="detail_fields">
                                 <select id="binding" name="binding">
                                    <option value="">Select Binding</option>
                                    <option data-price="100" data-split="250" value="19">Soft Binding/Paper Back</option>
                                    <option data-price="280" data-split="250" value="20">Hard Binding</option>
                                    <option data-price="100" data-split="250" value="21">Paper Back</option>
                                    <option data-price="80" data-split="250" value="22">Wiro</option>
                                    <option data-price="50" data-split="50" value="23">Centre Pin with Lamination</option>
                                    <option data-price="40" data-split="250" value="24">Spiral</option>
                                    <option data-price="100" data-split="250" value="25">Glue Bind</option>
                                    <option data-price="50" data-split="250" value="26">Spico</option>
                                    <option data-price="70" data-split="250" value="27">Velo</option>
                                    <option data-price="3" data-split="250" value="28">Side Staple Binding / corner staple</option>
                                    <option data-price="3" data-split="50" value="33">Corner Staple</option>
                                 </select>
                                 <span class="text-danger" id="bindingErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">Lamination</label>
                              <div class="detail_fields">
                                 <select id="lamination" name="lamination">
                                    <option value="">Select Lamination</option>
                                    <option data-price="15" value="5">Lamination - Soft</option>
                                 </select>
                                 <span class="text-danger" id="laminationErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">Cover</label>
                              <div class="detail_fields">
                                 <select id="cover" name="cover">
                                    <option value="">Select Cover</option>
                                 </select>
                                 <span class="text-danger" id="coverErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">No of Pages</label>
                              <div class="label_input choose">
                                 <input min="1" id="noOfPages" name="noOfPages" type="text" style="width:100%;" placeholder="No of Pages">
                                 <span class="text-danger" id="noOfPagesErr"></span>
                              </div>
                           </div>
                           <div class="input_field">
                              <label for="select">No of Copies</label>
                              <div class="label_input choose">
                                 <input min="1" id="noOfCopies" name="noOfCopies" type="text" style="width:100%;" placeholder="No of Copies" value="1">
                                 <span class="text-danger" id="noOfCopiesErr"></span>
                              </div>
                           </div>
                         </div>
                         <div>
                           <p><p>Choose a quantity between 1 - 1000 for instant ordering. For higher quantities, you will be allowed to request quotations from Sales Team.</p>
                         </div>
                        
                      </form>
                        
                    </div>
                </div>
            </div>
            <div class="tab-content tab-3-content" id="tab3">
                <div class="tab-content-inner">
                    <div class="tab-content-left">
                      <div class="input-result-boxes-sec" style="margin-top:0;">
                         <a href="#" class="result-box shipping-datail-value-sec">
                            <span class="shipping-name" style="margin-bottom:4%; display:block;">faizan</span>
                            <p class="shipping-datail-value-sec-inner" style="text-transform: uppercase; font-size: 11px;">
                              <p>faizan</p>
                              <p>City-Agra</p>
                              <p>State-UTTAR PRADESH</p>
                              <p>Pincode-282003</p>
                              <p>Mobile-8077796066</p>
                            </p>
                            <p class="delete">
                              <b class="rvpric">
                                <i class="icon-rupee"></i> <span class="delete-price">0.63</span> 
                                 <span class="text-danger small delete-icon" title="banner-1-newjpg-1723629445.jpg">
                                 <i class="icon-trash"></i>
                                  Delete File</span>
                                </b>
                              </p>
                          </a>
                       </div>
                       <div class="home_btn_main">
                        <button class="home_btn proceed-to-next-btn-tab3" style="margin-left:23px;">Proceed to Next Step <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <div class="tab-content-right-sec shopping-cart">
                       <div class="cart-ship-info">
                        <h6>SHIPPING DETAILS</h6>
                         <form id="customerAddressForm" method="post" action="http://localhost/eprintcafe/checkout/doSaveAddress">
                            <ul class="row">
                              <li class="col-md-6">
                                <label>*NAME
                                  <input type="text" name="shippingName" value="Arsalan" placeholder="">
                                </label>
                                <span class="error" id="shippingNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <label>COMPANY NAME
                                  <input type="text" name="shippingCompanyName" value="" placeholder="">
                                </label>
                                <span class="error" id="shippingCompanyNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <label>GST Number
                                  <input type="text" name="gstNumber" value="" placeholder="">
                                </label>
                                <span class="error" id="gstNumberErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <label>*ADDRESS
                                  <input type="text" name="shippingAddress" value="Address" placeholder="">
                                </label>
                                <span class="error" id="shippingAddressErr"></span>
                              </li>

                              <li class="col-md-6">
                                <label>*TOWN/CITY
                                  <input type="text" name="shippingCity" value="Agra" placeholder="">
                                </label>
                                <span class="error" id="shippingCityErr"></span>
                              </li>
                            
                              <li class="col-md-6">
                                <label>*State
                                  <input type="text" name="shippingState" value="AN" placeholder="">
                                </label>
                                <span class="error" id="shippingStateErr"></span>
                              </li>

                              <li class="col-md-6">
                                <label>*Pincode
                                  <input type="number" name="shippingPincode" value="" placeholder="">
                                </label>
                                <span class="error" id="shippingPincodeErr"></span>
                              </li>
                              
                              <!-- EMAIL ADDRESS -->
                              <li class="col-md-6">
                                <label> *EMAIL ADDRESS
                                  <input type="text" name="shippingEmail" value="arsalan.agra@gmail.com" placeholder="">
                                </label>
                                <span class="error" id="shippingEmailErr"></span>
                              </li>
                              <!-- PHONE -->
                              <li class="col-md-6">
                                <label> *PHONE
                                  <input type="text" name="shippingPhone" value="8439319918" placeholder="">
                                </label>
                                <span class="error" id="shippingPhoneErr"></span>
                              </li>

                                                
                              <!-- CREATE AN ACCOUNT -->
                              <li class="col-md-6">
                                <div class="checkbox margin-0 margin-top-20">
                                  <input checked="" id="checkbox1" class="styled" type="checkbox" name="isBillingAddressSame" value="true">
                                  <label for="checkbox1"> Is Billing Address Same as Shipping Address </label>
                                </div>
                              </li>
                            </ul>
                          
                            <!-- SHIPPING info -->
                            <h6 style="" class="billing-address margin-top-20">Billing Details</h6>
                            <ul style="" class="billing-address row">
                              
                              <!-- Name -->
                              <li class="col-md-6">
                                <label> *NAME
                                  <input type="text" name="billingName" value="" placeholder="">
                                </label>
                                <span class="error" id="billingNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <!-- COMPANY NAME -->
                                <label>COMPANY NAME
                                  <input type="text" name="billingCompanyName" value="" placeholder="">
                                </label>
                                <span class="error" id="billingCompanyNameErr"></span>
                              </li>
                              <li class="col-md-6"> 
                                <!-- ADDRESS -->
                                <label>*ADDRESS
                                  <input type="text" name="billingAddress" value="" placeholder="">
                                </label>
                                <span class="error" id="billingAddressErr"></span>
                              </li>
                              <!-- TOWN/CITY -->
                              <li class="col-md-6">
                                <label>*TOWN/CITY
                                  <input type="text" name="billingCity" value="" placeholder="">
                                </label>
                                <span class="error" id="billingCityErr"></span>
                              </li>
                              
                              <!-- COUNTRY -->
                              <li class="col-md-6">
                                <label>*State
                                  <input type="text" name="billingState" value="" placeholder="">
                                </label>
                                <span class="error" id="billingStateErr"></span>
                              </li>

                              <li class="col-md-6">
                                <label>*Pincode
                                  <input type="text" name="billingPincode" value="" placeholder="">
                                </label>
                                <span class="error" id="billingPincodeErr"></span>
                              </li>
                              
                              <!-- EMAIL ADDRESS -->
                              <li class="col-md-6">
                                <label> *EMAIL ADDRESS
                                  <input type="text" name="billingEmail" value="" placeholder="">
                                </label>
                                <span class="error" id="billingEmailErr"></span>
                              </li>
                              <!-- PHONE -->
                              <li class="col-md-6">
                                <label> *PHONE
                                  <input type="text" name="billingPhone" value="" placeholder="">
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
                                <form id="couponCodeForm" method="post" action="http://localhost/eprintcafe/doApplyPromo">
                                  <input id="couponCode" name="couponCode" type="text" value="" placeholder="ENTER YOUR CODE IF YOU HAVE ONE">
                                  <button id="couponCodeFormBtn" type="submit" class="btn btn-small btn-dark">APPLY CODE</button>
                                  <p id="couponCodeErr" class="removeErr"></p>
                                </form>

                                <h6 style="margin-top: 2rem; color: #fff; font-size: 14px;" id="suggest-you-input">Suggest Your Inputs</h6>
                                <textarea id="remark" class="form-control" rows="3" name="remark"></textarea>

                                <div class="order-detail">
                                  <p>Weight <span id="cartWeight">0.0255</span></p>
                                  <p>Discount <span id="discountData">0</span></p>
                                  <p>Shipping Charge <span id="shippingData">0</span></p>
                                  <p>Sub Total <span id="subTotalData">2.67</span></p>
                                  <p>Packaging Charges (5%) <span id="packagingChargeData">0.1335</span></p>
                                  <!-- SUB TOTAL -->
                                  <p class="all-total">TOTAL<span id="totalData">2.8035</span></p>
                                  
                                </div>


                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                      <div class="home_btn_main" id="place-order" style="bottom: -14%; width:100%;">
                          <button class="home_btn place-order-btn" >PLACE ORDER <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="tab-content-right-sec" style="overflow-y: auto;">
                       <div class="shipping-tab-inner">
                         <div class="shipping-heading">
                           <h2>Printing Order</h2>
                           <button class="home_btn edit-address" style="margin:unset;">EDIT ADDRESS</button>
                         </div>
                         <div class="shipping-content">
                           <p class="nobottommargin"><span class="t600">Contact Person: dd</span><br>Address: ddd<br>Landmark :<br>PinCode :282003<br>Mobile :8077796066<br></p>
                         </div>
                         <div class="order-confirmation">
                           <h2>Order confirmation email will sent to : <a href="mailto:faizan7017084@gmail.com">faizan7017084@gmail.com</a></h2>
                           <p>Expected Delivery Timeline - Delhi NCR (4 to 7 working Days) and in other states (7 to 10 Working Days). Subject to extension of lockdown/Other Restrictions by the government.</p>
                         </div>
                       </div>
                       <div class="shipping-tab-inner" style="margin-top:5%;">
                         <div class="shipping-heading">
                           <h2>Shipping Address</h2>
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
   $('.tab-heading-sec a').removeClass('active');
   $(this).addClass('active')
   var targetTab = $(this).attr('href');
   $('.tab-content').removeClass('active')
   $(`.tab-content${targetTab}`).addClass('active')

});

// delete icon js 
  $(".delete-icon").click(function(event) {
      event.preventDefault();
      var box = $(this).parent('.rvpric').parent('.delete').parent('.result-box');
      box.hide();    
      // console.log(box)
  });




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
            }
          } else {
            $("#customerAddressFormMsg").html(res.msg);

            //subTotal = ((res.priceData.shipping+res.priceData.total)+res.priceData.discount);
            subTotal = ((res.priceData.total-res.priceData.shipping));

            $("#discountData").html(res.priceData.discount)
            $("#shippingData").html(res.priceData.shipping)
            // $("#subTotalData").html(parseFloat(subTotal).toFixed(2));
            $("#subTotalData").html(res.priceData.subTotal);
            //$("#totalData").html(res.priceData.total)

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
      wetransferLink = $("#wetransferLink").val();
      courier = $(".courier-option:checkbox:checked").val();
      paymentMethod = $(".paymentMethod-option:checkbox:checked").val();

      formData = $("#customerAddressForm").serialize()+"&acceptTermsCondition="+acceptTerms+"&remark="+remark+"&wetransferLink="+wetransferLink+"&courier="+courier+"&paymentMethod="+paymentMethod;

      url = "{{ route('placeOrder') }}"

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
            } else {
              $("#placeOrderMsg").html(res.msg).css('color', 'red');
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
            }
          } else {

            subTotal = ((res.priceData.total-res.priceData.shipping));

            $("#discountData").html(res.priceData.discount)
            $("#shippingData").html(res.priceData.shipping)
            // $("#subTotalData").html(parseFloat(subTotal).toFixed(2))
            $("#subTotalData").html(res.priceData.subTotal);
            //$("#subTotalData").html(parseFloat(subTotal));
            //$("#totalData").html(res.priceData.total);

            $("#packagingChargeData").html(res.packagingCharges);
            $("#totalData").html(res.paidAmount);

            // $("#totalDiscount").html(res.discount);
            // $("#totalCost").html(res.grandTotal);

            $("#couponCodeErr").html(res.msg).css('color', 'green');
          }

          $("#couponCodeFormBtn").html('Apply Code');
        } 
      })

    });

    // document.getElementById('openDropzoneButton').addEventListener('click', function() {
    //     $("#upload").trigger('click');
    // });

    // Dropzone.autoDiscover = false;
    // $("#upload").dropzone({
    //     url: "{{ route('doUploadDropbox') }}",
    //     method: 'POST',
    //     parallelUploads: 30,
    //     uploadMultiple: true,
    //     maxFilesize: 1000, //MB
    //     maxFiles: 30, //Cannot upload more than 10 files
    //     acceptedFiles: ".jpg, .jpeg, .png, .zip, application/pdf, application/zip",
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     init: function() {
    //         this.on("error", function(file, res) {
    //             // Handle errors if the file size exceeds the limit or for other reasons
    //             if (res) {
    //                 console.log(res);
    //             }
    //         }),
    //         this.on("success", function(file, res) {
    //             // Handle errors if the file size exceeds the limit or for other reasons
        
    //             res = JSON.parse(res);
    //             $this = this;

    //             if (res.error) {
                    
    //                 if (res.eType == 'final') {
    //                     //toastr.error(res.msg);
    //                     $("#dropzoneMsg").html(res.msg).css('color', 'red');
    //                 } else {
    //                     $.each(res.errors, function(index, val) {
    //                        //toastr.error(val);  
    //                       $("#dropzoneMsg").html(val).css('color', 'red');
    //                     });
    //                 }

    //             } else {

    //                 // toastr.success(res.msg);

    //                 $("#dropzoneMsg").html(res.msg).css('color', 'green');
    //                 $("#uploadedDocuments").html(res.docTemplate);

    //                 timeout = setTimeout(function() {
    //                     //$this.removeAllFiles();
    //                     $("#dropzoneMsg").hide();
    //                     clearTimeout(timeout);
    //                 }, 3000);

    //             }

    //         })
    //     },
    // });

  });
</script>

@endsection