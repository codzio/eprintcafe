<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head>
        <title>Abandoned Cart</title>
        <meta charset="utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="shortcut icon" href="{{ asset('public/backend') }}/media/logos/favicon.ico" />
        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href="{{ asset('public/backend') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/backend') }}/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <!--end::Global Stylesheets Bundle-->
        <style type="text/css">
            .table-body tr{
                width: 88%;
                margin:auto;
                display: block;
            }
            .table-body p{
                font-size:18px;
            }
            .content-css  h1{
                text-align:left;
                line-height:1.5;
                font-size:36px!important;
            }
            .table_btn{
                display:block;
                padding:8px 33px;
                background:#000;
                color:#fff;
                border:1px solid #000;
                border-radius:8px;
                width:fit-content;
                margin: auto;
                font-size:23px;
            }
            .table_btn:hover{
                color: #fff;
            }
        </style>
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" class="auth-bg">
        <!--begin::Main-->
        <!--begin::Root-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Email template-->
            <style>html,body { padding:0; margin:0; font-family: Inter, Helvetica, "sans-serif"; } a:hover { color: #009ef7; }</style>
            <div id="#kt_app_body_content" style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%;">
                <div style="background-color:#ffffff; padding: 45px 0 55px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse">
                        <tbody class="table-body">
                            <tr>
                                <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
                                    <!--begin:Email content-->
                                    <div style="text-align:center; margin:0 15px 34px 15px">
                                        <!--begin:Logo-->
                                        <!-- <div style="margin-bottom: 10px">
                                            <a href="{{ route('homePage') }}" rel="noopener" target="_blank">
                                                <img alt="" src="{{ getImg(setting('website_logo')); }}" style="height: 35px" />
                                            </a>
                                        </div> -->
                                        <!--end:Logo-->
                                        <!--begin:Media-->
                                        <!-- <div style="margin-bottom: 15px">
                                            <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-positive-vote-1.png" />
                                        </div> -->
                                        <!--end:Media-->
                                        <!--begin:Text-->
                                        <div style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;" class="content-css">
                                            <!-- <p style="margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">Hey {{ $customer->name }}</p> -->
                                            <h1 style="margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">Hey {{ $customer->name }}</h1>
                                            <div style=" margin: auto; text-align: justify;">
                                                <p>We noticed that you left some items in your cart, and we wanted to reach out and make sure everything is alright. We understand how busy life can get, but we didn't want you to miss out on your favorite products.</p>

                                                <p>Don't let these items slip away! Complete your order today</p>

                                                <p><a href="{{ route('cartPage') }}">Click here</a> to return to your cart and complete your order now.</p>

                                                <p>If you have any questions or need assistance, feel free to reach out to our customer support team at [<a href="mailto:mail@eprintcafe.com"> mail@eprintcafe.com</a>/<a href="tel:+918448193390">+91 84481 93390</a>]. We're here to help!</p>
                                            </div> 

                                        </div>
                                    </div>
                                    <!--end:Email content-->
                                </td>
                            </tr>
                            <!-- <tr>
                                <td align="center" valign="center" style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; font-family:Arial,Helvetica,sans-serif">
                                    <p style="color:#181C32; font-size: 16px; font-weight: 600; margin-bottom:9px">It’s all about customers!</p>
                                    
                                    <p style="margin-bottom:2px">Call our customer care number: 7065132319</p>

                                    <p style="margin-bottom:4px">You may reach us at 
                                    <a href="#" rel="noopener" target="_blank" style="font-weight: 600">{{ setting('from_address') }}</a>.</p>
                                </td>
                            </tr> -->
                            <!-- <tr>
                                <td align="center" valign="center" style="text-align:center; padding-bottom: 20px;">
                                    <a href="{{ setting('linkedin_url') }}" style="margin-right:10px">
                                        <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-linkedin.png" />
                                    </a>
                                    <a href="{{ setting('facebook_url') }}" style="margin-right:10px">
                                        <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-facebook.png" />
                                    </a>
                                    <a href="{{ setting('twitter_url') }}">
                                        <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-twitter.png" />
                                    </a>
                                </td>
                            </tr> -->
                            <!-- <tr>
                                <td align="center" valign="center" style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; font-family:Arial,Helvetica,sans-serif">
                                    <p>&copy; Copyright {{ setting('website_name') }}. 
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                    <a style="text-decoration: none; color: white" href="{{ route('cartPage') }}" class="table_btn">Complete Your Order</a>
                </div>
            </div>
            <!--end::Email template-->
        </div>
        <!--end::Root-->
        <!--end::Main-->
        <!--begin::Javascript-->
        <script>var hostUrl = "{{ asset('public/backend') }}/";</script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="{{ asset('public/backend') }}/plugins/global/plugins.bundle.js"></script>
        <script src="{{ asset('public/backend') }}/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->
</html>