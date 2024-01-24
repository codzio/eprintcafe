@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Add Paper Type</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminPaperType') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="{{ route('adminDoAddPaperType') }}">
                    <div class="card-body border-top p-9">

                        <!-- <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Size</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="paperSize" data-control="select2" data-hide-search="true" data-placeholder="Select Paper Size">
                                            <option value="">Select Paper Size</option>
                                            @if(!empty($paperSize))
                                            @foreach($paperSize as $paperSz)
                                            <option value="{{ $paperSz->id }}">{{ $paperSz->size }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperSizeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div> -->

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Type</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input onkeyup="generateSlug(this, '#slug')" type="text" name="paperType" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Paper Type" value="">
                                        <span id="paperTypeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Slug</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="slug" type="text" name="slug" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Slug" value="">
                                        <span id="slugErr" class="text-danger"></span>
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
        dataUrl = '{{ route("getAdminPaperType") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/paper-type.js') }}"></script>

@endsection