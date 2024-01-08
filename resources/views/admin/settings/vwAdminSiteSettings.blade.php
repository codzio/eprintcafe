@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
	<div id="kt_app_content_container" class="app-container  container-fluid ">

		<div class="card mb-5 mb-xl-10">
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_settings_profile_details" aria-expanded="true" aria-controls="kt_site_setting_general_details">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">General</h3>
				</div>
			</div>
			
			<div id="kt_account_settings_profile_details" class="collapse show">				
				<form id="kt_site_setting_general_details_form" class="form" action="{{ route('adminDoUpdateGeneralSetting') }}">
					<div class="card-body border-top p-9">
						<div class="row mb-6">
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">Admin Logo</label>   
							<div class="col-lg-9">
								<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
									<div id="adminLogoDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $adminLogo }}')"></div>
									
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change admin logo" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#adminLogo'})">
										<i class="ki-outline ki-pencil fs-7"></i>
									</label>

									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel admin logo">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<span id="adminLogoRemoveBtn" onclick="removeMedia({field: '#adminLogo', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove admin logo">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<input type="hidden" name="adminLogo" id="adminLogo" value="{{ $setting->admin_logo }}">
								</div>
								<div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
								<span id="adminLogoErr" class="text-danger"></span>
							</div>									
						</div>

						<div class="row mb-6">
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">Website Logo</label>   
							<div class="col-lg-9">
								<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
									<div id="websiteLogoDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $websiteLogo }}')"></div>
									
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change website logo" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#websiteLogo'})">
										<i class="ki-outline ki-pencil fs-7"></i>
									</label>

									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel website logo">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<span id="websiteLogoRemoveBtn" onclick="removeMedia({field: '#websiteLogo', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove website logo">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<input type="hidden" name="websiteLogo" id="websiteLogo" value="{{ $setting->website_logo }}">
								</div>
								<div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
								<span id="websiteLogoErr" class="text-danger"></span>
							</div>									
						</div>

						<div class="row mb-6">
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">Favicon</label>   
							<div class="col-lg-9">
								<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
									<div id="faviconDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $favicon }}')"></div>
									
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change favicon" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#favicon'})">
										<i class="ki-outline ki-pencil fs-7"></i>
									</label>

									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel favicon">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<span id="faviconRemoveBtn" onclick="removeMedia({field: '#favicon', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove favicon">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<input type="hidden" name="favicon" id="favicon" value="{{ $setting->favicon }}">
								</div>
								<div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
								<span id="faviconErr" class="text-danger"></span>
							</div>									
						</div>

						<div class="row mb-6">
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">Admin Background Banner</label>   
							<div class="col-lg-9">
								<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
									<div id="backgroundImageDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $backgroundImage }}')"></div>
									
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change Admin Background Image" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#backgroundImage'})">
										<i class="ki-outline ki-pencil fs-7"></i>
									</label>

									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Admin Background Image">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<span id="backgroundImageRemoveBtn" onclick="removeMedia({field: '#backgroundImage', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Admin Background Image">
										<i class="ki-outline ki-cross fs-2"></i>                            
									</span>

									<input type="hidden" name="backgroundImage" id="backgroundImage" value="{{ $setting->background_image }}">
								</div>
								<div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
								<span id="backgroundImageErr" class="text-danger"></span>
							</div>									
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">Website Name</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="websiteName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Website Name" value="{{ $setting->website_name }}">
										<span id="websiteNameErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">Copyright</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="copyright" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Copyright" value="{{ $setting->copyright }}">
										<span id="copyrightErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

					</div>

					<div class="d-flex justify-content-end py-6 px-9">
						<button type="submit" class="btn btn-primary" id="kt_site_setting_general_details_submit">
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

		<div class="card mb-5 mb-xl-10"   >
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_scripts">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">Scripts</h3>
				</div>
			</div>

			<div id="kt_scripts" class="collapse">				
				<form id="kt_site_setting_scripts_form" class="form" action="{{ route('adminDoUpdateScripts') }}">
					<div class="card-body border-top p-9">
						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Header Scripts</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<textarea rows="20" name="headerScripts" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Header Scripts">{{ $setting->header_scripts }}</textarea>
										<span id="headerScriptsErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Body Scripts</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<textarea rows="20" name="bodyScripts" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Body Scripts">{{ $setting->body_scripts }}</textarea>
										<span id="bodyScriptsErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Footer Scripts</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<textarea rows="20" name="footerScripts" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Footer Scripts">{{ $setting->footer_scripts }}</textarea>
										<span id="footerScriptsErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Custom CSS</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<textarea rows="20" name="customCss" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Custom CSS">{{ $setting->custom_css }}</textarea>
										<span id="customCssErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Custom JS</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<textarea rows="20" name="customJs" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Custom JS">{{ $setting->custom_js }}</textarea>
										<span id="customJsErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

					</div>

					<div class="d-flex justify-content-end py-6 px-9">
						<button type="submit" class="btn btn-primary" id="kt_site_setting_scripts_submit">
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

		<div class="card mb-5 mb-xl-10"   >
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_social_icons">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">Social Icons</h3>
				</div>
			</div>

			<div id="kt_social_icons" class="collapse">				
				<form id="kt_site_setting_social_form" class="form" action="{{ route('adminDoUpdateSocialIcons') }}">
					<div class="card-body border-top p-9">
						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Facebook</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="facebookUrl" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Facebook URL" value="{{ $setting->facebook_url }}">
										<span id="facebookUrlErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Twitter (X)</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="twitterUrl" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Twitter URL" value="{{ $setting->twitter_url }}">
										<span id="twitterUrlErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">LinkedIn</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="linkedinUrl" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="LinkedIn URL" value="{{ $setting->linkedin_url }}">
										<span id="linkedinUrlErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">YouTube</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="youtubeUrl" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="YouTube URL" value="{{ $setting->youtube_url }}">
										<span id="youtubeUrlErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">Instagram</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="instagramUrl" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Instagram URL" value="{{ $setting->instagram_url }}">
										<span id="instagramUrlErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6">WhatsApp</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="whatsAppUrl" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="WhatsApp URL" value="{{ $setting->whatsapp_url }}">
										<span id="whatsAppUrlErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

					</div>

					<div class="d-flex justify-content-end py-6 px-9">
						<button type="submit" class="btn btn-primary" id="kt_site_setting_social_submit">
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

		<div class="card mb-5 mb-xl-10"   >
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_smtp_config">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">SMTP Configuration</h3>
				</div>
			</div>

			<div id="kt_smtp_config" class="collapse">				
				<form id="kt_site_setting_smtp_form" class="form" action="{{ route('adminDoUpdateSMTP') }}">
					<div class="card-body border-top p-9">
						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">Email Encryption</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="emailEncryption" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Email Encryption" value="{{ $setting->email_encryption }}">
										<span id="emailEncryptionErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">SMTP Host</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="smtpHost" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="SMTP Host" value="{{ $setting->smtp_host }}">
										<span id="smtpHostErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">SMTP Port</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="smtpPort" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="SMTP Port" value="{{ $setting->smtp_port }}">
										<span id="smtpPortErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">SMTP Email</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="smtpEmail" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="SMTP Email" value="{{ $setting->smtp_email }}">
										<span id="smtpEmailErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">From Address</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="fromAddress" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="From Address" value="{{ $setting->from_address }}">
										<span id="fromAddressErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">From Name</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="fromName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="From Name" value="{{ $setting->from_name }}">
										<span id="fromNameErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">SMTP Username</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="text" name="smtpUsername" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="SMTP Username" value="{{ $setting->smtp_username }}">
										<span id="smtpUsernameErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

						<div class="row mb-6">                    
							<label class="col-lg-3 col-form-label fw-semibold fs-6 required">SMTP Password</label>
							<div class="col-lg-9">                        
								<div class="row">
									<div class="col-lg-12 fv-row">
										<input type="password" name="smtpPassword" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="SMTP Password" value="{{ $setting->smtp_password }}">
										<span id="smtpPasswordErr" class="text-danger"></span>
									</div>                            
								</div>
							</div>                    
						</div>

					</div>

					<div class="d-flex justify-content-end py-6 px-9">
						<button type="submit" class="btn btn-primary" id="kt_site_setting_smtp_submit">
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

	</div>
</div>

	<!--begin::Custom Javascript(used for this page only)-->
	<script src="{{ asset('public/backend') }}/js/admin/site-settings.js"></script>
	<!-- <script src="{{ asset('public/backend') }}/js/custom/account/settings/profile-details.js"></script>
	<script src="{{ asset('public/backend') }}/js/custom/account/settings/deactivate-account.js"></script>
	<script src="{{ asset('public/backend') }}/js/custom/pages/user-profile/general.js"></script>
	<script src="{{ asset('public/backend') }}/js/custom/utilities/modals/two-factor-authentication.js"></script> -->
	<!--end::Custom Javascript-->

@endsection