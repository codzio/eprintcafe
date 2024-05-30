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
use App\Models\PaperSizeModel;
use App\Models\PaperTypeModel;
use App\Models\PricingModel;
use App\Models\CategoryModel;
use App\Models\GsmModel;
use App\Models\AdminModel;

class Pricing extends Controller {

	private $status = array();

	public function index(Request $request, $productId) {

		if (!can('read', 'pricing')){
			return redirect(route('adminDashboard'));
		}

		//check if product exist
		$getProduct = ProductModel::where('id', $productId)->first();

		if (!$getProduct) {
			return redirect()->route('adminProduct');
		}

		$data = array(
			'title' => $getProduct->name.' Pricing',
			'pageTitle' => $getProduct->name.' Pricing',
			'menu' => 'pricing',
			'product' => $getProduct
		);

		return view('admin/pricing/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'pricing')){
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

		    $singleDelUrl = route('adminDeletePricing');

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
		    $totalRecords = PricingModel::
		    join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
		    ->join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
		    ->join('paper_type', 'pricing.paper_type_id', '=', 'paper_type.id')
		    ->where('product_id', $request->get('id'))
		    ->select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = PricingModel::
		    join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
		    ->join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
		    ->join('paper_type', 'pricing.paper_type_id', '=', 'paper_type.id')
		    ->where('product_id', $request->get('id'))
		    ->select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('paper_size.size', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.gsm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.weight', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.per_sheet_weight', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.paper_type_price', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_type.paper_type', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('pricing.side', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('pricing.color', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('pricing.other_price', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('product.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('product.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		    // Fetch records
		    $records = PricingModel::
		    join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
		    ->join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
		    ->join('paper_type', 'pricing.paper_type_id', '=', 'paper_type.id')
		    ->where('product_id', $request->get('id'))
		    ->select('pricing.*', 'paper_size.size', 'paper_size.measurement', 'gsm.gsm', 'gsm.weight', 'gsm.per_sheet_weight', 'gsm.paper_type_price', 'paper_type.paper_type')
		    ->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        
			        $query->where('paper_size.size', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.gsm', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.weight', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.per_sheet_weight', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('gsm.paper_type_price', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('paper_type.paper_type', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('pricing.side', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('pricing.color', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('pricing.other_price', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('product.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('product.is_active', 'like', '%0%');
			        // }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('pricing.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){

			        $id = $record->id;
			        $productId = $record->product_id;

			        $editUrl = route('adminEditPricing', [$productId, $id]);

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
			          	"gsm" => $record->gsm,
			          	"paper_type" => $record->paper_type,
			          	"side" => $record->side,
			          	"color" => $record->color,
			          	"weight" => $record->weight,
			          	"per_sheet_weight" => $record->per_sheet_weight,
			          	"paper_price" => $record->paper_type_price,
			          	"other_price" => $record->other_price,
			          	"total" => $record->paper_type_price+$record->other_price,
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

	public function add(Request $request, $productId) {

		if (!can('create', 'pricing')){
			return redirect(route('adminPricing', $productId));
		}

		//check if product id exist
		$getProduct = ProductModel::where('id', $productId)->first();

		if (!$getProduct) {
			return redirect()->route('adminProduct');
		}

		$getSize = PaperSizeModel::get();

		$data = array(
			'title' => 'Pricing',
			'pageTitle' => 'Pricing',
			'menu' => 'pricing',
			'product' => $getProduct,
			'paperSize' => $getSize,
		);

		return view('admin/pricing/add', $data);
	}

	
	public function edit($productId, $pricingId) {

		if (!can('update', 'pricing')){
			return redirect(route('adminPricing'));
		}

		$getData = PricingModel::
		join('product', 'pricing.product_id', '=', 'product.id')
		->join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
		->where(['pricing.id' => $pricingId, 'product_id' => $productId])
		->select('product.name','pricing.*', 'gsm.weight', 'gsm.per_sheet_weight', 'gsm.paper_type_price')
		->first();

		if (empty($getData)) {
			return redirect(route('adminProduct'));
		}

		$getSize = PaperSizeModel::get();

		$paperSize = $getData->paper_size_id;
		$paperGsm = $getData->paper_gsm_id;

		$getPaperGsm = GsmModel::where('paper_size', $paperSize)->get();

    	$getPaperType = GsmModel::
    	join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
    	->where(['gsm.paper_size' => $paperSize, 'gsm.id' => $paperGsm])
    	->select('paper_type.*')
    	->distinct('paper_type.id')
    	->get();

		$data = array(
			'title' => 'Pricing',
			'pageTitle' => 'Pricing',
			'menu' => 'pricing',
			'pricing' => $getData,
			'paperSize' => $getSize,
			'paperGsm' => $getPaperGsm,
			'paperType' => $getPaperType
		);

		return view('admin/pricing/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'pricing')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
				'productId' => 'required|numeric|exists:product,id',
				'paperSize' => 'required|numeric|exists:gsm,paper_size',
				'paperGsm' => 'required|numeric|exists:gsm,id',
				'paperType' => 'required|numeric|exists:gsm,paper_type',
	            'sides' => 'required',
	            'color' => 'required',
	            'otherPrice' => 'required|numeric',
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

	        	//check if already exist
	        	$isExist = PricingModel::where([
	        		'product_id' => $request->post('productId'),
	        		'paper_size_id' => $request->post('paperSize'),
	        		'paper_gsm_id' => $request->post('paperGsm'),
	        		'paper_type_id' => $request->post('paperType'),
	        		'side' => $request->post('sides'),
	        		'color' => $request->post('color'),
	        	])->first();

	        	if (!empty($isExist) && $isExist->count()) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The price is already exist'
					);

	        	} else {
	        		$obj = [
		        		'admin_id' => adminId(),
		        		'product_id' => $request->post('productId'),
		        		'paper_size_id' => $request->post('paperSize'),
		        		'paper_gsm_id' => $request->post('paperGsm'),
		        		'paper_type_id' => $request->post('paperType'),
		        		'side' => $request->post('sides'),
		        		'color' => $request->post('color'),
		        		'other_price' => $request->post('otherPrice'),
		        	];

		        	$isAdded = PricingModel::create($obj);

		        	if ($isAdded) {
	    				$this->status = array(
							'error' => false,								
							'msg' => 'Product has been added successfully.'
						);
	    			} else {
	    				$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Something went wrong.'
						);
	    			}
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

			if (!can('update', 'pricing')){
				
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
				'productId' => 'required|numeric|exists:product,id',
				'paperSize' => 'required|numeric|exists:gsm,paper_size',
				'paperGsm' => 'required|numeric|exists:gsm,id',
				'paperType' => 'required|numeric|exists:gsm,paper_type',
	            'sides' => 'required',
	            'color' => 'required',
	            'otherPrice' => 'required|numeric',
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

	        	$productId = $request->post('productId');
	        	$getProduct = ProductModel::where(['id' => $productId])->first();
	        	
	        	if (empty($getProduct)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);
	        	}

	        	//check if already exist
	        	$isExist = PricingModel::where([
	        		'product_id' => $request->post('productId'),
	        		'paper_size_id' => $request->post('paperSize'),
	        		'paper_gsm_id' => $request->post('paperGsm'),
	        		'paper_type_id' => $request->post('paperType'),
	        		'side' => $request->post('sides'),
	        		'color' => $request->post('color'),
	        	])
	        	->where('id', '!=', $id)
	        	->first();

	        	if (!empty($isExist) && $isExist->count()) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'The price is already exist'
					);

	        	} else {

	        		$obj = [
		        		'product_id' => $request->post('productId'),
		        		'paper_size_id' => $request->post('paperSize'),
		        		'paper_gsm_id' => $request->post('paperGsm'),
		        		'paper_type_id' => $request->post('paperType'),
		        		'side' => $request->post('sides'),
		        		'color' => $request->post('color'),
		        		'other_price' => $request->post('otherPrice'),
		        	];

		        	$isUpdated = PricingModel::where('id', $id)->update($obj);

		        	if ($isUpdated) {
	    				$this->status = array(
							'error' => false,								
							'msg' => 'Product has been updated successfully.'
						);
	    			} else {
	    				$this->status = array(
							'error' => true,
							'eType' => 'final',
							'msg' => 'Something went wrong.'
						);
	    			}

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

			if (!can('delete', 'pricing')){
				
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
	        	$getData = PricingModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {
	        		
	        		$isDeleted = PricingModel::where('id', $id)->delete();

        			if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Pricing has been deleted successfully.'
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
			if (!can('delete', 'pricing')){
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
	        	$getData = PricingModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		$isDeleted = PricingModel::whereIn('id', $ids)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Pricing has been deleted successfully.'
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

	//Other
	public function getPaperGsm(Request $request) {
		if ($request->ajax()) {

			//check permissions
			if (!can('delete', 'pricing')){
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);
				return json_encode($this->status);
			}

			$validator = Validator::make($request->post(), [
	            'paperSize' => 'required|numeric',
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
	        	$getPaperGsm = GsmModel::join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
	        	->where('gsm.paper_size', $paperSize)
	        	->select('gsm.*', 'paper_type.paper_type')
	        	->get();
	        	$options = '<option value="">Select Paper GSM</option>';

	        	if (!empty($getPaperGsm)) {
	        		foreach ($getPaperGsm as $paperGsm) {
	        			$options .= '<option data-url="'.route('getAdminPaperType').'" value="'.$paperGsm->id.'">'.$paperGsm->gsm.' GSM - '.$paperGsm->paper_type.'</option>';
	        		}
	        	}

	        	$this->status = array(
					'error' => false,
					'options' => $options
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

	public function getPaperType(Request $request) {
		if ($request->ajax()) {

			//check permissions
			if (!can('delete', 'pricing')){
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);
				return json_encode($this->status);
			}

			$validator = Validator::make($request->post(), [
	            'paperSize' => 'required|numeric',
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
	        	$paperGsm = $request->post('paperGsm');

	        	$getPaperType = GsmModel::
	        	join('paper_type', 'gsm.paper_type', '=', 'paper_type.id')
	        	->where(['gsm.paper_size' => $paperSize, 'gsm.id' => $paperGsm])
	        	->select('paper_type.*')
	        	->distinct('paper_type.id')
	        	->get();

	        	$options = '<option value="">Select Paper Type</option>';

	        	if (!empty($getPaperType)) {
	        		foreach ($getPaperType as $paperType) {
	        			$options .= '<option data-url="'.route('getAdminPricing').'" value="'.$paperType->id.'">'.$paperType->paper_type.'</option>';
	        		}
	        	}

	        	$this->status = array(
					'error' => false,
					'options' => $options
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

			//check permissions
			if (!can('delete', 'pricing')){
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);
				return json_encode($this->status);
			}

			$validator = Validator::make($request->post(), [
	            'paperSize' => 'required|numeric',
	            'paperGsm' => 'required|numeric',
	            'paperType' => 'required|numeric',
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
	        	$paperGsm = $request->post('paperGsm');
	        	$paperType = $request->post('paperType');

	        	$getData = GsmModel::where(['id' => $paperGsm, 'paper_size' => $paperSize, 'paper_type' => $paperType])->first();

	        	$perSheetWeight = 0;
	        	$paperTypePrice = 0;

	        	if (!empty($getData)) {
	        		$perSheetWeight = $getData->per_sheet_weight;
	        		$paperTypePrice = $getData->paper_type_price;
	        	}
	        	
	        	$this->status = array(
					'error' => false,
					'perSheetWeight' => $perSheetWeight,
					'paperTypePrice' => $paperTypePrice,
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

}