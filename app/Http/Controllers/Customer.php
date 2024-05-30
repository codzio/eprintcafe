<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\EmailSending;
use App\Http\Controllers\SmsSending;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\CartModel;
use App\Models\CustomerAddressModel;
use App\Models\OrderModel;

class Customer extends Controller {

	private $status = array();

	public function dashboard(Request $request) {

		$customerId =customerId();

		if (empty($customerId)) {
			return view('frontend/login');
		}else{
			$cond = [
	    		['id', $customerId],
	    	];

	    	$customer = CustomerModel::where($cond)->first();

	    	$address = CustomerAddressModel::where($cond)->first();

	    	$orders = OrderModel::where(['user_id' => $customerId])->latest()->get();

	    	// $productDetails= json_decode($orders->product_details);

	    	// $priceDetailsComp= json_decode($orders->price_details);

	    	// $customerAdd= json_decode($orders->customer_address);


	    	// echo "<pre>";
	    	// print_r($orders->toArray());
	    	// print_r($productDetails);
	    	// print_r($priceDetailsComp);
	    	// print_r($customerAdd);
	    	// die();

			$data = array(
				'title' => 'Dashboard',
				'pageTitle' => 'Dashboard',
				'menu' => 'dashboard',
				'customer' => $customer,
				'address' => $address,
				// 'customerAdd' => $customerAdd,
				// 'productDetails' => $productDetails,
				// 'priceDetailsComp' => $priceDetailsComp,
				'orders' => $orders,
			);

			return view('frontend/dashboard', $data);
		}

	}

	public function logout() {
		// Session::forget('adminTwoFactorSess');
		Session::forget('customerSess');
		return redirect(route('loginPage'));
	}

	public function login(Request $request) {

		$action = $request->get('action');
		$registerPageUrl = route('registerPage');

		if (!empty($action)) {
			
			if ($action == 'checkout') {
				$registerPageUrl = route('registerPage', ['action' => $action]);
			}

			//check if product slug exist
			$isProductExist = ProductModel::where('slug', $action)->first();
			if (!empty($isProductExist) && $isProductExist->count()) {
				$registerPageUrl = route('registerPage', ['action' => $action]);
			}

		}

		$data = array(
			'title' => 'Login',
			'pageTitle' => 'Login',
			'menu' => 'login',
			'action' => $action,
			'registerPageUrl' => $registerPageUrl
		);

		$customerSess = Session::get('customerSess');
		
		if (empty($customerSess)) {

			return view('frontend/login', $data);;

		} else {
			return redirect(route('customerDashboard'));
		}

	}

	public function forgotPassword(Request $request) {

		$data = array(
			'title' => 'Forgot Passwoord',
			'pageTitle' => 'Forgot Passwoord',
			'menu' => 'forgot-password',
		);

		return view('frontend/forgetPassword', $data);

	}

	public function register(Request $request) {

		$action = $request->get('action');
		$loginPageUrl = route('loginPage');

		// if (!empty($action) && $action == 'checkout') {
		// 	$loginPageUrl = route('loginPage', ['action' => $action]);
		// }

		if (!empty($action)) {
			
			if ($action == 'checkout') {
				$loginPageUrl = route('loginPage', ['action' => $action]);
			}

			//check if product slug exist
			if (ProductModel::where('slug', $action)->first()->count()) {
				$loginPageUrl = route('loginPage', ['action' => $action]);
			}

		}

		$data = array(
			'title' => 'Register',
			'pageTitle' => 'Register',
			'menu' => 'register',
			'action' => $action,
			'loginPageUrl' => $loginPageUrl 
		);

		Session::forget('unregisCustomerData');
		$customerSess = Session::get('customerSess');
		
		if (empty($customerSess)) {

			return view('frontend/register', $data);;

		} else {
			return redirect(route('customerDashboard'));
		}

	}

