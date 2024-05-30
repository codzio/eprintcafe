@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Add Shipping Bulk Import</h3>
                </div>


                <div class="card-title">
                    <a href="{{ asset('public/backend') }} /media/shipping_bulk_import.xlsx " download class="btn btn-primary">Sample File</a>
                    <a style="margin-left: 5px;" href="{{ route('adminShipping') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" enctype="multipart/form-data" class="form" action="{{ route('adminDoShippingBulkImport') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">File</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="file" name="file" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0">
                                        <span id="fileErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary" id="kt_form_submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">
                                Please wait...    
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>            
                </form>
            </div>
        </div>

    </div>
</div>

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">
        dataUrl = '{{ route("getAdminCoupon") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/shipping.js') }}"></script>

@endsection