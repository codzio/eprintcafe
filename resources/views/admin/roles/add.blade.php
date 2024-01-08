@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Add Role</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminRoles') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="{{ route('adminDoAddRole') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Role Name</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="roleName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Role Name" value="">
                                        <span id="roleNameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Permissions</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">

                                        <button type="button" class="allow btn btn-primary mb-5">Allow All</button>
                                        <button type="button" class="disallow btn btn-primary mb-5">Disallow All</button>

                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Create</th>
                                                    <th>Read</th>
                                                    <th>Update</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($modules)
                                                @foreach($modules as $module)
                                                <tr>
                                                    <th>{{ $module->name }}</th>
                                                    <td><input class="checkbox" {{ $module->can_create? '':'disabled' }} type="checkbox" name="module[{{ $module->slug }}][create]" value="1"></td>
                                                    <td><input class="checkbox" {{ $module->can_read? '':'disabled' }} type="checkbox" name="module[{{ $module->slug }}][read]" value="1"></td>
                                                    <td><input class="checkbox" {{ $module->can_update? '':'disabled' }} type="checkbox" name="module[{{ $module->slug }}][update]" value="1"></td>
                                                    <td><input class="checkbox" {{ $module->can_delete? '':'disabled' }} type="checkbox" name="module[{{ $module->slug }}][delete]" value="1"></td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No Modules Found.</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>

                                        <span id="moduleErr" class="text-danger"></span>

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
        dataUrl = '{{ route("getAdminRoles") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/roles.js') }}"></script>
    <!-- <script src="{{ asset('public/backend') }}/js/custom/account/settings/profile-details.js"></script>
    <script src="{{ asset('public/backend') }}/js/custom/account/settings/deactivate-account.js"></script>
    <script src="{{ asset('public/backend') }}/js/custom/pages/user-profile/general.js"></script>
    <script src="{{ asset('public/backend') }}/js/custom/utilities/modals/two-factor-authentication.js"></script> -->
    <!--end::Custom Javascript-->

@endsection