@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Add Category</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminCategory') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="{{ route('adminDoAddCategory') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Name</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input onkeyup="generateSlug(this, '#slug')" type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="">
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
                                        <input id="slug" type="text" name="slug" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Slug" value="">
                                        <span id="slugErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Description</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <textarea name="description" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Description"></textarea>
                                        <span id="descriptionErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Status</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select Status">
                                            <option value="">Select Status</option>
                                            <option selected value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span id="statusErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Category Image</label>   
                            <div class="col-lg-9">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
                                    <div id="categoryImgDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $categoryImg }}')"></div>
                                    
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change admin logo" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#categoryImg'})">
                                        <i class="ki-outline ki-pencil fs-7"></i>
                                    </label>

                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel admin logo">
                                        <i class="ki-outline ki-cross fs-2"></i>                            
                                    </span>

                                    <span style="display:none;" id="categoryImgRemoveBtn" onclick="removeMedia({field: '#categoryImg', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove admin logo">
                                        <i class="ki-outline ki-cross fs-2"></i>                            
                                    </span>

                                    <input type="hidden" name="categoryImg" id="categoryImg" value="">
                                </div>
                                <div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
                                <span id="categoryImgErr" class="text-danger"></span>
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
        dataUrl = '{{ route("getAdminUsers") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/category.js') }}"></script>

@endsection