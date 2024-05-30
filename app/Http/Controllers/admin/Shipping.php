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

use App\Models\ShippingModel;
use App\Models\AdminModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;

class Shipping extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'shipping')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Shipping',
			'pageTitle' => 'Shipping',
			'menu' => 'shipping',
		);

		return view('admin/shipping/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'shipping')){
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

		    $singleDelUrl = route('adminDeleteShipping');

		    //get type
		    $columnIndex_arr = $request->get('order');
		    $columnName_arr = $request->get('columns');
		    $order_arr = $request->get('order');
		    $search_arr = $request->get('search');

		    $columnIndex = isset($columnIndex_arr[0]['column'])? $columnIndex_arr[0]['column']:''; // Column index
		    $columnName = !empty($columnIndex)? $columnName_arr[$columnIndex]['data']:''; // Column name
		    $columnSortOrder = !empty($order_arr)? $order_arr[0]['dir']:''; // asc or desc
		    $searchValue = $search_arr['value']; // Search value

		     // Total records
		    $totalRecords = ShippingModel::select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = ShippingModel::select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('shipping.pincode', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.under_500gm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.from500_1000gm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.from1000_2000gm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.from2000_3000gm', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('shipping.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('shipping.is_active', 'like', '%0%');
			        }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = ShippingModel::select('shipping.*')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        $query->where('shipping.pincode', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.under_500gm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.from500_1000gm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.from1000_2000gm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('shipping.from2000_3000gm', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('shipping.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('shipping.is_active', 'like', '%0%');
			        }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('shipping.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditShipping', $id);

			        $checkbox = '<div onclick="checkCheckbox(this)" class="form-check form-check-sm form-check-custom form-check-solid">
							<input name="delete[]" data-kt-check-target="#media .single-check-input" class="form-check-input" type="checkbox" value="'.$id.'" />
						</div>';

					$action = '
			          	<td class="text-end" data-kt-filemanager-table="action_dropdown">
						<div class="d-flex justify-content-end">
							<div class="ms-2">
								<div class="menu menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
									
									<div class="menu-item">
										<a title="Edit" href="'.$editUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-pencil fs-2"></i></span>
										</a>
									</div>
									
									<div class="menu-item">
										<a title="Delete" href="javascript:void(0)" data-url="'.$singleDelUrl.'" onclick="deleteData(this)" data-id="'.$id.'" class="menu-link text-danger px-3">
											<span class="menu-icon"><i class="ki-outline ki-trash fs-2"></i></span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</td>';

					if ($record->is_active) {
						$status = '<div class="badge badge-success">Active</div>';
					} else {
						$status = '<div class="badge badge-danger">Inactive</div>';
					}


			        $data_arr[] = array(
			        	"checkbox" => $checkbox,
			          	"pincode" => $record->pincode,
			          	"under 500gm" => $record->{'under_500gm'},
			          	"500-1000 gm" => $record->{'from500_1000gm'},
			          	"1000-2000 gm" => $record->{'from1000_2000gm'},
			          	"2000-3000 gm" => $record->{'from2000_3000gm'},
			          	"is_active" => $status,
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

		if (!can('create', 'shipping')){
			return redirect(route('adminShipping'));
		}

		$data = array(
			'title' => 'Shipping',
			'pageTitle' => 'Shipping',
			'menu' => 'shipping',
		);

		return view('admin/shipping/add', $data);
	}

	public function bulkImport(Request $request) {

		if (!can('create', 'shipping')){
			return redirect(route('adminShipping'));
		}

		$data = array(
			'title' => 'Shipping Bulk Import',
			'pageTitle' => 'Shipping Bulk Import',
			'menu' => 'shipping',
		);

		return view('admin/shipping/bulkImport', $data);
	}

	public function doShippingBulkImport(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'shipping')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->all(), [
	            'file' => 'required|mimes:xlsx,xls,application/excel,application/vnd.ms-excel,application/vnd.msexcel|max:50000',
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

	        	$file = $request->file('file');

	        	// echo "<pre>";

	        	// print_r($file);
	        	// die();


		        if ($request->file('file')->isValid()) {
		            $spreadsheet = IOFactory::load($file);
		            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		            $validationErrors = $this->validateSheetData($sheetData);

		            if (!empty($validationErrors)) {
		                
		                $this->status = array(
		                	'error' => true,
							'eType' => 'field',
							'errors' => ['file' => $validationErrors],
							'msg' => 'Something went wrong.'
						);

		            } else {

		            	for ($i=2; $i <= count($sheetData); $i++) {
		            		
		            		$obj = [
				        		'admin_id' => adminId(),
				        		'pincode' => $sheetData[$i]['A'],
				        		'free_shipping' => $sheetData[$i]['B'],
				        		'under_500gm' => $sheetData[$i]['C'],
				        		'from500_1000gm' => $sheetData[$i]['D'],
				        		'from1000_2000gm' => $sheetData[$i]['E'],
				        		'from2000_3000gm' => $sheetData[$i]['F'],
				        		'is_active' => (strtolower($sheetData[$i]['G']) == 'active')? 1:0,
				        	];


				        		$isExist = ShippingModel::where('pincode', $sheetData[$i]['A'])->count();

				        		if ($isExist) {
				        			$isAdded = ShippingModel::where('pincode', $sheetData[$i]['A'])->update($obj);
				        		} else {
				        			$isAdded = ShippingModel::create($obj);
				        		}
		            	}

		            	$this->status = array(
							'error' => false,								
							'msg' => 'Shipping has been imported successfully.'
						);

		            }
		    
		        } else {
		            $this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Invalid File.'
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

	private function validateSheetData($sheetData){
	        $validationErrors = '';

	        $line=1;
	        $statusList = ['active', 'inactive'];
	        for ($i=2; $i <= count($sheetData); $i++) { 
	        	
	        	if (!isset($sheetData[$i]['A']) OR empty($sheetData[$i]['A'])) {
	        		$validationErrors .= '<p>The pincode is required on line no. '.$line.'</p>';
	        	} elseif (!is_numeric($sheetData[$i]['A'])) {
	        		$validationErrors .= '<p>The pincode must be numeric on line no. '.$line.'</p>';
	        	} elseif (strlen($sheetData[$i]['A']) != 6) {
	        		$validationErrors .= '<p>The pincode must be of 6 digits on line no. '.$line.'</p>';
	        	} 
	        	// else {
	        	// 	$isExist = ShippingModel::where('pincode', $sheetData[$i]['A'])->count();

	        	// 	if ($isExist) {
	        	// 		$validationErrors .= '<p>The pincode is already exists on line no. '.$line.'</p>';
	        	// 	}
	        		
	        	// }

	        	if (isset($sheetData[$i]['B']) OR !empty($sheetData[$i]['B'])) {
	        		if (!is_numeric($sheetData[$i]['B'])) {
		        		$validationErrors .= '<p>The free shipping price must be numeric on line no. '.$line.'</p>';
		        	}
	        	}

	        	if (!isset($sheetData[$i]['C']) OR empty($sheetData[$i]['C'])) {
	        		$validationErrors .= '<p>The under 500 gm price is required on line no. '.$line.'</p>';
	        	} elseif (!is_numeric($sheetData[$i]['C'])) {
	        		$validationErrors .= '<p>The under 500 gm price must be numeric on line no. '.$line.'</p>';
	        	}

	        	if (isset($sheetData[$i]['D']) OR !empty($sheetData[$i]['D'])) {
	        		if (!is_numeric($sheetData[$i]['D'])) {
		        		$validationErrors .= '<p>The under 500-1000 gm price must be numeric on line no. '.$line.'</p>';
		        	}
	        	}

	        	if (isset($sheetData[$i]['E']) OR !empty($sheetData[$i]['E'])) {
	        		if (!is_numeric($sheetData[$i]['E'])) {
		        		$validationErrors .= '<p>The under 1000-2000 gm price must be numeric on line no. '.$line.'</p>';
		        	}
	        	}

	        	if (isset($sheetData[$i]['F']) OR !empty($sheetData[$i]['F'])) {
	        		if (!is_numeric($sheetData[$i]['F'])) {
		        		$validationErrors .= '<p>The under 2000-3000 gm price must be numeric on line no. '.$line.'</p>';
		        	}
	        	}

	        	if (!isset($sheetData[$i]['G']) OR empty($sheetData[$i]['G'])) {
	        		$validationErrors .= '<p>The status is required on line no. '.$line.'</p>';
	        	} elseif (!in_array(strtolower($sheetData[$i]['G']), $statusList)) {
	        		$validationErrors .= '<p>The status should contain active or inactive on line no. '.$line.'</p>';
	        	}

	        	$line++;

	        }

	        return $validationErrors;

    }

	
	public function edit($id) {

		if (!can('update', 'shipping')){
			return redirect(route('adminShipping'));
		}

		$getData = ShippingModel::where(['id' => $id])->first();

		if (empty($getData)) {
			return redirect(route('adminShipping'));
		}

		$data = array(
			'title' => 'Shipping',
			'pageTitle' => 'Shipping',
			'menu' => 'shipping',
			'shipping' => $getData
		);

		return view('admin/shipping/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {


			if (!can('create', 'shipping')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'pincode' => 'required|numeric|digits:6|unique:shipping,pincode',
	            'free_shipping' => 'sometimes|nullable|numeric',
	            'under_500gm' => 'required|numeric',
	            'from500_1000gm' => 'sometimes|nullable|numeric',
	            'from1000_2000gm' => 'sometimes|nullable|numeric',
	            'from2000_3000gm' => 'sometimes|nullable|numeric',
	            'status' => 'required|numeric|in:0,1'
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
	        		'pincode' => $request->post('pincode'),
	        		'free_shipping' => $request->post('free_shipping'),
	        		'under_500gm' => $request->post('under_500gm'),
	        		'from500_1000gm' => $request->post('from500_1000gm'),
	        		'from1000_2000gm' => $request->post('from1000_2000gm'),
	        		'from2000_3000gm' => $request->post('from2000_3000gm'),
	        		'is_active' => $request->post('status'),
	        	];

	        	$isAdded = ShippingModel::create($obj);

	        	if ($isAdded) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Shipping has been added successfully.'
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


	public function doUpdate(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'coupon')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);
			}

			$id = $request->post('id');

	        $validator = Validator::make($request->post(), [
	        	'id' => 'required|numeric',
	            'pincode' => 'required|numeric|digits:6|unique:shipping,pincode,'.$id,
	            'free_shipping' => 'sometimes|nullable|numeric',
	            'under_500gm' => 'required|numeric',
	            'from500_1000gm' => 'sometimes|nullable|numeric',
	            'from1000_2000gm' => 'sometimes|nullable|numeric',
	            'from2000_3000gm' => 'sometimes|nullable|numeric',
	            'status' => 'required|numeric|in:0,1'
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

	        	$getCoupon = ShippingModel::where(['id' => $id])->first();
	        	
	        	if (empty($getCoupon)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);
	        	}

	        	$obj = [
	        		'pincode' => $request->post('pincode'),
	        		'free_shipping' => $request->post('free_shipping'),
	        		'under_500gm' => $request->post('under_500gm'),
	        		'from500_1000gm' => $request->post('from500_1000gm'),
	        		'from1000_2000gm' => $request->post('from1000_2000gm'),
	        		'from2000_3000gm' => $request->post('from2000_3000gm'),
	        		'is_active' => $request->post('status'),
	        	];

	        	$isUpdated = ShippingModel::where('id', $id)->update($obj);


	        	if ($isUpdated) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Shipping has been updated successfully.'
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


	public function doDelete(Request $request) {
		if ($request->ajax()) {

			if (!can('delete', 'coupon')){
				
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
	        	$getData = ShippingModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = ShippingModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Shipping has been deleted successfully.'
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

			//check permissions
			if (!can('delete', 'coupon')){
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

	        	//check if data exist
	        	$getData = ShippingModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = ShippingModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Shipping has been deleted successfully.'
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

}