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
use Illuminate\Validation\Rule;

use App\Models\PaperSizeModel;
use App\Models\BindingModel;
use App\Models\AdminModel;

class Binding extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'binding')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Binding',
			'pageTitle' => 'Binding',
			'menu' => 'binding',
		);

		return view('admin/binding/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'binding')){
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

		    $singleDelUrl = route('adminDeleteBinding');

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
		    $totalRecords = BindingModel::join('paper_size', 'binding.paper_size_id', '=', 'paper_size.id')->select('count(paper_type.*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = BindingModel::join('paper_size', 'binding.paper_size_id', '=', 'paper_size.id')->select('count(paper_type.*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('paper_size.size', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_size.slug', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('binding.binding_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('binding.price', 'like', '%' . $searchValue . '%');		        	  

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('category.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('category.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = BindingModel::join('paper_size', 'binding.paper_size_id', '=', 'paper_size.id')->select('paper_size.size', 'binding.*')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        
			        $query->where('paper_size.size', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_size.slug', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('binding.binding_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('binding.price', 'like', '%' . $searchValue . '%');

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
		    	$records->orderBy('binding.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditBinding', $id);

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
			          	"size" => $record->size,
			          	"binding_name" => $record->binding_name,
			          	"price" => $record->price,
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

		if (!can('create', 'binding')){
			return redirect(route('adminBinding'));
		}

		$paperSize = PaperSizeModel::get();

		$data = array(
			'title' => 'Binding',
			'pageTitle' => 'Binding',
			'menu' => 'binding',
			'paperSize' => $paperSize
		);

		return view('admin/binding/add', $data);
	}

	
	public function edit($id) {

		if (!can('update', 'binding')){
			return redirect(route('adminBinding'));
		}

		$getData = BindingModel::where(['id' => $id])->first();

		if (empty($getData)) {
			return redirect(route('adminBinding'));
		}

		$paperSize = PaperSizeModel::get();

		$data = array(
			'title' => 'Binding',
			'pageTitle' => 'Binding',
			'menu' => 'binding',
			'binding' => $getData,
			'paperSize' => $paperSize
		);

		return view('admin/binding/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'binding')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

	        $validator = Validator::make($request->post(), [
			    'paperSize' => 'required|exists:paper_size,id',
			    'bindingName' => 'required',
			    'price' => 'required|numeric',
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

	        	//check if binding data exist
	        	$isExist = BindingModel::where(['paper_size_id' => $request->post('paperSize'), 'binding_name' => $request->post('bindingName')])->first();

	        	if ($isExist) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The binding is already created.'
					);

					echo json_encode($this->status);
					exit;

	        	}

	        	$obj = [
	        		'admin_id' => adminId(),
	        		'paper_size_id' => $request->post('paperSize'),
	        		'binding_name' => $request->post('bindingName'),
	        		'price' => $request->post('price'),
	        	];

	        	$isAdded = BindingModel::create($obj);

	        	if ($isAdded) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Binding has been added successfully.'
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

			if (!can('update', 'binding')){
				
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
			    'paperSize' => 'required|exists:paper_size,id',
			    'bindingName' => 'required',
			    'price' => 'required|numeric',
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

	        	$getBinding = BindingModel::where(['id' => $id])->first();
	        	
	        	if (empty($getBinding)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);

	        	}

	        	//check if binding exist
	        	$isExist = BindingModel::where(['paper_size_id' => $request->post('paperSize'), 'binding_name' => $request->post('bindingName')])->where('id', '!=', $request->post('id'))->first();

	        	if ($isExist) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The binding is already created.'
					);

					echo json_encode($this->status);
					exit;

	        	}

	        	$getBinding->paper_size_id = $request->post('paperSize');
	        	$getBinding->binding_name = $request->post('bindingName');
	        	$getBinding->price = Str::slug($request->post('price'));
	        	$isUpdated = $getBinding->save();

	        	if ($isUpdated) {
    				
    				$this->status = array(
						'error' => false,								
						'msg' => 'Binding has been updated successfully.'
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

			if (!can('delete', 'binding')){
				
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
	        	$getData = BindingModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = BindingModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Binding has been deleted successfully.'
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
			if (!can('delete', 'binding')){
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
	        	$getData = BindingModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = BindingModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Binding has been deleted successfully.'
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