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
use App\Models\GsmModel;
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

use Ixudra\Curl\Facades\Curl;

use Storage;
use League\Flysystem\Filesystem;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Spatie\Dropbox\Client as DropboxClient; // Import the DropboxClient

use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordParser;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetParser;

class Upload extends Controller {

	private $status = array();

	public function index(Request $request) {

		Session::forget('shippingSess');
		Session::forget('couponSess');
		Session::forget('paymentSess');
		Session::forget('documents');

		$customerData = customerData();

		if (!$customerData) {
			return redirect(route('loginPage', ['action' => 'upload']));
		}

		$customerId = $customerData->id;

		$cond = [
			'product.is_active' => 1,
			'cart.user_id' => $customerId
		];

		$getCartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)->select('cart.*', 'product.name', 'product.thumbnail_id')->orderBy('cart.id', 'desc')->get();

		$productList = ProductModel::where(['is_active' => 1])->get();

		//Get Customer Address
		$customerAddress = CustomerAddressModel::where('user_id', $customerId)->first();

		$action = $request->get('product');

		$data = array(
			'title' => 'Upload',
			'pageTitle' => 'Upload',
			'menu' => 'upload',
			'slug' => $request->get('product'),
			'customerData' => customerData(),
			'cartData' => $getCartData,
			'productList' => $productList,
			'customerAddress' => $customerAddress,
			'action' => $action
		);

