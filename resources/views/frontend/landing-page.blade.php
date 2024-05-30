@extends('vwFrontMaster')

@section('content')

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
<section id="page-title-new" class="page-title-parallax title-center page-title-dark" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ $bannerImg }}) center center; padding: 64px 0px;">
  <div class="container clearfix ng-scope">
    <h1>{{ $title }}</h1>
    
    @if(!empty($shortDescription))
      <span>{{ $shortDescription }}</span>
    @endif

    <div class="banner_btn">
        <a class="button" href="#">Enquire Now</a>
    </div>
  </div>
</section>

<section class="{{ $customerId? 'padding-top-100':''; }} padding-bottom-100">
  <div class="container"> 
    
    <!-- SHOP DETAIL -->
    <div class="shop-detail">
      
      <div class="row"> 
        
        <!-- Popular Images Slider -->
        <div class="col-md-6"> 
            
          <!-- Images Slider -->
          <div class="images-slider">
            <ul class="slides">
              <li data-thumb="{{ $img }}"> <img class="img-responsive" src="{{ $img }}"  alt=""> </li>
            </ul>
          </div>
        </div>
        
        <!-- Content -->
        <div class="col-md-6 detail_ul">
          <h4 style="color:var(--primary-color-1);">{{ $title }}</h4>
          
          <form method="post" id="addToCartForm" class="detail_page_form" style="margin-top:25px;">
            <div class="input_field">
                <label for="select">Select Options</label>
                <div class="detail_fields">
                  <select id="options" name="options">
                    <option>Business Cards/Visiting Card Printing(300GSM )</option>
                    <option>NTR 125GSM Micron</option>
                    <option>NTR 175 Micron</option>
                    <option>Hard Laminated Card (PVC) 350 MICRON</option>
                    <option>Hard Laminated Card (PVC) 350 MICRON</option>
                    <option>Special Visiting Cards</option>
                    <option>ID Cards</option>
                    <option>Event Id Cards</option>
                  </select>
                  <span class="text-danger" id="optionsErr"></span>
                </div>
            </div>

            <div class="input_field">
                <label for="select">Name</label>
                <div class="detail_fields">
                  <input name="name" type="text" style="width:100%;" placeholder="Name" value="">
                  <span class="text-danger" id="nameErr"></span>
                </div>
            </div>

            <div class="input_field">
                <label for="select">Phone Number</label>
                <div class="detail_fields">
                  <input name="phoneNumber" type="text" style="width:100%;" placeholder="Phone Number" value="">
                  <span class="text-danger" id="phoneNumberErr"></span>
                </div>
            </div>

            <div class="input_field">
                <label for="select">Email</label>
                <div class="detail_fields">
                  <input name="email" type="text" style="width:100%;" placeholder="Email" value="">
                  <span class="text-danger" id="emailErr"></span>
                </div>
            </div>

            <div class="input_field">
                <label for="select">Location</label>
                <div class="detail_fields">
                  <input name="text" type="text" style="width:100%;" placeholder="Location" value="">
                  <span class="text-danger" id="LocationErr"></span>
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

            <div class="input_field">
                <label for="select">Requirement Specification</label>
                <div class="detail_fields">
                  <textarea name="requirementSpecification" class="form-control" style="width:100%;" placeholder="Requirement Specification"></textarea>
                  <span class="text-danger" id="requirementSpecificationErr"></span>
                </div>
            </div>

          <div class="price-desktop" style="margin-bottom: 5px;">
             <div class="red_text"></div>

             <div class="input_field" style="display:block;">

                <button id="addToCartFormBtn" class="theme-btn mt-20 home_btn">
                  <span id="addToCartFormTxt">Enquire Now</span>
                </button>

            </div>                

          </div>
        </form>
      </div>
    </div>

      <div class="detail_page_disc">
        {!! $description !!}

        <div class="col-md-12 product-desc"> 
          <h3>Print ebooks &amp; digital publications</h3>
        
          <!-- Product Single - Short Description
                      ============================================= -->
      <p>Got an ebook, digital download or publication to print out?<br>
        From study materials, training documents and fitness guides to diet plans, gaming rulebooks &amp; more - we've got ebook printing covered.<br>
        Print and bind your PDF in a variety of styles, from simple loose sheets to wire bound documents and bookshop quality books.</p>
      <h3>An important note on copyright</h3>
      <div>If you are not the copyright holder, you must ensure you have relevant permissions from the copyright holder to print the document. By using our service, you confirm that you have obtained the relevant permissions to print the document(s).</div>
      <p><strong>Recommended Settings For E-Books Printing:</strong></p>
      <ul>
        <li><strong>Size :</strong>&nbsp;Select A4 or B5 Size Paper</li>
        <li><strong>Binding :</strong>&nbsp;Select Binding Type SOFT COVER BINDING</li>
        <li><strong>Color :</strong>&nbsp;If your file have a Images select Color Printing. If Only text then select Black and White Printing</li>
        <li><strong>Cover :</strong>&nbsp;We will take 1st and last page of your File to use as a cover</li>
      </ul>
      <br>
      <p>&nbsp;</p>
      <div class="col-md-6"> <h5>Available Papers </h5>
    <ul class="iconlist">
      <li> <i class="icon-line-arrow-right"></i> Economy White Paper (75gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Premium White Paper (75gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Bond Paper (85gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Duo White Paper (90gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Glossy White Paper (100gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Glossy White Paper (130gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Glossy White Paper (170gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Matte Paper (250gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Glossy Paper (250gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Matte Paper (300gsm)</li>
      <li> <i class="icon-line-arrow-right"></i> Glossy Paper (300gsm)</li>
    </ul>
      </div>
         
          <div class="col-md-6">  <div>
      <h5>Available Document Sizes</h5>
      <ul class="iconlist">
        <li> <i class="icon-line-arrow-right"></i> A3 (297 × 420 millimeters or 11.69 × 16.54 inches)</li>
        <li> <i class="icon-line-arrow-right"></i> A4 (210 × 297 millimeters or 8.27 × 11.69 inches)</li>
        <li> <i class="icon-line-arrow-right"></i> A5 (148 × 210 millimeters or 5.83 × 8.27 inches)</li>
      <li> <i class="icon-line-arrow-right"></i> B5 (175 × 250 millimeters or 6.9 × 8.27 inches)</li>
      </ul>
    </div>
    <h5>Binding Option</h5>
    <ul class="iconlist">
      <li> <i class="icon-line-arrow-right"></i> Corner Stapled</li>
      <li> <i class="icon-line-arrow-right"></i> Spiral Binding</li>
      <li> <i class="icon-line-arrow-right"></i> Twin Loop Wire or Wiro Binding</li>
      <li> <i class="icon-line-arrow-right"></i> Stapled Binding</li>
      <li> <i class="icon-line-arrow-right"></i> Saddle Stitch</li>
      <li> <i class="icon-line-arrow-right"></i> Hard Bindng</li>
      <li> <i class="icon-line-arrow-right"></i> Hard Bindng with Golden Print</li>
      <li> <i class="icon-line-arrow-right"></i> Soft Cover Binding / Perfect Binding</li>
      <li> <i class="icon-line-arrow-right"></i> Glue Binding / Tape Binding</li>
    </ul>
      </div>
          <!-- Product Single - Short Description End -->
          
         
      
      
      
      
      
      
      

      
      
        </div>


      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $(document).ready(function() {
    
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

  });
</script>

@endsection