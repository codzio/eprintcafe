<!DOCTYPE html>
<html lang="en" >
<!--begin::Head-->
<head>
<title> {{ setting('website_name') }} | Admin</title>
<meta charset="utf-8"/>
<meta name="description" content=""/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>      
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- <link rel="shortcut icon" href="{{ asset('public/backend') }}/media/logos/favicon.ico"/> -->
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

<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">

<!--begin::Authentication - Sign-in -->
<div class="d-flex flex-column flex-lg-row flex-column-fluid">    
    <!--begin::Body-->
    <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
        <!--begin::Form-->
        <div class="d-flex flex-center flex-column flex-lg-row-fluid">
            <!--begin::Wrapper-->
            <div class="w-lg-500px p-10">

                <!--begin::Form-->
                <form class="form w-100" method="post" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('adminDoLogin') }}">
                    <!--begin::Heading-->
                    <div class="text-center mb-11">
                        <!--begin::Title-->
                        <h1 class="text-dark fw-bolder mb-3">
                            Sign In
                        </h1>
                        <!--end::Title-->
                    </div>
                    <!--begin::Heading-->

                    <!--begin::Input group--->
                    <div class="fv-row mb-8">
                        <!--begin::Email-->
                        <input name="email" type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent"/> 
                        <span id="emailErr" class="text-danger"></span>
                        <!--end::Email-->
                    </div>

                    <!--end::Input group--->
                    <div class="fv-row mb-3">
                        <input name="password" type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent"/>
                        <span id="passwordErr" class="text-danger"></span>                        
                    </div>
                    <!--end::Input group--->

                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                        <div></div>

                        <!--begin::Link-->
                        <a href="{{ route('adminForgotPassword'); }}" class="link-primary">
                            Forgot Password?
                        </a>
                        <!--end::Link-->
                    </div>
                    <!--end::Wrapper-->                    

                    <!--begin::Submit button-->
                    <div class="d-grid mb-10">
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">

                            <!--begin::Indicator label-->
                            <span class="indicator-label">
                            Sign In</span>
                            <!--end::Indicator label-->

                            <!--begin::Indicator progress-->
                            <span class="indicator-progress">
                                Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                            <!--end::Indicator progress-->        </button>
                        </div>
                        <!--end::Submit button-->

                    </form>
                    <!--end::Form--> 
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
        <!--end::Authentication - Sign-in-->



    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script>
        var hostUrl = "";        
    </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('public/backend') }}/plugins/global/plugins.bundle.js"></script>
    <script src="{{ asset('public/backend') }}/js/scripts.bundle.js"></script>
    <script src="{{ asset('public/backend') }}/js/admin/auth.js"></script>
    <!--end::Global Javascript Bundle-->

</body>
<!--end::Body-->
</html>