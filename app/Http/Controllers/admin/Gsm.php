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

use App\Models\GsmModel;
use App\Models\PaperSizeModel;
use App\Models\PaperTypeModel;
use App\Models\AdminModel;

class Gsm extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'gsm')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Paper GSM',
			'pageTitle' => 'Paper GSM',
			'menu' => 'gsm',
		);

		return view('admin/gsm/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'gsm')){
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

		    $singleDelUrl = route('adminDeleteGsm');

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
		    $totalRecords = GsmModel::
		    join('paper_size', 'gsm.paper_size', '=', 'paper_size.id')
		    ->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
		    ->select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = GsmModel::
		    join('paper_size', 'gsm.paper_size', '=', 'paper_size.id')
		    ->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
		    ->select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('gsm.gsm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.weight', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.rate', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.paper_type_price', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_size.size', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_size.measurement', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_type.paper_type', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('category.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('category.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = GsmModel::
		    join('paper_size', 'gsm.paper_size', '=', 'paper_size.id')
		    ->join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
		    ->select('gsm.*', 'paper_size.size', 'paper_size.measurement', 'paper_type.paper_type')
		    ->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        
			        $query->where('gsm.gsm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.weight', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.rate', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.paper_type_price', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_size.size', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_size.measurement', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_type.paper_type', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('category.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('category.is_active', 'like', '%0%');
			        // }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('gsm.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditGsm', $id);

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

					// if ($record->is_active) {
					// 	$status = '<div class="badge badge-success">Active</div>';
					// } else {
					// 	$status = '<div class="badge badge-danger">Inactive</div>';
					// }

			        $data_arr[] = array(
			        	"checkbox" => $checkbox,
			          	"paper_size" => $record->size,
			          	"measurement" => $record->measurement,
			          	"gsm" => $record->gsm,
			          	"weight" => $record->weight,
			          	"rate" => $record->rate,
			          	"paper_type" => $record->paper_type,
			          	"paper_type_price" => $record->paper_type_price,
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

		if (!can('create', 'gsm')){
			return redirect(route('adminGsm'));
		}

		$getPaperSize = PaperSizeModel::get();
		$getPaperType = PaperTypeModel::get();

		$data = array(
			'title' => 'Paper GSM',
			'pageTitle' => 'Paper GSM',
			'menu' => 'gsm',
			'paperSize' => $getPaperSize,
			'paperType' => $getPaperType,
		);

		return view('admin/gsm/add', $data);
	}

	
	public function edit($id) {

		if (!can('update', 'gsm')){
			return redirect(route('adminGsm'));
		}

		$getData = GsmModel::
		join('paper_size', 'gsm.paper_size', '=', 'paper_size.id')
		->select('gsm.*', 'paper_size.size', 'paper_size.measurement')
		->where(['gsm.id' => $id])->first();

		if (empty($getData)) {
			return redirect(route('adminGsm'));
		}

		$getPaperSize = PaperSizeModel::get();
		$getPaperType = PaperTypeModel::get();

		// echo "<pre>";
		// print_r($getData);
		// die();

		$data = array(
			'title' => 'Paper GSM',
			'pageTitle' => 'Paper GSM',
			'menu' => 'gsm',
			'gsm' => $getData,
			'paperSize' => $getPaperSize,
			'paperType' => $getPaperType,
		);

		return view('admin/gsm/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'gsm')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
				'paperSize' => 'required|numeric',
	            'gsm' => 'required|numeric',
	            'weight' => 'required|numeric',
	            'rate' => 'required|numeric',
	            'paperType' => 'required|numeric',
	            'paperTypePrice' => 'required|numeric',
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

	        	$paperSize = $request->post('paperSize');
	        	$gsm = $request->post('gsm');
	        	$paperType = $request->post('paperType');

	        	//check if paper size and gsm exist
	        	$isExist = GsmModel::where(['paper_size' => $paperSize, 'gsm' => $gsm, 'paper_type' => $paperType])->first();

	        	if (empty($isExist)) {
	        		
	        		$obj = [
		        		'admin_id' => adminId(),
		        		'paper_size' => $paperSize,
		        		'gsm' => $request->post('gsm'),
		        		'weight' => $request->post('weight'),
		        		'rate' => $request->post('rate'),
		        		'paper_type' => $request->post('paperType'),
		        		'paper_type_price' => $request->post('paperTypePrice'),
		        	];

		        	$isAdded = GsmModel::create($obj);

		        	if ($isAdded) {
	    				$this->status = array(
							'error' => false,								
							'msg' => 'GSM has been added successfully.'
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
						'msg' => 'The GSM is already added.'
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

			if (!can('update', 'gsm')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);
			}

			$id = $request->post('id');

	        $validator = Validator::make($request->post(), [
	        	'id' => 'required|numeric|exists:gsm,id',
	            'paperSize' => 'required|numeric',
	            'gsm' => 'required|numeric',
	            'weight' => 'required|numeric',
	            'rate' => 'required|numeric',
	            'paperType' => 'required|numeric',
	            'paperTypePrice' => 'required|numeric',
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

	        	$paperSize = $request->post('paperSize');
	        	$gsm = $request->post('gsm');
	        	$paperType = $request->post('paperType');
	        	$id = $request->post('id');

	        	//check if paper size and gsm exist
	        	$isExist = GsmModel::where(['paper_size' => $paperSize, 'gsm' => $gsm, 'paper_type' => $paperType])->where('id', '!=', $id)->first();

	        	if (empty($isExist)) {

	        		$obj = [
		        		'paper_size' => $paperSize,
		        		'gsm' => $request->post('gsm'),
		        		'weight' => $request->post('weight'),
		        		'rate' => $request->post('rate'),
		        		'paper_type' => $request->post('paperType'),
		        		'paper_type_price' => $request->post('paperTypePrice'),
		        	];

	        		$isUpdated = GsmModel::where('id', $id)->update($obj);

		        	if ($isUpdated) {
	    				
	    				$this->status = array(
							'error' => false,								
							'msg' => 'Gsm has been updated successfully.'
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
						'msg' => 'The GSM is already added.'
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

			if (!can('delete', 'gsm')){
				
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
	        	$getData = GsmModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = GsmModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Gsm has been deleted successfully.'
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
			if (!can('delete', 'gsm')){
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
	        	$getData = GsmModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = GsmModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Gsm has been deleted successfully.'
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