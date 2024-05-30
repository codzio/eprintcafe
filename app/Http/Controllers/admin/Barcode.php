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

use App\Models\BarcodeModel;
use App\Models\AdminModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;

class Barcode extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'barcode')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Barcode',
			'pageTitle' => 'Barcode',
			'menu' => 'barcode',
		);

		return view('admin/barcode/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'barcode')){
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

		    $singleDelUrl = route('adminDeleteBarcode');

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
		    $totalRecords = BarcodeModel::select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = BarcodeModel::select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('barcode.barcode', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'yes') {
			        	$query->orWhere('barcode.is_used', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'no') {
			        	$query->orWhere('barcode.is_used', 'like', '%0%');
			        }

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('barcode.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('barcode.is_active', 'like', '%0%');
			        }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = BarcodeModel::select('barcode.*')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        
			        $query->where('barcode.barcode', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'yes') {
			        	$query->orWhere('barcode.is_used', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'no') {
			        	$query->orWhere('barcode.is_used', 'like', '%0%');
			        }

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('barcode.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('barcode.is_active', 'like', '%0%');
			        }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('barcode.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditBarcode', $id);

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

					if ($record->is_used) {
						$isUsed = '<div class="badge badge-success">Yes</div>';
					} else {
						$isUsed = '<div class="badge badge-danger">No</div>';
					}

			        $data_arr[] = array(
			        	"checkbox" => $checkbox,
			          	"barcode" => $record->barcode,
			          	"is_used" => $isUsed,
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

		if (!can('create', 'barcode')){
			return redirect(route('adminBarcode'));
		}

		$data = array(
			'title' => 'Barcode',
			'pageTitle' => 'Barcode',
			'menu' => 'barcode',
		);

		return view('admin/barcode/add', $data);
	}

	public function bulkImport(Request $request) {

		if (!can('create', 'barcode')){
			return redirect(route('adminBarcode'));
		}

		$data = array(
			'title' => 'Barcode Bulk Import',
			'pageTitle' => 'Barcode Bulk Import',
			'menu' => 'barcode',
		);

		return view('admin/barcode/bulkImport', $data);
	}

	public function doBarcodeBulkImport(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'barcode')){
				
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
				        		'barcode' => $sheetData[$i]['A'],
				        		'is_active' => (strtolower($sheetData[$i]['B']) == 'active')? 1:0,
				        	];

			        		$isExist = BarcodeModel::where('barcode', $sheetData[$i]['A'])->count();

			        		if ($isExist) {
			        			$isAdded = BarcodeModel::where('barcode', $sheetData[$i]['A'])->update($obj);
			        		} else {
			        			$isAdded = BarcodeModel::create($obj);
			        		}
		            	}

		            	$this->status = array(
							'error' => false,								
							'msg' => 'Barcode has been imported successfully.'
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
	        		$validationErrors .= '<p>The barcode is required on line no. '.$line.'</p>';
	        	} else {

	        		$isExist = BarcodeModel::where('barcode', $sheetData[$i]['A'])->count();

	        		if ($isExist) {
	        			$validationErrors .= '<p>The barcode is already exists on line no. '.$line.'</p>';
	        		}
	        		
	        	}

	        	if (!isset($sheetData[$i]['B']) OR empty($sheetData[$i]['B'])) {
	        		$validationErrors .= '<p>The status is required on line no. '.$line.'</p>';
	        	} elseif (!in_array(strtolower($sheetData[$i]['B']), $statusList)) {
	        		$validationErrors .= '<p>The status should contain active or inactive on line no. '.$line.'</p>';
	        	}

	        	$line++;

	        }

	        return $validationErrors;

    }

	
	public function edit($id) {

		if (!can('update', 'barcode')){
			return redirect(route('adminBarcode'));
		}

		$getData = BarcodeModel::where(['id' => $id])->first();

		if (empty($getData)) {
			return redirect(route('adminBarcode'));
		}

		$data = array(
			'title' => 'Barcode',
			'pageTitle' => 'barcode',
			'menu' => 'barcode',
			'barcode' => $getData
		);

		return view('admin/barcode/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {


			if (!can('create', 'barcode')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'barcode' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:barcode,barcode',
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
	        		'barcode' => $request->post('barcode'),
	        		'is_active' => $request->post('status'),
	        	];

	        	$isAdded = BarcodeModel::create($obj);

	        	if ($isAdded) {
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

			if (!can('update', 'barcode')){
				
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
	            'barcode' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:barcode,barcode,'.$id,
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

	        	$getCoupon = BarcodeModel::where(['id' => $id])->first();
	        	
	        	if (empty($getCoupon)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);
	        	}

	        	$obj = [
	        		'barcode' => $request->post('barcode'),
	        		'is_active' => $request->post('status'),
	        	];

	        	$isUpdated = BarcodeModel::where('id', $id)->update($obj);


	        	if ($isUpdated) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Barcode has been updated successfully.'
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

			if (!can('delete', 'barcode')){
				
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
	        	$getData = BarcodeModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = BarcodeModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Barcode has been deleted successfully.'
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
			if (!can('delete', 'barcode')){
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
	        	$getData = BarcodeModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = BarcodeModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Barcode has been deleted successfully.'
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