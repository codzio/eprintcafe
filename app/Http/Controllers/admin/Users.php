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

use App\Models\RolesModel;
use App\Models\AdminModel;

class Users extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'users')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Users',
			'pageTitle' => 'Users',
			'menu' => 'users',
		);

		return view('admin/users/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'users')){
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

		    $singleDelUrl = route('adminDeleteUser');

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
		    $totalRecords = AdminModel::join('roles', 'admins.role_id', '=', 'roles.id')->select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = AdminModel::join('roles', 'admins.role_id', '=', 'roles.id')->select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('admins.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('admins.email', 'like', '%' . $searchValue . '%')
			              ->orWhere('admins.phone_number', 'like', '%' . $searchValue . '%')
			              ->orWhere('roles.role_name', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('admins.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('admins.is_active', 'like', '%0%');
			        }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = AdminModel::join('roles', 'admins.role_id', '=', 'roles.id')->select('admins.*', 'roles.role_name')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        $query->where('admins.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('admins.email', 'like', '%' . $searchValue . '%')
			              ->orWhere('admins.phone_number', 'like', '%' . $searchValue . '%')
			              ->orWhere('admins.is_active', 'like', '%' . $searchValue . '%')
			              ->orWhere('roles.role_name', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('admins.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('admins.is_active', 'like', '%0%');
			        }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('admins.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditUser', $id);

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

					$role = '<div class="badge badge-light-success">'.$record->role_name.'</div>';

					if ($id == 1) {
						$checkbox = '';
						$action = '';
					}

			        $data_arr[] = array(
			        	"checkbox" => $checkbox,
			          	"name" => $record->name,
			          	"email" => $record->email,
			          	"phone_number" => $record->phone_number,
			          	"role_name" => $role,
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

		if (!can('create', 'users')){
			return redirect(route('adminUsers'));
		}

		$profileImg = url('public/backend/media/svg/avatars/blank.svg');
		$roles = RolesModel::get();

		$data = array(
			'title' => 'Users',
			'pageTitle' => 'Users',
			'menu' => 'users',
			'allowMedia' => true,
			'profileImg' => $profileImg,
			'roles' => $roles
		);

		return view('admin/users/add', $data);
	}

	
	public function edit($id) {

		if (!can('update', 'users')){
			return redirect(route('adminUsers'));
		}

		$getData = AdminModel::where(['id' => $id])->first();

		if (empty($getData) || $id == 1) {
			return redirect(route('adminUsers'));
		}

		$profileImg = url('public/backend/media/svg/avatars/blank.svg');

		if (getImg($getData->profile)) {
			$profileImg = getImg($getData->profile);
		}

		$roles = RolesModel::get();

		$data = array(
			'title' => 'Users',
			'pageTitle' => 'Users',
			'menu' => 'users',
			'allowMedia' => true,
			'profileImg' => $profileImg,
			'roles' => $roles,
			'user' => $getData
		);

		return view('admin/users/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'users')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'name' => 'required|regex:/^[\pL\s\-]+$/u',
	            'email' => 'required|email|unique:admins,email',
	            'password' => 'required|min:6',
	            'address' => 'sometimes|nullable',
	            'phoneNumber' => 'sometimes|nullable|numeric|unique:admins,phone_number',
	            'twoStep' => 'required|numeric|in:0,1',
	            'status' => 'required|numeric',
	            'role' => 'required|exists:roles,id',
	            'profileImg' => 'sometimes|nullable'
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
	        		'role_id' => $request->post('role'),
	        		'name' => $request->post('name'),
	        		'email' => $request->post('email'),
	        		'password' => Hash::make($request->post('password')),
	        		'address' => $request->post('address'),
	        		'phone_number' => $request->post('phoneNumber'),
	        		'two_step' => $request->post('twoStep'),
	        		'is_active' => $request->post('status'),
	        		'role_id' => $request->post('role'),
	        		'profile' => $request->post('profileImg'),
	        	];

	        	$isAdded = AdminModel::create($obj);

	        	if ($isAdded) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'User has been added successfully.'
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

			if (!can('update', 'users')){
				
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
	            'email' => 'required|email|unique:admins,email,'.$id,
	            'address' => 'sometimes|nullable',
	            'phoneNumber' => 'sometimes|nullable|numeric|unique:admins,phone_number,'.$id,
	            'twoStep' => 'required|numeric|in:0,1',
	            'status' => 'required|numeric',
	            'role' => 'required|exists:roles,id',
	            'profileImg' => 'sometimes|nullable'
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

	        	$getUser = AdminModel::where(['id' => $id])->first();
	        	
	        	if (empty($getUser)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);
	        	}

	        	$getUser->role_id = $request->post('role');
	        	$getUser->name = $request->post('name');
	        	$getUser->email = $request->post('email');

	        	if (!empty($request->post('password'))) {
	        		$getUser->password = Hash::make($request->post('password'));
	        	}

	        	$getUser->address = $request->post('address');
	        	$getUser->phone_number = $request->post('phoneNumber');
	        	$getUser->two_step = $request->post('twoStep');
	        	$getUser->is_active = $request->post('status');
	        	$getUser->role_id = $request->post('role');
	        	$getUser->profile = $request->post('profileImg');

	        	$isUpdated = $getUser->save();

	        	if ($isUpdated) {
    				$this->status = array(
						'error' => false,								
						'msg' => 'User has been updated successfully.'
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

			if (!can('delete', 'users')){
				
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
	        	$getData = AdminModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = AdminModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'User has been deleted successfully.'
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
			if (!can('delete', 'users')){
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
	        	$getData = AdminModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = AdminModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'User has been deleted successfully.'
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