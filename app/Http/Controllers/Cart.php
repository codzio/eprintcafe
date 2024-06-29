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

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\PricingModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\ShippingModel;
use App\Models\CartModel;
use App\Models\CouponModel;
use App\Models\OrderModel;

class Cart extends Controller {

	private $status = array();

	public function index(Request $request) {

		$tempId = $request->cookie('tempUserId');
		$userId = customerId();

		$cond = ['product.is_active' => 1];

		if (!empty($userId)) {
			$cond['cart.user_id'] = $userId;
		} else {
			$cond['cart.temp_id'] = $tempId;
		}

		$getCartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		// ->take(1)
		->get();

		if (!empty($getCartData) && $getCartData->count()) {

			//remove promo
		    Session::forget('couponSess');

		    //remove shipping
		    Session::forget('shippingSess');
		
			$data = array(
				'title' => 'Cart',
				'pageTitle' => 'Cart',
				'menu' => 'cart',
				'cartData' => $getCartData,
			);

			return view('frontend/cart', $data);

		} else {
			return redirect()->route('homePage');
		}

	}

	public function doAddToCart(Request $request) {
		if ($request->ajax()) {

			//check session
			if (isPaymentInit()) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Payment Initiated & cannot add to cart.'
				);

				return response($this->status);
				die();

			}

			$validator = Validator::make($request->post(), [
			    'productId' => 'required|numeric',
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
			    // 'documentLink' => 'required|url:http,https',
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

	        	$productId = $request->post('productId');
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
	        	// $documentLink = $request->post('documentLink');

	        	//check if product exist for delivery
	        	$isProductExist = ProductModel::where(['id' => $productId, 'is_active' => 1])->first();

	        	if (!empty($isProductExist) && $isProductExist->count()) {
	        	
	        		$pricingObj = [
	        			'product_id' => $productId,
	        			'paper_size_id' => $paperSize,
	        			'paper_gsm_id' => $paperGsm,
	        			'paper_type_id' => $paperType,
	        			'side' => $paperSides,
	        			'color' => $color,
	        		];

	        		//check if pricing exist
	        		$isPricingExist = PricingModel::where($pricingObj)->first();

	        		if (!empty($isPricingExist) && $isPricingExist->count()) {

	        			$isBindingExist = true;
	        			$isLaminationExist = true;
	        			$isCoverExist = true;

	        			//Check Binding
	        			if (!empty($binding)) {
	        				
	        				$getBindingData = BindingModel::where('id', $binding)->count();

	        				if (!$getBindingData) {
	        					$isBindingExist = false;
	        				}

	        			}

	        			//check Lamination
	        			if (!empty($lamination)) {
	        				
	        				$getLaminationData = LaminationModel::where('id', $lamination)->count();

	        				if (!$getLaminationData) {
	        					$isLaminationExist = false;
	        				}

	        			}

	        			//check Cover
	        			if (!empty($cover)) {
	        				
	        				$getCoverData = CoverModel::where('id', $cover)->count();

	        				if (!$getCoverData) {
	        					$isCoverExist = false;
	        				}

	        			}

	        			if ($isBindingExist && $isLaminationExist && $isCoverExist) {

	        				$tempId = $request->cookie('tempUserId');
	        				$customerId = customerId();

	        				$cartObj = [
	        					'temp_id' => $tempId,
	        					'user_id' => $customerId,
	        					'product_id' => $productId,
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
	        					'amount' => productSinglePriceForAmount($productId, $paperSize, $paperGsm, $paperType, $paperSides, $color, $binding, $lamination, $cover, $noOfPages, $noOfCopies)->total,
	        					// 'document_link' => $documentLink,
	        				];

	        				//remove the cart if any
	        				//CartModel::where('temp_id', $tempId)->delete();

	        				Session::forget('shippingSess');
			        		Session::forget('couponSess');
			        		Session::forget('paymentSess');
			        		Session::forget('documents');

	        				//CartModel::where('temp_id', $tempId)->delete();

			        		//check whether cart need to update or delete
			        		if (!empty($customerId)) {
							    $condition = ['user_id' => $customerId, 'product_id' => $productId];
							} else {
							    $condition = ['temp_id' => $tempId, 'product_id' => $productId];
							}

			        		$isCartDataExist = CartModel::where($condition)->count();

			        		if (!$isCartDataExist) {
			        			$isAdded = CartModel::create($cartObj);
			        		} else {
			        			$isAdded = CartModel::where($condition)->update($cartObj);
			        		}
	        				

	        				if ($isAdded) {
	        					$this->status = array(
									'error' => false,
									'msg' => 'The product has been added into the cart.'
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
								'msg' => 'The product pricing is not available.'
							);
	        			}


	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'The product pricing is not available.'
						);
	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The product is not available.'
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

	public function doRemoveCartItem(Request $request) {
		if ($request->ajax()) {

			//check session
			if (isPaymentInit()) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Payment Initiated & cannot remove from the cart.'
				);

				return response($this->status);

			}

			$validator = Validator::make($request->post(), [
			    'cartId' => 'required|numeric',
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

	        	$cartId = $request->post('cartId');
	        	CartModel::where('id', $cartId)->delete();

	        	//remove other items
	        	// if (customerId()) {
	        	// 	CartModel::where('user_id', customerId())->delete();
	        	// } else {
	        	// 	CartModel::where('temp_id', tempId())->delete();
	        	// }

	        	//remove coupon && shipping
	        	//Session::forget('documents');
	        	Session::forget('shippingSess');
        		Session::forget('couponSess');
        		Session::forget('paymentSess');

	        	$this->status = array(
					'error' => false,
					'msg' => 'The cart item has been removed'
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

	public function doUpdateCartItem(Request $request) {
		if ($request->ajax()) {

			//check session
			if (isPaymentInit()) {
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Payment Initiated & cannot update cart item.'
				);

				return response($this->status);
				die();

			}

			$validator = Validator::make($request->post(), [
			    'qty' => 'required',
			    'noOfCopies' => 'required',
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
				$userId = customerId();

				if (!empty($userId)) {
					$cond['cart.user_id'] = $userId;
				} else {
					$cond['cart.temp_id'] = $tempId;
				}

				$qty = $request->post('qty');
				$noOfCopies = $request->post('noOfCopies');

				foreach ($qty as $cartId => $value) {
					$cond['cart.id'] = $cartId;

					$cartData = CartModel::where($cond)->first();

					$amount = productSinglePrice($cartData->product_id, $cartData->user_id)->total;

					CartModel::where($cond)->update(['qty' => $value, 'no_of_copies' => $noOfCopies[$cartId], 'amount' => $amount]);	
				}

	        	//remove coupon && shipping

	        	$this->status = array(
					'error' => false,
					'msg' => 'The cart item has been updated'
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
				$userId = customerId();

				if (!empty($userId)) {
					$cond['cart.user_id'] = $userId;
				} else {
					$cond['cart.temp_id'] = $tempId;
				}

	        	//check if data exist
	        	$isExist = CartModel::where($cond)->first();

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

		                	$productPrice = productPriceMulti();
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
			        			$paidAmount += $packagingCharges;
			        		}

			        		$paidAmount += $priceData->shipping;
			        		$paidAmount -= $priceData->discount;

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
}