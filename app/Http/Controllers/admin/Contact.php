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

use App\Models\ContactModel;
use App\Models\AdminModel;

class Contact extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'contact')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Contact',
			'pageTitle' => 'Contact',
			'menu' => 'contact',
		);

		return view('admin/contact/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'contact')){
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

		    $singleDelUrl = route('adminDeleteContact');

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
		    $totalRecords = ContactModel::select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = ContactModel::select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('contact.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.email', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.subject', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.message', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('contact.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('contact.is_active', 'like', '%0%');
			        }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = ContactModel::select('contact.*')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        $query->where('contact.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.email', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.subject', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('contact.message', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('contact.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('contact.is_active', 'like', '%0%');
			        }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('contact.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

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
			          	"name" => $record->name,
			          	"email" => $record->email,
			          	"phone" => $record->phone,
			          	"subject" => $record->subject,
			          	"message" => $record->name,
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

	

	public function doDelete(Request $request) {
		if ($request->ajax()) {

			if (!can('delete', 'contact')){
				
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
	        	$getData = ContactModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = ContactModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Contact Query has been deleted successfully.'
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
			if (!can('delete', 'contact')){
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
	        	$getData = ContactModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = ContactModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Contact Query has been deleted successfully.'
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