		return view('frontend/fileUpload', $data);

	}

	public function doUploadFiles(Request $request) {

		if ($request->ajax()) {

			$validator = Validator::make($request->all(), [
	            'file.*' => 'required|mimes:png,jpg,jpeg,pdf,zip,docx|max:110000',
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

	        	$slug = $request->post('slug');
	        	$files = $request->file('file');
	        	$customerId = customerId();

	        	//get default product (PDF Printing)
	        	$productData = ProductModel::where(['is_active' => 1, 'slug' => 'pdf-printing'])->first();

	        	if (!empty($slug)) {
	        		$productData = ProductModel::where(['is_active' => 1, 'slug' => $slug])->first();
	        	}

	        	if (!empty($productData)) {

	        		$year = now()->year;
				    $month = now()->month;
				    $date = now()->day;

	        		$tempId = $request->cookie('tempUserId');
	        		$productId = $productData->id;

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

					foreach ($files as $file) {

		        		//$uniqueId = md5(microtime());
		        		$uniqueId = Str::random(8) . time();
		        		
		        		$originalName = $file->getClientOriginalName();
		        		$originalNameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

		        		$ext = $file->extension();
		        		$size = $file->getSize();

		        		$nameWithoutExtSlugify = Str::slug($originalNameWithoutExt.'-'.$uniqueId);
			        	$finalName = $nameWithoutExtSlugify.'.'.$ext;

			        	$craftPath = $year.'/'.$month.'/'.$date;
						$path = $year.'/'.$month.'/'.$date. '/' . $finalName;

			        	$destinationPath = 'documents/'.$year.'/'.$month.'/'.$date;
			        	File::makeDirectory(public_path($destinationPath), $mode = 0777, true, true);
			   			
			   			$filePath = public_path($destinationPath . '/' . $finalName);
			   			$isUploaded = $file->move(public_path($destinationPath), $finalName);

			   			$pageCount = 1;

			   			if ($ext === 'pdf') {
					        // For PDF files
					        $parser = new PdfParser();
					        $pdf = $parser->parseFile($filePath);
					        $pageCount = count($pdf->getPages());
					    } elseif ($ext === 'docx') {
					        // For Word documents
					        $word = WordParser::load($filePath);
					        $sections = $word->getSections();
					        $pageCount = count($sections); // Approximated
					    } elseif (in_array($ext, ['xlsx', 'xls'])) {
					        // For Excel files
					        $spreadsheet = SpreadsheetParser::load($filePath);
					        $pageCount = $spreadsheet->getSheetCount(); // Number of sheets as a proxy for pages
					    }

			        	$printSide = $defPaperSides->paperSideId;
			        	$color = $defPaperColor->paperColorId;
			   			$qty = $pageCount;
			   			$noOfCopies = 1;
			   			$binding = '';
			   			$lamination = '';
			   			$cover = '';

			   			$amount = productSinglePriceForAmount($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId, $printSide, $color, $binding, $lamination, $cover, $qty, $noOfCopies)->total;

			   			$obj = [
			   				'temp_id' => $tempId,
			   				'user_id' => $customerId,
			   				'product_id' => $productId,
			   				'paper_size_id' => $defPaperSizeId,
			   				'paper_gsm_id' => $defGsm->gsmId,
			   				'paper_type_id' => $defPaperType->paperTypeId,
			   				'print_side' => $printSide,
			   				'color' => $color,
			   				'qty' => $qty,
			   				'no_of_copies' => $noOfCopies,
			   				'amount' => $amount,
			   				'file_path' => $destinationPath,
			   				'file_name' => $finalName,
			   			];

			   			CartModel::create($obj);

		        	}

		        	Session::forget('shippingSess');
	        		Session::forget('couponSess');
	        		Session::forget('paymentSess');
	        		Session::forget('documents');

	        		$this->status = array(
						'error' => false,
						'msg' => 'The product has been added into the cart.',
						'redirect' => route('uploadPage'),
					);

	        		
	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The product data is not found.'
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

	public function getProductPricing(Request $request) {
		if ($request->ajax()) {

			$productId = $request->post('productId');
			$cartId = $request->post('cartId');
			$action = $request->post('action');
			$customerData = customerData();

			$cond = [
				'id' => $cartId,
				'user_id' => $customerData->id
			];

			//check if cart item exist
			$getCartData = CartModel::where($cond)->first();

			//check product exist
			$getProductData = ProductModel::where(['id' => $productId, 'is_active' => 1])->first();

			if (!empty($getCartData) && !empty($getProductData)) {
				
				if (!empty($action)) {		
					
					if ($action == 'size') {
						
						$paperSizeList = PricingModel::
						join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
						->where('pricing.product_id', $productId)
						->select('paper_size.*')
						->distinct('paper_size.id')
						->get();

						$defPaperSize = PricingModel::defPaperSize($productId);

						$paperSizeOptions = '<option value="">Select Paper Size</option>';

						if (!empty($paperSizeList)) {
							foreach ($paperSizeList as $paperSize) {
								$paperSizeOptions .= '<option value="'.$paperSize->id.'">'.$paperSize->size.'</option>';
							}
						}

						$this->status = array(
							'error' => false,
							'paperSizeOptions' => $paperSizeOptions,
						);

					} elseif ($action == 'gsm') {

						$paperSize = $request->post('paperSize');
						
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

						// $getPaperType = PricingModel::
						// join('gsm', 'pricing.paper_type_id', '=', 'gsm.paper_type')
						// ->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
						// ->where(['pricing.product_id' => $productId, 'pricing.paper_size_id' => $paperSize, 'pricing.paper_gsm_id' => $paperGsm])
						// ->select('pricing.paper_type_id', 'paper_type.paper_type', 'gsm.paper_type_price')
						// ->distinct('gsm.id')
						// ->get();

						$getPaperType = GsmModel::
						join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
						->select('paper_type.paper_type', 'paper_type.id as paper_type_id', 'gsm.paper_type_price')
						->where(['gsm.paper_size' => $paperSize, 'gsm.id' => $paperGsm])->get();

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

					} else {
						$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'The action is invalid.'
						);
					}

				} else {

					$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The action is requried.'
					);

				}

			} else {
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'The cart/product item seems not to be found.'
				);
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

	public function doUpdateCart(Request $request) {
		if ($request->ajax()) {

			$obj = [
				'product' => 'required|exists:product,id',
			    'paperSize' => 'required|numeric',
			    'paperGsm' => 'required|numeric',
			    'paperType' => 'required|numeric',
			    'paperSides' => 'required',
			    'color' => 'required',
			    'binding' => 'sometimes|nullable|numeric',
			    'lamination' => 'sometimes|nullable|numeric',
			    'cover' => 'sometimes|nullable|numeric',
			    'noOfPages' => 'required|numeric|min:1',
			    'noOfCopies' => 'required|numeric|min:1',
			    'remark' => 'sometimes|nullable',
			    'cartId' => 'required|exists:cart,id',
			];

			$validator = Validator::make($request->all(), $obj);

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

	        	$product = $request->post('product');
	        	$paperSize = $request->post('paperSize');
	        	$paperGsm = $request->post('paperGsm');
	        	$paperType = $request->post('paperType');
	        	$paperSides = $request->post('paperSides');
	        	$color = $request->post('color');
	        	$binding = $request->post('binding');
	        	$lamination = $request->post('lamination');
	        	$cover = $request->post('cover');
	        	$noOfPages = $request->post('noOfPages');
	        	$noOfCopies = $request->post('noOfCopies');
	        	$remark = $request->post('remark');
	        	$cartId = $request->post('cartId');

	        	//get default product (PDF Printing)
	        	$productData = ProductModel::where(['is_active' => 1, 'id' => $product])->first();

	        	if (!empty($productData)) {

	        		//check cart id
	        		$isCartDataExist = CartModel::where(['id' => $cartId, 'user_id' => $customerId])->first();

	        		if (!empty($isCartDataExist)) {
	        			
	        			$amount = productSinglePriceForAmount($product, $paperSize, $paperGsm, $paperType, $paperSides, $color, $binding, $lamination, $cover, $noOfPages, $noOfCopies)->total;

	        			//$productSinglePrice = productSinglePriceForAmount($product, $paperSize, $paperGsm, $paperType, $paperSides, $color, $binding, $lamination, $cover, $noOfPages, $noOfCopies);

	        			// print_r($productSinglePrice);
	        			// die();

			   			$obj = [
			   				'user_id' => $customerId,
			   				'product_id' => $product,
			   				'paper_size_id' => $paperSize,
			   				'paper_gsm_id' => $paperGsm,
			   				'paper_type_id' => $paperType,
			   				'print_side' => $paperSides,
			   				'color' => $color,
			   				'binding_id' => $binding,
			   				'lamination_id' => $lamination,
			   				'cover_id' => $cover,
			   				'qty' => $noOfPages,
			   				'no_of_copies' => $noOfCopies,
			   				'amount' => $amount,
			   				'remark' => $remark
			   			];

			   			$isUpdated = CartModel::where('id', $cartId)->update($obj);

			   			if ($isUpdated) {

			   				Session::forget('shippingSess');
			        		Session::forget('couponSess');
			        		Session::forget('paymentSess');
			        		Session::forget('documents');

			        		$this->status = array(
								'error' => false,
								'msg' => 'You cart has been updated successfully.',
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
							'msg' => 'The cart data is not found.'
						);
	        		}
	        		
	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The product data is not found.'
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

	public function getTab4Data(Request $request) {
		if ($request->ajax()) {

			//Remove session
			Session::forget('shippingSess');
    		Session::forget('couponSess');
    		Session::forget('paymentSess');
    		Session::forget('documents');

			$customerId = customerId();

			$cond = [
				'product.is_active' => 1,
				'cart.user_id' => $customerId
			];

			$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
			->where($cond)->select('cart.*', 'product.name', 'product.thumbnail_id')->orderBy('cart.id', 'desc')->get();
			$customerAddress = CustomerAddressModel::where('user_id', $customerId)->first();

			//update shipping
			if (!empty($customerAddress) && $customerAddress->count()) {
				
				$isPincodeExist = ShippingModel::where(['pincode' => $customerAddress->shipping_pincode, 'is_active' => 1])->first();

				if (!empty($isPincodeExist) && $isPincodeExist->count()) {
					
					$productPrice = productPriceMulti();
	        		$totalAmount = $productPrice->total;
	        		$totalWeight = cartWeightMulti(); //in kg
	        		$totalWeightInGm = $totalWeight*1000;

	        		$shipping = 0;

	        		//check free shipping
	        		if ($isPincodeExist->free_shipping && ($totalAmount >= $isPincodeExist->free_shipping)) {
	        			$shipping = 0;
	        		} elseif ($totalWeightInGm <= 500) {
	        			$shipping = $isPincodeExist->under_500gm;
	        		} elseif ($totalWeightInGm <= 1000) {
	        			$shipping = $isPincodeExist->from500_1000gm;
	        		} elseif ($totalWeightInGm <= 2000) {
	        			$shipping = $isPincodeExist->from1000_2000gm;
	        		} elseif ($totalWeightInGm <= 3000) {
	        			$shipping = $isPincodeExist->from2000_3000gm;
	        		} else {
	        			$shipping = $isPincodeExist->from2000_3000gm;
	        		}

	        		//Start free shipping till 31Aug
	        		$dateToCompare = strtotime('31-Aug-2024');
					$currentDate = strtotime(date('Y-m-d'));

					if ($currentDate <= $dateToCompare && $totalAmount >= 50) {
						$shipping = 0;
					}
					//End free shipping till 31Aug

					$shippingSessObj = [
	        			'pincode' => $request->post('shippingPincode'),
	        			'shipping' => $shipping
	        		];

	        		$request->session()->put('shippingSess', $shippingSessObj);

				}

			}

			$priceData = productPriceMulti();

        	// $paidAmount = $priceData->total;
        	$paidAmount = $priceData->subTotal;
        	$packagingCharges = 0;
        	if (setting('packaging_charges')) {
        		$packagingCharges = ($paidAmount*setting('packaging_charges'))/100;
        		$packagingCharges = round($packagingCharges, 2);
    			$paidAmount += $packagingCharges;
    		}

    		$paidAmount += $priceData->shipping;

    		//add 5% GST
    		$gstCharges = 0;
    		if (setting('gst')) {
    			$gstCharges = ($paidAmount*setting('gst'))/100;
    			$gstCharges = round($gstCharges, 2);
    			$paidAmount += $gstCharges;
    		}

    		$template = view('frontend/components/review-order', compact('cartData', 'customerAddress'))->render();

    		$data = [
    			'data' => $template,
    			'weight' => cartWeightMulti(),
    			'discount' => $priceData->discount,
    			'shippingCharge' => $priceData->shipping,
    			'subTotal' => floatval(number_format($priceData->total-$priceData->shipping-$priceData->discount, 2, '.', '')),
    			'packagingCharges' => $packagingCharges,
    			'gstCharges' => $gstCharges,
    			'paidAmount' => floatval(number_format($paidAmount, 2, '.', '')),
    			'error' => false,
    			'msg' => 'Data Fetched'
    		];
    			
    		$this->status = $data;

		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Direct access not allowed'
			);
		}

		echo json_encode($this->status);
	}

	public function doApplyPromo(Request $request) {
		if ($request->ajax()) {

			//check session
			if (isPaymentInit()) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Payment Initiated & cannot apply promo.'
				);

				return response($this->status);
				die();

			}

			$validator = Validator::make($request->post(), [
			    'couponCode' => 'required',
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

	        	$tempId = $request->cookie('tempUserId');
				$customerId = customerId();

				$cond = [
					'product.is_active' => 1,
					'cart.user_id' => $customerId
				];

				$isExist = CartModel::join('product', 'cart.product_id', '=', 'product.id')
				->where($cond)->select('cart.*', 'product.name', 'product.thumbnail_id')->orderBy('cart.id', 'desc')->get();

	        	if (!empty($isExist) && $isExist->count()) {

	        		//get coupon data
	        		$couponCode = $request->post('couponCode');
	        		$getCoupon = CouponModel::where(['coupon_code' => $couponCode, 'is_active' => 1])->first();

	        		if (!empty($getCoupon) && $getCoupon->count()) {
	        			
	        			$currentDate = date('Y-m-d');

				        $isDateValidated = false;
		                $startDate = $getCoupon->start_date;
		                $endDate = $getCoupon->end_date;

		                //check start date and end date
		                if (empty($startDate) && empty($endDate)) {
		                    
		                    $isDateValidated = true;

		                } elseif (!empty($startDate) && !empty($endDate)) {
		                    
		                    if (($currentDate >= $startDate) && ($currentDate <= $endDate)) {
		                        $isDateValidated = true;
		                    }

		                } elseif (!empty($startDate) && empty($endDate)) {
		                    
		                    if ($currentDate >= $startDate) {
		                        $isDateValidated = true;
		                    }

		                } elseif (empty($startDate) && !empty($endDate)) {
		                    
		                    if ($currentDate <= $endDate) {
		                        $isDateValidated = true;
		                    }

		                }

		                if ($isDateValidated) {

		                	//check coupon usage
		                	$getCouponUsage = $getCoupon->coupon_usage;

		                	//Min Cart Amount
		                	$getMinCartAmount = $getCoupon->min_cart_amount;

		                	if ($getCouponUsage == 'single') {
		                		//check if coupon is already used

		                		if (!customerId()) {
		                			return response(array(
										'error' => true,
										'eType' => 'final',
										'msg' => 'Please login your account to use coupon code'
									));
		                		} else {

		                			$isUsed = OrderModel::where(['coupon_code' => $getCoupon->coupon_code, 'user_id' => customerId()])->count();

		                			if ($isUsed) {
		                				return response(array(
											'error' => true,
											'eType' => 'final',
											'msg' => 'You have already used this coupon code.'
										));
		                			}

		                		}

		                	}

		                	//remove promo
		                	Session::forget('couponSess');
		                	// Session::forget('shippingSess');

		                	$productPrice = productPriceMulti();
		                	$getCustomerAdd = CustomerAddressModel::where('user_id', customerId())->latest()->first();
		                	if (!empty($getCustomerAdd)) {
		                				
	                			$shippingPincode = $getCustomerAdd->shipping_pincode;
		                		$isPincodeExist = ShippingModel::where(['pincode' => $shippingPincode, 'is_active' => 1])->first();

		                		if (!empty($isPincodeExist)) {

		                			$productPrice = productPriceMulti();
					        		$totalAmount = $productPrice->total;
					        		$subTotal = $productPrice->subTotal;
					        		$totalWeight = cartWeightMulti(); //in kg
					        		$totalWeightInGm = $totalWeight*1000;

					        		$shipping = 0;

					        		//check free shipping
					        		if ($isPincodeExist->free_shipping && ($totalAmount >= $isPincodeExist->free_shipping)) {
					        			$shipping = 0;
					        		} elseif ($totalWeightInGm <= 500) {
					        			$shipping = $isPincodeExist->under_500gm;
					        		} elseif ($totalWeightInGm <= 1000) {
					        			$shipping = $isPincodeExist->from500_1000gm;
					        		} elseif ($totalWeightInGm <= 2000) {
					        			$shipping = $isPincodeExist->from1000_2000gm;
					        		} elseif ($totalWeightInGm <= 3000) {
					        			$shipping = $isPincodeExist->from2000_3000gm;
					        		} else {
					        			$shipping = $isPincodeExist->from2000_3000gm;
					        		}

					        		//Start free shipping till 31Aug
					        		$dateToCompare = strtotime('31-Aug-2024');
									$currentDate = strtotime(date('Y-m-d'));

									if ($currentDate <= $dateToCompare && $totalAmount >= 50) {
										$shipping = 0;
									}
									//End free shipping till 31Aug
		                				
	                				if ($getCoupon->coupon_for == 'shipping') {

	                					if (!$shipping) {
						        			return response(array(
												'error' => true,
												'eType' => 'final',
												'msg' => 'The coupon cannot apply on 0 shipping.'
											));
						        		}

						        		//check min cart amount
			                			if (!empty($getMinCartAmount)) {
			                				if ($subTotal < $getMinCartAmount) {
			                					return response()->json([
			                						'error' => true,
													'eType' => 'final',
													'msg' => 'The minimum cart amount should be Rs. '. $getMinCartAmount
			                					]);
			                				}
			                			}

			                			$deliveryCharges = 0;

			                			$discountRate = $getCoupon->coupon_price;
			                			if ($getCoupon->coupon_type == 'percentage') {
			                				$discount = ($shipping*$discountRate)/100;
			                			} else {
			                				$discount = $discountRate;
			                			}

			                			//check max discount
			                			$maxDiscount = $getCoupon->max_discount;

			                			if (!empty($maxDiscount)) {
			                				if ($discount > $maxDiscount) {
			                					$discount = $maxDiscount;
			                				}
			                			}

			                			$newShipping = $shipping;
			                			//check shipping charge
			                			if ($discount >= $shipping) {
			                				$newShipping = 0;
			                				$discount = 0;
			                			} else {
			                				$deliveryCharges = $newShipping-$discount;
			                			}

			                			$totalDiscount = $subTotal-$discount;
			                			$grandTotal = $subTotal+$deliveryCharges-$discount;

			                			$sessionObj = array(
							              'coupon_id' => $getCoupon->id,
							              'coupon_for' => $getCoupon->coupon_for,
							              'coupon_code' => $getCoupon->coupon_code,
							              'discount' => 0,
							            );
						            
						            	$request->session()->put('couponSess', $sessionObj);

						            	$shippingSessObj = [
						        			'pincode' => $getCustomerAdd->shipping_pincode,
						        			// 'shipping' => $discount
						        			'shipping' => $deliveryCharges
						        		];

						        		$request->session()->put('shippingSess', $shippingSessObj);

						            	$priceData = productPriceMulti();

						            	// $paidAmount = $priceData->total;
						            	$paidAmount = $priceData->subTotal;
						            	$packagingCharges = 0;
						            	if (setting('packaging_charges')) {
						            		$packagingCharges = ($paidAmount*setting('packaging_charges'))/100;
						            		$packagingCharges = round($packagingCharges, 2);
						        			$paidAmount += $packagingCharges;
						        		}

						        		// $paidAmount += $priceData->shipping;
						        		if ($newShipping) {
						        			$paidAmount += $priceData->shipping;
						        		} else {
						        			// $paidAmount += $newShipping;
						        			$paidAmount += $deliveryCharges;
						        		}
						        		$paidAmount -= $priceData->discount;

						        		$paidAmount = round($paidAmount, 2);

						            	$this->status = array(
											'error' => false,
											'discount' => $discount,
											'grandTotal' => $grandTotal,
											'priceData' => $priceData,
											'paidAmount' => $paidAmount,
											'packagingCharges' => $packagingCharges,
											'msg' => 'The coupon has been applied'
										);

	                				} else {

	                					$shippingSessObj = [
						        			'pincode' => $shippingPincode,
						        			'shipping' => $shipping
						        		];

						        		$request->session()->put('shippingSess', $shippingSessObj);

	                				}

	                				// $subTotal = $productPrice->total;
				                	$subTotal = $productPrice->subTotal;
		                			$deliveryCharges = 0;

		                			//check min cart amount
		                			if (!empty($getMinCartAmount)) {
		                				if ($subTotal < $getMinCartAmount) {
		                					return response()->json([
		                						'error' => true,
												'eType' => 'final',
												'msg' => 'The minimum cart amount should be Rs. '. $getMinCartAmount
		                					]);
		                				}
		                			}

		                			$discountRate = $getCoupon->coupon_price;
		                			if ($getCoupon->coupon_type == 'percentage') {
		                				$discount = ($subTotal*$discountRate)/100;
		                			} else {
		                				$discount = $discountRate;
		                			}

		                			//check max discount
		                			$maxDiscount = $getCoupon->max_discount;

		                			if (!empty($maxDiscount)) {
		                				if ($discount > $maxDiscount) {
		                					$discount = $maxDiscount;
		                				}
		                			}

		                			$totalDiscount = $subTotal-$discount;
		                			$grandTotal = $subTotal+$deliveryCharges-$discount;

		                			$sessionObj = array(
						              'coupon_id' => $getCoupon->id,
						              'coupon_for' => $getCoupon->coupon_for,
						              'coupon_code' => $getCoupon->coupon_code,
						              'discount' => $discount,
						            );
					            
					            	$request->session()->put('couponSess', $sessionObj);

					            	$priceData = productPriceMulti();

					            	// $paidAmount = $priceData->total;
					            	$paidAmount = $priceData->subTotal;
					            	$packagingCharges = 0;
					            	if (setting('packaging_charges')) {
					            		$packagingCharges = ($paidAmount*setting('packaging_charges'))/100;
					            		$packagingCharges = round($packagingCharges, 2);
					        			$paidAmount += $packagingCharges;
					        		}

					        		$paidAmount += $priceData->shipping;
					        		$paidAmount -= $priceData->discount;

					        		//add 5% GST
						    		$gstCharges = 0;
						    		if (setting('gst')) {
						    			$gstCharges = ($paidAmount*setting('gst'))/100;
						    			$gstCharges = round($gstCharges, 2);
						    			$paidAmount += $gstCharges;
						    		}

					        		$paidAmount = round($paidAmount, 2);

					            	$this->status = array(
										'error' => false,
										'discount' => $discount,
										'grandTotal' => $grandTotal,
										'priceData' => $priceData,
										'paidAmount' => $paidAmount,
										'gstCharges' => $gstCharges,
										'packagingCharges' => $packagingCharges,
										'msg' => 'The coupon has been applied'
									);

	                			} else {
	                				return response(array(
										'error' => true,
										'eType' => 'final',
										'msg' => 'The delivery is not available on this pincode.'
									));
	                			}

	                		} else {
	                			return response(array(
									'error' => true,
									'eType' => 'final',
									'msg' => 'Please enter your shipping details.'
								));
	                		}

		                } else {
		                	$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'The entered coupon is expired'
							);
		                }

	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'The entered coupon is invalid'
						);
	        		}
	        		
	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'There are no cart data found.'
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

	public function doPlaceOrder(Request $request) {

		if ($request->ajax()) {

			//check if is customer logged in
			if (!customerId()){
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Please login to save the address.'
				);
				return json_encode($this->status);
			}

			$obj = [
				'shippingName' => 'required',
				'shippingCompanyName' => 'sometimes|nullable',
				'shippingAddress' => 'required',
				'shippingCity' => 'required',
	            'shippingState' => 'required',
	            'shippingPincode' => 'required|numeric|digits:6',
	            'shippingEmail' => 'required|email',
	            'shippingPhone' => 'required|numeric',
	            'gstNumber' => 'sometimes|nullable',
	            'acceptTermsCondition' => 'required',
	            'remark' => 'sometimes|nullable',
	            'wetransferLink' => 'sometimes|nullable',
	            'courier' => 'required|in:DTDC,India Post',
	            'paymentMethod' => 'required|in:phonepe,payu'
	        ];

	        $isBillingAddrSame = $request->post('isBillingAddressSame');

	        if (!isset($isBillingAddrSame)) {
	        	$obj['billingName'] = 'required';
	        	$obj['billingCompanyName'] = 'sometimes|nullable';
	        	$obj['billingAddress'] = 'required';
	        	$obj['billingCity'] = 'required';
	        	$obj['billingState'] = 'required';
	        	$obj['billingPincode'] = 'required|numeric|digits:6';
	        	$obj['billingEmail'] = 'required|email';
	        	$obj['billingPhone'] = 'required|numeric';
	        }

			$validator = Validator::make($request->post(), $obj);

	        if ($validator->fails()) {
	            
	            $errors = $validator->errors()->getMessages();

	            $this->status = array(
					'error' => true,
					'eType' => 'field',
					'errors' => $errors,
					'msg' => 'Validation failed'
				);

	        } else {

	        	$shippingPincode = $request->post('shippingPincode');

	        	//check if pincode exist
	        	$isPincodeExist = ShippingModel::where(['pincode' => $shippingPincode, 'is_active' => 1])->first();

	        	if (!empty($isPincodeExist) && $isPincodeExist->count()) {
	        		
	        		//check if customer address exist
	        		$userId = customerId();
	        		$getCustomerAdd = CustomerAddressModel::where('user_id', $userId)->latest()->first();

	        		//Remove shipping session
	        		Session::forget('shippingSess');

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
			            'gst_number' => $request->post('gstNumber'),
			            'is_billing_same' => 1,
			        ];

			        $isBillingAddrSame = $request->post('isBillingAddressSame');

			        if (!isset($isBillingAddrSame)) {
			        	$obj['is_billing_same'] = 0;
			        	$obj['billing_name'] = $request->post('billingName');
			        	$obj['billing_company_name'] = $request->post('billingCompanyName');
			        	$obj['billing_address'] = $request->post('billingAddress');
			        	$obj['billing_city'] = $request->post('billingCity');
			        	$obj['billing_state'] = $request->post('billingState');
			        	$obj['billing_pincode'] = $request->post('billingPincode');
			        	$obj['billing_email'] = $request->post('billingPincode');
			        	$obj['billing_phone'] = $request->post('billingPhone');
			        }

	        		if (!empty($getCustomerAdd) && $getCustomerAdd->count()) {
	        			//update
	        			$isUpdated = CustomerAddressModel::where('user_id', $userId)->update($obj);
	        		} else {
	        			//insert
	        			$isUpdated = CustomerAddressModel::create($obj);
	        		}

	        		/*
	        			1. Get Total Amount
	        			2. Check Free Shipping
	        			3. Get Weight
	        			4. Get Weight Price
	        		*/

	        		$productPrice = productPriceMulti();
	        		$totalAmount = $productPrice->total;
	        		$totalWeight = cartWeightMulti(); //in kg
	        		$totalWeightInGm = $totalWeight*1000;

	        		$couponSess = Session::get('couponSess');

	        		$shipping = 0;

	        		//check free shipping
	        		if ($isPincodeExist->free_shipping && ($totalAmount >= $isPincodeExist->free_shipping)) {
	        			$shipping = 0;
	        		} elseif ($totalWeightInGm <= 500) {
	        			$shipping = $isPincodeExist->under_500gm;
	        		} elseif ($totalWeightInGm <= 1000) {
	        			$shipping = $isPincodeExist->from500_1000gm;
	        		} elseif ($totalWeightInGm <= 2000) {
	        			$shipping = $isPincodeExist->from1000_2000gm;
	        		} elseif ($totalWeightInGm <= 3000) {
	        			$shipping = $isPincodeExist->from2000_3000gm;
	        		} else {
	        			$shipping = $isPincodeExist->from2000_3000gm;
	        		}

	        		//Start free shipping till 31Aug
	        		$dateToCompare = strtotime('31-Aug-2024');
					$currentDate = strtotime(date('Y-m-d'));

					if ($currentDate <= $dateToCompare && $totalAmount >= 50) {
						$shipping = 0;
					}
					//End free shipping till 31Aug

	        		$newShipping = $shipping;

	        		//check if coupon session exist
	        		if (!empty($couponSess)) {

	        			$getCoupon = CouponModel::where(['id' => $couponSess['coupon_id'], 'is_active' => 1])->first();

	        			if (!empty($getCoupon)) {
	        					
	        				if ($getCoupon->coupon_for == 'shipping') {

		        				//check coupon usage
			                	$getCouponUsage = $getCoupon->coupon_usage;

			                	//Min Cart Amount
			                	$getMinCartAmount = $getCoupon->min_cart_amount;
	        					
	        					if (!empty($getMinCartAmount)) {
	                				if (productPriceMulti()->subTotal < $getMinCartAmount) {
	                					return response()->json([
	                						'error' => true,
											'eType' => 'final',
											'msg' => 'The minimum cart amount should be Rs. '. $getMinCartAmount
	                					]);
	                				}
	                			}

	                			$deliveryCharges = 0;

	                			$discountRate = $getCoupon->coupon_price;
	                			if ($getCoupon->coupon_type == 'percentage') {
	                				$discount = ($shipping*$discountRate)/100;
	                			} else {
	                				$discount = $discountRate;
	                			}

	                			//check max discount
	                			$maxDiscount = $getCoupon->max_discount;

	                			if (!empty($maxDiscount)) {
	                				if ($discount > $maxDiscount) {
	                					$discount = $maxDiscount;
	                				}
	                			}

	                			//check shipping charge
	                			if ($discount >= $shipping) {
	                				$newShipping = 0;
	                				$discount = 0;
	                			} else {
	                				$deliveryCharges = $newShipping-$discount;
	                			}

	                			$shippingSessObj = [
				        			'pincode' => $request->post('shippingPincode'),
				        			// 'shipping' => $discount
				        			'shipping' => $deliveryCharges
				        		];

				        		$request->session()->put('shippingSess', $shippingSessObj);

		        			} else {

		        				$deliveryCharges = $shipping;

		        				//Start free shipping till 31Aug
				        		$dateToCompare = strtotime('31-Aug-2024');
								$currentDate = strtotime(date('Y-m-d'));

								if ($currentDate <= $dateToCompare && $totalAmount >= 50) {
									$shipping = 0;
								}
								//End free shipping till 31Aug

		        				$shippingSessObj = [
				        			'pincode' => $request->post('shippingPincode'),
				        			'shipping' => $shipping
				        		];

				        		$request->session()->put('shippingSess', $shippingSessObj);

		        			}

	        			} else {
	        				$deliveryCharges = $newShipping;
	        			}

	        		} else {

	        			$deliveryCharges = $shipping;

	        			//Start free shipping till 31Aug
		        		$dateToCompare = strtotime('31-Aug-2024');
						$currentDate = strtotime(date('Y-m-d'));

						if ($currentDate <= $dateToCompare && $totalAmount >= 50) {
							$shipping = 0;
						}
						//End free shipping till 31Aug

	        			$shippingSessObj = [
		        			'pincode' => $request->post('shippingPincode'),
		        			'shipping' => $shipping
		        		];

		        		$request->session()->put('shippingSess', $shippingSessObj);

	        		}

	        		$remarkSessObj = [
	        			'remark' => $request->post('remark'),
	        		];

	        		$request->session()->put('remarkSess', $remarkSessObj);

	        		$wetransferSessObj = [
	        			'wetransferLink' => $request->post('wetransferLink'),
	        		];

	        		$request->session()->put('wetransferLinkSess', $wetransferSessObj);

	        		$courierSessObj = [
	        			'courier' => $request->post('courier'),
	        		];

	        		$request->session()->put('courierSess', $courierSessObj);

	        		$paymentMethodSessObj = [
	        			'paymentMethod' => $request->post('paymentMethod'),
	        		];

	        		$request->session()->put('paymentMethodSess', $paymentMethodSessObj);

	        		$priceData = productPriceMulti();
	        		$weightData = cartWeightMulti();
	        		// $productSpec = productSpec(getCartId());
	        		$productSpec = 'eprintcafe';
	        		$shippingSess = Session::get('shippingSess');
	        		$documentSess = Session::get('documents');
	        		
	        		//check document session

	        		// if (empty($documentSess)) {
	        			
	        		// 	$this->status = array(
					// 		'error' => true,
					// 		'eType' => 'final',
					// 		'msg' => 'Please upload your document'
					// 	);

					// 	echo json_encode($this->status);
					// 	die();
						
	        		// }

	        		// print_r($priceData);
	        		// print_r($weightData);
	        		// print_r($shippingSess);
	        		// print_r($couponSess);

	        		// $paidAmount = ceil($priceData->total*100);
	        		//$paidAmount = $priceData->total*100;
	        		//$paidAmount = 1;
	        		// $paidAmount = $priceData->total;
	        		$paidAmount = $priceData->subTotal;
	        		$packagingCharges = 0;
	        		//Add Packaging Charges
	        		if (setting('packaging_charges')) {
	        			$packagingCharges = ($paidAmount*setting('packaging_charges'))/100;
	        			$packagingCharges = round($packagingCharges, 2);
			        	$paidAmount += $packagingCharges;
	        		}

	        		//$paidAmount += $priceData->shipping;
	        		if ($newShipping) {
	        			$paidAmount += $priceData->shipping;
	        		} else {
	        			//$paidAmount += $newShipping;
	        			$paidAmount += $deliveryCharges;
	        		}
	        		$paidAmount -= $priceData->discount;

	        		//add 5% GST
		    		$gstCharges = 0;
		    		if (setting('gst')) {
		    			$gstCharges = ($paidAmount*setting('gst'))/100;
		    			$gstCharges = round($gstCharges, 2);
		    			$paidAmount += $gstCharges;
		    		}

	        		$paidAmount = round($paidAmount, 2);

	        		$transactionId = uniqid();

	        		$paymentMethod = $request->post('paymentMethod');

	        		if ($paymentMethod == 'phonepe') {

	        			//$paidAmount = 1;

	        			$paymentObj = array (
				            'merchantId' => env("PROD_MERCHANT_ID"),
				            'merchantTransactionId' => $transactionId,
				            'merchantUserId' => 'MUID123',
				            'amount' => $paidAmount*100,
				            'redirectUrl' => route('response'),
				            'redirectMode' => 'REDIRECT',
				            'callbackUrl' => route('response'),
				            'mobileNumber' => $request->post('shippingPhone'),
				            'paymentInstrument' => array (
				            	'type' => 'PAY_PAGE',
				            ),
				        );

	        			$encode = base64_encode(json_encode($paymentObj));
				        $saltKey = env('PROD_MERCHANT_KEY');
	        			$saltIndex = 1;

	        			$string = $encode.'/pg/v1/pay'.$saltKey;
	        			$sha256 = hash('sha256',$string);
	        			$finalXHeader = $sha256.'###'.$saltIndex;
	        			$url = env('PROD_URL')."/pg/v1/pay";

	        			$response = Curl::to($url)
		                ->withHeader('Content-Type:application/json')
		                ->withHeader('X-VERIFY:'.$finalXHeader)
		                ->withData(json_encode(['request' => $encode]))
		                ->post();

		                $rData = json_decode($response);

		                if (isset($rData->success) && $rData->success) {

		        			Session::forget('paymentSess');

		        			//create session for payment initiated
		        			$paymentInitObj = [
			        			'transactionId' => $transactionId,
			        			'paidAmount' => $paidAmount,
			        		];

			        		$request->session()->put('paymentSess', $paymentInitObj);
		        			
		        			$this->status = array(
								'error' => false,
								'redirect' => $rData->data->instrumentResponse->redirectInfo->url,
								'msg' => 'Payment Initiated',
								'paymentMethod' => 'phonepe',
							);

		        		} else {
		        			$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'Something went wrong while initiating payment.'
							);
		        		}
	        			
	        		} else {

	        			$MERCHANT_KEY = "q8S6BB"; // TEST MERCHANT KEY
	        			$SALT = "FwyslfXn3zDZtugwyHCiZu70zDmariAM"; // TEST SALT

	        			//$PAYU_BASE_URL = "https://test.payu.in";
	        			$PAYU_BASE_URL = "https://secure.payu.in"; // PRODUCATION

	        			$name = $request->post('shippingName');
				        $successURL = route('paymentResponse');
				        $failURL = route('paymentFailPage');
				        $email = $request->post('shippingEmail');

				        //$productName = ProductModel::where('id', getCartProductId())->value('name');
				        $productName = 'eprintcafe';

				        $action = '';
				        //$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
				        $txnid = $transactionId;
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

				        Session::forget('paymentSess');

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

		        		$request->session()->put('paymentSess', $paymentInitObj);
	        			
	        			$this->status = array(
							'error' => false,
							// 'redirect' => $rData->data->instrumentResponse->redirectInfo->url,
							'redirect' => route('payumoneyPage'),
							'msg' => 'Payment Initiated',
							'paymentMethod' => 'payu',
						);

	        		}

	                // // Log information
					// Log::info('API Host: ' . $url);
					// Log::info('Request Payload: ' . json_encode(['request' => $encode]));
					// Log::info('Response Payload: ' . $response);
					// Log::info('Header values: ' . $finalXHeader);

	                // print_r($rData);
	                // print_r($paymentObj);
	                // die();

	        		// if (isset($rData->success) && $rData->success) {

	        		// 	Session::forget('paymentSess');

	        		// 	//create session for payment initiated
	        		// 	$paymentInitObj = [
		        	// 		'transactionId' => $transactionId,
		        	// 		'paidAmount' => $paidAmount,
		        	// 	];

		        	// 	$request->session()->put('paymentSess', $paymentInitObj);
	        			
	        		// 	$this->status = array(
					// 		'error' => false,
					// 		'redirect' => $rData->data->instrumentResponse->redirectInfo->url,
					// 		'msg' => 'Payment Initiated'
					// 	);

	        		// } else {
	        		// 	$this->status = array(
					// 		'error' => true,
					// 		'eType' => 'final',
					// 		'msg' => 'Something went wrong while initiating payment.'
					// 	);
	        		// }

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'field',
						'errors' => ['shippingPincode' => 'The delivery is not available on this pincode'],
						'msg' => 'Validation failed'
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
}