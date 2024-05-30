<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\EmailSending;
use App\Models\AdminModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\PaperSizeModel;
use App\Models\PaperTypeModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\GsmModel;
use App\Models\CouponModel;
use App\Models\ShippingModel;
use App\Models\ContactModel;
use App\Models\OrderModel;
use App\Models\CustomerModel;

class Auth extends Controller {

	private $status = array();

	public function index(Request $request) {
		
		// Delete two factor session
		Session::forget('adminTwoFactorSess');

		$adminSess = Session::get('adminSess');
		
		if (empty($adminSess)) {

			//$data = ['siteSettings' => siteSettings()];
			return view('admin/vwAdminLogin');

		} else {
			return redirect(route('adminDashboard'));
		}
	}

	public function twoStepVerify(Request $request) {
		
		$adminTwoFactorSess = Session::get('adminTwoFactorSess');

		//check if two factor session exist

		if (!empty($adminTwoFactorSess)) {

			$adminTwoFactorSess = (object) $adminTwoFactorSess;

			//check if two step verification expired
			$currentTime =  date('H:i');			
			$expiredTime = $adminTwoFactorSess->expiredAt;

			if ($currentTime >= $expiredTime) {
				return redirect(route('adminLogin'));
			}

			$cond = [
        		['id', $adminTwoFactorSess->adminId],
        	];

        	//get data on the basis of admin id stored in two factor session
        	$getAdmin = AdminModel::where($cond)->first();

        	if (empty($getAdmin)) {
        		return redirect(route('adminLogin'));
        	}

        	$data = array(
        		'adminData' => $getAdmin,
        		'email' => Str::mask($getAdmin->email, '*', 2, 6)
        	);

			return view('admin/vwAdminTwoStep', $data);

		} else {
			return redirect(route('adminLogin'));
		}

	}

	public function forgotPassword(Request $request) {
		
		Session::forget('adminTwoFactorSess');
		Session::forget('adminSess');

		return view('admin/vwAdminForgotPassword');

	}

	public function resetPassword(Request $request, $token) {
		
		Session::forget('adminTwoFactorSess');
		Session::forget('adminSess');

		//get admin user from the token

		$cond = [
    		['forgot_token', $token],
    		['is_active', 1]
    	];

		$getAdmin = AdminModel::where($cond)->first();
		
		if (!empty($getAdmin)) {
			
			//validate date
			$getForgotTokenValidity = $getAdmin->forgot_token_validity;
			$currentDate = date('Y-m-d');

			$proceedProcess = true;

			if ($getForgotTokenValidity != $currentDate) {
				$proceedProcess = false;
			}

			if ($proceedProcess) {

				$data = array('token' => $token);
				return view('admin/vwResetPassword', $data);

			} else {

				$getAdmin->forgot_token = null;
				$getAdmin->forgot_token_validity = null;
				$getAdmin->save();
				return redirect(route('adminLogin'));

			}

		} else {
			return redirect(route('adminLogin'));
		}

	}

	public function dashboard(Request $request) {
		
		$data = array(
			'title' => 'Dashboard',
			'pageTitle' => 'Dashboard',
			'menu' => 'dashboard',
			'users' => AdminModel::count(),
			'products' => ProductModel::count(),
			'category' => CategoryModel::count(),
			'paperSize' => PaperSizeModel::count(),
			'paperType' => PaperTypeModel::count(),
			'binding' => BindingModel::count(),
			'lamination' => LaminationModel::count(),
			'cover' => CoverModel::count(),
			'paperGsm' => GsmModel::count(),
			'coupon' => CouponModel::count(),
			'shipping' => ShippingModel::count(),
			'contact' => ContactModel::count(),
			'customer' => CustomerModel::count(),
		);
		
		return view('admin/dashboard/vwAdminDashboard', $data);

	}

