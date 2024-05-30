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
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

 use App\Http\Controllers\SmsSending;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\PricingModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\ShippingModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CartModel;
use App\Models\CouponModel;
use App\Models\CustomerAddressModel;
use App\Models\CustomerModel;
use App\Models\BarcodeModel;

use App\Models\ContactModel;
use App\Models\LandingPageEnquiryModel;

use Storage;
use League\Flysystem\Filesystem;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Spatie\Dropbox\Client as DropboxClient; // Import the DropboxClient

class Home extends Controller {

	private $status = array();

	public function index(Request $request) {

		$categoryList = CategoryModel::
		select('category.*', DB::raw('(SELECT COUNT(*) FROM product as b WHERE b.category_id = category.id) as totalProducts'))
		->where('category.is_active', 1)
		->get();

		$popularProds = ProductModel::where(['is_active' => 1, 'display_on_home' => 1])->get();

		$data = array(
			'title' => 'Home',
			'pageTitle' => 'Home',
			'menu' => 'home',
			'categoryList' => $categoryList,
			'popularProds' => $popularProds,
		);

		return view('frontend/home', $data);

	}

	public function about(Request $request) {

		$data = array(
			'title' => 'About Us',
			'pageTitle' => 'About Us',
			'menu' => 'about',
		);

		return view('frontend/about', $data);

	}

	public function privacyPolicy(Request $request) {

		$data = array(
			'title' => 'Privacy Policy',
			'pageTitle' => 'Privacy Policy',
			'menu' => 'privacy-policy',
		);

		return view('frontend/privacy-policy', $data);

	}

	public function returnPolicy(Request $request) {

		$data = array(
			'title' => 'Return Policy',
			'pageTitle' => 'Return Policy',
			'menu' => 'return-policy',
		);

		return view('frontend/return-policy', $data);

	}

	public function shippingPolicy(Request $request) {

		$data = array(
			'title' => 'Shipping Policy',
			'pageTitle' => 'Shipping Policy',
			'menu' => 'shipping-policy',
		);

		return view('frontend/shipping-policy', $data);

	}

	public function cancellationPolicy(Request $request) {

		$data = array(
			'title' => 'Cancellation Policy',
			'pageTitle' => 'Cancellation Policy',
			'menu' => 'cancellation-policy',
		);

		return view('frontend/cancellation-policy', $data);

	}

	public function termsAndCondition(Request $request) {

		$data = array(
			'title' => 'Terms and Conditions',
			'pageTitle' => 'Terms and Conditions',
			'menu' => 'terms-and-conditions',
		);

		return view('frontend/terms-conditions', $data);

	}

	public function contact(Request $request) {

		$data = array(
			'title' => 'Contact Us',
			'pageTitle' => 'Contact Us',
			'menu' => 'contact',
		);

		return view('frontend/contact', $data);

	}

	public function category(Request $request, $slug) {
		
		$isCategoryExist = CategoryModel::
		select('category.*', DB::raw('(SELECT COUNT(*) FROM product as b WHERE b.category_id = category.id) as totalProducts'))
		->where(['category.is_active' => 1, 'category.category_slug' => $slug])
		->first();

		if (!empty($isCategoryExist) && $isCategoryExist->count()) {

			$getProducts = ProductModel::where(['category_id' => $isCategoryExist->id, 'is_active' => 1])->get();
			$backgroundImage = getImg($isCategoryExist->banner_img);
			
			$data = array(
				'title' => $isCategoryExist->category_name,
				'pageTitle' => $isCategoryExist->category_name,
				'menu' => 'category',
				'category' => $isCategoryExist,
				'products' => $getProducts,
				'backgroundImage' => $backgroundImage,
			);

			return view('frontend/category', $data);

		} else {
			return redirect()->to(route('homePage'));
		}
		
	}

