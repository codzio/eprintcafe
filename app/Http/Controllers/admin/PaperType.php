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
use App\Models\PaperTypeModel;
use App\Models\AdminModel;

class PaperType extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'paper-type')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Paper Type',
			'pageTitle' => 'Paper Type',
			'menu' => 'paper-type',
		);

		return view('admin/paper-type/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'paper-type')){
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

		    $singleDelUrl = route('adminDeletePaperType');

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
		    // $totalRecords = PaperTypeModel::join('paper_size', 'paper_type.paper_size_id', '=', 'paper_size.id')->select('count(paper_type.*) as allcount');
		    $totalRecords = PaperTypeModel::select('count(paper_type.*) as allcount');
		    $totalRecords = $totalRecords->count();

		    // $totalRecordswithFilter = PaperTypeModel::join('paper_size', 'paper_type.paper_size_id', '=', 'paper_size.id')->select('count(paper_type.*) as allcount');
		    $totalRecordswithFilter = PaperTypeModel::select('count(paper_type.*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        // $query->where('paper_size.size', 'like', '%' . $searchValue . '%')
			        // 	  ->orWhere('paper_size.slug', 'like', '%' . $searchValue . '%')
			        // 	  ->orWhere('paper_type.paper_type', 'like', '%' . $searchValue . '%')
			        // 	  ->orWhere('paper_type.paper_type_slug', 'like', '%' . $searchValue . '%');

			        $query->where('paper_type.paper_type', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_type.paper_type_slug', 'like', '%' . $searchValue . '%');			        	  

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('category.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('category.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    // $records = PaperTypeModel::join('paper_size', 'paper_type.paper_size_id', '=', 'paper_size.id')->select('paper_size.size', 'paper_type.*')->skip($start)->take($rowperpage);

		    $records = PaperTypeModel::select('paper_type.*')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        
			        $query->where('paper_type.paper_type', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_type.paper_type_slug', 'like', '%' . $searchValue . '%');

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
		    	$records->orderBy('paper_type.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditPaperType', $id);

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
			          	// "size" => $record->size,
			          	"paper_type" => $record->paper_type,
			          	"paper_type_slug" => $record->paper_type_slug,
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

		if (!can('create', 'paper-type')){
			return redirect(route('adminPaperType'));
		}

		$paperSize = PaperSizeModel::get();

		$data = array(
			'title' => 'Paper Type',
			'pageTitle' => 'Paper Type',
			'menu' => 'paper-type',
			'paperSize' => $paperSize
		);

		return view('admin/paper-type/add', $data);
	}

	
	public function edit($id) {

		if (!can('update', 'paper-type')){
			return redirect(route('adminPaperType'));
		}

		$getData = PaperTypeModel::where(['id' => $id])->first();

		if (empty($getData)) {
			return redirect(route('adminPaperType'));
		}

		$paperSize = PaperSizeModel::get();

		$data = array(
			'title' => 'Paper Type',
			'pageTitle' => 'Paper Type',
			'menu' => 'paper-type',
			'paperType' => $getData,
			'paperSize' => $paperSize
		);

		return view('admin/paper-type/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'paper-type')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

	        $validator = Validator::make($request->post(), [
			    // 'paperSize' => 'required|exists:paper_size,id',
			    // 'paperType' => [
			    //     'required',
			    //     Rule::unique('paper_type', 'paper_type')->where(function ($query) use ($request) {
			    //         return $query->where('paper_size_id', $request->input('paperSize'));
			    //     }),
			    // ],
			    // 'slug' => [
			    //     'required',
			    //     Rule::unique('paper_type', 'paper_type_slug')->where(function ($query) use ($request) {
			    //         return $query->where('paper_size_id', $request->input('paperSize'));
			    //     }),
			    // ],
			    'paperType' => 'required|unique:paper_type,paper_type',
	            'slug' => 'required|unique:paper_type,paper_type_slug',
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
	        		// 'paper_size_id' => $request->post('paperSize'),
	        		'paper_type' => $request->post('paperType'),
	        		'paper_type_slug' => Str::slug($request->post('slug')),
	        	];

	        	$isAdded = PaperTypeModel::create($obj);

	        	if ($isAdded) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Paper Type has been added successfully.'
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

			if (!can('update', 'paper-type')){
				
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
			    // 'paperSize' => 'required|exists:paper_size,id',
			    // 'paperType' => [
			    //     'required',
			    //     Rule::unique('paper_type', 'paper_type')->where(function ($query) use ($request) {
			    //         return $query->where('paper_size_id', $request->input('paperSize'))->where('id', '!=', $request->input('id'));
			    //     }),
			    // ],
			    // 'slug' => [
			    //     'required',
			    //     Rule::unique('paper_type', 'paper_type_slug')->where(function ($query) use ($request) {
			    //         return $query->where('paper_size_id', $request->input('paperSize'))->where('id', '!=', $request->input('id'));
			    //     }),
			    // ],
			    'paperType' => 'required|unique:paper_type,paper_type,'.$id,
	            'slug' => 'required|unique:paper_type,paper_type_slug,'.$id,
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

	        	$getPaperType = PaperTypeModel::where(['id' => $id])->first();
	        	
	        	if (empty($getPaperType)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);
	        	}

	        	// $getPaperType->paper_size_id = $request->post('paperSize');
	        	$getPaperType->paper_type = $request->post('paperType');
	        	$getPaperType->paper_type_slug = Str::slug($request->post('slug'));
	        	$isUpdated = $getPaperType->save();

	        	if ($isUpdated) {
    				
    				$this->status = array(
						'error' => false,								
						'msg' => 'Paper type has been updated successfully.'
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

			if (!can('delete', 'paper-type')){
				
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
	        	$getData = PaperTypeModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = PaperTypeModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Paper type has been deleted successfully.'
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
			if (!can('delete', 'paper-type')){
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
	        	$getData = PaperTypeModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = PaperTypeModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Paper type has been deleted successfully.'
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