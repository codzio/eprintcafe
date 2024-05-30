@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit User</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminUsers') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form_update" class="form" action="{{ route('adminDoUpdateUser') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Name</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ $user->name }}">
                                        <span id="nameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Email</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="email" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Email" value="{{ $user->email }}">
                                        <span id="emailErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Password</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="password" name="password" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Password" value="">
                                        <span id="passwordErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Address</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <textarea name="address" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0">{{ $user->address }}</textarea>
                                        <span id="addressErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Phone Number</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="phoneNumber" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Phone Number" value="{{ $user->phone_number }}">
                                        <span id="phoneNumberErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Two Step Verification</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        Enable <input {{ ($user->two_step == 1)? 'checked':'' }} type="radio" name="twoStep" value="1">
                                        Disable <input {{ ($user->two_step == 0)? 'checked':'' }} type="radio" name="twoStep" value="0">
                                        <span id="phoneNumberErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Status</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        Active <input {{ ($user->is_active == 1)? 'checked':'' }} type="radio" name="status" value="1">
                                        Inactive <input {{ ($user->is_active == 0)? 'checked':'' }} type="radio" name="status" value="0">
                                        <span id="statusErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Role</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="role" data-control="select2" data-hide-search="true" data-placeholder="Select Role">
                                            <option value="">Select Role</option>
                                            @if(!empty($roles))
                                            @foreach($roles as $role)
                                            <option {{ ($role->id == $user->role_id)? 'selected':'' }} value="{{ $role->id }}">{{ $role->role_name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="roleErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Profile</label>   
                            <div class="col-lg-9">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
                                    <div id="profileImgDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $profileImg }}')"></div>
                                    
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change Profile" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#profileImg'})">
                                        <i class="ki-outline ki-pencil fs-7"></i>
                                    </label>

                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Profile">
                                        <i class="ki-outline ki-cross fs-2"></i>                            
                                    </span>

                                    <span style="display:none;" id="profileImgRemoveBtn" onclick="removeMedia({field: '#profileImg', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Profile">
                                        <i class="ki-outline ki-cross fs-2"></i>                            
                                    </span>

                                    <input type="hidden" name="profileImg" id="profileImg" value="{{ $user->profile }}">
                                </div>
                                <div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
                                <span id="profileImgErr" class="text-danger"></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">

                        <input type="hidden" name="id" value="{{ $user->id }}">

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
        dataUrl = '{{ route("getAdminUsers") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/users.js') }}"></script>
    <!-- <script src="{{ asset('public/backend') }}/js/custom/account/settings/profile-details.js"></script>
    <script src="{{ asset('public/backend') }}/js/custom/account/settings/deactivate-account.js"></script>
    <script src="{{ asset('public/backend') }}/js/custom/pages/user-profile/general.js"></script>
    <script src="{{ asset('public/backend') }}/js/custom/utilities/modals/two-factor-authentication.js"></script> -->
    <!--end::Custom Javascript-->

@endsection