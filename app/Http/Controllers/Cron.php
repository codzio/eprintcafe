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
use App\Http\Controllers\SmsSending;

use App\Models\CartModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\PricingModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\ShippingModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CouponModel;
use App\Models\CustomerAddressModel;
use App\Models\CustomerModel;
use App\Models\BarcodeModel;

use App\Models\ContactModel;
use DateTime;

class Cron extends Controller {

	private $status = array();

	public function abdCart(Request $request) {

		$getRecords = CartModel::join('customer', 'cart.user_id', '=', 'customer.id')
		->whereNotNull('cart.user_id')
		->groupBy('cart.user_id')
		->select('customer.*')
		->get();

		if (!empty($getRecords) && $getRecords->count()) {
		
			foreach ($getRecords as $record) {

				$userId = $record->id;
				
				//get cart item
				$cartItem = CartModel::where('user_id', $userId)->latest()->first();
				
				if (!empty($cartItem) && $cartItem->count()) {
					
					$lastUpdated = new DateTime($cartItem->updated_at);
					$currentDate = new DateTime();
					$dateDiff = $currentDate->diff($lastUpdated);
					//$hoursDiff = $dateDiff->h + ($dateDiff->days * 24); // Total hours difference
					$hoursDiff = $dateDiff->h;
					//$hoursDiff = 24;

					$shootEmail = false;

					//check if 2 hours has been passed
					if ($hoursDiff >= 2 && $hoursDiff < 3) {
						$shootEmail = true;
					} elseif ($hoursDiff >= 24 && $hoursDiff < 25) {
						//check if 24 hours has been passed
						$shootEmail = true;
					}

					if ($shootEmail) {
						EmailSending::abandonedCartEmail($record);
					}


				}

			}

		}

		
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

}