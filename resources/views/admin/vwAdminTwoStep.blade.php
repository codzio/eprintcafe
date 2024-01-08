<!DOCTYPE html>
<html lang="en" >
<!--begin::Head-->
<head>
<title>{{ setting('website_name') }} | Two Step Verification</title>
<meta charset="utf-8"/>
<meta name="description" content=""/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>      
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" href="{{ getImg(setting('favicon')); }}"/>

<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>        <!--end::Fonts-->

<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="{{ asset('public/backend') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/backend') }}/css/style.bundle.css" rel="stylesheet" type="text/css"/>
<!--end::Global Stylesheets Bundle-->

    
<script>
    // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
    if (window.top != window.self) {
        window.top.location.replace(window.self.location.href);
    }
</script>

</head>
<!--end::Head-->

    <!--begin::Body-->
    <body  id="kt_body"  class="app-blank" >
        <!--begin::Theme mode setup on page load-->
        
        <!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    
<!--begin::Authentication - Two-factor -->
<div class="d-flex flex-column flex-lg-row flex-column-fluid">    
    <!--begin::Body-->
    <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
        <!--begin::Form-->
        <div class="d-flex flex-center flex-column flex-lg-row-fluid">
            <!--begin::Wrapper-->
            <div class="w-lg-500px p-10">
                
<!--begin::Form-->
<form class="form w-100 mb-13" method="post" novalidate="novalidate" id="kt_sing_in_two_factor_form" action="{{ route('adminDoCheckTwoStep') }}">
    <!--begin::Icon-->
    <div class="text-center mb-10">
        <img alt="Logo" class="mh-125px" src="{{ asset('public/backend') }}/media/svg/misc/smartphone-2.svg"/>
    </div>
    <!--end::Icon-->

    <!--begin::Heading-->
    <div class="text-center mb-10">
        <!--begin::Title-->
        <h1 class="text-dark mb-3">
            Two-Factor Verification
        </h1>
        <!--end::Title-->      

        <!--begin::Sub-title-->   
        <div class="text-muted fw-semibold fs-5 mb-5">Enter the verification code we sent to</div>
        <!--end::Sub-title-->   

        <!--begin::Mobile no-->    
        <div class="fw-bold text-dark fs-3">{{ $email }}</div>
        <!--end::Mobile no-->    
    </div>
    <!--end::Heading-->

    <!--begin::Section-->
    <div class="mb-10">
        <!--begin::Label-->
        <div class="fw-bold text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit security code</div>
        <!--end::Label-->

        <!--begin::Input group-->
        <div class="d-flex flex-wrap flex-stack">                      
            <input type="text" name="code_1" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value=""/>
            <input type="text" name="code_2" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value=""/>
            <input type="text" name="code_3" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value=""/> 
            <input type="text" name="code_4" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value=""/> 
            <input type="text" name="code_5" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value=""/>                     
            <input type="text" name="code_6" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value=""/> 
        </div>                
        <!--begin::Input group-->
    </div>
    <!--end::Section-->

    <!--begin::Submit-->
    <div class="d-flex flex-center">
        <button type="submit" id="kt_sing_in_two_factor_submit" class="btn btn-lg btn-primary fw-bold">
            <span class="indicator-label">
                Submit
            </span>
            <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
    <!--end::Submit-->
</form>
<!--end::Form-->

<!--begin::Notice-->
<div class="text-center fw-semibold fs-5">
    <span id="resendOtpTimeout" class="text-muted me-1">
        Resend OTP in <span class="js-timeout">1:00</span> Min
    </span>
    <span style="display: none;" id="resendOtpBtn" class="text-muted me-1">
        Didn't receive code?
        <a data-url="{{ route('adminDoResendOTP'); }}" href="javascript:void(0)" class="link-primary fs-5 me-1">Resend</a>
    </span>
</div>
<!--end::Notice-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Form-->
    </div>
    <!--end::Body-->
    
    <!--begin::Aside-->
    <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ getImg(setting('background_image')); }})">
        <!-- <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">                    
            <a href="../../../index.html" class="mb-0 mb-lg-12">
                <img alt="Logo" src="{{ asset('public/backend') }}/media/logos/custom-1.png" class="h-60px h-lg-75px"/>
            </a>    
        </div>  -->               
    </div>
    <!--end::Aside-->
</div>
<!--end::Authentication - Two-factor-->
                         


                         </div>
<!--end::Root-->
        
        <!--begin::Javascript-->

        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
            <script src="{{ asset('public/backend') }}/plugins/global/plugins.bundle.js"></script>
            <script src="{{ asset('public/backend') }}/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
            <script src="{{ asset('public/backend') }}/js/admin/auth.js"></script>
            
            <script type="text/javascript">
                $('.js-timeout').text("1:00");
                countdown();
            </script>

        </body>
    <!--end::Body-->
</html>