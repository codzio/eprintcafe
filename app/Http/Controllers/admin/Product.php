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
use App\Models\PricingModel;
use App\Models\CategoryModel;
use App\Models\AdminModel;

class Product extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'product')){
			return redirect(route('adminDashboard'));
		}

		$data = array(
			'title' => 'Product',
			'pageTitle' => 'Product',
			'menu' => 'product',
		);

		return view('admin/product/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'product')){
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

		    $singleDelUrl = route('adminDeleteProduct');

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
		    $totalRecords = ProductModel::join('category', 'product.category_id', '=', 'category.id')->select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = ProductModel::join('category', 'product.category_id', '=', 'category.id')->select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('category.category_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('category.category_slug', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('product.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('product.slug', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('product.description', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('product.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('product.is_active', 'like', '%0%');
			        }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = ProductModel::join('category', 'product.category_id', '=', 'category.id')->select('product.*', 'category.category_name')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        
			        $query->where('category.category_name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('category.category_slug', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('product.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('product.slug', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('product.description', 'like', '%' . $searchValue . '%');

			        if (strtolower($searchValue) == 'active') {
			        	$query->orWhere('product.is_active', 'like', '%1%');
			        } elseif (strtolower($searchValue) == 'inactive') {
			        	$query->orWhere('product.is_active', 'like', '%0%');
			        }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('product.id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $editUrl = route('adminEditProduct', $id);
			        $pricingUrl = route('adminPricing', $id);

			        $checkbox = '<div onclick="checkCheckbox(this)" class="form-check form-check-sm form-check-custom form-check-solid">
							<input name="delete[]" data-kt-check-target="#media .single-check-input" class="form-check-input" type="checkbox" value="'.$id.'" />
						</div>';

					$action = '
			          	<td class="text-end" data-kt-filemanager-table="action_dropdown">
						<div class="d-flex justify-content-end">
							<div class="ms-2">
								<div class="menu menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">

									<div class="menu-item">
										<a title="Pricing" href="'.$pricingUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-eye fs-2"></i></span>
										</a>
									</div>
									
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
			          	"category" => $record->category_name,
			          	"product" => $record->name,
			          	"slug" => $record->slug,
			          	"status" => $status,
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

		if (!can('create', 'product')){
			return redirect(route('adminProduct'));
		}

		$categoryList = CategoryModel::where('is_active', 1)->get();
		$productImg = url('public/backend/media/svg/avatars/blank.svg');

		$data = array(
			'title' => 'Product',
			'pageTitle' => 'Product',
			'menu' => 'product',
			'allowMedia' => true,
			'editor' => true,
			'productImg' => $productImg,
			'categoryList' => $categoryList
		);

		return view('admin/product/add', $data);
	}

	
	public function edit($id) {

		if (!can('update', 'product')){
			return redirect(route('adminProduct'));
		}

		$getData = ProductModel::where(['id' => $id])->first();

		if (empty($getData)) {
			return redirect(route('adminProduct'));
		}

		$categoryList = CategoryModel::where('is_active', 1)->get();

		$productImg = url('public/backend/media/svg/avatars/blank.svg');

		if (getImg($getData->thumbnail_id)) {
			$productImg = getImg($getData->thumbnail_id);
		}

		$galleryImgs = [];

		if (!empty($getData->gallery_images)) {
			$galleryImgs = json_decode($getData->gallery_images);
		}

		$data = array(
			'title' => 'Product',
			'pageTitle' => 'Product',
			'menu' => 'product',
			'allowMedia' => true,
			'editor' => true,
			'product' => $getData,
			'categoryList' => $categoryList,
			'productImg' => $productImg,
			'galleryImgs' => $galleryImgs,
		);

		return view('admin/product/edit', $data);

	}

	public function doAdd(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'product')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
				'category' => 'required|numeric|exists:category,id',
	            'name' => 'required',
	            'slug' => 'required|unique:product,slug',
	            'description' => 'required',
	            'status' => 'required|numeric|in:0,1',
	            'productImg' => 'required|numeric',
	            'galleryImages' => 'sometimes|nullable'
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

	        	$galleryImgs = [];

	        	if ($request->post('galleryImages')) {
	        		$galleryImgs = json_encode($request->post('galleryImages'));
	        	}

	        	$obj = [
	        		'admin_id' => adminId(),
	        		'name' => $request->post('name'),
	        		'slug' => Str::slug($request->post('slug')),
	        		'category_id' => $request->post('category'),
	        		'description' => $request->post('description'),
	        		'thumbnail_id' => $request->post('productImg'),
	        		'gallery_images' => $galleryImgs,
	        		'is_active' => $request->post('status'),
	        	];

	        	$isAdded = ProductModel::create($obj);

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

			if (!can('update', 'product')){
				
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
				'category' => 'required|numeric|exists:category,id',
	            'name' => 'required',
	            'slug' => 'required|unique:product,slug,'.$id,
	            'description' => 'required',
	            'status' => 'required|numeric|in:0,1',
	            'productImg' => 'required|numeric',
	            'galleryImages' => 'sometimes|nullable'
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

	        	$getProduct = ProductModel::where(['id' => $id])->first();
	        	
	        	if (empty($getProduct)) {
	        		
	        		$this->status = array(
						'error' => true,
						'eType' => 'final',
						'msg' => 'Something went wrong'
					);

					return json_encode($this->status);
	        	}

	        	$galleryImgs = [];

	        	if ($request->post('galleryImages')) {
	        		$galleryImgs = json_encode($request->post('galleryImages'));
	        	}

	        	$getProduct->name = $request->post('name');
	        	$getProduct->slug = Str::slug($request->post('slug'));
	        	$getProduct->category_id = $request->post('category');
	        	$getProduct->description = $request->post('description');
	        	$getProduct->thumbnail_id = $request->post('productImg');
	        	$getProduct->gallery_images = $galleryImgs;
	        	$getProduct->is_active = $request->post('status');
	        	$isUpdated = $getProduct->save();

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

			if (!can('delete', 'product')){
				
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
	        	$getData = ProductModel::where('id', '=', $id)->first();	        	
	        	
	        	if (!empty($getData)) {

	        		//Delete all pricing
	        		$deletePricing = PricingModel::where('product_id', $id)->delete();
	        			
	        		//Delete Product
	        		$isDeleted = ProductModel::where('id', $id)->delete();

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
			if (!can('delete', 'product')){
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
	        	$getData = ProductModel::whereIn('id', $ids)->get();	        	
	        	
	        	if (!empty($getData)) {

	        		//Delete all Pricing
	        		$deletePricing = PricingModel::whereIn('product_id', $ids)->delete();

	        		//Delete Product
	        		$isDeleted = ProductModel::whereIn('id', $ids)->delete();
	        		
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