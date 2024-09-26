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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\OrderModel;
use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\CustomerAddressModel;
use App\Models\WalletHistoryModel;


class Customers extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'customers')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Customers',
			'pageTitle' => 'Customers',
			'menu' => 'customer',
		);

		return view('admin/customers/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'customers')){
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

		    $singleDelUrl = '';

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
		    $totalRecords = CustomerModel::select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = CustomerModel::select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.email', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.address', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('orders.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('orders.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = CustomerModel::skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {

			        $query->where('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.email', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.address', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%');

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
		    	$records->orderBy('customer.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $viewUrl = route('adminViewCustomer', $id);
			        $loginUrl = route('adminUserLogin', ['id' => $id]);

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
										<a title="ViewOrder" href="'.$viewUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-eye fs-2"></i></span>
										</a>
									</div>

									<div class="menu-item">
										<a title="ViewOrder" href="'.$loginUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-lock fs-2"></i></span>
										</a>
									</div>

								</div>
							</div>
						</div>
					</td>';				

			        $data_arr[] = array(
			        	"checkbox" => $checkbox,
			          	"name" => $record->name,
			          	"email" => $record->email,
			          	"phone" => $record->phone,
			          	"address" => $record->address,
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

		if (!can('create', 'customers')){
			return redirect(route('adminDashboard'));
		}

		$stateList = DB::table('states')->orderBy('state')->get();

		$data = array(
			'title' => 'Customers',
			'pageTitle' => 'Customers',
			'menu' => 'customer',
			'stateList' => $stateList
		);

		return view('admin/customers/add', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'customers')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

	        $validator = Validator::make($request->post(), [
			    'name' => 'required|string',
			    'email' => 'required|email|unique:customer,email',
			    'phoneNumber' => 'required|numeric|digits:10|unique:customer,phone',
			    'address' => 'required',
			    'city' => 'required',
			    'state' => 'required',
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

	        	$obj = [
	        		'name' => $request->post('name'),
	        		'email' => $request->post('email'),
	        		'phone' => $request->post('phoneNumber'),
	        		'address' => $request->post('address'),
	        		'city' => $request->post('city'),
	        		'state' => $request->post('state'),
	        		'password' => Hash::make($request->post('password')),
	        	];

	        	$isAdded = CustomerModel::create($obj);

	        	if ($isAdded) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Customer has been added successfully.'
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

		echo json_encode($this->status);
	}

	public function doAddWalletAmount(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'customers')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

	        $validator = Validator::make($request->post(), [
			    'customerId' => 'required|numeric|exists:customer,id',
			    'amount' => 'required|numeric',
			    'type' => 'required|in:credit,debit',
			    'narration' => 'sometimes|nullable',
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

	        	$amount = $request->post('amount');
	        	$transferType = $request->post('type');

	        	$credit = $transferType == 'credit'? $amount:0;
	        	$debit = $transferType == 'debit'? $amount:0;
	        	$userId = $request->post('customerId');

	        	$obj = [
	        		'admin_id' => adminId(),
	        		'user_id' => $userId,
	        		'debit' => $debit,
	        		'credit' => $credit,
	        		'narration' => $request->post('narration')
	        	];

	        	$isAdded = WalletHistoryModel::create($obj);

	        	if ($isAdded) {

	        		if ($transferType == 'credit') {
						CustomerModel::where('id', $userId)->increment('wallet_amount', $amount);
	        		} else {
	        			CustomerModel::where('id', $userId)->decrement('wallet_amount', $amount);
	        		}

    				$this->status = array(
						'error' => false,								
						'msg' => 'Customer has been added successfully.'
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

		echo json_encode($this->status);
	}

	public function view(Request $request, $id) {

		if (!can('read', 'customers')){
			return redirect(route('adminDashboard'));
		}

		$customer = CustomerModel::where('id', $id)->first();

		if (!empty($customer) && $customer->count()){

			$userId = $customer->id;
			$customerAddress = CustomerAddressModel::where('user_id', $userId)->first();
			$getAdminData = adminInfo();
			$walletHistory = WalletHistoryModel::where('user_id', $userId)->latest()->get();

			$data = array(
				'title' => 'Customer Detail',
				'pageTitle' => 'Customer Detail',
				'menu' => 'customers',
				'customer' => $customer,
				'customerAddress' => $customerAddress,
				'walletHistory' => $walletHistory,
				'adminData' => $getAdminData,
			);

			return view('admin/customers/view', $data);
			
		} else {
			return redirect(route('adminCustomers'));
		}

	}

	public function export(Request $request) {

		if (!can('read', 'customers')){
			return redirect(route('adminDashboard'));
		}

		$customers = CustomerModel::orderBy('id', 'desc')->get();
		$adminData = adminInfo();

		if (!empty($customers) && $customers->count()) {

			$dataRows = [];
		
			foreach ($customers as $customer) {

				$phoneNumber = $customer->phone;
				$customerEmail = $customer->email;

				if($adminData->role_id != 1) {
					$phoneNumber = '';
					$customerEmail = '';
				}

				$dataRows[] = [
					$customer->name,
					$customerEmail,
					$phoneNumber,
					$customer->address,
					$customer->city,
					$customer->wallet_amount,
					date('d-m-Y h:i A', strtotime($customer->created_at)),
				];
			}

			$spreadsheet = new Spreadsheet();
			$headers = ['Name', 'Email', 'Phone', 'Address', 'City', 'Wallet Amount', 'Date'];
			$spreadsheet->getActiveSheet()->fromArray([$headers], null, 'A1');

			$spreadsheet->getActiveSheet()->fromArray($dataRows, null, 'A2');

			// Bold the first row
			foreach (range('A', $spreadsheet->getActiveSheet()->getHighestColumn()) as $column) {
			    $spreadsheet->getActiveSheet()->getStyle($column . '1')->getFont()->setBold(true);
			}

			$writer = new Xlsx($spreadsheet);
			$filePath = storage_path('app/temp_export.xlsx');
			$writer->save($filePath);
			return response()->download($filePath, 'customer-exports.xlsx')->deleteFileAfterSend(true);

		}

		

	}

	public function login(Request $request, $id) {
		$getCustomer = CustomerModel::where('id', $id)->first();
		if (!empty($getCustomer)) {

			$request->session()->put('customerSess', array(
    			'customerId' => $getCustomer->id
    		));

    		return redirect(route('cartPage'));
			
		} else {
			return redirect(route('adminCustomers'));
		}
	}

}