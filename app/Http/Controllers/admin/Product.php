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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

	public function bulkUpdate(Request $request) {

		if (!can('read', 'product')){
			return redirect(route('adminDashboard'));
		}

		//check if is there any request for download sample file
		if ($request->get('action') == 'export') {
			
			$selectedProduct = $request->get('product');

			$pricingQuery = PricingModel::
			select('product.name as product_name', 'paper_size.size', 'gsm.gsm', 'paper_type.paper_type', 'pricing.*')
			->join('product', 'pricing.product_id', '=', 'product.id')
		    ->join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
		    ->join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
		    ->join('paper_type', 'pricing.paper_type_id', '=', 'paper_type.id');

		    if ($selectedProduct != 'all') {
		    	$pricingQuery = $pricingQuery->where('product_id', $selectedProduct);
		    }

		    $getFilterProduct = $pricingQuery->get();

		    if (!empty($getFilterProduct) && $getFilterProduct->count()) {

		    	// echo "<pre>";
		    	// print_r($getFilterProduct->toArray());
		    	// die();

		    	$spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Set the header row
                $sheet->setCellValue('A1', 'Product');
                $sheet->setCellValue('B1', 'Paper Size');
                $sheet->setCellValue('C1', 'Paper GSM');
                $sheet->setCellValue('D1', 'Paper Type');
                $sheet->setCellValue('E1', 'Print Side');
                $sheet->setCellValue('F1', 'Color');
                $sheet->setCellValue('G1', 'Price');

                $row = 2;
                foreach ($getFilterProduct as $item) {
                    $sheet->setCellValue('A' . $row, $item->product_name);
                    $sheet->setCellValue('B' . $row, $item->size);
                    $sheet->setCellValue('C' . $row, $item->gsm);
                    $sheet->setCellValue('D' . $row, $item->paper_type);
                    $sheet->setCellValue('E' . $row, $item->side);
                    $sheet->setCellValue('F' . $row, $item->color);
                    $sheet->setCellValue('G' . $row, $item->other_price);
                    $row++;
                }

                $fileName = 'pricing-data-' . now()->format('Y-m-d_H-i-s') . '.xlsx';
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                exit;

		    } else {

		    	$request->session()->flash('status', array('error' => true, 'msg' => 'There are no records found.'));
		    	return redirect(route('adminProductBulkUpdate'));
		    }

		}

		$productList = ProductModel::where('is_active', 1)->orderBy('name')->get();

		$data = array(
			'title' => 'Bulk Product Update',
			'pageTitle' => 'Bulk Product Update',
			'menu' => 'product',
			'productList' => $productList
		);

		return view('admin/product/bulkUpdateProduct', $data);

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
		$bannerImg = url('public/backend/media/svg/avatars/blank.svg');

		$data = array(
			'title' => 'Product',
			'pageTitle' => 'Product',
			'menu' => 'product',
			'allowMedia' => true,
			'editor' => true,
			'productImg' => $productImg,
			'bannerImg' => $bannerImg,
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

		$bannerImg = url('public/backend/media/svg/avatars/blank.svg');

		if (getImg($getData->banner_image)) {
			$bannerImg = getImg($getData->banner_image);
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
			'bannerImg' => $bannerImg,
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
	            'galleryImages' => 'sometimes|nullable',
	            'displayOnHome' => 'sometimes|nullable',
	            'registeredHsnCode' => 'required',
	            'unregisteredHsnCode' => 'required',
	            'shortDescription' => 'sometimes|nullable',
	            'bannerImg' => 'sometimes|nullable',
	            'buttonName' => 'sometimes|nullable',
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

	        	$displayOnHome = 0;

	        	if ($request->post('displayOnHome')) {
	        		$displayOnHome = 1;
	        	}

	        	$obj = [
	        		'admin_id' => adminId(),
	        		'name' => $request->post('name'),
	        		'slug' => Str::slug($request->post('slug')),
	        		'category_id' => $request->post('category'),
	        		'description' => $request->post('description'),
	        		'thumbnail_id' => $request->post('productImg'),
	        		'gallery_images' => !empty($galleryImgs)? $galleryImgs:null,
	        		'is_active' => $request->post('status'),
	        		'display_on_home' => $displayOnHome,
	        		'registered_hsn_code' => $request->post('registeredHsnCode'),
	        		'unregistered_hsn_code' => $request->post('unregisteredHsnCode'),
	        		'short_description' => $request->post('shortDescription'),
	            	'banner_image' => $request->post('bannerImg'),
	            	'button_name' => $request->post('buttonName'),
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
	            'galleryImages' => 'sometimes|nullable',
	            'displayOnHome' => 'sometimes|nullable',
	            'registeredHsnCode' => 'required',
	            'unregisteredHsnCode' => 'required',
	            'shortDescription' => 'sometimes|nullable',
	            'bannerImg' => 'sometimes|nullable',
	            'buttonName' => 'sometimes|nullable',
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

	        	$displayOnHome = 0;

	        	if ($request->post('displayOnHome')) {
	        		$displayOnHome = 1;
	        	}

	        	$getProduct->name = $request->post('name');
	        	$getProduct->slug = Str::slug($request->post('slug'));
	        	$getProduct->category_id = $request->post('category');
	        	$getProduct->description = $request->post('description');
	        	$getProduct->thumbnail_id = $request->post('productImg');
	        	$getProduct->gallery_images = $galleryImgs;
	        	$getProduct->is_active = $request->post('status');
	        	$getProduct->display_on_home = $displayOnHome;
	        	$getProduct->registered_hsn_code = $request->post('registeredHsnCode');
	        	$getProduct->unregistered_hsn_code = $request->post('unregisteredHsnCode');
	        	$getProduct->short_description = $request->post('shortDescription');
	           	$getProduct->banner_image = $request->post('bannerImg');
	           	$getProduct->button_name = $request->post('buttonName');
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

	public function doUpdateProductPricing(Request $request) {

		if ($request->ajax()) {

			if (!can('update', 'product')){
				
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
		            		
		            		PricingModel::
						    join('product', 'pricing.product_id', '=', 'product.id')
						    ->join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
						    ->join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
						    ->join('paper_type', 'pricing.paper_type_id', '=', 'paper_type.id')
						    ->where([
						        ['product.name', '=', $sheetData[$i]['A']],
						        ['paper_size.size', '=', $sheetData[$i]['B']],
						        ['gsm.gsm', '=', $sheetData[$i]['C']],
						        ['paper_type.paper_type', '=', $sheetData[$i]['D']],
						        ['pricing.side', '=', $sheetData[$i]['E']],
						        ['pricing.color', '=', $sheetData[$i]['F']]
						    ])->update(['pricing.other_price' => $sheetData[$i]['G']]);

		            	}

		            	$this->status = array(
							'error' => false,								
							'msg' => 'The product pricing has been updated successfully.'
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
	        for ($i=2; $i <= count($sheetData); $i++) { 
	        	
	        	if (!isset($sheetData[$i]['A']) OR empty($sheetData[$i]['A'])) {
	        		$validationErrors .= '<p>The product name is required on line no. '.$line.'</p>';
	        	}

	        	if (!isset($sheetData[$i]['B']) OR empty($sheetData[$i]['B'])) {
	        		$validationErrors .= '<p>The paper size is required on line no. '.$line.'</p>';
	        	}      	

	        	if (!isset($sheetData[$i]['C']) OR empty($sheetData[$i]['C'])) {
	        		$validationErrors .= '<p>The paper gsm is required on line no. '.$line.'</p>';
	        	}

	        	if (!isset($sheetData[$i]['D']) OR empty($sheetData[$i]['D'])) {
	        		$validationErrors .= '<p>The paper type is required on line no. '.$line.'</p>';
	        	}

	        	if (!isset($sheetData[$i]['E']) OR empty($sheetData[$i]['E'])) {
	        		$validationErrors .= '<p>The print side is required on line no. '.$line.'</p>';
	        	}

	        	if (!isset($sheetData[$i]['F']) OR empty($sheetData[$i]['F'])) {
	        		$validationErrors .= '<p>The color is required on line no. '.$line.'</p>';
	        	}

	        	if (!isset($sheetData[$i]['G']) OR empty($sheetData[$i]['G'])) {
	        		$validationErrors .= '<p>The price is required on line no. '.$line.'</p>';
	        	} elseif (!is_numeric($sheetData[$i]['G'])) {
	        		$validationErrors .= '<p>The price must be numeric on line no. '.$line.'</p>';
	        	}

	        	$pricingCount = PricingModel::
			    join('product', 'pricing.product_id', '=', 'product.id')
			    ->join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
			    ->join('gsm', 'pricing.paper_gsm_id', '=', 'gsm.id')
			    ->join('paper_type', 'pricing.paper_type_id', '=', 'paper_type.id')
			    ->where([
			        ['product.name', '=', $sheetData[$i]['A']],
			        ['paper_size.size', '=', $sheetData[$i]['B']],
			        ['gsm.gsm', '=', $sheetData[$i]['C']],
			        ['paper_type.paper_type', '=', $sheetData[$i]['D']],
			        ['pricing.side', '=', $sheetData[$i]['E']],
			        ['pricing.color', '=', $sheetData[$i]['F']]
			    ])->count();

			    if (!$pricingCount) {
			    	$validationErrors .= '<p>The product is not matched on line no. '.$line.'</p>';
			    }

	        	$line++;

	        }

	        return $validationErrors;

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