	public function product(Request $request, $slug) {
		
		$isProdExist = ProductModel::
		where(['is_active' => 1, 'slug' => $slug])
		->first();

		$landingPages = ['visiting-cards', 'business-stationary', 'business-material', 'posters', 'sticker-printing-services'];

		$customerId = customerId();

		if (!empty($isProdExist) && $isProdExist->count()) {

			$getRelProds = ProductModel::where(['is_active' => 1, 'category_id' => $isProdExist->category_id])->where('id', '!=', $isProdExist->id)->get();

			$productId = $isProdExist->id;

			//Pricing
			$paperSize = PricingModel::
			join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
			->where('pricing.product_id', $productId)
			->select('paper_size.*')
			->distinct('paper_size.id')
			->get();

			$defPaperSize = PricingModel::defPaperSize($productId);
			$defGsm = PricingModel::defPaperGsm($productId, $defPaperSize);
			$defPaperType = PricingModel::defPaperType($productId, $defPaperSize, $defGsm->gsmId);
			
			$defPaperSizeId = isset($defPaperSize->id)? $defPaperSize->id:null;
			$defPaperSides = PricingModel::defPaperSides($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId);

			$defPaperColor = PricingModel::defPaperColor($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId, $defPaperSides->paperSideId);

			$defGsmOpt = $defGsm->gsmOptions;			
			$defPaperTypeOpt = $defPaperType->paperOptions;
			$defPaperSidesOpt = $defPaperSides->paperSidesOptions;
			$defPaperColorOpt = $defPaperColor->paperColorOptions;
			$defBindingOpt = $defGsm->bindingOptions;
			$defLaminationOpt = $defGsm->laminationOptions;

			$covers = CoverModel::get();
			$buttonName = $isProdExist->name;

			if (!empty($isProdExist->button_name)) {
				$buttonName = $isProdExist->button_name;
			}

			$bannerImg = getImg($isProdExist->thumbnail_id);

			if (!empty($isProdExist->banner_image)) {
				$bannerImg = getImg($isProdExist->banner_image);
			}
			
			$data = array(
				'title' => $isProdExist->name,
				'pageTitle' => $isProdExist->name,
				'menu' => 'product',
				'product' => $isProdExist,
				'relProducts' => $getRelProds,
				'paperSize' => $paperSize,
				'covers' => $covers,

				'defPaperSize' => $defPaperSize,
				'defGsmOpt' => $defGsmOpt,
				'defPaperTypeOpt' => $defPaperTypeOpt,
				'defPaperSidesOpt' => $defPaperSidesOpt,
				'defPaperColorOpt' => $defPaperColorOpt,
				'defBindingOpt' => $defBindingOpt,
				'defLaminationOpt' => $defLaminationOpt,
				'customerId' => $customerId,
				'buttonName' => $buttonName,
				'bannerImg' => $bannerImg,
			);

			return view('frontend/product-detail', $data);

		} elseif (in_array($slug, $landingPages)) {

			$title = ucwords(str_replace('-', ' ', $slug));

			if ($slug == 'visiting-cards') {
				$renderFile = 'frontend/visiting-cards-lp';
			} elseif ($slug == 'business-stationary') {
				$renderFile = 'frontend/business-stationary-lp';
			} elseif ($slug == 'business-material') {
				$renderFile = 'frontend/business-material-lp';
			} elseif ($slug == 'posters') {
				$renderFile = 'frontend/posters-lp';
			} elseif ($slug == 'sticker-printing-services') {
				$renderFile = 'frontend/sticker-printing-services-lp';
			}
			
			$data = array(
				'menu' => 'product',
				'title' => $title,
				'pageTitle' => $title,
				'customerId' => $customerId,
			);

			return view($renderFile, $data);

		} else {
			return redirect()->to(route('homePage'));
		}
		
	}

	public function getContact(Request $request) {
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
			    'name' => 'required',
			    'email' => 'required|email',
			    'phone' => 'required|numeric|digits:10',
			    'subject' => 'required',
			    'message' => 'required',
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
	        		'admin_id' => adminId(),
	        		'name' => $request->post('name'),
	        		'email' => $request->post('email'),
	        		'phone' => $request->post('phone'),
	        		'subject' => $request->post('subject'),
	        		'message' => $request->post('message'),
	        	];

	        	$isAdded = ContactModel::create($obj);

	        	if ($isAdded) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Contact Query has been submitted successfully.'
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

