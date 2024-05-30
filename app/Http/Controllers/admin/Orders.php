<?php

namespace App\Http\Controllers\admin;

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

use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\CustomerAddressModel;
use App\Models\BarcodeModel;

use App\Models\PricingModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\ShippingModel;
use App\Models\CustomCartModel;

use App\Http\Controllers\SmsSending;
use App\Http\Controllers\EmailSending;

use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

//GDrive
use App\Services\GoogleDriveService;

class Orders extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'orders')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Orders',
			'pageTitle' => 'Orders',
			'menu' => 'order',
		);

		return view('admin/order/index', $data);

	}

	public function deletedOrders(Request $request) {

		if (!can('read', 'orders')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Deleted Orders',
			'pageTitle' => 'Deleted Orders',
			'menu' => 'order',
		);

		return view('admin/order/deleted-orders', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'orders')){
				$response = array(
			        "draw" => intval($draw),
			        "iTotalRecords" => 0,
			        "iTotalDisplayRecords" => 0,
			        "aaData" => []
			    );

			    echo json_encode($response);
			    exit;
			}
			
		    $start = $request->get("start");
		    $rowperpage = $request->get("length"); // Rows display per page
		    $inputName = $request->get('field');

		    $singleDelUrl = route('adminDeleteOrder');

		    //get type
		    $columnIndex_arr = $request->get('orders');
		    $columnName_arr = $request->get('columns');
		    $order_arr = $request->get('orders');
		    $search_arr = $request->get('search');

		    $columnIndex = isset($columnIndex_arr[0]['column'])? $columnIndex_arr[0]['column']:''; // Column index
		    $columnName = !empty($columnIndex)? $columnName_arr[$columnIndex]['data']:''; // Column name
		    $columnSortOrder = !empty($order_arr)? $order_arr[0]['dir']:''; // asc or desc
		    $searchValue = $search_arr['value']; // Search value

		     // Total records
		    $totalRecords = OrderModel::join('customer', 'orders.user_id', '=', 'customer.id')->select('count(*) as allcount')->where('orders.is_deleted', 0);
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = OrderModel::join('customer', 'orders.user_id', '=', 'customer.id')->select('count(*) as allcount')->where('orders.is_deleted', 0);

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('orders.order_id', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.product_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.qty', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.no_of_copies', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.paid_amount', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.status', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.order_status', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('orders.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('orders.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = OrderModel::join('customer', 'orders.user_id', '=', 'customer.id')->select('orders.*', 'customer.name','customer.phone')->where('orders.is_deleted', 0)->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        $query->where('orders.order_id', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.product_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.qty', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.no_of_copies', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.paid_amount', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.status', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.order_status', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('orders.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('orders.is_active', 'like', '%0%');
			        // }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('orders.id','desc');
		    }

		    $getAdminData = adminInfo();

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $viewUrl = route('adminViewOrder', $id);
			        $invoiceUrl = route('adminInvoiceOrder', $id);
			        $packingSlip = '';
			        $singleDel = '';
			        
			        $editUrl = route('adminEditOrders', $id);
			        $editOrder = '';

			        if ($record->status == 'unpaid') {
			        	$editOrder = 
			        	'
			        		<div class="menu-item">
								<a title="Edit" href="'.$editUrl.'" class="menu-link px-3">
									<span class="menu-icon"><i class="ki-outline ki-pencil fs-2"></i></span>
								</a>
							</div>
						';
			        }

			        if ($record->shipping_label_number) {
			        	$packingSlip = '<div class="menu-item">
										<a target="_blank" title="Download Packing Slip" href="'.route('adminPackingSlipOrder', $id).'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-bandage fs-2"></i></span>
										</a>
									</div>';
			        }

			        if ($getAdminData->role_id == 1) {
			        	$singleDel = '<div class="menu-item">
										<a title="Delete" href="javascript:void(0)" data-url="'.$singleDelUrl.'" onclick="deleteData(this)" data-id="'.$id.'" class="menu-link text-danger px-3">
											<span class="menu-icon"><i class="ki-outline ki-trash fs-2"></i></span>
										</a>
									</div>';
			        }

			        // $editUrl = route('adminEditShipping', $id);

			        $checkbox = '<div onclick="checkCheckbox(this)" class="form-check form-check-sm form-check-custom form-check-solid">
							<input name="delete[]" data-kt-check-target="#media .single-check-input" class="form-check-input" type="checkbox" value="'.$id.'" />
						</div>';

					$action = '
			          	<td class="text-end" data-kt-filemanager-table="action_dropdown">
						<div class="d-flex justify-content-end">
							<div class="ms-2">
								<div class="menu menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">

									<div class="menu-item">
										<a target="_blank" title="Download Invoice" href="'.$invoiceUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-document fs-2"></i></span>
										</a>
									</div>

									'.$packingSlip.'
									
									<div class="menu-item">
										<a title="View Order" href="'.$viewUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-eye fs-2"></i></span>
										</a>
									</div>

									'.$editOrder.'

									'.$singleDel.'

								</div>
							</div>
						</div>
					</td>';

					$phoneNumber = $record->phone;

					if($getAdminData->role_id != 1) {
						$phoneNumber = 'XXXXXXXXXX';
					}

					if ($record->status == 'paid') {
						$status = '<div class="badge badge-success">PAID</div>';
					} else {
						$status = '<div class="badge badge-danger">UNPAID</div>';
					}

					switch ($record->order_status) {
						case 'Production':
							$orderStatus = '<div class="badge badge-warning">Production</div>';
							break;

						case 'Dispatch':
							$orderStatus = '<div class="badge badge-success">Dispatch</div>';
							break;

						case 'Cancel':
							$orderStatus = '<div class="badge badge-danger">Cancel</div>';
							break;
						
						default:
							$orderStatus = '<div class="badge badge-primary">Confirm</div>';
							break;
					}

			        $data_arr[] = array(
			        	"checkbox" => $checkbox,
			          	"order_id" => strtoupper($record->order_id),
			          	"name" => $record->name,
			          	// "phone" => $phoneNumber,
			          	// "product_name" => $record->product_name,
			          	// "qty" => $record->qty,
			          	// "no_of_copies" => $record->no_of_copies,
			          	// "paid_amount" => $record->paid_amount,
			          	"status" => $status,
			          	"order_status" => $orderStatus,
			          	"created_at" => date('d-m-Y h:i A', strtotime($record->created_at)),
			          	"action" => $action
			        );
			    }
		    }

		    $response = array(
		        "draw" => intval($draw),
		        "iTotalRecords" => $totalRecords,
		        "iTotalDisplayRecords" => $totalRecordswithFilter,
		        "aaData" => $data_arr
		    );

		    echo json_encode($response);
		    exit;

		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		echo json_encode($this->status);
	}

	public function getDeletedOrders(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'orders')){
				$response = array(
			        "draw" => intval($draw),
			        "iTotalRecords" => 0,
			        "iTotalDisplayRecords" => 0,
			        "aaData" => []
			    );

			    echo json_encode($response);
			    exit;
			}
			
		    $start = $request->get("start");
		    $rowperpage = $request->get("length"); // Rows display per page
		    $inputName = $request->get('field');

		    $singleDelUrl = route('adminPerDeleteOrder');

		    //get type
		    $columnIndex_arr = $request->get('orders');
		    $columnName_arr = $request->get('columns');
		    $order_arr = $request->get('orders');
		    $search_arr = $request->get('search');

		    $columnIndex = isset($columnIndex_arr[0]['column'])? $columnIndex_arr[0]['column']:''; // Column index
		    $columnName = !empty($columnIndex)? $columnName_arr[$columnIndex]['data']:''; // Column name
		    $columnSortOrder = !empty($order_arr)? $order_arr[0]['dir']:''; // asc or desc
		    $searchValue = $search_arr['value']; // Search value

		     // Total records
		    $totalRecords = OrderModel::join('customer', 'orders.user_id', '=', 'customer.id')->select('count(*) as allcount')->where('orders.is_deleted', 1);
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = OrderModel::join('customer', 'orders.user_id', '=', 'customer.id')->select('count(*) as allcount')->where('orders.is_deleted', 1);

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('orders.order_id', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.product_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.qty', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.no_of_copies', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.paid_amount', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.status', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.order_status', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('orders.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('orders.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = OrderModel::join('customer', 'orders.user_id', '=', 'customer.id')->select('orders.*', 'customer.name','customer.phone')->where('orders.is_deleted', 1)->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        $query->where('orders.order_id', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.product_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.qty', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.no_of_copies', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.paid_amount', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.status', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('orders.order_status', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('orders.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('orders.is_active', 'like', '%0%');
			        // }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('orders.id','desc');
		    }

		    $getAdminData = adminInfo();

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $viewUrl = route('adminViewOrder', $id);
			        $invoiceUrl = route('adminInvoiceOrder', $id);
			        $singleDel = '';
			        
			        $editUrl = route('adminEditOrders', $id);
			        $editOrder = $packingSlip = '';

			        if ($getAdminData->role_id == 1) {
			        	$singleDel = '<div class="menu-item">
										<a title="Delete" href="javascript:void(0)" data-url="'.$singleDelUrl.'" onclick="deleteData(this)" data-id="'.$id.'" class="menu-link text-danger px-3">
											<span class="menu-icon"><i class="ki-outline ki-trash fs-2"></i></span>
										</a>
									</div>';
			        }

			        // $editUrl = route('adminEditShipping', $id);

			        $checkbox = '<div onclick="checkCheckbox(this)" class="form-check form-check-sm form-check-custom form-check-solid">
							<input name="delete[]" data-kt-check-target="#media .single-check-input" class="form-check-input" type="checkbox" value="'.$id.'" />
						</div>';

					$action = '
			          	<td class="text-end" data-kt-filemanager-table="action_dropdown">
						<div class="d-flex justify-content-end">
							<div class="ms-2">
								<div class="menu menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
									
									<div class="menu-item">
										<a title="View Order" href="'.$viewUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-eye fs-2"></i></span>
										</a>
									</div>

									'.$editOrder.'

									'.$singleDel.'

								</div>
							</div>
						</div>
					</td>';

					$phoneNumber = $record->phone;

					if($getAdminData->role_id != 1) {
						$phoneNumber = 'XXXXXXXXXX';
					}

					if ($record->status == 'paid') {
						$status = '<div class="badge badge-success">PAID</div>';
					} else {
						$status = '<div class="badge badge-danger">UNPAID</div>';
					}

					switch ($record->order_status) {
						case 'Production':
							$orderStatus = '<div class="badge badge-warning">Production</div>';
							break;

						case 'Dispatch':
							$orderStatus = '<div class="badge badge-success">Dispatch</div>';
							break;

						case 'Cancel':
							$orderStatus = '<div class="badge badge-danger">Cancel</div>';
							break;
						
						default:
							$orderStatus = '<div class="badge badge-primary">Confirm</div>';
							break;
					}

			        $data_arr[] = array(
			        	"checkbox" => $checkbox,
			          	"order_id" => strtoupper($record->order_id),
			          	"name" => $record->name,
			          	// "phone" => $phoneNumber,
			          	// "product_name" => $record->product_name,
			          	// "qty" => $record->qty,
			          	// "no_of_copies" => $record->no_of_copies,
			          	// "paid_amount" => $record->paid_amount,
			          	"status" => $status,
			          	"order_status" => $orderStatus,
			          	"created_at" => date('d-m-Y h:i A', strtotime($record->created_at)),
			          	"action" => $action
			        );
			    }
		    }

		    $response = array(
		        "draw" => intval($draw),
		        "iTotalRecords" => $totalRecords,
		        "iTotalDisplayRecords" => $totalRecordswithFilter,
		        "aaData" => $data_arr
		    );

		    echo json_encode($response);
		    exit;

		} else {
			$this->status = array(
				'error' => true,
				'eType' => 'final',
				'msg' => 'Something went wrong'
			);
		}

		echo json_encode($this->status);
	}

	public function add(Request $request) {

		if (!can('create', 'orders')){
			return redirect(route('adminDashboard'));
		}

		$stateList = DB::table('states')->orderBy('state')->get();
		$customerList = CustomerModel::latest()->get();
		$productList = ProductModel::where('is_active', 1)->get();

		// $saveProductList = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
		// ->where('user_id', 6)
		// ->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
		// ->latest()
		// ->get();

		$data = array(
			'title' => 'Orders',
			'pageTitle' => 'Orders',
			'menu' => 'orders',
			'stateList' => $stateList,
			'customerList' => $customerList,
			'productList' => $productList,
			// 'saveProductList' => $saveProductList
		);

		return view('admin/order/add', $data);

	}

	public function edit($id, Request $request) {

		if (!can('update', 'orders')){
			return redirect(route('adminDashboard'));
		}

		//check if this order exist
		$getOrder = OrderModel::where('id', $id)->first();

		if (!empty($getOrder) && $getOrder->count()) {
			
			if ($getOrder->status == 'unpaid') {
				
				$orderItem = OrderItemModel::where('order_id', $id)->first();

				if (!empty($orderItem) && $orderItem->count()) {
					
					$stateList = DB::table('states')->orderBy('state')->get();
					$customerList = CustomerModel::latest()->get();
					$productList = ProductModel::where('is_active', 1)->get();
					$customerAddress = json_decode($getOrder->customer_address);

					$productId = $orderItem->product_id;
					$productDetailIds = json_decode($orderItem->product_detail_ids);
					//$priceDetails = json_decode($orderItem->price_details);

					//Update New Price Details
					$priceDetails = productPriceForSaveCustomOrderMulti($getOrder->id);

					$paperSizeList = PricingModel::
					join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
					->where('pricing.product_id', $productId)
					->select('paper_size.*')
					->distinct('paper_size.id')
					->get();

					$getGsm = PricingModel::
					join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
					->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
					->where(['pricing.product_id' => $productId, 'pricing.paper_size_id' => $productDetailIds->paperSizeId])
					->select('gsm.*', 'paper_type.paper_type as paper_type_name')
					->distinct('gsm.id')
					->orderBy('gsm.gsm', 'asc')
					->get();

					$getBinding = BindingModel::where('paper_size_id', $productDetailIds->paperSizeId)->get();

					$getLamination = LaminationModel::where('paper_size_id', $productDetailIds->paperSizeId)->get();

					$getPaperType = PricingModel::
					join('gsm', 'pricing.paper_type_id', '=', 'gsm.paper_type')
					->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
					->where(['pricing.product_id' => $productId, 'pricing.paper_size_id' => $productDetailIds->paperSizeId, 'pricing.paper_gsm_id' => $productDetailIds->paperGsmId])
					->select('pricing.paper_type_id', 'paper_type.paper_type', 'gsm.paper_type_price')
					->distinct('gsm.id')
					->get();

					$getPaperSides = PricingModel::
					where(['product_id' => $productId, 'paper_size_id' => $productDetailIds->paperSizeId, 'paper_gsm_id' => $productDetailIds->paperGsmId, 'paper_type_id' => $productDetailIds->paperTypeId])
					->select('side')
					->distinct('product_id')
					->get();

					$getPaperColor = PricingModel::
					where(['product_id' => $productId, 'paper_size_id' => $productDetailIds->paperSizeId, 'paper_gsm_id' => $productDetailIds->paperGsmId, 'paper_type_id' => $productDetailIds->paperTypeId, 'side' => $productDetailIds->paperSides])
					->select('color', 'other_price')
					->distinct('color')
					->get();

					$orderItemList = OrderItemModel::join('product', 'order_items.product_id', '=', 'product.id')
					->where('order_id', $getOrder->id)
					->select('order_items.*', 'product.name', 'product.thumbnail_id')
					->latest()
					->get();

					$data = array(
						'title' => 'Orders',
						'pageTitle' => 'Orders',
						'menu' => 'orders',
						'stateList' => $stateList,
						'customerList' => $customerList,
						'productList' => $productList,
						'order' => $getOrder,
						'orderItem' => $orderItem,
						'customerAddress' => $customerAddress,
						'productDetailIds' => $productDetailIds,
						'paperSizeList' => $paperSizeList,
						'gsmList' => $getGsm,
						'bindingList' => $getBinding,
						'laminationList' => $getLamination,
						'paperTypeList' => $getPaperType,
						'paperSideList' => $getPaperSides,
						'paperColorList' => $getPaperColor,
						'priceDetails' => $priceDetails,
						'orderItemList' => $orderItemList
					);

					return view('admin/order/edit', $data);

				} else {
					return redirect(route('adminOrders'));
				}

			} else {
				return redirect(route('adminOrders'));
			}

		} else {
			return redirect(route('adminOrders'));
		}

	}

	public function view(Request $request, $id) {

		if (!can('read', 'orders')){
			return redirect(route('adminDashboard'));
		}

		$orderData = OrderModel::where('id', $id)->first();

		if (!empty($orderData) && $orderData->count()){

			$userId = $orderData->user_id;
			$customerData = CustomerModel::where('id', $userId)->first();
			$priceDetail = json_decode($orderData->price_details);
			$transactionDetail = json_decode($orderData->transaction_details);
			$addressDetails = json_decode($orderData->customer_address);
			$documentLinks = [];

			if (!empty($orderData->document_link)) {
				$documentLinks = json_decode($orderData->document_link);
			}

			$getAdminData = adminInfo();
			$adminUserData = adminInfoById($orderData->admin_id);
			$orderItems = OrderItemModel::where('order_id', $orderData->id)->get();

			$data = array(
				'title' => 'Order Detail',
				'pageTitle' => 'Order Detail',
				'menu' => 'order',
				'order' => $orderData,
				'customer' => $customerData,
				'priceDetail' => $priceDetail,
				'transactionDetail' => $transactionDetail,
				'addressDetails' => $addressDetails,
				'documentLinks' => $documentLinks,
				'adminData' => $getAdminData,
				'orderItems' => $orderItems,
				'adminUserData' => $adminUserData,
			);

			return view('admin/order/view', $data);
			
		} else {
			return redirect(route('adminOrders'));
		}

	}

	public function invoice(Request $request, $id) {

		if (!can('read', 'orders')){
			return redirect(route('adminDashboard'));
		}

		$orderData = OrderModel::where('id', $id)->first();

		if (!empty($orderData) && $orderData->count()){

			if (!empty($orderData->product_id)) {
				
				$getOrder = OrderModel::
				join('product', 'orders.product_id', '=', 'product.id')
				->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
				->where('orders.id', $orderData->id)->first();

				$priceData = json_decode($getOrder->price_details);
		        $productDetails = json_decode($getOrder->product_details);
		        $customerAddress = json_decode($getOrder->customer_address);
		        $gstRate = 12;
		        $hsnCode = $getOrder->unregistered_hsn_code;

		        $customerName = $customerAddress->shipping_name;
		        $shippingState = strtolower($customerAddress->shipping_state);

		        $isIntrastate = false;

		        if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
		            $isIntrastate = true;
		        }

		        if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
		            $gstRate = 18;
		            $hsnCode = $getOrder->registered_hsn_code;

		            $customerName = !empty($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:$customerAddress->shipping_name;
		        }

		        $newPaidAmount = $getOrder->paid_amount+$getOrder->additional_discount;

		        $gstCalc = calculateGSTComponents($newPaidAmount, $gstRate);

		        $data = array('order' => $getOrder, 'priceData' => $priceData, 'productDetails' => $productDetails, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate, 'hsnCode' => $hsnCode);
		        echo $template = view('email_templates/admin/vwOrderTemplate', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

			} else {

				$getOrder = OrderItemModel::
				join('product', 'order_items.product_id', '=', 'product.id')
				->select('order_items.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
				->where('order_items.order_id', $orderData->id)->get();

				//$priceData = json_decode($getOrder->price_details);
		        //$productDetails = json_decode($getOrder->product_details);
		        $customerAddress = json_decode($orderData->customer_address);
		        $gstRate = 12;
		        //$hsnCode = $getOrder->unregistered_hsn_code;

		        $customerName = $customerAddress->shipping_name;
		        $shippingState = strtolower($customerAddress->shipping_state);

		        $isIntrastate = false;

		        if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
		            $isIntrastate = true;
		        }

		        if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
		            // $gstRate = 18;
		            // $hsnCode = $getOrder->registered_hsn_code;
		            $customerName = !empty($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:$customerAddress->shipping_name;
		        }

		        //$gstCalc = calculateGSTComponents($orderData->paid_amount, $gstRate);

		        $newPaidAmount = $orderData->paid_amount+$orderData->additional_discount;

		        $gstCalc = calculateGSTComponents($newPaidAmount, $gstRate);

		        $data = array('order' => $orderData, 'orderItem' => $getOrder, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate);

				echo $template = view('email_templates/admin/vwOrderTemplateMulti', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

			}
			
		} else {
			return redirect(route('adminOrders'));
		}

	}

	public function packingSlip(Request $request, $id) {

		if (!can('read', 'orders')){
			return redirect(route('adminDashboard'));
		}

		$orderData = OrderModel::where('id', $id)->first();

		if (!empty($orderData) && $orderData->count()){

			if (!empty($orderData->product_id)) {

				$getOrder = OrderModel::
				join('product', 'orders.product_id', '=', 'product.id')
				->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
				->where('orders.id', $orderData->id)->first();

				$priceData = json_decode($getOrder->price_details);
		        $productDetails = json_decode($getOrder->product_details);
		        $customerAddress = json_decode($getOrder->customer_address);
		        $gstRate = 12;
		        $hsnCode = $getOrder->unregistered_hsn_code;

		        $customerName = $customerAddress->shipping_name;
		        $shippingState = strtolower($customerAddress->shipping_state);

		        $isIntrastate = false;

		        if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
		            $isIntrastate = true;
		        }

		        if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
		            $gstRate = 18;
		            $hsnCode = $getOrder->registered_hsn_code;

		            $customerName = !empty($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:$customerAddress->shipping_name;
		        }

		        $gstCalc = calculateGSTComponents($getOrder->paid_amount, $gstRate);

		        $data = array('order' => $getOrder, 'priceData' => $priceData, 'productDetails' => $productDetails, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate, 'hsnCode' => $hsnCode);
		        echo $template = view('email_templates/admin/vwPackingSlipTemplate', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

		    } else {

		    	$getOrder = OrderItemModel::
				join('product', 'order_items.product_id', '=', 'product.id')
				->select('order_items.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
				->where('order_items.order_id', $orderData->id)->get();
				
				//$priceData = json_decode($getOrder->price_details);
				//$productDetails = json_decode($getOrder->product_details);
				$customerAddress = json_decode($orderData->customer_address);
				$gstRate = 12;
				//$hsnCode = $getOrder->unregistered_hsn_code;

				$customerName = $customerAddress->shipping_name;
				$shippingState = strtolower($customerAddress->shipping_state);

				$isIntrastate = false;

				if ($shippingState == 'dl' OR strtolower($shippingState) == 'delhi') {
				    $isIntrastate = true;
				}

				if (isset($customerAddress->gst_number) && !empty($customerAddress->gst_number)) {
				    // $gstRate = 18;
				    // $hsnCode = $getOrder->registered_hsn_code;

				    $customerName = !empty($customerAddress->shipping_company_name)? $customerAddress->shipping_company_name:$customerAddress->shipping_name;
				}

				$gstCalc = calculateGSTComponents($orderData->paid_amount, $gstRate);

				$data = array('order' => $orderData, 'orderItem' => $getOrder, 'customerAddress' => $customerAddress, 'gstRate' => $gstRate);

				echo $template = view('email_templates/admin/vwPackingSlipTemplateMulti', $data, compact('customerName', 'gstCalc', 'isIntrastate'));

		    }
			
		} else {
			return redirect(route('adminOrders'));
		}

	}

	public function doUpdateBarcode(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

	        $isExist = OrderModel::where(['id' => $request->post('id')])->first();

        	if ($isExist && $isExist->count()) {

        		//check if shipping_label_number exist

        		if (!$isExist->shipping_label_number) {
        			
        			//get barcode
        			$barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();

        			$obj = array(
        				'shipping_label_number' => $barcode->barcode
        			);

        			$isUpdated = OrderModel::where(['id' => $request->post('id')])->update($obj);

        			if ($isUpdated) {
        				
        				BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);

    					$this->status = array(
							'error' => false,								
							'msg' => 'Barcode has been added successfully.'
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
						'msg' => 'Shipping label already updated.'
					);
        		}

        	} else {
        		$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'The order is not exist.'
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

	public function doUpdateOrderStatus(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

	        $isExist = OrderModel::where(['id' => $request->post('id')])->first();

        	if ($isExist && $isExist->count()) {

        		//check if shipping_label_number exist

        		if (!$isExist->shipping_label_number) {

        			$orderStatus = $request->post('orderStatus');

        			$obj = array(
        				'order_status' => $orderStatus
        			);

        			if ($orderStatus == 'Dispatch') {
        				
        				//get barcode
        				$barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();
        				$obj['shipping_label_number'] = $barcode->barcode;

        			}

        			$isUpdated = OrderModel::where(['id' => $request->post('id')])->update($obj);

        			if ($isUpdated) {
        				
        				if ($orderStatus == 'Dispatch') {
	        				BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);
						}

						$this->status = array(
							'error' => false,								
							'msg' => 'Order status has been updated successfully.'
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
						'msg' => 'Shipping label already updated.'
					);
        		}

        	} else {
        		$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'The order is not exist.'
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

	public function doDelete(Request $request) {
		if ($request->ajax()) {

			$getAdminData = adminInfo();

			if ($getAdminData->role_id != 1){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'id' => 'required|numeric',
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

	        	$id = $request->post('id');
	        	$action = $request->post('action');

	        	//check if data exist
	        	$getData = OrderModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		if ($action == 'temp') {
	        			$isDeleted = OrderModel::where('id', $id)->update(['is_deleted' => 1]);
	        		} else {
	        			OrderItemModel::where('order_id', $id)->delete();
	        			$isDeleted = OrderModel::where('id', $id)->delete();
	        		}

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Order has been deleted successfully.'
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

	public function doPerDelete(Request $request) {
		if ($request->ajax()) {

			$getAdminData = adminInfo();

			if ($getAdminData->role_id != 1){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'id' => 'required|numeric',
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

	        	$id = $request->post('id');

	        	//check if data exist
	        	$getData = OrderModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		OrderItemModel::where('order_id', $id)->delete();
	        		$isDeleted = OrderModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Order has been deleted successfully.'
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

	public function doBulkDelete(Request $request) {
		if ($request->ajax()) {

			$getAdminData = adminInfo();

			if ($getAdminData->role_id != 1){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'ids' => 'required|array',
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

	        	$ids = $request->post('ids');
	        	$action = $request->post('action');

	        	//check if data exist
	        	$getData = OrderModel::whereIn('id', $ids)->get();	
	        	
	        	if (!empty($getData)) {

	        		if ($action == 'temp') {
	        			$isDeleted = OrderModel::whereIn('id', $ids)->update(['is_deleted' => 1]);
	        		} else {
	        			OrderItemModel::whereIn('order_id', $ids)->delete();
	        			$isDeleted = OrderModel::whereIn('id', $ids)->delete();
	        		}
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Orders has been deleted successfully.'
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

	public function doPerBulkDelete(Request $request) {
		if ($request->ajax()) {

			$getAdminData = adminInfo();

			if ($getAdminData->role_id != 1){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'ids' => 'required|array',
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

	        	$ids = $request->post('ids');
	        	$action = $request->post('action');

	        	//check if data exist
	        	$getData = OrderModel::whereIn('id', $ids)->get();	
	        	
	        	if (!empty($getData)) {

	        		OrderItemModel::whereIn('order_id', $ids)->delete();
	        		$isDeleted = OrderModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Orders has been deleted successfully.'
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

	public function doBulkRestoreDelete(Request $request) {
		if ($request->ajax()) {

			$getAdminData = adminInfo();

			if ($getAdminData->role_id != 1){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'ids' => 'required|array',
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

	        	$ids = $request->post('ids');
	        	$action = $request->post('action');

	        	//check if data exist
	        	$getData = OrderModel::whereIn('id', $ids)->get();	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = OrderModel::whereIn('id', $ids)->update(['is_deleted' => 0]);
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Orders has been restored successfully.'
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

	public function getCustomerAddress(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$customerId = $request->post('customerId');

	        $isExist = CustomerModel::where(['id' => $customerId])->first();

        	if ($isExist && $isExist->count()) {

        		//get customer address
        		$customerAddress = CustomerAddressModel::where('user_id', $customerId)->latest()->first();

        		if ($customerAddress && $customerAddress->count()) {

        			$saveProductList = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
							->where('user_id', $customerId)
							->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
							->latest()
							->get();

		        	$saveProductListTemp = view('admin/components/savedProductListData', compact('saveProductList'))->render();
        			
        			$this->status = array(
						'error' => false,
						'address' => $customerAddress,
						'saveProductListTemp' => $saveProductListTemp,
					);

        		} else {
        			$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The customer address doesn\'t exist.'
					);
        		}

        	} else {
        		$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'The customer is not exist.'
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

	public function getPricing(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$productId = $request->post('productId');
			$action = $request->post('action');

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

		echo json_encode($this->status);
	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$customerId = $request->post('customer');
			$getSavedCartItem = CustomCartModel::where('user_id', $customerId)->get();

			$obj = [
			    'customer' => 'required|exists:customer,id',
			    'status' => 'required|in:paid,unpaid',
			    'shippingFree' => 'required|in:1,0',
			    'shippingName' => 'required',
				'companyName' => 'sometimes|nullable',
				'gstNumber' => 'sometimes|nullable',
				'shippingAddress' => 'required',
				'shippingCity' => 'required',
	            'shippingState' => 'required',
	            'shippingPincode' => 'required|numeric|digits:6',
	            'shippingEmail' => 'required|email',
	            'shippingPhone' => 'required|numeric',
	            // 'uploadDocuments.*' => 'required|file|mimes:png,jpg,jpeg,pdf,zip|max:200000',
	            'uploadDocuments.*' => 'sometimes|nullable|file|mimes:png,jpg,jpeg,pdf,zip,doc,docx|max:200000',
	            'remark' => 'sometimes|nullable',
	            'additionalDiscount' => 'sometimes|nullable|numeric',
	            'courier' => 'required',
	            'paymentMethod' => 'required|in:Payu,Phonepe,QR,NEFT/IMPS',
			];

			if (!empty($getSavedCartItem) && !$getSavedCartItem->count()) {
				$obj['product'] = 'required|exists:product,id';
			    $obj['paperSize'] = 'required|numeric';
			    $obj['paperGsm'] = 'required|numeric';
			    $obj['paperType'] = 'required|numeric';
			    $obj['paperSides'] = 'required';
			    $obj['color'] = 'required';
			    $obj['binding'] = 'sometimes|nullable|numeric';
			    $obj['lamination'] = 'sometimes|nullable|numeric';
			    $obj['cover'] = 'sometimes|nullable|numeric';
			    $obj['noOfPages'] = 'required|numeric|min:1';
			    $obj['noOfCopies'] = 'required|numeric|min:1';
			}

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

	        	$action = $request->post('action');

	        	$productId = $request->post('product');
	        	$paperSizeId = $request->post('paperSize');
	        	$paperGsmId = $request->post('paperGsm');
	        	$paperTypeId = $request->post('paperType');
	        	$paperSides = $request->post('paperSides');
	        	$color = $request->post('color');
	        	$binding = $request->post('binding');
	        	$lamination = $request->post('lamination');
	        	$cover = $request->post('cover');
	        	$qty = $request->post('noOfPages');
	        	$noOfCopies = $request->post('noOfCopies');
	        	$isShippingFree = $request->post('shippingFree');

	        	//check shipping
	        	$shippingPincode = $request->post('shippingPincode');
	        	$isPincodeExist = ShippingModel::where(['pincode' => $shippingPincode, 'is_active' => 1])->first();

	        	if (!empty($isPincodeExist) && $isPincodeExist->count()) {
	        		
	        		//check if customer address exist
	        		$userId = $request->post('customer');
	        		$getCustomerAdd = CustomerAddressModel::where('user_id', $userId)->first();

	        		//Remove shipping session
	        		Session::forget('adminShippingSess');

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

	        		if (!empty($getSavedCartItem) && $getSavedCartItem->count()) {
	        			
	        			$productPrice = productPriceForCustomOrderMulti($customerId);

	        			$totalWeight = cartWeightForCustomOrderMulti($customerId);

	        		} else {
	        			
	        			$productPrice = productPriceForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover, $qty, $noOfCopies);

	        			$totalWeight = cartWeightForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover, $qty, $noOfCopies);

	        		}

	        		$totalAmount = $productPrice->total;

	        		$totalWeightInGm = $totalWeight*1000;

	        		$additionalDiscount = $request->post('additionalDiscount');

	        		if ($additionalDiscount) {
	        			$totalAmount = $totalAmount-$additionalDiscount;
	        		}


	        		$shipping = 0;

	        		//check free shipping
	        		if (!$isShippingFree) {
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
	        		}

	        		$shippingSessObj = [
	        			'pincode' => $request->post('shippingPincode'),
	        			'shipping' => $shipping
	        		];

	        		$request->session()->put('adminShippingSess', $shippingSessObj);

	        		if (!empty($getSavedCartItem) && $getSavedCartItem->count()) {
	        			
	        			$priceData = productPriceForCustomOrderMulti($customerId);

	        		} else {
	        			
	        			$priceData = productPriceForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover, $qty, $noOfCopies);

	        		}

	        		//check action
	        		if ($action == 'calculate') {

		        		$this->status = array(
							'error' => false,
							'priceData' => $priceData,
							'totalWeight' => number_format($totalWeight, 2),
							'additionalDiscount' => $additionalDiscount,
						);

		        	} elseif ($action == 'save') {

	        			$cartObj = [
        					'user_id' => $userId,
        					'product_id' => $request->post('product'),
        					'paper_size_id' => $request->post('paperSize'),
        					'paper_gsm_id' => $request->post('paperGsm'),
        					'paper_type_id' => $request->post('paperType'),
        					'print_side' => $request->post('paperSides'),
        					'color' => $request->post('color'),
        					'binding_id' => $request->post('binding'),
        					'lamination_id' => $request->post('lamination'),
        					'cover_id' => $request->post('cover'),
        					'qty' => $request->post('noOfPages'),
        					'no_of_copies' => $request->post('noOfCopies'),
        				];
		        		
		        		$condition = ['user_id' => $userId, 'product_id' => $request->post('product')];
		        		$isCartDataExist = CustomCartModel::where($condition)->count();

		        		if (!$isCartDataExist) {
		        			$isAdded = CustomCartModel::create($cartObj);
		        		} else {
		        			$isAdded = CustomCartModel::where($condition)->update($cartObj);
		        		}

		        		if ($isAdded) {

		        			$saveProductList = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
							->where('user_id', $userId)
							->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
							->latest()
							->get();

			        		$saveProductListTemp = view('admin/components/savedProductListData', compact('saveProductList'))->render();

        					$this->status = array(
								'error' => false,
								'msg' => 'The product has been added.',
								'priceData' => $priceData,
								'totalWeight' => number_format($totalWeight, 2),
								'additionalDiscount' => $additionalDiscount,
								'saveProductListTemp' => $saveProductListTemp
							);

        				} else {
        					$this->status = array(
								'error' => true,
								'eType' => 'final',
								'msg' => 'Something went wrong.'
							);
        				}
		        		

		        	} else {
		        		
		        		/*
							1. Upload Documents
							2. Create Object
							3. Save Data
		        		*/

						$customerId = $userId;
						$files = $request->file('uploadDocuments');

	        			if (!empty($files)) {
	        				
	        				//Gdrive
							$googleDriveService = new GoogleDriveService();

							$uploadedFileList = [];
			        		$year = date('Y');
				        	$month = date('m');
				        	$date = date('d');

				        	foreach ($files as $file) {

				        		$uniqueId = md5(microtime());
				        		
				        		$originalName = $file->getClientOriginalName();
				        		$originalNameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

				        		$ext = $file->extension();
				        		//$size = formatSize($file->getSize());
				        		$size = $file->getSize();

				        		$nameWithoutExtSlugify = Str::slug($originalNameWithoutExt.'-'.$uniqueId);
					        	$finalName = $nameWithoutExtSlugify.'.'.$ext;

					        	$craftPath = $year.'/'.$month.'/'.$date;
								$path = $year.'/'.$month.'/'.$date. '/' . $finalName;
								//$filesystem->write($path, file_get_contents($file->getRealPath()));

								//$filePath = $file->storeAs($craftPath, $finalName);
								//$folderId = '1AgOwXplcpb1Y1xW-MYQ6FAgDhP_mC3Sw';
								//$folderId = '1KYl_BlpStYJRqTg7-yMELsvEx8dqWFBu';
								$folderId = '1pZDVfPNcFMP_2ciyW-35bYzYCxHZHess';
								//$fileId = $googleDriveService->uploadFile(storage_path("app/{$filePath}"), $folderId);

								$fileContent = $file->get();
								$fileId = $googleDriveService->uploadFileContent($fileContent, $folderId, $finalName);

								//$uploadedFileList[] = $path;
								$uploadedFileList[] = [
									'fileName' => $finalName,
									'fileId' => $fileId
								];

				        	}

				        	if (!empty($uploadedFileList)) {

				        		//get stored document if any from the documents session
				        		$storedDocs = Session::get('adminDocuments');

				        		if (!empty($storedDocs)) {
				        			
				        			$oldDocs = json_decode($storedDocs);
				        			$newDocs = $uploadedFileList;

				        			$fileList = array_merge($oldDocs, $newDocs);
				        			$fileList = json_encode($fileList);

				        		} else {
				        			$fileList = json_encode($uploadedFileList);
				        		}

				        	}

	        			}

						$productPrice = $priceData;

						$couponCode = null;
			        	$discount = 0;

			        	$couponData = Session::get('adminCouponSess');

			        	if (!empty($couponData)) {
			        		$couponCode = $couponData['coupon_code'];
			        		$discount = $couponData['discount'];
			        	}	 

			        	$shipping = 0;

			        	$shippingData = Session::get('adminShippingSess');
			        	if (!empty($shippingData)) {
			        		$shipping = $shippingData['shipping'];
			        	}

			        	$customerAdd = CustomerAddressModel::where('user_id', $userId)->first();

			        	$orderObj = array(
			        		'admin_id' => adminId(),
			        		'order_id' => uniqid(),
			        		'user_id' => $userId,
			        		// 'product_id' => $productId,
			        		// 'product_name' => $productName,
			        		// 'product_details' => json_encode(productSpecForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover)),
			        		// 'weight_details' => json_encode($totalWeight),
			        		'coupon_code' => $couponCode,
			        		'discount' => $discount,
			        		'shipping' => $shipping,
			        		// 'paid_amount' => ceil($productPrice->total),
			        		// 'paid_amount' => $productPrice->total,
			        		//'paid_amount' => $totalAmount,
			        		'paid_amount' => $priceData->total-$additionalDiscount,
			        		//'price_details' => json_encode($productPrice),
			        		'transaction_details' => null,
			        		'customer_address' => json_encode($customerAdd->toArray()),
			        		//'document_link' => null,
			        		//'qty' => $qty,
			        		//'no_of_copies' => $noOfCopies,
			        		'document_link' => isset($fileList)? $fileList:null,
			        		'status' => $request->post('status'),
			        		'remark' => $request->post('remark'),
			        		'additional_discount' => $additionalDiscount,
			        		'is_shipping_free' => $request->post('shippingFree')? 1:0,
			        		'courier' => $request->post('courier'),
			        		'payment_method' => $request->post('paymentMethod'),
			        	);

			        	if ($request->post('status') == 'paid') {
			        		
			        		// $barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();

			        		// if (!empty($barcode)) {
				        	// 	$orderObj['shipping_label_number'] = $barcode->barcode;
				        	// }

			        	}

			        	$isOrderCreated = OrderModel::create($orderObj);

			        	if ($isOrderCreated) {

			        		//check if single or multiple order

			        		if (!empty($getSavedCartItem) && $getSavedCartItem->count()) {
			        			

			        			foreach ($getSavedCartItem as $cartData) {

					    			$productName = ProductModel::where('id', $cartData->product_id)->value('name');

					    			$productDetailIds = [
					        			'paperSizeId' => $cartData->paper_size_id,
					        			'paperGsmId' => $cartData->paper_gsm_id,
					        			'paperTypeId' => $cartData->paper_type_id,
					        			'paperSides' => $cartData->print_side,
					        			'color' => $cartData->color,
					        			'binding' => $cartData->binding_id,
					        			'lamination' => $cartData->lamination_id,
					        			'cover' => $cartData->cover_id,
					        		];
					    			
					    			$orderItemObj = array(
					    				'order_id' => $isOrderCreated->id,
					    				'product_id' => $cartData->product_id,
					    				'product_name' => $productName,
					    				'qty' => $cartData->qty,
					    				'no_of_copies' => $cartData->no_of_copies,
					    				'product_details' => json_encode(productSpecForCustomOrder($cartData->product_id, $cartData->paper_size_id, $cartData->paper_gsm_id, $cartData->paper_type_id, $cartData->print_side, $cartData->color, $cartData->binding_id, $cartData->lamination_id, $cartData->cover_id)),
					    				'product_detail_ids' => json_encode($productDetailIds),
					    				'weight_details' => json_encode(cartWeightSingleCusOrder($cartData->id, $cartData->user_id)),
	    								'price_details' => json_encode(productSinglePriceForCusOrder($cartData->product_id, $cartData->user_id)),
					    			);

					    			OrderItemModel::create($orderItemObj);

					    		}


			        		} else {

			        			$productName = ProductModel::where('id', $productId)->value('name');
			        			
			        			$productDetailIds = [
				        			'paperSizeId' => $paperSizeId,
				        			'paperGsmId' => $paperGsmId,
				        			'paperTypeId' => $paperTypeId,
				        			'paperSides' => $paperSides,
				        			'color' => $color,
				        			'binding' => $binding,
				        			'lamination' => $lamination,
				        			'cover' => $cover,
				        		];

				        		$orderItemObj = array(
				        			'order_id' => $isOrderCreated->id,
				        			'product_id' => $productId,
				        			'product_name' => $productName,
				        			'qty' => $qty,
				        			'no_of_copies' => $noOfCopies,
				        			'product_details' => json_encode(productSpecForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover)),
				        			'product_detail_ids' => json_encode($productDetailIds),
				        			'weight_details' => json_encode($totalWeight),
				        			'price_details' => json_encode($productPrice),
				        		);

				        		OrderItemModel::create($orderItemObj);

			        		}
			        		

			        		if ($request->post('status') == 'paid') {
				        		
				        		//update barcode once used
				        		// if (!empty($barcode)) {
				        		// 	BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);
				        		// }

				        		$getCustomer = CustomerModel::where('id', $userId)->first();

				        		//send SMS
				        		//SmsSending::orderPlaced($getCustomer->phone, $getCustomer->name);

				        		//send email
								// $getOrder = OrderModel::
								// join('product', 'orders.product_id', '=', 'product.id')
								// ->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
								// ->where('orders.id', $isOrderCreated->id)->first();

								// EmailSending::orderEmail($getOrder);
								//EmailSending::orderEmailNew($isOrderCreated->id);

				        	}

			        	}

			        	CustomCartModel::where('user_id', $userId)->delete();
			        	Session::forget('adminShippingSess');
		        		Session::forget('adminCouponSess');
		        		Session::forget('adminDocuments');

		        		$this->status = array(
							'error' => false,
							'msg' => 'The order has been created successfully.'
						);

		        	}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',						
						'msg' => 'The delivery is not available on this pincode'
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

	public function doUpdate(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$orderId = $request->post('orderId');
			$getOrderItems = OrderItemModel::where('order_id', $orderId)->get();

			$obj = [
				'orderId' => 'required|exists:orders,id',
			    'customer' => 'required|exists:customer,id',
			    'status' => 'required|in:paid,unpaid',
			    'shippingFree' => 'required|in:1,0',
			    'shippingName' => 'required',
				'companyName' => 'sometimes|nullable',
				'gstNumber' => 'sometimes|nullable',
				'shippingAddress' => 'required',
				'shippingCity' => 'required',
	            'shippingState' => 'required',
	            'shippingPincode' => 'required|numeric|digits:6',
	            'shippingEmail' => 'required|email',
	            'shippingPhone' => 'required|numeric',
	            // 'uploadDocuments.*' => 'required|file|mimes:png,jpg,jpeg,pdf,zip|max:190000',
	            'uploadDocuments.*' => 'sometimes|nullable|file|mimes:png,jpg,jpeg,pdf,zip,doc,docx|max:200000',
	            'remark' => 'sometimes|nullable',
	            'additionalDiscount' => 'sometimes|nullable|numeric',
	            'courier' => 'required',
	            'paymentMethod' => 'required|in:Payu,Phonepe,QR,NEFT/IMPS',
			];

			$prodValidation = false;

			if (!empty($getOrderItems) && !$getOrderItems->count()) {
				$prodValidation = true;
			} elseif (!empty($request->post('product'))) {
				$prodValidation = true;
			} elseif ($request->post('action') == 'save') {
				$prodValidation = true;
			}

			if ($prodValidation) {
				$obj['product'] = 'required|exists:product,id';
			    $obj['paperSize'] = 'required|numeric';
			    $obj['paperGsm'] = 'required|numeric';
			    $obj['paperType'] = 'required|numeric';
			    $obj['paperSides'] = 'required';
			    $obj['color'] = 'required';
			    $obj['binding'] = 'sometimes|nullable|numeric';
			    $obj['lamination'] = 'sometimes|nullable|numeric';
			    $obj['cover'] = 'sometimes|nullable|numeric';
			    $obj['noOfPages'] = 'required|numeric|min:1';
			    $obj['noOfCopies'] = 'required|numeric|min:1';	
			}

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

	        	//check order id
	        	$orderId = $request->post('orderId');

	        	$getOrder = OrderModel::where('id', $orderId)->first();

	        	if (!empty($getOrder) && $getOrder->count()) {
	        		
	        		if ($getOrder->status == 'unpaid') {

	        			$action = $request->post('action');
			        	$productId = $request->post('product');
			        	$paperSizeId = $request->post('paperSize');
			        	$paperGsmId = $request->post('paperGsm');
			        	$paperTypeId = $request->post('paperType');
			        	$paperSides = $request->post('paperSides');
			        	$color = $request->post('color');
			        	$binding = $request->post('binding');
			        	$lamination = $request->post('lamination');
			        	$cover = $request->post('cover');
			        	$qty = $request->post('noOfPages');
			        	$noOfCopies = $request->post('noOfCopies');
			        	$isShippingFree = $request->post('shippingFree');

			        	//check shipping
			        	$shippingPincode = $request->post('shippingPincode');
			        	$isPincodeExist = ShippingModel::where(['pincode' => $shippingPincode, 'is_active' => 1])->first();

			        	if (!empty($isPincodeExist) && $isPincodeExist->count()) {
			        		
			        		//check if customer address exist
			        		$userId = $request->post('customer');
			        		$getCustomerAdd = CustomerAddressModel::where('user_id', $userId)->first();

			        		//Remove shipping session
			        		Session::forget('adminShippingSess');

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

			        		if (!empty($getOrderItems) && $getOrderItems->count()) {
        			
			        			$productPrice = productPriceForSaveCustomOrderMulti($orderId);
			        			$totalWeight = cartWeightForSaveCustomOrderMulti($orderId);

			        		} elseif (!empty($request->post('product'))) {
								
								$productPrice = productPriceForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover, $qty, $noOfCopies);

			        			$totalWeight = cartWeightForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover, $qty, $noOfCopies);

							}
			        		
			        		$totalAmount = $productPrice->total;
			        		$totalWeightInGm = $totalWeight*1000;

			        		$additionalDiscount = $request->post('additionalDiscount');

			        		if ($additionalDiscount) {
			        			$totalAmount = $totalAmount-$additionalDiscount;
			        		}

			        		$shipping = 0;

			        		//check free shipping
			        		if (!$isShippingFree) {
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
			        		}

			        		$shippingSessObj = [
			        			'pincode' => $request->post('shippingPincode'),
			        			'shipping' => $shipping
			        		];

			        		$request->session()->put('adminShippingSess', $shippingSessObj);

			        		if (!empty($getOrderItems) && $getOrderItems->count()) {
        			
			        			$priceData = productPriceForSaveCustomOrderMulti($orderId);

			        		} else {
			        			
			        			$priceData = productPriceForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover, $qty, $noOfCopies);

			        		}

			        		if ($action == 'calculate') {

			        			//update amount				        		
				        		$newPaidAmount = ($priceData->subTotal+$shipping)-$additionalDiscount;

			        			OrderModel::where('id', $orderId)->update([
			        				'paid_amount' => $newPaidAmount,
			        				'shipping' => $shipping,
			        				'additional_discount' => $additionalDiscount,
			        				'is_shipping_free' => ($isShippingFree)? 1:0,
			        			]);

			        			if (!empty($getOrderItems) && $getOrderItems->count()) {
				        			$priceData = productPriceForSaveCustomOrderMulti($orderId);
				        		} else {
				        			$priceData = productPriceForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover, $qty, $noOfCopies);
				        		}

				        		$this->status = array(
									'error' => false,
									'priceData' => $priceData,
									'totalWeight' => number_format($totalWeight, 2),
									'additionalDiscount' => $additionalDiscount,
								);

				        	} elseif ($action == 'save') {

				        		$orderId = $request->post('orderId');
				        		$productId = $request->post('product');

				        		//check if product id exist
				        		$isProdItemExist = OrderItemModel::where(['order_id' => $orderId, 'product_id' => $productId])->count();

				        		if ($isProdItemExist) {
				        			$this->status = array(
										'error' => true,
										'eType' => 'final',
										'msg' => 'The product is already exist.'
									);
									echo json_encode($this->status);
									die();
				        		}

				        		$productDetailIds = [
				        			'paperSizeId' => $request->post('paperSize'),
				        			'paperGsmId' => $request->post('paperGsm'),
				        			'paperTypeId' => $request->post('paperType'),
				        			'paperSides' => $request->post('paperSides'),
				        			'color' => $request->post('color'),
				        			'binding' => $request->post('binding'),
				        			'lamination' => $request->post('lamination'),
				        			'cover' => $request->post('cover'),
				        		];

				        		$productName = ProductModel::where('id', $request->post('product'))->value('name');
				    			
				    			$orderItemObj = array(
				    				'order_id' => $orderId,
				    				'product_id' => $request->post('product'),
				    				'product_name' => $productName,
				    				'qty' => $request->post('noOfPages'),
				    				'no_of_copies' => $request->post('noOfCopies'),
				    				'product_details' => json_encode(productSpecForCustomOrder($request->post('product'), $request->post('paperSize'), $request->post('paperGsm'), $request->post('paperType'), $request->post('paperSides'), $request->post('color'), $request->post('binding'), $request->post('lamination'), $request->post('cover'))),
				    				'product_detail_ids' => json_encode($productDetailIds),
				    				'weight_details' => json_encode(cartWeightSingleSaveCusOrder($request->post('paperSize'), $request->post('paperGsm'), $request->post('noOfPages'), $request->post('noOfCopies'))),
    								'price_details' => json_encode(productSinglePriceForSaveCusOrderUpdate($request->post('product'), $productDetailIds, $request->post('noOfPages'),$request->post('noOfCopies'))),
				    			);
				        		
				        		$isAdded = OrderItemModel::create($orderItemObj);

				        		if ($isAdded) {

				        			//get weight and sub total
				        			$priceData = productPriceForSaveCustomOrderMulti($orderId);
						        	$totalWeight = cartWeightForSaveCustomOrderMulti($orderId);

						        	$totalAmount = $priceData->subTotal;
					        		$totalWeightInGm = $totalWeight*1000;

					        		$additionalDiscount = $request->post('additionalDiscount');

					        		if ($additionalDiscount) {
					        			$totalAmount = $totalAmount-$additionalDiscount;
					        		}

					        		$shipping = 0;
					        		$isShippingFree = $request->post('shippingFree');
					        		$shippingPincode = $request->post('shippingPincode');
				        			$isPincodeExist = ShippingModel::where(['pincode' => $shippingPincode, 'is_active' => 1])->first();

					        		//check free shipping
					        		if (!$isShippingFree) {
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
					        		}

					        		$updateOrderObj = array(
						        		'shipping' => $shipping,
						        		'paid_amount' => $totalAmount+$shipping,
						        		'additional_discount' => $additionalDiscount,
						        		'is_shipping_free' => $request->post('shippingFree')? 1:0,
						        	);

						        	OrderModel::where(['id' => $orderId])->update($updateOrderObj);

						        	$this->status = array(
										'error' => false,
										'msg' => 'Product has been added.'
									);

			    				} else {
			    					$this->status = array(
										'error' => true,
										'eType' => 'final',
										'msg' => 'Something went wrong.'
									);
			    				}
				        		

				        	} else {
				        		
				        		/*
									1. Upload Documents
									2. Create Object
									3. Save Data
				        		*/

								$files = $request->file('uploadDocuments');
			        			$customerId = $userId;

			        			$uploadedFileList = [];

			        			if (!empty($files)) {
			        				
			        				//Gdrive
									$googleDriveService = new GoogleDriveService();

					        		$year = date('Y');
						        	$month = date('m');
						        	$date = date('d');

						        	foreach ($files as $file) {

						        		$uniqueId = md5(microtime());
						        		
						        		$originalName = $file->getClientOriginalName();
						        		$originalNameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

						        		$ext = $file->extension();
						        		//$size = formatSize($file->getSize());
						        		$size = $file->getSize();

						        		$nameWithoutExtSlugify = Str::slug($originalNameWithoutExt.'-'.$uniqueId);
							        	$finalName = $nameWithoutExtSlugify.'.'.$ext;

							        	$craftPath = $year.'/'.$month.'/'.$date;
										$path = $year.'/'.$month.'/'.$date. '/' . $finalName;
										//$filesystem->write($path, file_get_contents($file->getRealPath()));

										//$filePath = $file->storeAs($craftPath, $finalName);
										//$folderId = '1AgOwXplcpb1Y1xW-MYQ6FAgDhP_mC3Sw';
										//$folderId = '1KYl_BlpStYJRqTg7-yMELsvEx8dqWFBu';
										$folderId = '1pZDVfPNcFMP_2ciyW-35bYzYCxHZHess';
										//$fileId = $googleDriveService->uploadFile(storage_path("app/{$filePath}"), $folderId);

										$fileContent = $file->get();
										$fileId = $googleDriveService->uploadFileContent($fileContent, $folderId, $finalName);

										//$uploadedFileList[] = $path;
										$uploadedFileList[] = [
											'fileName' => $finalName,
											'fileId' => $fileId
										];

						        	}

			        			}			        			

					        	if (!empty($uploadedFileList)) {

					        		//get stored document if any from the documents session
					        		$storedDocs = Session::get('adminDocuments');

					        		if (!empty($storedDocs)) {
					        			
					        			$oldDocs = json_decode($storedDocs);
					        			$newDocs = $uploadedFileList;

					        			$fileList = array_merge($oldDocs, $newDocs);
					        			$fileList = json_encode($fileList);

					        		} else {
					        			$fileList = json_encode($uploadedFileList);
					        		}

					        	}


								$productPrice = $priceData;

								$couponCode = null;
					        	$discount = 0;

					        	$couponData = Session::get('adminCouponSess');

					        	if (!empty($couponData)) {
					        		$couponCode = $couponData['coupon_code'];
					        		$discount = $couponData['discount'];
					        	}	 

					        	$shipping = 0;

					        	$shippingData = Session::get('adminShippingSess');
					        	if (!empty($shippingData)) {
					        		$shipping = $shippingData['shipping'];
					        	}

					        	$customerAdd = CustomerAddressModel::where('user_id', $userId)->first();
					        	$productName = ProductModel::where('id', $productId)->value('name');

					        	$orderObj = array(
					        		'order_id' => $getOrder->order_id,
					        		'user_id' => $userId,
					        		// 'product_id' => $productId,
					        		// 'product_name' => $productName,
					        		// 'product_details' => json_encode(productSpecForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover)),
					        		// 'weight_details' => json_encode($totalWeight),
					        		'coupon_code' => $couponCode,
					        		'discount' => $discount,
					        		'shipping' => $shipping,
					        		// 'paid_amount' => ceil($productPrice->total),
					        		// 'paid_amount' => $totalAmount,
					        		'paid_amount' => $priceData->total-$additionalDiscount,
					        		//'price_details' => json_encode($productPrice),
					        		'transaction_details' => null,
					        		'customer_address' => json_encode($customerAdd->toArray()),
					        		//'document_link' => null,
					        		//'qty' => $qty,
					        		//'no_of_copies' => $noOfCopies,
					        		'document_link' => isset($fileList)? $fileList:$getOrder->document_link,
					        		'is_shipping_free' => $request->post('shippingFree')? 1:0,
					        		'status' => $request->post('status'),
					        		'remark' => $request->post('remark'),
									'additional_discount' => $additionalDiscount,
									'courier' => $request->post('courier'),
									'payment_method' => $request->post('paymentMethod'),
					        	);

					        	if ($request->post('status') == 'paid') {
					        		
					        		// $barcode = BarcodeModel::where(['is_active' => 1, 'is_used' => 0])->first();

					        		// if (!empty($barcode)) {
						        	// 	$orderObj['shipping_label_number'] = $barcode->barcode;
						        	// }

					        	}

					        	$isOrderUpdated = OrderModel::where('id', $getOrder->id)->update($orderObj);

					        	// $productDetailIds = [
				        		// 	'paperSizeId' => $paperSizeId,
				        		// 	'paperGsmId' => $paperGsmId,
				        		// 	'paperTypeId' => $paperTypeId,
				        		// 	'paperSides' => $paperSides,
				        		// 	'color' => $color,
				        		// 	'binding' => $binding,
				        		// 	'lamination' => $lamination,
				        		// 	'cover' => $cover,
				        		// ];

				        		// $orderItemObj = array(
				        		// 	'order_id' => $getOrder->id,
				        		// 	'product_id' => $productId,
				        		// 	'product_name' => $productName,
				        		// 	'qty' => $qty,
				        		// 	'no_of_copies' => $noOfCopies,
				        		// 	'product_details' => json_encode(productSpecForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $paperSides, $color, $binding, $lamination, $cover)),
				        		// 	'product_detail_ids' => json_encode($productDetailIds),
				        		// 	'weight_details' => json_encode($totalWeight),
				        		// 	'price_details' => json_encode($productPrice),
				        		// );

				        		// OrderItemModel::where('order_id', $getOrder->id)->update($orderItemObj);

				        		if ($request->post('status') == 'paid') {
					        		
					        		//update barcode once used
					        		// if (!empty($barcode)) {
					        		// 	BarcodeModel::where('id', $barcode->id)->update(['is_used' => 1]);
					        		// }

					        		$getCustomer = CustomerModel::where('id', $userId)->first();

					        		//send SMS
					        		//SmsSending::orderPlaced($getCustomer->phone, $getCustomer->name);

					        		//send email
									// $getOrder = OrderModel::
									// join('product', 'orders.product_id', '=', 'product.id')
									// ->select('orders.*', 'product.registered_hsn_code', 'product.unregistered_hsn_code')
									// ->where('orders.id', $isOrderCreated->id)->first();

									// EmailSending::orderEmail($getOrder);
									//EmailSending::orderEmailNew($getOrder->id);

					        	}

					        	Session::forget('adminShippingSess');
				        		Session::forget('adminCouponSess');
				        		Session::forget('adminDocuments');

				        		$this->status = array(
									'error' => false,
									'msg' => 'The order has been updated successfully.'
								);

				        	}

			        	} else {
			        		$this->status = array(
								'error' => true,
								'eType' => 'final',						
								'msg' => 'The delivery is not available on this pincode'
							);
			        	}

	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Only unpaid orders can be update.'
						);
	        		}

	        	} else {
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Unable to find an order.'
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

	public function doUpdateCustomCart(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$obj = [
				'userId' => 'required|exists:customer,id',
			    'cartId' => 'required|exists:custom_cart,id',
			    'action' => 'required',
			];

			if ($request->post('action') != 'delete') {
				$obj['value'] = 'required';
			}

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

	        	$action = $request->post('action');
	        	$userId = $request->post('userId');
	        	$cartId = $request->post('cartId');
	        	$value = $request->post('value');

	        	//check if data exist
	        	$getData = CustomCartModel::where(['id' => $cartId, 'user_id' => $userId])->first();

	        	if (!empty($getData) && $getData->count()) {
	        		
	        		if ($action == 'qty') {
	        			$obj = ['qty' => $value];
	        		} elseif ($action == 'copies') {
	        			$obj = ['no_of_copies' => $value];
	        		}

	        		if ($action != 'delete') {
	        			$isUpdated = CustomCartModel::where(['id' => $cartId, 'user_id' => $userId])->update($obj);
	        		} else {
	        			$isUpdated = CustomCartModel::where(['id' => $cartId, 'user_id' => $userId])->delete();
	        		}

	        		if ($isUpdated) {

	        			$saveProductList = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
							->where('user_id', $userId)
							->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
							->latest()
							->get();

			        	$saveProductListTemp = view('admin/components/savedProductListData', compact('saveProductList'))->render();
	        			
	        			$this->status = array(
							'error' => false,
							'saveProductListTemp' => $saveProductListTemp,
							'msg' => 'The product data has been updated.'
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

	public function doUpdateSaveCustomOrder(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$obj = [
				'orderId' => 'required',
			    'id' => 'required',
			    'action' => 'required',
			    'isShippingFree' => 'required|in:0,1',
			    'additionalDiscount' => 'numeric|sometimes|nullable',
			];

			if ($request->post('action') != 'delete') {
				$obj['value'] = 'required';
			}

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

	        	$action = $request->post('action');
	        	$orderId = $request->post('orderId');
	        	$id = $request->post('id');
	        	$value = $request->post('value');

	        	//check if data exist
	        	$orderData = OrderModel::where(['id' => $orderId])->first();
	        	$getData = OrderItemModel::where(['id' => $id, 'order_id' => $orderId])->first();

	        	$totalSubItems = OrderItemModel::where('order_id', $orderId)->count();

	        	if (!empty($getData) && $getData->count()) {

	        		$customerAddress = json_decode($orderData->customer_address);
	        		
	        		if ($action == 'qty') {
	        			$obj = ['qty' => $value];
	        		} elseif ($action == 'copies') {
	        			$obj = ['no_of_copies' => $value];
	        		}

	        		$msg = "Something went wrong.";

	        		if ($action != 'delete') {

	        			$isUpdated = OrderItemModel::where(['id' => $id, 'order_id' => $orderId])->update($obj);

	        		} else {
	        			if ($totalSubItems >= 2) {
	        				$isUpdated = OrderItemModel::where(['id' => $id, 'order_id' => $orderId])->delete();
	        			} else {
	        				$isUpdated = false;
	        				$msg = "Cannot delete all items from the order.";
	        			}
	        		}

	        		if ($isUpdated) {

	        			//get weight and sub total
	        			$priceData = productPriceForSaveCustomOrderMulti($orderId);
			        	$totalWeight = cartWeightForSaveCustomOrderMulti($orderId);

			        	$totalAmount = $priceData->subTotal;
		        		$totalWeightInGm = $totalWeight*1000;

		        		$additionalDiscount = $request->post('additionalDiscount');

		        		if ($additionalDiscount) {
		        			$totalAmount = $totalAmount-$additionalDiscount;
		        		}

		        		$shipping = 0;
		        		$isShippingFree = $request->post('isShippingFree');

		        		$shippingPincode = $customerAddress->shipping_pincode;
	        			$isPincodeExist = ShippingModel::where(['pincode' => $shippingPincode, 'is_active' => 1])->first();

		        		//check free shipping
		        		if (!$isShippingFree) {
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
		        		}

		        		$updateOrderObj = array(
			        		'shipping' => $shipping,
			        		'paid_amount' => $totalAmount+$shipping,
			        		'additional_discount' => $additionalDiscount,
			        		'is_shipping_free' => $request->post('isShippingFree')? 1:0,
			        	);

			        	OrderModel::where(['id' => $orderId])->update($updateOrderObj);
	        			
	        			$this->status = array(
							'error' => false,
							'msg' => 'The product data has been updated.'
						);

	        		} else {
	        			$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => $msg
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

		echo json_encode($this->status);
	}

	public function doSendInvoice(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'orders')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$obj = [
				'orderId' => 'required',
			    'action' => 'required|in:sms,email',
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

	        	$action = $request->post('action');
	        	$orderId = $request->post('orderId');
	        	$orderData = OrderModel::where(['id' => $orderId, 'status' => 'paid'])->first();

	        	if (!empty($orderData) && $orderData->count()) {

	        		if ($action == 'SMS') {
	        			$getCustomer = CustomerModel::where('id', $orderData->user_id)->first();
	        			$isSent = SmsSending::orderPlaced($getCustomer->phone, $getCustomer->name);
	        			$msg = 'The SMS has been sent';
	        		} else {
	        			$isSent = EmailSending::orderEmailNew($orderId);
	        			$msg = 'The Email has been sent';
	        		}

	        		if ($isSent) {
	        			$this->status = array(
							'error' => false,
							'msg' => $msg
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

}