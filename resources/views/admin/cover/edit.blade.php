@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Cover</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminCover') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form_update" class="form" action="{{ route('adminDoUpdateCover') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Name</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input onkeyup="generateSlug(this, '#slug')" type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ $cover->cover }}">
                                        <span id="nameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Slug</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="slug" type="text" name="slug" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Slug" value="{{ $cover->slug }}">
                                        <span id="slugErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>
                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">

                        <input type="hidden" name="id" value="{{ $cover->id }}">

                        <button type="submit" class="btn btn-primary" id="kt_form_update_submit">
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
        dataUrl = '{{ route("getAdminCover") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/cover.js') }}"></script>

@endsection