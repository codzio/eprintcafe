@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >


	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container  container-fluid ">

		<div class="card mb-5 mb-xl-10">
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">Profile Details</h3>
				</div>
			</div>
			
			<div id="kt_account_settings_profile_details" class="collapse show">				
				<form id="kt_account_profile_details_form" class="form" action="{{ route('adminDoUpdateProfile') }}">
					<div class="card-body border-top p-9">
								<div class="row mb-6">
									<label class="col-lg-3 col-form-label fw-semibold fs-6">Profile Picture</label>   
									<div class="col-lg-9">
										<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
											<div id="profilePictureDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $profilePicture }}')"></div>
											
											<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change profile picture" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#profilePicture'})">
												<i class="ki-outline ki-pencil fs-7"></i>
											</label>

											<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel profile picture">
												<i class="ki-outline ki-cross fs-2"></i>                            
											</span>

											<span id="profilePictureRemoveBtn" onclick="removeMedia({field: '#profilePicture', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove profile picture">
												<i class="ki-outline ki-cross fs-2"></i>                            
											</span>

											<input type="hidden" name="profilePicture" id="profilePicture">
										</div>
											<div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
									</div>									
								</div>

								<div class="row mb-6">                    
									<label class="col-lg-3 col-form-label required fw-semibold fs-6">Full Name</label>
									<div class="col-lg-9">                        
										<div class="row">
											<div class="col-lg-12 fv-row">
												<input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ $admin->name }}" />
												<span id="nameErr" class="text-danger"></span>
											</div>                            
										</div>
									</div>                    
								</div>

								<div class="row mb-6">                    
									<label class="col-lg-3 col-form-label fw-semibold fs-6">Email</label>
									<div class="col-lg-9">                        
										<div class="row">
											<div class="col-lg-12 fv-row">
												<input disabled type="text" name="email" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Email Address" value="{{ $admin->email }}" />
											</div>                            
										</div>
									</div>                    
								</div>

								<div class="row mb-6">                    
									<label class="col-lg-3 col-form-label fw-semibold fs-6">
									<span class="">Phone Number</span>
									<span class="ms-1"  data-bs-toggle="tooltip" title="Phone number must be active" >
										<i class="ki-outline ki-information-5 text-gray-500 fs-6"></i></span>
									</label>                    

									<div class="col-lg-9 fv-row">
										<input type="tel" name="phoneNumber" class="form-control form-control-lg form-control-solid" placeholder="Phone number" value="{{ $admin->phone_number }}" />
										<span id="phoneNumberErr" class="text-danger"></span>
									</div>                   
								</div>                

								<div class="row mb-6">                    
									<label class="col-lg-3 col-form-label fw-semibold fs-6">Address</label>
									<div class="col-lg-9 fv-row">
										<input type="text" name="address" class="form-control form-control-lg form-control-solid" placeholder="Address" value="{{ $admin->address }}" />
										<span id="addressErr" class="text-danger"></span>
									</div>
								</div>
						</div>

						<div class="d-flex justify-content-end py-6 px-9">
							<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">
								<span class="indicator-label">Save Changes</span>
	                            <span class="indicator-progress">
	                            	Please wait...    
	                            	<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
	                            </span>
							</button>
						</div>            
					</form>
				</div>
			</div>

			<div class="card  mb-5 mb-xl-10"   >
				<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
					<div class="card-title m-0">
						<h3 class="fw-bold m-0">Sign-in Method</h3>
					</div>
				</div>

				<div id="kt_account_settings_signin_method" class="collapse show">
					<div class="card-body border-top p-9">
						<div class="d-flex flex-wrap align-items-center">
							<div id="kt_signin_email">
								<div class="fs-6 fw-bold mb-1">Email Address</div>
								<div class="fw-semibold text-gray-600">{{ $admin->email }}</div>
							</div>							
							<div id="kt_signin_email_edit" class="flex-row-fluid d-none">
								<form id="kt_signin_change_email" class="form" action="{{ route('adminDoUpdateEmail') }}">
									<div class="row mb-6">
										<div class="col-lg-6 mb-4 mb-lg-0">
											<div class="fv-row mb-0">
												<label for="emailAddress" class="form-label fs-6 fw-bold mb-3">Enter New Email Address</label>
												<input type="email" class="form-control form-control-lg form-control-solid" id="emailAddress" placeholder="Email Address" name="emailAddress" value="" />
												<span id="emailAddressErr" class="text-danger"></span>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="fv-row mb-0">
												<label for="confirmEmailPassword" class="form-label fs-6 fw-bold mb-3">Confirm Password</label>
												<input type="password" class="form-control form-control-lg form-control-solid" name="confirmEmailPassword" id="confirmEmailPassword" placeholder="Confirm Password" />
												<span id="confirmEmailPasswordErr" class="text-danger"></span>
											</div>
										</div>
									</div>
									<div class="d-flex">
										<button id="kt_signin_change_email_submit" type="submit" class="btn btn-primary  me-2 px-6">
											<span class="indicator-label">Update Email</span>
				                            <span class="indicator-progress">
				                            	Please wait...    
				                            	<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
				                            </span>
										</button>
										<button id="kt_signin_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
									</div>
								</form>
							</div>
							<div id="kt_signin_email_button" class="ms-auto">
								<button class="btn btn-light btn-active-light-primary">Change Email</button>
							</div>
						</div>

						<div class="separator separator-dashed my-6"></div>

						<div class="d-flex flex-wrap align-items-center mb-10">							
							<div id="kt_signin_password">
								<div class="fs-6 fw-bold mb-1">Password</div>
								<div class="fw-semibold text-gray-600">************</div>
							</div>
							<div id="kt_signin_password_edit" class="flex-row-fluid d-none">								
								<form id="kt_signin_change_password" class="form" action="{{ route('adminDoChangePassword') }}">
									<div class="row mb-1">
										<div class="col-lg-4">
											<div class="fv-row mb-0">
												<label for="currentPassword" class="form-label fs-6 fw-bold mb-3">Current Password</label>
												<input type="password" class="form-control form-control-lg form-control-solid " name="currentPassword" id="currentPassword" />
												<span id="currentPasswordErr" class="text-danger"></span>
											</div>
										</div>

										<div class="col-lg-4">
											<div class="fv-row mb-0">
												<label for="newPassword" class="form-label fs-6 fw-bold mb-3">New Password</label>
												<input type="password" class="form-control form-control-lg form-control-solid " name="newPassword" id="newPassword" />
												<span id="newPasswordErr" class="text-danger"></span>
											</div>
										</div>

										<div class="col-lg-4">
											<div class="fv-row mb-0">
												<label for="confirmPassword" class="form-label fs-6 fw-bold mb-3">Confirm New Password</label>
												<input type="password" class="form-control form-control-lg form-control-solid " name="confirmPassword" id="confirmPassword" />
												<span id="confirmPasswordErr" class="text-danger"></span>
											</div>
										</div>
									</div>

									<div class="form-text mb-5"></div>

									<div class="d-flex">
										<button id="kt_password_submit" type="submit" class="btn btn-primary me-2 px-6">
											<span class="indicator-label">Update Password</span>
				                            <span class="indicator-progress">
				                            	Please wait...    
				                            	<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
				                            </span>
										</button>
										<button id="kt_password_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
									</div>
								</form>
							</div>

							<div id="kt_signin_password_button" class="ms-auto">
								<button class="btn btn-light btn-active-light-primary">Reset Password</button>
							</div>
						</div>						
						
						<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed  p-6">
							<i class="ki-outline ki-shield-tick fs-2tx text-primary me-4"></i>
							<div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
								<div class="mb-3 mb-md-0 fw-semibold">
									<h4 class="text-gray-900 fw-bold">Secure Your Account</h4>
									<div class="fs-6 text-gray-700 pe-7">Two-factor authentication adds an extra layer of security to your account. To log in, in addition you'll need to provide a 6 digit code</div>
								</div>
								<div class="form-check form-check-solid form-switch form-check-custom fv-row">
									<input data-action="{{ route('adminDoUpdateTwoStep') }}" class="form-check-input w-45px h-30px" type="checkbox" id="twoStep" value="1" {{ $admin->two_step? 'checked':''; }} />
									<label class="form-check-label" for="twoStep"></label>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>		
	</div>

	<!--begin::Custom Javascript(used for this page only)-->
	<script src="{{ asset('public/backend') }}/js/admin/account-settings.js"></script>
	<script src="{{ asset('public/backend') }}/js/custom/account/settings/signin-methods.js"></script>
	<!-- <script src="{{ asset('public/backend') }}/js/custom/account/settings/profile-details.js"></script>
	<script src="{{ asset('public/backend') }}/js/custom/account/settings/deactivate-account.js"></script>
	<script src="{{ asset('public/backend') }}/js/custom/pages/user-profile/general.js"></script>
	<script src="{{ asset('public/backend') }}/js/custom/utilities/modals/two-factor-authentication.js"></script> -->
	<!--end::Custom Javascript-->

@endsection