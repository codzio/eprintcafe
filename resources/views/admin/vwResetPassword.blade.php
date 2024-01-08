<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Reset Password</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>      
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ getImg(setting('favicon')); }}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>    
    <link href="{{ asset('public/backend') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/backend') }}/css/style.bundle.css" rel="stylesheet" type="text/css"/>    

    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>

<body  id="kt_body"  class="app-blank" >

<div class="d-flex flex-column flex-root" id="kt_app_root">
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">        
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">        
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px p-10">                
                    <form class="form w-100" novalidate="novalidate" id="kt_new_password_form" action="{{ route('adminDoResetPassword') }}">
                        <div class="text-center mb-10">

                            <h1 class="text-dark fw-bolder mb-3">
                                Setup New Password
                            </h1>

                            <div class="text-gray-500 fw-semibold fs-6">
                                Have you already reset the password ?
                                <a href="{{ route('adminLogin') }}" class="link-primary fw-bold">Sign in</a>
                            </div>                        
                        </div>

                        <div class="fv-row mb-8" data-kt-password-meter="true">
                            <div class="mb-1">
                                <div class="position-relative mb-3">    
                                    <input class="form-control bg-transparent" type="password" placeholder="Password" name="password" autocomplete="off"/>

                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                            <i class="ki-outline ki-eye-slash fs-2"></i>                    
                                            <i class="ki-outline ki-eye fs-2 d-none"></i>                
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>                                
                                </div>

                                <div class="text-muted">
                                    Use 8 or more characters with a mix of letters, numbers & symbols.
                                </div>

                                <span id="passwordErr" class="text-danger"></span>                    
                            </div>

                            <div class="fv-row mb-8">    
                                <input type="password" placeholder="Repeat Password" name="confirmPass" autocomplete="off" class="form-control bg-transparent"/>

                                <span id="confirmPassErr" class="text-danger"></span>
                            </div>

                            <input type="hidden" name="resetToken" value="{{ $token }}">
                                
                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_new_password_submit" class="btn btn-primary">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">
                                        Please wait...    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>

                    </form>
                </div>
            </div>
        </div>

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
</div>
    
<script src="{{ asset('public/backend') }}/plugins/global/plugins.bundle.js"></script>
<script src="{{ asset('public/backend') }}/js/scripts.bundle.js"></script>
<script src="{{ asset('public/backend') }}/js/admin/auth.js"></script>
    

</body>
</html>