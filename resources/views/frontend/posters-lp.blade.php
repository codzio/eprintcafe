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
<section id="page-title-new" class="page-title-parallax title-center page-title-dark" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('public/frontend/img/landing-pages/poster-banner.jpg') }}) center center; padding: 64px 0px;">
  <div class="container clearfix ng-scope">
    <h1>{{ $title }}</h1>
      <!-- <span>dadasdas</span> -->
    <div class="banner_btn">
        <a class="button" href="#addToCartForm">Enquire Now</a>
    </div>
  </div>
</section>

<section class="padding-top-100 padding-bottom-100">
  <div class="container"> 
    
    <!-- SHOP DETAIL -->
    <div class="shop-detail">
      
      <div class="row"> 
        
        <!-- Popular Images Slider -->
        <div class="col-md-6"> 
            
          <!-- Images Slider -->
          <div class="images-slider">
            <ul class="slides">
              <li data-thumb="{{ asset('public/frontend/img/landing-pages/poster-img.jpg') }}"> <img class="img-responsive" src="{{ asset('public/frontend/img/landing-pages/poster-img.jpg') }}"  alt=""> </li>
            </ul>
          </div>
        </div>
        
        <!-- Content -->
        <div class="col-md-6 detail_ul">
          <h4 style="color:var(--primary-color-1);">{{ $title }}</h4>
          
          <form action="{{ route('doSaveLandingPageLead') }}" method="post" id="leadingForm" class="detail_page_form" style="margin-top:25px;">
            <div class="input_field">
                <label for="select">Select Options</label>
                <div class="detail_fields">
                  <select id="options" name="options">
                    <option>Frame Poster</option>
                    <option>Posters</option>
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
                  <input name="location" type="text" style="width:100%;" placeholder="Location" value="">
                  <span class="text-danger" id="locationErr"></span>
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

                <input type="hidden" name="product" value="{{ $title }}">

                <button id="leadingFormBtn" class="theme-btn mt-20 home_btn">
                  <span id="leadingFormTxt">Enquire Now</span>
                </button>

                <div id="leadingFormMsg"></div>

            </div>                

          </div>
        </form>
      </div>
    </div>

      <div class="detail_page_disc">

        <div class="col-md-12 product-desc">
          <h5>Poster Printing Services</h5>

          <p>At Eprintcafe, we specialize in providing high-quality poster printing services that help you make a bold statement and capture attention. Whether you're promoting an event, advertising a product, or showcasing artwork, our poster printing services are tailored to meet your specific needs.</p>

          <h5>Why Choose Eprintcafe for Your Poster Printing?</h5>

          <ul>
            <li><strong>Custom Sizes and Designs:</strong> We offer a wide range of customization options, including various sizes, paper types, and finishes, allowing you to create posters that align perfectly with your vision and objectives.</li>
            
            <li><strong>High-Quality Printing:</strong> Our state-of-the-art printing technology ensures that your posters are produced with crisp, vibrant colors and sharp details, resulting in professional-looking prints that command attention.</li>
            
            <li><strong>Fast Turnaround Times:</strong> We understand the importance of meeting deadlines. With our efficient production process, we guarantee quick turnaround times without compromising on quality, so you can promote your event or campaign effectively.</li>
            
            <li><strong>Expert Assistance:</strong> Our team of experienced professionals is here to assist you every step of the way, from selecting the right design and paper stock to optimizing your artwork for printing, ensuring that your posters meet your exact specifications.</li>
            
            <li><strong>Competitive Pricing:</strong> We offer competitive pricing options to fit any budget, whether you need a small quantity of posters for a personal project or a large volume for a commercial campaign.</li>
            
            <li><strong>Eco-Friendly Options:</strong> We're committed to sustainability. Choose from our range of eco-friendly paper options and printing practices to minimize your environmental footprint without compromising on the quality of your posters.</li>
          </ul>

          <p>Let Eprintcafe be your trusted partner in printing posters that leave a lasting impression. Contact us today to discuss your poster printing needs and request a quote. With our dedication to quality and customer satisfaction, we're here to help you achieve your goals with high-quality printed posters.</p>
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