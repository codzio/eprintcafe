@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/backend') }}/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css"/>
<!--end::Vendor Stylesheets-->

<div id="kt_app_content" class="app-content  flex-column-fluid " >
    
           
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-fluid ">
            
    
           
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-fluid ">
            
            <!--begin::Col-->
            <div class="col-md-12">
                <!--begin::Row-->
                <div class="row g-5 g-xl-10">

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #FFBBCC">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Users</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $users }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #d7e3fc">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Products</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $products }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #e9f5db">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Category</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $category }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #4cc9f0">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Paper Size</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $paperSize }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #e0aaff">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Paper Type</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $paperType }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #BDE0FE">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Binding</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $binding }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #c5c3c6">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Lamination</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $lamination }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #88d4ab">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Cover</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $cover }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #eaf4f4">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Paper GSM</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $paperGsm }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #eaf4f4">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Coupon</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $coupon }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #eaf4f4">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Shipping</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $shipping }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #eaf4f4">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Contact</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $contact }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-flush h-xl-100" style="background-color: #eaf4f4">  
                            <div class="card-header flex-nowrap pt-5">
                                <h3 class="card-title align-items-start flex-column">            
                                    <span class="card-label fw-bold fs-4 text-gray-800">Customers</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-5">
                                <div class="text-start">            
                                    <span class="d-block fw-bold fs-1 text-gray-800">{{ $customer }}</span>
                                </div>                    
                            </div>
                        </div>
                    </div>
    </div>
    <!--end::Row-->
    </div>
    <!--end::Col-->   
        </div>
        <!--end::Content container-->
        </div>
        <!--end::Content container-->
    </div>



<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="{{ asset('public/backend') }}/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
<!--end::Vendors Javascript-->

<!--begin::Custom Javascript(used for this page only)-->
<script src="{{ asset('public/backend') }}/js/widgets.bundle.js"></script>
<script src="{{ asset('public/backend') }}/js/custom/widgets.js"></script>
<script src="{{ asset('public/backend') }}/js/custom/apps/chat/chat.js"></script>
<script src="{{ asset('public/backend') }}/js/custom/utilities/modals/create-campaign.js"></script>
<script src="{{ asset('public/backend') }}/js/custom/utilities/modals/users-search.js"></script>
<!--end::Custom Javascript-->

@endsection