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

use App\Models\CategoryModel;
use App\Models\AdminModel;

class Category extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'category')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Category',
			'pageTitle' => 'Category',
			'menu' => 'category',
		);

		return view('admin/category/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'category')){
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

		    $singleDelUrl = route('adminDeleteCategory');

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
		    $totalRecords = CategoryModel::select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = CategoryModel::select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('category.category_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('category.category_slug', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('category.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('category.is_active', 'like', '%0%');
			        }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = CategoryModel::select('category.*')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        $query->where('category.category_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('category.category_slug', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('category.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('category.is_active', 'like', '%0%');
			        }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('category.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditCategory', $id);

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
			          	"category_name" => $record->category_name,
			          	"category_slug" => $record->category_slug,
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

		if (!can('create', 'category')){
			return redirect(route('adminCategory'));
		}

		$categoryImg = url('public/backend/media/svg/avatars/blank.svg');

		$data = array(
			'title' => 'Category',
			'pageTitle' => 'Category',
			'menu' => 'category',
			'allowMedia' => true,
			'categoryImg' => $categoryImg,
		);

		return view('admin/category/add', $data);
	}

	
	public function edit($id) {

		if (!can('update', 'category')){
			return redirect(route('adminCategory'));
		}

		$getData = CategoryModel::where(['id' => $id])->first();

		if (empty($getData)) {
			return redirect(route('adminCategory'));
		}

		$categoryImg = url('public/backend/media/svg/avatars/blank.svg');

		if (getImg($getData->category_img)) {
			$categoryImg = getImg($getData->category_img);
		}

		$data = array(
			'title' => 'Category',
			'pageTitle' => 'Category',
			'menu' => 'category',
			'allowMedia' => true,
			'categoryImg' => $categoryImg,
			'category' => $getData
		);

		return view('admin/category/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'category')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'name' => 'required|regex:/^[\pL\s\-]+$/u',
	            'slug' => 'required|unique:category,category_slug',
	            'description' => 'sometimes|nullable',
	            'status' => 'required|numeric|in:0,1',
	            'categoryImg' => 'sometimes|nullable'
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
	        		'category_name' => $request->post('name'),
	        		'category_slug' => $request->post('slug'),
	        		'category_description' => $request->post('description'),
	        		'parent' => 0,
	        		'is_active' => $request->post('status'),
	        		'category_img' => $request->post('categoryImg'),
	        	];

	        	$isAdded = CategoryModel::create($obj);

	        	if ($isAdded) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Category has been added successfully.'
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

			if (!can('update', 'category')){
				
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
	            'name' => 'required|regex:/^[\pL\s\-]+$/u',
	            'slug' => 'required|unique:category,category_slug,'.$id,
	            'description' => 'sometimes|nullable',
	            'status' => 'required|numeric|in:0,1',
	            'categoryImg' => 'sometimes|nullable'
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

	        	$getCategory = CategoryModel::where(['id' => $id])->first();
	        	
	        	if (empty($getCategory)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);
	        	}

	        	$getCategory->category_name = $request->post('name');
	        	$getCategory->category_slug = $request->post('slug');
	        	$getCategory->is_active = $request->post('status');
	        	$getCategory->category_description = $request->post('description');
	        	$getCategory->category_img = $request->post('categoryImg');
	        	$isUpdated = $getCategory->save();

	        	if ($isUpdated) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'Category has been updated successfully.'
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

			if (!can('delete', 'category')){
				
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
	        	$getData = CategoryModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = CategoryModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Category has been deleted successfully.'
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
			if (!can('delete', 'category')){
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
	        	$getData = CategoryModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = CategoryModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Category has been deleted successfully.'
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