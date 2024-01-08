<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head>
        <title>Two Step Verification</title>
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
                <div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse">
                        <tbody>
                            <tr>
                                <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
                                    <!--begin:Email content-->
                                    <div style="text-align:center; margin:0 15px 34px 15px">
                                        <!--begin:Logo-->
                                        <div style="margin-bottom: 10px">
                                            <a href="#" rel="noopener" target="_blank">
                                                <img alt="" src="{{ asset('public/admin/media/email/logo-1.svg') }}" style="height: 35px" />
                                            </a>
                                        </div>
                                        <!--end:Logo-->
                                        <!--begin:Media-->
                                        <div style="margin-bottom: 15px">
                                            <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-positive-vote-1.svg" />
                                        </div>
                                        <!--end:Media-->
                                        <!--begin:Text-->
                                        <div style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;">
                                            <p style="margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">Hey {{ $name }}</p>
                                            <p style="margin-bottom:2px; color:#7E8299">Your verification passcode is: <strong>{{ $otp }}</strong></p>
                                            <p style="margin-bottom:2px; color:#7E8299">Note: you have 5 minutes to enter this passcode before it expires.</p>
                                            <p style="margin-bottom:2px; color:#7E8299">If you have not tried to login, ignore this message.</p>
                                        </div>
                                    </div>
                                    <!--end:Email content-->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="center" style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; color: #A1A5B7; font-family:Arial,Helvetica,sans-serif">
                                    <p style="color:#181C32; font-size: 16px; font-weight: 600; margin-bottom:9px">It’s all about customers!</p>
                                    
                                    <p style="margin-bottom:2px">Call our customer care number: 6395028377</p>

                                    <p style="margin-bottom:4px">You may reach us at 
                                    <a href="#" rel="noopener" target="_blank" style="font-weight: 600">alfaizm19@gmail.com</a>.</p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="center" style="text-align:center; padding-bottom: 20px;">
                                    <a href="#" style="margin-right:10px">
                                        <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-linkedin.svg" />
                                    </a>
                                    <a href="#" style="margin-right:10px">
                                        <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-facebook.svg" />
                                    </a>
                                    <a href="#">
                                        <img alt="Logo" src="{{ asset('public/backend') }}/media/email/icon-twitter.svg" />
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="center" style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
                                    <p>&copy; Copyright Ecom. 
                                </td>
                            </tr>
                        </tbody>
                    </table>
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