	public function accountSettings(Request $request) {	

		if (!can('read', 'account_settings')){
			return redirect(route('adminDashboard'));
		}

		$adminId = adminId();
		$adminData = AdminModel::where('id', $adminId)->first();

		$profilePicture = "../public/backend/media/svg/avatars/blank.svg";

		if (!empty($adminData->profile)) {
			$profilePicture = getImg($adminData->profile);
		}
		
		$data = array(
			'title' => 'Account Settings',
			'pageTitle' => 'Account Settings',
			'menu' => 'account-settings',
			'allowMedia' => true,
			'admin' => $adminData,
			'profilePicture' => $profilePicture
		);
		
		return view('admin/account/vwAdminAccountSettings', $data);

	}

	public function doLogin(Request $request) {
		
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
	            'email' => 'required|email',
	            'password' => 'required',
	        ]);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {
	        	
	        	$cond = [
	        		['email', $request->post('email')],
	        		['is_active', 1]
	        	];

	        	$getAdmin = AdminModel::where($cond)->first();

	        	if (!empty($getAdmin)) {

	        		//check if password match
	        		if (Hash::check($request->post('password'), $getAdmin->password)) {

	        			//check if two step auth enabled
	        			if ($getAdmin->two_step) {
	        				
	        				//check if user attempt 3 otp or blocked
	        				$getTotalAttempt = $getAdmin->otp_attempt;
	        				$getOtpBlockDate = $getAdmin->otp_block_date;
	        				$getOtpBlockTime = $getAdmin->otp_block_time;

	        				$checkBlockTime = date("H:i", strtotime('+30 minutes', strtotime($getOtpBlockTime)));

	        				$canStartTwoStepAuth = false;

	        				if ($getTotalAttempt) {

	        					//validate block date
	        					$currentDate = date('Y-m-d');
	        					$currentTime = date('H:i');

	        					if (empty($getOtpBlockDate)) {
	        						
	        						$canStartTwoStepAuth = true;

	        					} elseif ($currentDate != $getOtpBlockDate) {
	        						
	        						$canStartTwoStepAuth = true;
	        						$getAdmin->otp_attempt = null;
	        						$getAdmin->otp_block_date = null;
	        						$getAdmin->otp_block_time = null;
	        						$getAdmin->save();

	        					} elseif ($currentTime > $checkBlockTime) {
	        						
	        						$canStartTwoStepAuth = true;
	        						$getAdmin->otp_attempt = 2;
	        						$getAdmin->otp_block_date = null;
	        						$getAdmin->otp_block_time = null;
	        						$getAdmin->save();

	        					} else {
	        						
	        						$this->status = array(
										'error' => true,
										'eType' => 'final',
										'msg' => 'You\'ve exceeded limit for OTP attempts or resends. Please try in 30 minutes.'
									);

	        					}

	        				} else {
	        					$canStartTwoStepAuth = true;
	        				}

	        				if ($canStartTwoStepAuth) {
	        					
	        					$otp = random_int(100000, 999999);
		        				$otp = 123456;
		        				$expiredAt = date('H:i', strtotime('+5 Min'));

		        				$twoFactorAuthObj = array(
		        					'adminId' => $getAdmin->id,
		        					'name' => $getAdmin->name,
		        					'email' => $getAdmin->email,
		        					'otp' => $otp,
		        					'expiredAt' => $expiredAt,
		        					'resend' => 0,
		        				);

		        				//$isMailSent = EmailSending::adminTwoFactorAuth($twoFactorAuthObj);
	        					$isMailSent = true;

	        					if ($isMailSent) {
	        						
	        						$request->session()->put('adminTwoFactorSess', $twoFactorAuthObj);

					        		$this->status = array(
										'error' => false,
										'redirect' => route('adminTwoStep')
									);

	        					} else {
	        						
	        						$this->status = array(
										'error' => true,
										'eType' => 'final',
										'msg' => 'Unable to send two factor authentication code.'
									);

	        					}
	        				}

	        			} else {
	        				
	        				$request->session()->put('adminSess', array(
			        			'adminId' => $getAdmin->id
			        		));

		        			$this->status = array(
								'error' => false,
								'redirect' => route('adminDashboard')
							);

	        			}

	        		} else {
	        			
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'The email or password you provided may be incorrect.'
						);

	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears that either your email or password might be incorrect, or your account could be inactive.'
					);
	        	}
	        }


		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}

	public function doCheckTwoStep(Request $request) {

		if ($request->ajax()) {

			//check if two step session exist
			$adminTwoFactorSess = Session::get('adminTwoFactorSess');

			if (empty($adminTwoFactorSess)) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Your session has been expired.'
				);

				return response($this->status);

			}

			$adminTwoFactorSess = (object) $adminTwoFactorSess;

			//check if session expired
			$currentTime = date('H:i');
			$expiredTime = $adminTwoFactorSess->expiredAt;

			if ($currentTime >= $expiredTime) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Your session has been expired.'
				);

				return response($this->status);

			}

			$cond = [
        		['id', $adminTwoFactorSess->adminId],
        		['is_active', 1],
        	];

			//check if admin exist
			$getAdmin = AdminModel::where($cond)->first();

			if (empty($getAdmin)) {

				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Your account seems doesn\'t found or inactive.'
				);

				return response($this->status);
			}

			//validate entered OTP
			$enteredOTP = $request->post('code_1').$request->post('code_2').$request->post('code_3').$request->post('code_4').$request->post('code_5').$request->post('code_6');

			if (strlen($enteredOTP) != 6) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Please enter the 6 digit OTP code.'
				);

				return response($this->status);

			}

			$currentOTP = $adminTwoFactorSess->otp;
			
			if ($currentOTP == $enteredOTP) {

				$request->session()->put('adminSess', array(
        			'adminId' => $getAdmin->id
        		));

    			$this->status = array(
					'error' => false,
					'redirect' => route('adminDashboard')
				);
				
			} else {
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'You\'ve entered the incorrect OTP.'
				);
			}

		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}

	public function doResendOTP(Request $request) {
		if ($request->ajax()) {

			//check if two step session exist
			$adminTwoFactorSess = Session::get('adminTwoFactorSess');

			if (empty($adminTwoFactorSess)) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Your session has been expired.'
				);

				return response($this->status);

			}

			$adminTwoFactorSess = (object) $adminTwoFactorSess;

			//check if session expired
			$currentTime = date('H:i');
			$expiredTime = $adminTwoFactorSess->expiredAt;

			if ($currentTime >= $expiredTime) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Your session has been expired.'
				);

				return response($this->status);

			}

			$cond = [
        		['id', $adminTwoFactorSess->adminId],
        		['is_active', 1],
        	];

			//check if admin exist
			$getAdmin = AdminModel::where($cond)->first();

			if (empty($getAdmin)) {

				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Your account seems doesn\'t found or inactive.'
				);

				return response($this->status);
			}

			//validate otp block date & time
			$otpAttempt = $getAdmin->otp_attempt;
			$otpBlockDate = $getAdmin->otp_block_date;
			$otpBlockTime = $getAdmin->otp_block_time;

			$checkBlockTime = date("H:i", strtotime('+30 minutes', strtotime($otpBlockTime)));

			$currentDate = date('Y-m-d');
	        $currentTime = date('H:i');

			$canResendOTP = false;

			if (empty($otpBlockDate)) {
	        						
				$canResendOTP = true;

			} elseif ($currentDate != $otpBlockDate) {
				
				$canResendOTP = true;
				$getAdmin->otp_attempt = null;
				$getAdmin->otp_block_date = null;
				$getAdmin->otp_block_time = null;
				$getAdmin->save();

			} elseif ($currentTime > $checkBlockTime) {
				
				$canResendOTP = true;
				$getAdmin->otp_attempt = 2;
				$getAdmin->otp_block_date = null;
				$getAdmin->otp_block_time = null;
				$getAdmin->save();

			} else {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'You\'ve exceeded limit for OTP attempts or resends. Please try in 30 minutes.'
				);

			}

			if ($canResendOTP) {
				
				$otp = random_int(100000, 999999);
				$otp = 654321;
				$expiredAt = date('H:i', strtotime('+5 Min'));
				$otpAttempt += 1;

				$twoFactorAuthObj = array(
					'adminId' => $getAdmin->id,
					'name' => $getAdmin->name,
					'email' => $getAdmin->email,
					'otp' => $otp,
					'expiredAt' => $expiredAt,
					'resend' => $otpAttempt,
				);

				//$isMailSent = EmailSending::adminTwoFactorAuth($twoFactorAuthObj);
				$isMailSent = true;

				if ($isMailSent) {

					//update
					$getAdmin->otp_attempt = $otpAttempt;

					if ($otpAttempt > 2) {
						$getAdmin->otp_block_date = date('Y-m-d');
						$getAdmin->otp_block_time = date('H:i');
					}

					$getAdmin->save();
					
					$request->session()->put('adminTwoFactorSess', $twoFactorAuthObj);

	        		$this->status = array(
						'error' => false,
						'msg' => 'The two factor code has been sent.'
					);

				} else {
					
					$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Unable to send two factor authentication code.'
					);

				}

			}

		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);
	}

	public function doForgotPassword(Request $request) {
		
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
	            'email' => 'required|email',	            
	        ]);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {
	        	
	        	$cond = [
	        		['email', $request->post('email')],
	        		['is_active', 1]
	        	];

	        	$getAdmin = AdminModel::where($cond)->first();

	        	if (!empty($getAdmin)) {

	        		$token = bin2hex(random_bytes(20));
	        		$tokenExpiry = date('Y-m-d');

	        		$getAdmin->forgot_token = $token;
	        		$getAdmin->forgot_token_validity = $tokenExpiry;
	        		$getAdmin->save();

    				$forgotPass = array(
    					'adminId' => $getAdmin->id,
    					'name' => $getAdmin->name,
    					'email' => $getAdmin->email,
    					'token' => $token,
    					'tokenExpiry' => $tokenExpiry,
    				);

    				$isMailSent = EmailSending::adminResetPassword($forgotPass);
					//$isMailSent = true;

					if ($isMailSent) {

		        		$this->status = array(
							'error' => false,
							'msg' => 'Please check your inbox and follow the provided link to reset your password.',
							'redirect' => route('adminLogin')
						);

					} else {
						
						$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Something went wrong'
						);

					}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears that either your email might be incorrect, or your account could be inactive.'
					);
	        	}
	        }


		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}

	public function doResetPassword(Request $request) {
		
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
	            'password' => 'required|min:8',
	            'confirmPass' => 'required|same:password',
	            'resetToken' => 'required',        
	        ]);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$token = $request->post('resetToken');
	        	
	        	$cond = [
					['forgot_token', $token],
					['is_active', 1]
				];

	        	$getAdmin = AdminModel::where($cond)->first();

	        	if (!empty($getAdmin)) {

	        		//validate date
					$getForgotTokenValidity = $getAdmin->forgot_token_validity;
					$currentDate = date('Y-m-d');

					$proceedProcess = true;

					if ($getForgotTokenValidity != $currentDate) {
						$proceedProcess = false;
					}

					if ($proceedProcess) {

						//check if user entering the same password as new password

						$newPassword = $request->post('password');

						if (Hash::check($newPassword, $getAdmin->password)) {
							
							$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'You cannot use your current password as new password.'
							);

							return response($this->status);

						}

						//change password
						$hashedNewPassword = Hash::make($newPassword);

						$getAdmin->password = $hashedNewPassword;
						$getAdmin->forgot_token = null;
						$getAdmin->forgot_token_validity = null;
						$getAdmin->save();

						$this->status = array(
							'error' => false,							
							'msg' => 'Your password has been reset successfully.',
							'redirect' => route('adminLogin')
						);

					} else {

						$getAdmin->forgot_token = null;
						$getAdmin->forgot_token_validity = null;
						$getAdmin->save();
						
						$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'It appears that your token might be incorrect, or token might be expired. Please referesh the page and try again'
						);

					}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears that your token might be incorrect, or your account could be inactive.'
					);
	        	}
	        }


		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}

	public function doUpdateProfile(Request $request) {
		
		if ($request->ajax()) {

			if (!can('update', 'account_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'profilePicture' => 'sometimes|nullable|numeric',
	            'name' => 'required',
	            'phoneNumber' => 'sometimes|nullable|numeric',
	            'address' => 'sometimes|nullable',
	        ]);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$adminId = adminId();
	        	
	        	$cond = [
	        		['id', $adminId],
	        		['is_active', 1]
	        	];

	        	$getAdmin = AdminModel::where($cond)->first();

	        	if (!empty($getAdmin)) {

	        		$getAdmin->profile = $request->post('profilePicture');
	        		$getAdmin->name = $request->post('name');
	        		$getAdmin->phone_number = $request->post('phoneNumber');
	        		$getAdmin->address = $request->post('address');
	        		$isUpdated = $getAdmin->save();

	        		if ($isUpdated) {
	        			$this->status = array(
							'error' => false,
							'msg' => 'The profile details has been updated.'
						);
	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Something went wrong.'
						);
	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears your account could be inactive.'
					);
	        	}
	        }


		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}

	public function doUpdateEmail(Request $request) {
		
		if ($request->ajax()) {

			if (!can('update', 'account_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'emailAddress' => 'required|email|unique:admins,email',
	            'confirmEmailPassword' => 'required',
	        ]);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$adminId = adminId();
	        	
	        	$cond = [
	        		['id', $adminId],
	        		['is_active', 1]
	        	];

	        	$getAdmin = AdminModel::where($cond)->first();

	        	if (!empty($getAdmin)) {

	        		//check if password match
	        		if (Hash::check($request->post('confirmEmailPassword'), $getAdmin->password)) {

	        			$getAdmin->email = $request->post('emailAddress');
		        		$isUpdated = $getAdmin->save();

		        		if ($isUpdated) {
		        			$this->status = array(
								'error' => false,
								'msg' => 'The email address has been updated.'
							);
		        		} else {
		        			$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'Something went wrong.'
							);
		        		}

	        		} else {
	        			
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'The password you provided is incorrect.'
						);

	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears your account could be inactive.'
					);
	        	}
	        }


		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}

	public function doChangePassword(Request $request) {
		
		if ($request->ajax()) {

			if (!can('update', 'account_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'currentPassword' => 'required|min:6',
	            'newPassword' => 'required|min:6',
	            'confirmPassword' => 'required|min:6|same:newPassword',
	        ]);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$adminId = adminId();
	        	
	        	$cond = [
	        		['id', $adminId],
	        		['is_active', 1]
	        	];

	        	$getAdmin = AdminModel::where($cond)->first();

	        	if (!empty($getAdmin)) {

	        		//check if password match
	        		if (Hash::check($request->post('currentPassword'), $getAdmin->password)) {

	        			$getAdmin->password = Hash::make($request->post('confirmPassword'));
		        		$isUpdated = $getAdmin->save();

		        		if ($isUpdated) {
		        			$this->status = array(
								'error' => false,
								'msg' => 'The password has been updated successfully.'
							);
		        		} else {
		        			$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'Something went wrong.'
							);
		        		}

	        		} else {
	        			
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'The password you provided is incorrect.'
						);

	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears your account could be inactive.'
					);
	        	}
	        }


		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}

	public function doUpdateTwoStep(Request $request) {
		
		if ($request->ajax()) {

			if (!can('update', 'account_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'enable' => 'sometimes|nullable',
	        ]);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$adminId = adminId();
	        	
	        	$cond = [
	        		['id', $adminId],
	        		['is_active', 1]
	        	];

	        	$getAdmin = AdminModel::where($cond)->first();

	        	if (!empty($getAdmin)) {

	        		$enableTwoStep = 0;
	        		$msg = "The Two-factor authentication has been disabled.";

	        		if ($request->post('enable')) {
	        			$enableTwoStep = 1;
	        			$msg = "The Two-factor authentication has been enable.";
	        		}

	        		$getAdmin->two_step = $enableTwoStep;
	        		$isUpdated = $getAdmin->save();

	        		if ($isUpdated) {
	        			$this->status = array(
							'error' => false,
							'msg' => $msg
						);
	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Something went wrong.'
						);
	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears your account could be inactive.'
					);
	        	}
	        }


		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		return response($this->status);

	}
	
	public function logout() {
		// Session::forget('adminTwoFactorSess');
		Session::forget('adminSess');
		return redirect(route('adminLogin'));
	}
}