	public function getPricing(Request $request) {
		if ($request->ajax()) {

			$productId = $request->post('productId');
			$paperSize = $request->post('paperSize');
			$action = $request->post('action');

			if (!empty($action) && !empty($paperSize)) {
				
				if ($action == 'gsm') {
					
					$getGsm = PricingModel::
					join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
					->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
					->where(['pricing.product_id' => $productId, 'pricing.paper_size_id' => $paperSize])
					->select('gsm.*', 'paper_type.paper_type as paper_type_name')
					->distinct('gsm.id')
					->orderBy('gsm.gsm', 'asc')
					->get();

					$getBinding = BindingModel::where('paper_size_id', $paperSize)->get();
					$getLamination = LaminationModel::where('paper_size_id', $paperSize)->get();

					$gsmOptions = '<option value="">Select Paper GSM</option>';

					if (!empty($getGsm) && $getGsm->count()) {
						foreach ($getGsm as $gsm) {
							$gsmOptions .= '<option data-weight="'.$gsm->per_sheet_weight.'" value="'.$gsm->id.'">'.$gsm->gsm.' GSM - '.$gsm->paper_type_name.'</option>';
						}
					}

					$bindingOptions = '<option value="">Select Binding</option>';

					if (!empty($getBinding) && $getBinding->count()) {
						foreach ($getBinding as $binding) {
							$bindingOptions .= '<option data-price="'.$binding->price.'" data-split="'.$binding->split.'" value="'.$binding->id.'">'.$binding->binding_name.'</option>';
						}
					}

					$laminationOptions = '<option value="">Select Lamination</option>';

					if (!empty($getLamination) && $getLamination->count()) {
						foreach ($getLamination as $lamination) {
							$laminationOptions .= '<option data-price="'.$lamination->price.'" value="'.$lamination->id.'">'.$lamination->lamination." - ".$lamination->lamination_type.'</option>';
						}
					}

					$this->status = array(
						'error' => false,
						'gsmOptions' => $gsmOptions,
						'bindingOptions' => $bindingOptions,
						'laminationOptions' => $laminationOptions,
					);

				} elseif ($action == 'paper_type') {
					
					$productId = $request->post('productId');
					$paperSize = $request->post('paperSize');
					$paperGsm = $request->post('paperGsm');

					$getPaperType = PricingModel::
					join('gsm', 'pricing.paper_type_id', '=', 'gsm.paper_type')
					->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
					->where(['pricing.product_id' => $productId, 'pricing.paper_size_id' => $paperSize, 'pricing.paper_gsm_id' => $paperGsm])
					->select('pricing.paper_type_id', 'paper_type.paper_type', 'gsm.paper_type_price')
					->distinct('gsm.id')
					->get();

					$paperTypeOptions = '<option value="">Select Paper Type</option>';

					if (!empty($getPaperType) && $getPaperType->count()) {
						foreach ($getPaperType as $paperType) {
							$paperTypeOptions .= '<option data-price="'.$paperType->paper_type_price.'" value="'.$paperType->paper_type_id.'">'.$paperType->paper_type.'</option>';
						}
					}

					$this->status = array(
						'error' => false,
						'paperOptions' => $paperTypeOptions,
					);

				} elseif ($action == 'paper_sides') {
					
					$productId = $request->post('productId');
					$paperSize = $request->post('paperSize');
					$paperGsm = $request->post('paperGsm');
					$paperType = $request->post('paperType');

					$getPaperSides = PricingModel::
					where(['product_id' => $productId, 'paper_size_id' => $paperSize, 'paper_gsm_id' => $paperGsm, 'paper_type_id' => $paperType])
					->select('side')
					->distinct('product_id')
					->get();

					$paperSideOptions = '<option value="">Select Print Sides</option>';

					if (!empty($getPaperSides) && $getPaperSides->count()) {
						foreach ($getPaperSides as $paperSide) {
							$paperSideOptions .= '<option value="'.$paperSide->side.'">'.$paperSide->side.'</option>';
						}
					}

					$this->status = array(
						'error' => false,
						'paperSides' => $paperSideOptions,
					);

				} elseif ($action == 'paper_color') {
					
					$productId = $request->post('productId');
					$paperSize = $request->post('paperSize');
					$paperGsm = $request->post('paperGsm');
					$paperType = $request->post('paperType');
					$paperSides = $request->post('paperSides');

					$getPaperColor = PricingModel::
					where(['product_id' => $productId, 'paper_size_id' => $paperSize, 'paper_gsm_id' => $paperGsm, 'paper_type_id' => $paperType, 'side' => $paperSides])
					->select('color', 'other_price')
					->distinct('color')
					->get();

					$paperColorOptions = '<option value="">Select Color</option>';

					if (!empty($getPaperColor) && $getPaperColor->count()) {
						foreach ($getPaperColor as $color) {
							$paperColorOptions .= '<option data-price="'.$color->other_price.'" value="'.$color->color.'">'.$color->color.'</option>';
						}
					}

					$this->status = array(
						'error' => false,
						'paperColor' => $paperColorOptions,
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

	public function checkPincode(Request $request) {
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
			    'pincode' => 'required|numeric|digits:6',
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

	        	$pincode = $request->post('pincode');

	        	//check if pincode exist for delivery
	        	$isExist = ShippingModel::where(['pincode' => $pincode, 'is_active' => 1])->first();

	        	if (!empty($isExist) && $isExist->count()) {
	        		
	        		$this->status = array(
						'error' => false,
						'msg' => 'Delivery is available.'
					);

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Delivery is not available.'
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

	public function checkDocumentLink(Request $request) {
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
			    'documentLink' => 'required|url:http,https',
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

	        	$this->status = array(
					'error' => false,
					'msg' => 'The document link has been updated.'
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

	public function payumoney(Request $request) {
		
		$paymentSess = Session::get('paymentSess');

		if (!empty($paymentSess)) {			

    		$action = $paymentSess['action'];
			$hash = $paymentSess['hash'];
			$MERCHANT_KEY = $paymentSess['MERCHANT_KEY'];
			$txnid = $paymentSess['txnid'];
			$successURL = $paymentSess['successURL'];
			$failURL = $paymentSess['failURL'];
			$name = $paymentSess['name'];
			$email = $paymentSess['email'];
			$paidAmount = $paymentSess['amount'];
			$productName = $paymentSess['productinfo'];

			// $posted = array(
	        //     'key' => $MERCHANT_KEY,
	        //     'txnid' => $txnid,
	        //     'amount' => $paidAmount,
	        //     'productinfo' => $productName,
	        //     'firstname' => $name,
	        //     'email' => $email,
	        //     'surl' => $successURL,
	        //     'furl' => $failURL,
	        //     'service_provider' => 'payu_paisa',
	        // );

    		// $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
	        // $hashString = '';
	        // foreach (explode('|', $hashSequence) as $key) {
	        //     $hashString .= isset($posted[$key]) ? $posted[$key] : '';
	        //     $hashString .= '|';
	        // }

	        // $hashString .= 'FwyslfXn3zDZtugwyHCiZu70zDmariAM';
	        // $calculatedHash = strtolower(hash('sha512', $hashString));

	        // if ($calculatedHash == $hash) {
	        // 	echo "matched";
	        // } else {
	        // 	echo "not matched";
	        // }
	        // die();
			
			return view('payu',compact('action','hash','MERCHANT_KEY','txnid','successURL','failURL','name','email','paidAmount', 'productName'));

		} else {
			return redirect(route('homePage'));
		}

	}

	public function paymentResponse(Request $request) {

		if ($request->post('status') == 'success') {
			
			$productPrice = productPriceMulti();
	    	$couponCode = null;
	    	$discount = 0;

	    	// echo "<pre>";
	    	// print_r($_POST);
			// print_r(productPrice());	    	
	    	// die();

	    	$couponData = Session::get('couponSess');

	    	if (!empty($couponData)) {
	    		$couponCode = $couponData['coupon_code'];
	    		$discount = $couponData['discount'];
	    	}	 

	    	$shipping = 0;

	    	$shippingData = Session::get('shippingSess');
	    	if (!empty($shippingData)) {
	    		$shipping = $shippingData['shipping'];
	    	}

	    	$remark = null;
	    	$remarkData = Session::get('remarkSess');
	    	if (!empty($remarkData)) {
	    		$remark = $remarkData['remark'];
	    	}

	    	$wetransferLink = null;
	    	$wetransferLinkData = Session::get('wetransferLinkSess');
	    	if (!empty($wetransferLinkData)) {
	    		$wetransferLink = $wetransferLinkData['wetransferLink'];
	    	}

	    	$courier = null;
	    	$courierData = Session::get('courierSess');
	    	if (!empty($courierData)) {
	    		$courier = $courierData['courier'];
	    	}

	    	$customerAdd = CustomerAddressModel::where('user_id', customerId())->first();

	    	// $getCartData = CartModel::where('user_id', customerId())
	    	// ->orderBy('cart.id', 'desc')
	    	// ->first();
	    	// $productName = ProductModel::where('id', getCartProductId())->value('name');

	    	$getCartData = CartModel::where('user_id', customerId())
	    	->orderBy('cart.id', 'desc')
	    	->get();

	    	$getDocumentLink = CartModel::where('user_id', customerId())->take(1)->value('document_link');

	    	//$barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();

	    	$orderObj = array(
	    		'order_id' => $request->post('txnid'),
	    		'user_id' => customerId(),
	    		// 'product_id' => getCartProductId(),
	    		// 'product_name' => $productName,
	    		// 'product_details' => json_encode(productSpec(getCartId())),
	    		// 'weight_details' => json_encode(cartWeight()),
	    		'coupon_code' => $couponCode,
	    		'discount' => $discount,
	    		'shipping' => $shipping,
	    		// 'paid_amount' => ceil($productPrice->total),
	    		'paid_amount' => $productPrice->total,
	    		// 'price_details' => json_encode($productPrice),
	    		'transaction_details' => json_encode($_POST),
	    		'customer_address' => json_encode($customerAdd->toArray()),
	    		'document_link' => $getDocumentLink,
	    		'remark' => $remark,
	    		'wetransfer_link' => $wetransferLink,
	    		'courier' => $courier,
	    		// 'qty' => $getCartData->qty,
	    		// 'no_of_copies' => $getCartData->no_of_copies,
	    	);

	    	// if (!empty($barcode)) {
	    	// 	$orderObj['shipping_label_number'] = $barcode->barcode;
	    	// }

	    	$isOrderCreated = OrderModel::create($orderObj);
	    	
	    	if ($isOrderCreated) {

	    		$orderId = $isOrderCreated->id;

	    		foreach ($getCartData as $cartData) {

	    			$productName = ProductModel::where('id', $cartData->product_id)->value('name');
	    			
	    			$orderItemObj = array(
	    				'order_id' => $orderId,
	    				'product_id' => $cartData->product_id,
	    				'product_name' => $productName,
	    				'qty' => $cartData->qty,
	    				'no_of_copies' => $cartData->no_of_copies,
	    				'product_details' => json_encode(productSpec($cartData->id)),
	    				'weight_details' => json_encode(cartWeightSingle($cartData->id)),
	    				'price_details' => json_encode(productSinglePrice($cartData->product_id)),
	    				'qty' => $cartData->qty,
	    				'no_of_copies' => $cartData->no_of_copies,
	    			);

	    			OrderItemModel::create($orderItemObj);

	    		}


	    		//update barcode once used
	    		// if (!empty($barcode)) {
	    		// 	BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);
	    		// }

	    		$getCustomer = CustomerModel::where('id', customerId())->first();

	    		//Remove Cart Data
	    		CartModel::where('user_id', customerId())->delete();

	    		//send SMS
	    		SmsSending::orderPlaced($getCustomer->phone, $getCustomer->name);

	    		//send email
	    		//$orderId = 8;
				// $getOrder = OrderModel::
				// join('product', 'orders.product_id', '=', 'product.id')
				// ->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
				// ->where('orders.id', $orderId)->first();

				EmailSending::orderEmailNew($orderId);
	    		
	    		Session::forget('shippingSess');
	    		Session::forget('couponSess');
	    		Session::forget('paymentSess');
	    		Session::forget('documents');
	    		Session::forget('remarkSess');
	    		Session::forget('wetransferLinkSess');
	    		Session::forget('courierSess');

	    		$amount = $request->post('amount');
				$transactionId = $request->post('txnid');

				return redirect()->route('thankyouPage', ['amount' => $amount, 'transactionId' => $transactionId]);

	    	} else {

	    		Session::forget('shippingSess');
	    		Session::forget('couponSess');
	    		Session::forget('paymentSess');
	    		Session::forget('documents');
	    		Session::forget('remarkSess');
	    		Session::forget('wetransferLinkSess');
	    		Session::forget('courierSess');

	    		return redirect()->route('paymentFailPage');

	    	}

		} else {
			
			Session::forget('shippingSess');
    		Session::forget('couponSess');
    		Session::forget('paymentSess');
    		Session::forget('documents');
    		Session::forget('remarkSess');
    		Session::forget('wetransferLinkSess');
    		Session::forget('courierSess');

    		return redirect()->route('paymentFailPage');

		}
	}

	public function paynowResponse(Request $request) {

		Log::channel('payment-log')->info('payment-success-'.date('Y-m-d h:i'), ['data' => json_encode($_REQUEST)]);

		if ($request->post('status') == 'success') {

			$orderId = $request->post('txnid');

			$getSession = Session::get('paymentPayNowSess');
			$getOrder = OrderModel::where('order_id', $orderId)->first();

			if (!empty($getSession) && !empty($getOrder)) {

				$updateObj = [
					'transaction_details' => json_encode($_POST),
					'status' => 'paid',
				];

				//$barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();

				// if (!empty($barcode)) {
		    	// 	$updateObj['shipping_label_number'] = $barcode->barcode;
		    	// }

				$isUpdated = OrderModel::where('order_id', $orderId)->update($updateObj);

				if ($isUpdated) {
					
					//update barcode once used
		    		// if (!empty($barcode)) {
		    		// 	BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);
		    		// }

		    		$customerId = $getOrder->user_id;

		    		$getCustomer = CustomerModel::where('id', $customerId)->first();

		    		//send SMS
		    		SmsSending::orderPlaced($getCustomer->phone, $getCustomer->name);

		    		//send email
		    		//$orderId = 8;
					// $getOrder = OrderModel::
					// join('product', 'orders.product_id', '=', 'product.id')
					// ->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
					// ->where('orders.id', $getOrder->id)->first();

					//EmailSending::orderEmail($getOrder);
					EmailSending::orderEmailNew($getOrder->id);

		    		$amount = $request->post('amount');
					$transactionId = $request->post('txnid');
					Session::forget('paymentPayNowSess');

					return redirect()->route('thankyouPage', ['amount' => $amount, 'transactionId' => $transactionId]);

				}	

			} else {

				Session::forget('paymentPayNowSess');
				return redirect()->route('paymentFailPage');

			}

			

			
			$productPrice = productPrice();
	    	$couponCode = null;
	    	$discount = 0;

	    	// echo "<pre>";
	    	// print_r($_POST);
			// print_r(productPrice());	    	
	    	// die();

	    	$couponData = Session::get('couponSess');

	    	if (!empty($couponData)) {
	    		$couponCode = $couponData['coupon_code'];
	    		$discount = $couponData['discount'];
	    	}	 

	    	$shipping = 0;

	    	$shippingData = Session::get('shippingSess');
	    	if (!empty($shippingData)) {
	    		$shipping = $shippingData['shipping'];
	    	}

	    	$customerAdd = CustomerAddressModel::where('user_id', customerId())->first();

	    	$getCartData = CartModel::where('user_id', customerId())
	    	->orderBy('cart.id', 'desc')
	    	->first();
	    	$productName = ProductModel::where('id', getCartProductId())->value('name');

	    	//$barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();

	    	$orderObj = array(
	    		'order_id' => $request->post('txnid'),
	    		'user_id' => customerId(),
	    		'product_id' => getCartProductId(),
	    		'product_name' => $productName,
	    		'product_details' => json_encode(productSpec(getCartId())),
	    		'weight_details' => json_encode(cartWeight()),
	    		'coupon_code' => $couponCode,
	    		'discount' => $discount,
	    		'shipping' => $shipping,
	    		// 'paid_amount' => ceil($productPrice->total),
	    		'paid_amount' => $productPrice->total,
	    		'price_details' => json_encode($productPrice),
	    		'transaction_details' => json_encode($_POST),
	    		'customer_address' => json_encode($customerAdd->toArray()),
	    		'document_link' => $getCartData->document_link,
	    		'qty' => $getCartData->qty,
	    		'no_of_copies' => $getCartData->no_of_copies,
	    	);

	    	// if (!empty($barcode)) {
	    	// 	$orderObj['shipping_label_number'] = $barcode->barcode;
	    	// }

	    	$isOrderCreated = OrderModel::create($orderObj);
	    	
	    	if ($isOrderCreated) {

	    		//update barcode once used
	    		// if (!empty($barcode)) {
	    		// 	BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);
	    		// }

	    		$getCustomer = CustomerModel::where('id', customerId())->first();

	    		//Remove Cart Data
	    		CartModel::where('user_id', customerId())->delete();

	    		//send SMS
	    		SmsSending::orderPlaced($getCustomer->phone, $getCustomer->name);

	    		//send email
	    		//$orderId = 8;
				$getOrder = OrderModel::
				join('product', 'orders.product_id', '=', 'product.id')
				->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
				->where('orders.id', $isOrderCreated->id)->first();

				//EmailSending::orderEmail($getOrder);
				EmailSending::orderEmailNew($isOrderCreated->id);
	    		
	    		Session::forget('shippingSess');
	    		Session::forget('couponSess');
	    		Session::forget('paymentSess');
	    		Session::forget('documents');

	    		$amount = $request->post('amount');
				$transactionId = $request->post('txnid');

				return redirect()->route('thankyouPage', ['amount' => $amount, 'transactionId' => $transactionId]);

	    	} else {

	    		Session::forget('shippingSess');
	    		Session::forget('couponSess');
	    		Session::forget('paymentSess');
	    		Session::forget('documents');

	    		return redirect()->route('paymentFailPage');

	    	}

		} else {
			
			Session::forget('shippingSess');
    		Session::forget('couponSess');
    		Session::forget('paymentSess');
    		Session::forget('documents');

    		return redirect()->route('paymentFailPage');

		}
	}

	public function thankyou(Request $request) {

		$amount = $request->get('amount');
		$transactionId = $request->get('txnid');

		$data = array(
			'title' => 'Thank You',
			'pageTitle' => 'Thank You',
			'menu' => 'thank-you',
			'amount' => $amount,
			'transactionId' => $transactionId,
		);

		return view('frontend/thankYou', $data);
		
	}

	// public function paymentFail(Request $request) {

	// 	if ($request->post('status') == 'failure') {

	// 		$customerId = 1;
	// 		$request->session()->put('customerSess', array(
    // 			'customerId' => $customerId
    // 		));
			
	// 		$productPrice = productPriceMulti();
	//     	$couponCode = null;
	//     	$discount = 0;

	//     	// echo "<pre>";
	//     	// print_r($_POST);
	// 		// //print_r(productPrice());	    	
	//     	// die();

	//     	$couponData = Session::get('couponSess');

	//     	if (!empty($couponData)) {
	//     		$couponCode = $couponData['coupon_code'];
	//     		$discount = $couponData['discount'];
	//     	}	 

	//     	$shipping = 0;

	//     	$shippingData = Session::get('shippingSess');
	//     	if (!empty($shippingData)) {
	//     		$shipping = $shippingData['shipping'];
	//     	}

	//     	$remark = null;
	//     	$remarkData = Session::get('remarkSess');
	//     	if (!empty($remarkData)) {
	//     		$remark = $remarkData['remark'];
	//     	}

	//     	$customerAdd = CustomerAddressModel::where('user_id', customerId())->first();

	//     	// $getCartData = CartModel::where('user_id', customerId())
	//     	// ->orderBy('cart.id', 'desc')
	//     	// ->first();
	//     	// $productName = ProductModel::where('id', getCartProductId())->value('name');

	//     	$getCartData = CartModel::where('user_id', customerId())
	//     	->orderBy('cart.id', 'desc')
	//     	->get();

	//     	$getDocumentLink = CartModel::where('user_id', customerId())->take(1)->value('document_link');

	//     	//$barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();

	//     	$orderObj = array(
	//     		'order_id' => $request->post('txnid'),
	//     		'user_id' => customerId(),
	//     		// 'product_id' => getCartProductId(),
	//     		// 'product_name' => $productName,
	//     		// 'product_details' => json_encode(productSpec(getCartId())),
	//     		// 'weight_details' => json_encode(cartWeight()),
	//     		'coupon_code' => $couponCode,
	//     		'discount' => $discount,
	//     		'shipping' => $shipping,
	//     		// 'paid_amount' => ceil($productPrice->total),
	//     		'paid_amount' => $productPrice->total,
	//     		// 'price_details' => json_encode($productPrice),
	//     		'transaction_details' => json_encode($_POST),
	//     		'customer_address' => json_encode($customerAdd->toArray()),
	//     		'document_link' => $getDocumentLink,
	//     		'remark' => $remark,
	//     		// 'qty' => $getCartData->qty,
	//     		// 'no_of_copies' => $getCartData->no_of_copies,
	//     	);

	//     	// if (!empty($barcode)) {
	//     	// 	$orderObj['shipping_label_number'] = $barcode->barcode;
	//     	// }

	//     	$isOrderCreated = OrderModel::create($orderObj);
	    	
	//     	if ($isOrderCreated) {

	//     		$orderId = $isOrderCreated->id;

	//     		foreach ($getCartData as $cartData) {

	//     			$productName = ProductModel::where('id', $cartData->product_id)->value('name');
	    			
	//     			$orderItemObj = array(
	//     				'order_id' => $orderId,
	//     				'product_id' => $cartData->product_id,
	//     				'product_name' => $productName,
	//     				'qty' => $cartData->qty,
	//     				'no_of_copies' => $cartData->no_of_copies,
	//     				'product_details' => json_encode(productSpec($cartData->id)),
	//     				'weight_details' => json_encode(cartWeightSingle($cartData->id)),
	//     				'price_details' => json_encode(productSinglePrice($cartData->product_id)),
	//     				'qty' => $cartData->qty,
	//     				'no_of_copies' => $cartData->no_of_copies,
	//     			);

	//     			OrderItemModel::create($orderItemObj);

	//     		}


	//     		//update barcode once used
	//     		// if (!empty($barcode)) {
	//     		// 	BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);
	//     		// }

	//     		$getCustomer = CustomerModel::where('id', customerId())->first();

	//     		//Remove Cart Data
	//     		CartModel::where('user_id', customerId())->delete();

	//     		//send SMS
	//     		//SmsSending::orderPlaced($getCustomer->phone, $getCustomer->name);

	//     		//send email
	//     		//$orderId = 8;
	// 			// $getOrder = OrderModel::
	// 			// join('product', 'orders.product_id', '=', 'product.id')
	// 			// ->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
	// 			// ->where('orders.id', $orderId)->first();

	// 			$getOrder = OrderModel::
	// 			join('product', 'orders.product_id', '=', 'product.id')
	// 			->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
	// 			->where('orders.id', $orderId)->get();

	// 			//EmailSending::orderEmail($getOrder);
	    		
	//     		Session::forget('shippingSess');
	//     		Session::forget('couponSess');
	//     		Session::forget('paymentSess');
	//     		Session::forget('documents');
	//     		Session::forget('remarkSess');
	//     		Session::forget('wetransferLinkSess');
	//     		Session::forget('courierSess');

	//     		$amount = $request->post('amount');
	// 			$transactionId = $request->post('txnid');

	// 			return redirect()->route('thankyouPage', ['amount' => $amount, 'transactionId' => $transactionId]);

	//     	} else {

	//     		Session::forget('shippingSess');
	//     		Session::forget('couponSess');
	//     		Session::forget('paymentSess');
	//     		Session::forget('documents');
	//     		Session::forget('remarkSess');
	//     		Session::forget('wetransferLinkSess');
	//     		Session::forget('courierSess');

	//     		return redirect()->route('paymentFailPage');

	//     	}

	// 	} else {
			
	// 		Session::forget('shippingSess');
    // 		Session::forget('couponSess');
    // 		Session::forget('paymentSess');
    // 		Session::forget('documents');
    // 		Session::forget('remarkSess');
    // 		Session::forget('wetransferLinkSess');
    // 		Session::forget('courierSess');

    // 		return redirect()->route('paymentFailPage');

	// 	}
	// }

	public function paymentFail(Request $request) {

		Log::channel('payment-log')->info('payment-failed-'.date('Y-m-d h:i'), ['data' => json_encode($_REQUEST)]);

		Session::forget('paymentSess');
		Session::forget('paymentPayNowSess');

		$data = array(
			'title' => 'Payment Failed',
			'pageTitle' => 'Payment Failed',
			'menu' => 'payment-failed',
		);

		return view('frontend/paymentFailure', $data);
	}

	public function upload() {
		$data = array(
			'title' => 'Upload',
			'pageTitle' => 'Upload',
			'menu' => 'upload',
		);

		return view('frontend/upload', $data);
	}

	public function doUploadDropbox(Request $request) {

		echo "<pre>";
		print_r($_FILES);

		$file = $request->file('documents');
		$fileName = $file->getClientOriginalName();
		$ext = $file->extension();
		
		//$path = $file->store('documents', 'dropbox');
		//print_r($path);

		$token = config('filesystems.disks.dropbox.token');
		$dropboxClient = new DropboxClient($token);
		$adapter = new DropboxAdapter($dropboxClient);
		$filesystem = new Filesystem($adapter);

        $path = 'documents/' . $file->getClientOriginalName();
        $filesystem->write($path, file_get_contents($file->getRealPath()));

        echo $path;
		die();

	}

	public function payNow(Request $request, $orderId) {
		//check the order id
		$getOrder = OrderModel::where(['order_id' => $orderId, 'status' => 'unpaid'])->first();
		if (!empty($getOrder) && $getOrder->count()) {
			
			$MERCHANT_KEY = "q8S6BB"; // TEST MERCHANT KEY
			$SALT = "FwyslfXn3zDZtugwyHCiZu70zDmariAM"; // TEST SALT

			//$PAYU_BASE_URL = "https://test.payu.in";
			$PAYU_BASE_URL = "https://secure.payu.in"; // PRODUCATION

			$customerAddress = json_decode($getOrder->customer_address);

			$name = $customerAddress->shipping_name;
	        $successURL = route('paynowResponse');
	        $failURL = route('paymentFailPage');
	        $email = $customerAddress->shipping_email;

	        //$productName = $getOrder->product_name;
	        $productName = 'eprintcafe';
	        $paidAmount = $getOrder->paid_amount;

	        $action = '';
	        //$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
	        $txnid = $getOrder->order_id;
	        $posted = array();
	        $posted = array(
	            'key' => $MERCHANT_KEY,
	            'txnid' => $txnid,
	            'amount' => $paidAmount,
	            'productinfo' => $productName,
	            'firstname' => $name,
	            'email' => $email,
	            'surl' => $successURL,
	            'furl' => $failURL,
	            'service_provider' => 'payu_paisa',
	        );

	        $hash = '';
	        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

	        if(empty($posted['hash']) && sizeof($posted) > 0) {
	            $hashVarsSeq = explode('|', $hashSequence);
	            $hash_string = '';  
	            foreach($hashVarsSeq as $hash_var) {
	                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
	                $hash_string .= '|';
	            }
	            $hash_string .= $SALT;

	            $hash = strtolower(hash('sha512', $hash_string));
	            $action = $PAYU_BASE_URL . '/_payment';
	        } elseif(!empty($posted['hash'])) {
	            $hash = $posted['hash'];
	            $action = $PAYU_BASE_URL . '/_payment';
	        }

	        Session::forget('paymentPayNowSess');

			//create session for payment initiated
			$paymentInitObj = [
				'action' => $action,
				'hash' => $hash,
				'MERCHANT_KEY' => $MERCHANT_KEY,
				'txnid' => $txnid,
				'successURL' => $successURL,
				'failURL' => $failURL,
				'name' => $name,
				'email' => $email,
				'amount' => $paidAmount,
				'productinfo' => $productName,
    		];

    		$request->session()->put('paymentPayNowSess', $paymentInitObj);

    		return view('payu',compact('action','hash','MERCHANT_KEY','txnid','successURL','failURL','name','email','paidAmount', 'productName'));

		} else {
			return redirect(route('homePage'));
		}
	}

	public function doSaveLandingPageLead(Request $request) {
		if ($request->ajax()) {

			$validator = Validator::make($request->post(), [
	            'product' => 'required',
	            'options' => 'required',
	            'name' => 'required|regex:/^[a-zA-Z]+$/',
	            'phoneNumber' => 'required|numeric',
	            'email' => 'required|email',
	            'location' => 'required|string',
	            'requirementSpecification' => 'sometimes|nullable',
	            'noOfPages' => 'required|numeric|min:1',
	            'noOfCopies' => 'required|numeric|min:1',
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
	        	$data = $request->post();

	        	LandingPageEnquiryModel::create([
	        		'product' => $request->post('product'),
	        		'options' => $request->post('options'),
	        		'name' => $request->post('name'),
	        		'phone_number' => $request->post('phoneNumber'),
	        		'email' => $request->post('email'),
	        		'location' => $request->post('location'),
	        		'no_of_pages' => $request->post('noOfPages'),
	        		'no_of_copies' => $request->post('noOfCopies'),
	        		'requirement_specification' => $request->post('requirementSpecification'),
	        	]);

	        	$isSent = EmailSending::landingPageLead($data);
	        	//$isSent = true;

	        	if ($isSent) {
	        		
	        		$this->status = array(
						'error' => false,
						'redirect' => route('submitLeadFormPage')
					);

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

		echo json_encode($this->status);
	}

	public function submitLeadFormPage() {
		$data = array(
			'title' => 'Thank you',
			'pageTitle' => 'Thank you',
			'menu' => 'thank-you',
		);

		return view('frontend/leadThankYouPage', $data);
	}

	public function testEmail() {

		// $orderId = 10;
		// $getOrder = OrderModel::
		// join('product', 'orders.product_id', '=', 'product.id')
		// ->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
		// ->where('orders.id', $orderId)->first();

		// echo EmailSending::orderEmail($getOrder);

		// $orderId = 19;
		// EmailSending::orderEmailNew($orderId);
		
		// if (!empty($getOrder)) {

		// 	$priceData = json_decode($getOrder->price_details);
		// 	$productDetails = json_decode($getOrder->product_details);
		// 	$customerAddress = json_decode($getOrder->customer_address);
		// 	$gstRate = 5;
		// 	$hsnCode = $getOrder->unregistered_hsn_code;

		// 	$customerName = $customerAddress->shipping_name;
		// 	$shippingState = strtolower($customerAddress->shipping_state);

		// 	$isIntrastate = false;

		// 	if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
		// 		$isIntrastate = true;
		// 	}

		// 	if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
		// 		$gstRate = 18;
		// 		$hsnCode = $getOrder->registered_hsn_code;

		// 		$customerName = $customerAddress->shipping_company_name;
		// 	}

		// 	$gstCalc = calculateGSTComponents($getOrder->paid_amount, $gstRate);

		// 	$data = array('order' => $getOrder, 'priceData' => $priceData, 'productDetails' => $productDetails, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate, 'hsnCode' => $hsnCode);
		// 	return view('email_templates/admin/vwTestTemplate', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

		// }

		// $obj = [
		// 	'name' => 'Alfaiz',
		// 	'otp' => 123456,
		// 	'email' => 'alfaizm19@gmail.com',
		// 	'debug' => true,
		// 	'level' => 0,
		// 	'token' => md5(time())		
		// ];
		// EmailSending::test($obj);
	}

	public function testSMS() {
		//$data = SmsSending::sendOTP('6395028377', '123456');
		//print_r($data);
		// if ($data->type == 'success') {
		// 	echo "Msg Sent";
		// } else {
		// 	echo "Something went wrong";
		// }
	}

	public function testPayu() {

		$MERCHANT_KEY = "q8S6BB"; // TEST MERCHANT KEY
        $SALT = "FwyslfXn3zDZtugwyHCiZu70zDmariAM"; // TEST SALT

        //$PAYU_BASE_URL = "https://test.payu.in";
        $PAYU_BASE_URL = "https://secure.payu.in"; // PRODUCATION

        $name = 'Alfaiz';
        $successURL = route('thankyouPage');
        $failURL = route('paymentFailPage');
        $email = 'alfaiz@codzio.com';
        $amount = 1;

        $action = '';
        //$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $txnid = uniqid();
        $posted = array();
        $posted = array(
            'key' => $MERCHANT_KEY,
            'txnid' => $txnid,
            'amount' => $amount,
            'productinfo' => 'Webappfix',
            'firstname' => $name,
            'email' => $email,
            'surl' => $successURL,
            'furl' => $failURL,
            'service_provider' => 'payu_paisa',
        );

        if(empty($posted['txnid'])) {
            // $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            $txnid = uniqid();
        }  else{
            $txnid = $posted['txnid'];
        }

        $hash = '';
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

        if(empty($posted['hash']) && sizeof($posted) > 0) {
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';  
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $SALT;

            $hash = strtolower(hash('sha512', $hash_string));
            $action = $PAYU_BASE_URL . '/_payment';
        } elseif(!empty($posted['hash'])) {
            $hash = $posted['hash'];
            $action = $PAYU_BASE_URL . '/_payment';
        }

        return view('payu',compact('action','hash','MERCHANT_KEY','txnid','successURL','failURL','name','email','amount'));

	}
}