	public function verifyAccount(Request $request) {

		$unregisCustomerDataSess = Session::get('unregisCustomerData');
		
		if (!empty($unregisCustomerDataSess)) {

			//check if OTP expired
			$expiredTime = $unregisCustomerDataSess['expire_time'];
			$totalUserCalls = $unregisCustomerDataSess['total_user_calls'];
			$currentTime = date('H:i');

			if (strtotime($currentTime) <= strtotime($expiredTime)) {
			//if (1) {

				$data = array(
					'title' => 'Verify Account',
					'pageTitle' => 'Verify Account',
					'menu' => 'register',
				);

				return view('frontend/verify-account', $data);

			} else {

				return redirect(route('registerPage'));

			}

		} else {
			return redirect(route('loginPage'));
		}

	}

	public function doVerifyAccount(Request $request) {
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
			    'otp' => 'required|numeric|digits:6',
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

	        	$unregisCustomerDataSess = Session::get('unregisCustomerData');

	        	if (!empty($unregisCustomerDataSess)) {

	        		$customerData = (object) $unregisCustomerDataSess;

	        		//check expiry
	        		$currentTime = date('H:i');
	        		$expiredTime = $customerData->expire_time;
	        		$enteredOTP = $request->post('otp');

	        		if (strtotime($currentTime) <= strtotime($expiredTime)) {

	        			//verify if OTP matched
	        			if ($enteredOTP == $customerData->otp) {
	        				
	        				$obj = [
				        		'name' => $customerData->name,
				        		'email' => $customerData->email,
				        		'phone' => $customerData->phone,
				        		'address' => $customerData->address,
				        		'city' => $customerData->city,
				        		'state' => $customerData->state,
				        		'password' => $customerData->password,
				        	];

	        				$isAdded = CustomerModel::create($obj);

				        	if ($isAdded) {

				        		$customer = CustomerModel::latest()->first();
				        		$request->session()->put('customerSess', ['customerId' => $customer->id]);
				        		Session::forget('unregisCustomerData');

				        		//update customer id if cart data exist
						        updateUserIdInCart();

			    				$this->status = array(
									'error' => false,								
									'msg' => 'The OTP has been verified and your account has been created successfully',
									'redirect' => $customerData->redirectUrl,
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
								'msg' => 'Your OTP is not matched.'
							);
	        			}

	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Your OTP has been expired.'
						);
	        		}
	        		
	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
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

	public function doResendRegisOtp(Request $request) {
		if ($request->ajax()) {

			//check if session exist
			$unregisCustomerDataSess = Session::get('unregisCustomerData');

			if (empty($unregisCustomerDataSess)) {
				return response(array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Something went wrong'
				));
			} else {

				//now check the expiry and the resend count
				$customerData = (object) $unregisCustomerDataSess;

        		//check expiry
        		$currentTime = date('H:i');
        		$expiredTime = $customerData->expire_time;
        		$enteredOTP = $request->post('otp');
        		$totalCalls = $customerData->total_user_calls;

        		if (strtotime($currentTime) <= strtotime($expiredTime)) {
        		//if (1) {

        			//check the total calls
        			if ($totalCalls < 3) {
        				
        				$otp = mt_rand(100000, 999999);
		        		//$otp = 654321;
		                $isOtpSend = SmsSending::sendOTP($request->post('phone'), $otp);
		                //$isOtpSend = true;

		                if ($isOtpSend) {
		                	$customerData->otp = $otp;
			                $customerData->generate_time = date('H:i');
			                $customerData->expire_time = date('H:i', strtotime("+5 min"));
			                $customerData->total_user_calls = $totalCalls+1;

			                $customerData = (array) $customerData;

			                $request->session()->put('unregisCustomerData', $customerData);

			                $this->status = array(
								'error' => false,								
								'msg' => 'The OTP has been resend successfully',
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
							'msg' => 'Your OTP resend limit has been exceeded.'
						);
        			}

        		} else {
        			$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Your OTP has been expired.'
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

	public function doResendForgotPassOtp(Request $request) {
		if ($request->ajax()) {

			//check if session exist
			$forgotPassDataSess = Session::get('forgotPassData');

			if (empty($forgotPassDataSess)) {
				return response(array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Something went wrong'
				));
			} else {

				//now check the expiry and the resend count
				$customerData = (object) $forgotPassDataSess;

        		//check expiry
        		$currentTime = date('H:i');
        		$expiredTime = $customerData->expire_time;
        		$enteredOTP = $request->post('otp');
        		$totalCalls = $customerData->total_user_calls;
        		$customerId = $customerData->customerId;

        		if (strtotime($currentTime) <= strtotime($expiredTime)) {
        		//if (1) {

        			//check the total calls
        			if ($totalCalls < 3) {

        				$getCustomer = CustomerModel::where('id', $customerId)->first();
        				
        				$otp = mt_rand(100000, 999999);
		        		//$otp = 000000;
		                $isOtpSend = SmsSending::forgotOTP($getCustomer->phone, $otp);
		                //$isOtpSend = true;

		                if ($isOtpSend) {
		                	$customerData->otp = $otp;
			                $customerData->generate_time = date('H:i');
			                $customerData->expire_time = date('H:i', strtotime("+5 min"));
			                $customerData->total_user_calls = $totalCalls+1;

			                $customerData = (array) $customerData;

			                $request->session()->put('forgotPassData', $customerData);

			                $this->status = array(
								'error' => false,								
								'msg' => 'The OTP has been resend successfully',
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
							'msg' => 'Your OTP resend limit has been exceeded.'
						);
        			}

        		} else {
        			$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Your OTP has been expired.'
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

	public function doRegister(Request $request) {
		if ($request->ajax()) {

			//check if session exist
			$unregisCustomerDataSess = Session::get('unregisCustomerData');

			if ($unregisCustomerDataSess) {
				return response(array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Something went wrong'
				));
			}

			$validator = Validator::make($request->post(), [
			    'name' => 'required',
			    'email' => 'required|email|unique:customer,email',
			    'phone' => 'required|numeric|unique:customer,phone|digits:10',
			    // 'address' => 'required',
			    // 'city' => 'required',
			    // 'state' => 'required',
			    'password' => 'required',
			    //'confirmPassword' => 'required_with:password|same:password|min:6',
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

	        	$redirectUrl = route('customerDashboard');

        		// if (!empty($request->post('action'))) {
        		// 	if ($request->post('action') == 'checkout') {
        		// 		$redirectUrl = route('checkoutPage');
        		// 	}
        		// }

        		$action = $request->post('action');

        		if (!empty($action)) {

					if ($action == 'checkout') {
						$redirectUrl = route('checkoutPage');
					}

					//check if product slug exist
					if (ProductModel::where('slug', $action)->first()->count()) {
						$redirectUrl = route('productPage', ['slug' => ProductModel::where('slug', $action)->value('slug')]);
					}

				}

        		$otp = mt_rand(100000, 999999);
        		//$otp = 123456;
                $isOtpSend = SmsSending::sendOTP($request->post('phone'), $otp);
                //$isOtpSend = true;

                if ($isOtpSend) {
                	
                	$obj = [
		        		'name' => $request->post('name'),
		        		'email' => $request->post('email'),
		        		'phone' => $request->post('phone'),
		        		'address' => $request->post('address'),
		        		'city' => $request->post('city'),
		        		'state' => $request->post('state'),
		        		'password' => Hash::make($request->post('password')),
		        		'redirectUrl' => $redirectUrl,
		        		'otp' => $otp,
                        'generate_time' => date('H:i'),
                        'expire_time' => date('H:i', strtotime("+5 min")),
                        'total_user_calls' => 0
		        	];

		        	$request->session()->put('unregisCustomerData', $obj);

		        	$this->status = array(
						'error' => false,								
						'msg' => 'We have sent an OTP on your mobile number. Please do verify.',
						'redirect' => route('verifyAccountPage')
					);

                } else {
                	$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Unable to send an OTP.'
					);
                }

	        	// $isAdded = CustomerModel::create($obj);

	        	// if ($isAdded) {

	        	// 	$customer = CustomerModel::latest()->first();
	        	// 	$request->session()->put('customerSess', ['customerId' => $customer->id]);

	        	// 	//update customer id if cart data exist
			    //     updateUserIdInCart();

    			// 	$this->status = array(
				// 		'error' => false,								
				// 		'msg' => 'Your account has been created sucessfully',
				// 		'redirect' => $redirectUrl,
				// 	);

    			// } else {
    			// 	$this->status = array(
				// 		'error' => true,
				// 		'eType' => 'final',
				// 		'msg' => 'Something went wrong.'
				// 	);
    			// }

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

	public function doLogin(Request $request) {
		if ($request->ajax()) {

			$phoneNumber = $request->post('phoneNumber');

			$condition = [
	            'password' => 'required',
	        ];

			if (is_numeric($phoneNumber)) {
				$condition['phoneNumber'] = 'required|numeric|digits:10';
			} else {
				$condition['phoneNumber'] = 'required|email';
			}

			$messages = [
			    'phoneNumber.required' => 'The phone number or email address is required.',
			    'phoneNumber.numeric' => 'The phone number must be numeric.',
			    'phoneNumber.digits' => 'The phone number must be 10 digits long.',
			    'phoneNumber.email' => 'The email address is invalid.'
			];

			$validator = Validator::make($request->post(), $condition, $messages);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {
	        
	        	if (is_numeric($phoneNumber)) {
					$cond = [
		        		['phone', $request->post('phoneNumber')],
		        	];
				} else {
					$cond = [
		        		['email', $request->post('phoneNumber')],
		        	];
				}

	        	$getCustomer = CustomerModel::where($cond)->first();

	        	if (!empty($getCustomer)) {

	        		//check if password match
	        		if (Hash::check($request->post('password'), $getCustomer->password)) {

	        				$request->session()->put('customerSess', array(
			        			'customerId' => $getCustomer->id
			        		));

			        		//update customer id if cart data exist
			        		updateUserIdInCart();

			        		$redirectUrl = route('customerDashboard');
			        		$action = $request->post('action');

			        		// if (!empty($request->post('action'))) {
			        		// 	if ($request->post('action') == 'checkout') {
			        		// 		$redirectUrl = route('checkoutPage');
			        		// 	}
			        		// }

			        		if (!empty($action)) {
			
								if ($action == 'checkout') {
									$redirectUrl = route('checkoutPage');
								}

								//check if product slug exist
								$isProductExist = ProductModel::where('slug', $action)->first();
								if (!empty($isProductExist) && $isProductExist->count()) {
									$redirectUrl = route('productPage', ['slug' => ProductModel::where('slug', $action)->value('slug')]);
								}

							}

		        			$this->status = array(
								'error' => false,
								'redirect' => $redirectUrl
							);

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
						'msg' => 'It appears that either your email or password might be incorrect.'
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
	            'phoneNumber' => 'required|numeric|digits:10',
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
	        		['phone', $request->post('phoneNumber')],
	        	];

	        	$getCustomer = CustomerModel::where($cond)->first();

	        	if (!empty($getCustomer)) {

	        		$otp = mt_rand(100000, 999999);
	        		//$otp = 112233;
	                $isOtpSend = SmsSending::forgotOTP($request->post('phoneNumber'), $otp);
	                //$isOtpSend = true;

		        	if ($isOtpSend) {
		        		
		        		$obj = [
		        			'customerId' => $getCustomer->id,
			        		'otp' => $otp,
	                        'generate_time' => date('H:i'),
	                        'expire_time' => date('H:i', strtotime("+5 min")),
	                        'total_user_calls' => 0
			        	];

			        	$request->session()->put('forgotPassData', $obj);

			        	$this->status = array(
							'error' => false,								
							'msg' => 'We have sent an OTP on your mobile number.',
							'redirect' => route('customerResetPassword')
						);

		        	} else {
		        		$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Unable to send OTP'
						);
		        	}

	        		// $token = bin2hex(random_bytes(20));
	        		// $tokenExpiry = date('Y-m-d');

	        		// $getCustomer->forgot_token = $token;
	        		// $getCustomer->forgot_token_validity = $tokenExpiry;
	        		// $getCustomer->save();

    				// $forgotPass = array(
    				// 	'name' => $getCustomer->name,
    				// 	'email' => $getCustomer->email,
    				// 	'token' => $token,
    				// 	'tokenExpiry' => $tokenExpiry,
    				// );

    				// $isMailSent = EmailSending::customerResetPassword($forgotPass);
					//$isMailSent = true;

					// if ($isMailSent) {

		        	// 	$this->status = array(
					// 		'error' => false,
					// 		'msg' => 'Please check your inbox and follow the provided link to reset your password.',
					// 		'redirect' => route('loginPage')
					// 	);

					// } else {
						
					// 	$this->status = array(
					// 		'error' => true,
					// 		'eType' => 'final',
					// 		'msg' => 'Something went wrong'
					// 	);

					// }

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears that either your phone number might be incorrect, or your account could be inactive.'
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

	public function resetPassword(Request $request) {
		
		Session::forget('customerSess');
		$forgotPassData = Session::get('forgotPassData');

		if (!empty($forgotPassData)) {
			
			$forgotPassData = (object) $forgotPassData;

    		//check expiry
    		$currentTime = date('H:i');
    		$expiredTime = $forgotPassData->expire_time;

    		if (strtotime($currentTime) <= strtotime($expiredTime)) {

				$data = array(
					'title' => 'Reset Password',
					'pageTitle' => 'Reset Password',
					'menu' => 'reset-password',
				);

				return view('frontend/resetPassword', $data);

    		} else {
    			Session::forget('forgotPassData');
    			return redirect(route('loginPage'));
    		}

		} else {
			return redirect(route('loginPage'));
		}

		//get admin user from the token
		// $cond = [
    	// 	['forgot_token', $token],
    	// ];

		// $getCustomer = CustomerModel::where($cond)->first();
		
		// if (!empty($getCustomer)) {
			
		// 	//validate date
		// 	$getForgotTokenValidity = $getCustomer->forgot_token_validity;
		// 	$currentDate = date('Y-m-d');

		// 	$proceedProcess = true;

		// 	if ($getForgotTokenValidity != $currentDate) {
		// 		$proceedProcess = false;
		// 	}

		// 	if ($proceedProcess) {

		// 		$data = array(
		// 			'token' => $token,
		// 			'title' => 'Reset Password',
		// 			'pageTitle' => 'Reset Password',
		// 			'menu' => 'reset-password',
		// 		);
		// 		return view('frontend/resetPassword', $data);

		// 	} else {

		// 		$getCustomer->forgot_token = null;
		// 		$getCustomer->forgot_token_validity = null;
		// 		$getCustomer->save();
		// 		return redirect(route('loginPage'));

		// 	}

		// } else {
		// 	return redirect(route('loginPage'));
		// }

	}

	public function doResetPassword(Request $request) {
		
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
				'otp' => 'required|numeric|digits:6',
	            'password' => 'required|min:8',
	            'confirmPass' => 'required|same:password',     
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

	        	$forgotPassData = Session::get('forgotPassData');

	        	if (!empty($forgotPassData)) {

	        		$forgotPassData = (object) $forgotPassData;

	        		//check expiry
	        		$currentTime = date('H:i');
	        		$expiredTime = $forgotPassData->expire_time;
	        		$enteredOTP = $request->post('otp');

	        		if (strtotime($currentTime) <= strtotime($expiredTime)) {

	        			//verify if OTP matched
	        			if ($enteredOTP == $forgotPassData->otp) {

	        				$newPassword = $request->post('password');
	        				$getCustomer = CustomerModel::where('id', $forgotPassData->customerId)->first();

							if (Hash::check($newPassword, $getCustomer->password)) {
								
								$this->status = array(
									'error' => true,
									'eType' => 'final',
									'msg' => 'You cannot use your current password as new password.'
								);

								return response($this->status);

							}

							$hashedNewPassword = Hash::make($newPassword);

							$getCustomer->password = $hashedNewPassword;
							$getCustomer->save();

							Session::forget('forgotPassData');

							$this->status = array(
								'error' => false,							
								'msg' => 'Your password has been reset successfully.',
								'redirect' => route('loginPage')
							);

	        			} else {
	        				$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'Your OTP is not matched.'
							);
	        			}

	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Your OTP has been expired.'
						);
	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'It appears that your session has been expired.'
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

	public function resetPasswordOld(Request $request, $token) {

		Session::forget('customerSess');

		//get admin user from the token

		$cond = [
    		['forgot_token', $token],
    	];

		$getCustomer = CustomerModel::where($cond)->first();
		
		if (!empty($getCustomer)) {
			
			//validate date
			$getForgotTokenValidity = $getCustomer->forgot_token_validity;
			$currentDate = date('Y-m-d');

			$proceedProcess = true;

			if ($getForgotTokenValidity != $currentDate) {
				$proceedProcess = false;
			}

			if ($proceedProcess) {

				$data = array(
					'token' => $token,
					'title' => 'Reset Password',
					'pageTitle' => 'Reset Password',
					'menu' => 'reset-password',
				);
				return view('frontend/resetPassword', $data);

			} else {

				$getCustomer->forgot_token = null;
				$getCustomer->forgot_token_validity = null;
				$getCustomer->save();
				return redirect(route('loginPage'));

			}

		} else {
			return redirect(route('loginPage'));
		}

	}

	public function doResetPasswordOld(Request $request) {
		
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
				];

	        	$getCustomer = CustomerModel::where($cond)->first();

	        	if (!empty($getCustomer)) {

	        		//validate date
					$getForgotTokenValidity = $getCustomer->forgot_token_validity;
					$currentDate = date('Y-m-d');

					$proceedProcess = true;

					if ($getForgotTokenValidity != $currentDate) {
						$proceedProcess = false;
					}

					if ($proceedProcess) {

						//check if user entering the same password as new password

						$newPassword = $request->post('password');

						if (Hash::check($newPassword, $getCustomer->password)) {
							
							$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'You cannot use your current password as new password.'
							);

							return response($this->status);

						}

						//change password
						$hashedNewPassword = Hash::make($newPassword);

						$getCustomer->password = $hashedNewPassword;
						$getCustomer->forgot_token = null;
						$getCustomer->forgot_token_validity = null;
						$getCustomer->save();

						$this->status = array(
							'error' => false,							
							'msg' => 'Your password has been reset successfully.',
							'redirect' => route('loginPage')
						);

					} else {

						$getCustomer->forgot_token = null;
						$getCustomer->forgot_token_validity = null;
						$getCustomer->save();
						
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

	public function doChangePassword(Request $request) {
		
		if ($request->ajax()) {

			// echo "<pre>";
			// print_r($_POST);
			// die();

			$validator = Validator::make($request->post(), [
	            'password' => 'required|min:6',
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

	        	$customerId = customerId();
	        	
	        	$cond = [
	        		['id', $customerId],
	        	];

	        	$getCustomer = CustomerModel::where($cond)->first();

	        	if (!empty($getCustomer)) {

	        		//check if password match
	        		if (Hash::check($request->post('password'), $getCustomer->password)) {

	        			$getCustomer->password = Hash::make($request->post('confirmPassword'));
		        		$isUpdated = $getCustomer->save();

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
						'msg' => 'Something went wrong'
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

	public function doUpdateAccDetails(Request $request) {
		if ($request->ajax()) {

			// echo "<pre>";
			// print_r($_POST);
			// die();

			$id = customerId();


			$validator = Validator::make($request->post(), [
			    'name' => 'required',
			    'email' => 'required|email|unique:customer,email,'.$id,
			    'phone' => 'required|numeric|digits:10|unique:customer,phone,'.$id,
			    'address' => 'required',
			    'city' => 'required',
			    'state' => 'required',
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

	        	$obj = [
	        		'name' => $request->post('name'),
	        		'email' => $request->post('email'),
	        		'phone' => $request->post('phone'),
	        		'address' => $request->post('address'),
	        		'city' => $request->post('city'),
	        		'state' => $request->post('state'),
	        	];

	        	$isUpdated = CustomerModel::where(['id' => $id])->update($obj);

	        	if ($isUpdated) {

    				$this->status = array(
						'error' => false,								
						'msg' => 'Your account details has been updated sucessfully',
					);

    			} else {
    				$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong.'
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

	public function doSaveShippingAddress(Request $request) {

		if ($request->ajax()) {

			// echo "<pre>";
			// print_r($_POST);
			// die();

			$id = customerId();

			$obj1 = [
				'shippingName' => 'required',
				'shippingCompanyName' => 'sometimes|nullable',
				'shippingAddress' => 'required',
				'shippingCity' => 'required',
	            'shippingState' => 'required',
	            'shippingPincode' => 'required|numeric|digits:6',
	            'shippingEmail' => 'required|email',
	            'shippingPhone' => 'required|numeric',
	        ];


			$validator = Validator::make($request->post(), $obj1);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$userId = customerId();

	        	$obj = [
	    			'user_id' => $userId,
					'shipping_name' => $request->post('shippingName'),
					'shipping_company_name' => $request->post('shippingCompanyName'),
					'shipping_address' => $request->post('shippingAddress'),
					'shipping_city' => $request->post('shippingCity'),
		            'shipping_state' => $request->post('shippingState'),
		            'shipping_pincode' => $request->post('shippingPincode'),
		            'shipping_email' => $request->post('shippingEmail'),
		            'shipping_phone' => $request->post('shippingPhone'),
		            'is_billing_same' => 1,
		        ];


		        $getShipAdd = CustomerAddressModel::where($obj)->count();

		        if ($getShipAdd) {

		        	$isUpdated = CustomerAddressModel::where(['user_id' => $userId])->update($obj);
		        	
		        }else{
		        	$isUpdated = CustomerAddressModel::create($obj);
		        }

	        	if ($isUpdated) {

    				$this->status = array(
						'error' => false,								
						'msg' => 'Your shipping address has been saved sucessfully',
					);

    			} else {
    				$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong.'
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

	public function doSaveBillingAddress(Request $request) {

		if ($request->ajax()) {

			$id = customerId();

			$obj1 = [
				'billingName' => 'required',
				'billingCompanyName' => 'sometimes|nullable',
				'billingAddress' => 'required',
				'billingCity' => 'required',
	            'billingState' => 'required',
	            'billingPincode' => 'required|numeric|digits:6',
	            'billingEmail' => 'required|email',
	            'billingPhone' => 'required|numeric',
	        ];


			$validator = Validator::make($request->post(), $obj1);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$userId = customerId();

	        	$obj = [
	    			'user_id' => $userId,
					'billing_name' => $request->post('billingName'),
					'billing_company_name' => $request->post('billingCompanyName'),
					'billing_address' => $request->post('billingAddress'),
					'billing_city' => $request->post('billingCity'),
		            'billing_state' => $request->post('billingState'),
		            'billing_pincode' => $request->post('billingPincode'),
		            'billing_email' => $request->post('billingEmail'),
		            'billing_phone' => $request->post('billingPhone'),
		            'is_billing_same' => 0,
		        ];


		        $getBillingAdd = CustomerAddressModel::where(['user_id' => $userId])->count();

		        if ($getBillingAdd) {

		        	$isUpdated = CustomerAddressModel::where(['user_id' => $userId])->update($obj);
		        	
		        }else{

		        	$obj['shipping_name'] = $request->post('billingName');
		        	$obj['shipping_company_name'] = $request->post('shippingCompanyName');
		        	$obj['shipping_address'] = $request->post('shippingAddress');
		        	$obj['shipping_city'] = $request->post('shippingCity');
		        	$obj['shipping_state'] = $request->post('shippingState');
		        	$obj['shipping_pincode'] = $request->post('shippingPincode');
		        	$obj['shipping_email'] = $request->post('shippingEmail');
		        	$obj['shipping_phone'] = $request->post('shippingPhone');

		        	$isUpdated = CustomerAddressModel::create($obj);
		        }

	        	if ($isUpdated) {

    				$this->status = array(
						'error' => false,								
						'msg' => 'Your billing address has been saved sucessfully',
					);

    			} else {
    				$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong.'
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



	
}