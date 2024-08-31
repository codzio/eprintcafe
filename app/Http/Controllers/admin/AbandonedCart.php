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
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\CustomerAddressModel;
use App\Models\BarcodeModel;
use App\Models\CartModel;
use App\Models\CustomCartModel;

use App\Models\PricingModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\ShippingModel;
use App\Models\PaperTypeModel;
use App\Models\PaperSizeModel;
use App\Models\GsmModel;

use App\Http\Controllers\SmsSending;
use App\Http\Controllers\EmailSending;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AbandonedCart extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'abandoned-cart')){
			return redirect(route('adminDashboard'));
		}

		$dateFrom = $request->get('dateFrom');
		$dateTo = $request->get('dateTo');
		$lessThan = $request->get('lessThan');
		$greaterThan = $request->get('greaterThan');
		$export = $request->get('export');

		if ($export == 'yes') {

			$query = CartModel::select('cart.*', 'customer.name', 'customer.email', 'customer.phone', 'product.name as product_name')
			->join('customer', 'cart.user_id', '=', 'customer.id')
			->join('product', 'cart.product_id', '=', 'product.id');

			if ($request->has('dateFrom') && !empty($request->input('dateFrom'))) {
		        $query->where('cart.created_at', '>=', $request->input('dateFrom'));
		    }

		    if ($request->has('dateTo') && !empty($request->input('dateTo'))) {
		        $query->where('cart.created_at', '<=', $request->input('dateTo'));
		    }

		    if ($request->has('lessThan') && !empty($request->input('lessThan'))) {
		        $query->where('cart.amount', '<', $request->input('lessThan'));
		    }

		    if ($request->has('greaterThan') && !empty($request->input('greaterThan'))) {
		        $query->where('cart.amount', '>', $request->input('greaterThan'));
		    }

		    $cartItems = $query->get();

		    if (!empty($cartItems) && $cartItems->count()) {
                // Create a new Spreadsheet object
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Set the header row
                $sheet->setCellValue('A1', 'Name');
                $sheet->setCellValue('B1', 'Email');
                $sheet->setCellValue('C1', 'Phone Number');
                $sheet->setCellValue('D1', 'Product Name');
                $sheet->setCellValue('E1', 'Paper Size');
                $sheet->setCellValue('F1', 'Paper GSM');
                $sheet->setCellValue('G1', 'Paper Type');
                $sheet->setCellValue('H1', 'Print Side');
                $sheet->setCellValue('I1', 'Color');
                $sheet->setCellValue('J1', 'Binding');
                $sheet->setCellValue('K1', 'Lamination');
                $sheet->setCellValue('L1', 'Cover');
                $sheet->setCellValue('M1', 'Quantity');
                $sheet->setCellValue('N1', 'Number of Copies');
                $sheet->setCellValue('O1', 'Amount');
                $sheet->setCellValue('P1', 'Created At');

                $row = 2;
                foreach ($cartItems as $item) {
                    $paperSizeName = PaperSizeModel::where('id', $item->paper_size_id)->value('size');
                    $paperGsm = GsmModel::where('id', $item->paper_gsm_id)->value('gsm');		        	
                    $paperTypeName = PaperTypeModel::where('id', $item->paper_type_id)->value('paper_type');

                    $bindingName = BindingModel::where('id', $item->binding_id)->value('binding_name');
		        	
                    $getLaminationData = LaminationModel::where('id', $item->lamination_id)->first();
                    $laminationName = $getLaminationData ? $getLaminationData->lamination . ' - ' . $getLaminationData->lamination_type : '';

                    $coverName = CoverModel::where('id', $item->cover_id)->value('cover');

                    $sheet->setCellValue('A' . $row, $item->name);
                    $sheet->setCellValue('B' . $row, $item->email);
                    $sheet->setCellValue('C' . $row, $item->phone);
                    $sheet->setCellValue('D' . $row, $item->product_name);
                    $sheet->setCellValue('E' . $row, $paperSizeName);
                    $sheet->setCellValue('F' . $row, $paperGsm);
                    $sheet->setCellValue('G' . $row, $paperTypeName);
                    $sheet->setCellValue('H' . $row, $item->print_side);
                    $sheet->setCellValue('I' . $row, $item->color);
                    $sheet->setCellValue('J' . $row, $bindingName);
                    $sheet->setCellValue('K' . $row, $laminationName);
                    $sheet->setCellValue('L' . $row, $coverName);
                    $sheet->setCellValue('M' . $row, $item->qty);
                    $sheet->setCellValue('N' . $row, $item->no_of_copies);
                    $sheet->setCellValue('O' . $row, $item->amount);
                    $sheet->setCellValue('P' . $row, $item->created_at);
                    $row++;
                }

                $fileName = 'cart-data_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                exit;
            } else {
                return redirect(route('adminAbandonedCart'));
            }

		}

		$data = array(
			'title' => 'Abandoned Cart',
			'pageTitle' => 'Abandoned Cart',
			'menu' => 'abandoned-cart',
		);

		return view('admin/abandoned-cart/index', $data);

	}

	public function get(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'abandoned-cart')){
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

		    //$singleDelUrl = route('adminDeleteOrder');
		    $singleDelUrl = '';

		    //get type
		    $columnIndex_arr = $request->get('orders');
		    $columnName_arr = $request->get('columns');
		    $order_arr = $request->get('orders');
		    $search_arr = $request->get('search');

		    $columnIndex = isset($columnIndex_arr[0]['column'])? $columnIndex_arr[0]['column']:''; // Column index
		    $columnName = !empty($columnIndex)? $columnName_arr[$columnIndex]['data']:''; // Column name
		    $columnSortOrder = !empty($order_arr)? $order_arr[0]['dir']:''; // asc or desc
		    $searchValue = $search_arr['value']; // Search value

		     // Total records
		    $totalRecords = CartModel::join('customer', 'cart.user_id', '=', 'customer.id')->whereNotNull('cart.user_id')->select('count(*) as allcount');
		    $totalRecords = $totalRecords->count();

		    $totalRecordswithFilter = CartModel::join('customer', 'cart.user_id', '=', 'customer.id')->whereNotNull('cart.user_id')->select('count(*) as allcount');

		    // if (!empty($searchValue)) {
		    // 	$totalRecordswithFilter->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $totalRecordswithFilter->where(function ($query) use ($searchValue) {

			        $query->where('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.email', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.city', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('cart.created_at', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('orders.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('orders.is_active', 'like', '%0%');
			        // }

			    });
			}

		    $totalRecordswithFilter = $totalRecordswithFilter->count();

		     // Fetch records
		    $records = CartModel::join('customer', 'cart.user_id', '=', 'customer.id')->whereNotNull('cart.user_id')->groupBy('cart.user_id')->select('customer.id', 'customer.name', 'customer.phone', 'customer.city', 'customer.email', 'cart.created_at')->skip($start)->take($rowperpage);

		    // if (!empty($searchValue)) {
		    // 	$records->where('admins.name', 'like', '%' .$searchValue . '%');
		    // }

		    if (!empty($searchValue)) {
			    $records->where(function ($query) use ($searchValue) {
			        
			        $query->where('customer.name', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.phone', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.email', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('customer.city', 'like', '%' . $searchValue . '%')
			        	  ->orWhere('cart.created_at', 'like', '%' . $searchValue . '%');

			        // if (strtolower($searchValue) == 'active') {
			        // 	$query->orWhere('orders.is_active', 'like', '%1%');
			        // } elseif (strtolower($searchValue) == 'inactive') {
			        // 	$query->orWhere('orders.is_active', 'like', '%0%');
			        // }

			    });
			}

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('cart.id','desc');
		    }

		    $getAdminData = adminInfo();
		    $records = $records->get();
		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;

			        $viewUrl = route('adminViewAbandonedCart', $id);

			        $checkbox = '<div onclick="checkCheckbox(this)" class="form-check form-check-sm form-check-custom form-check-solid">
							<input name="delete[]" data-kt-check-target="#media .single-check-input" class="form-check-input" type="checkbox" value="'.$id.'" />
						</div>';

					$action = '
			          	<td class="text-end" data-kt-filemanager-table="action_dropdown">
						<div class="d-flex justify-content-end">
							<div class="ms-2">
								<div class="menu menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
									
									<div class="menu-item">
										<a title="View Order" href="'.$viewUrl.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-eye fs-2"></i></span>
										</a>
									</div>

								</div>
							</div>
						</div>
					</td>';
					
			        $data_arr[] = array(
			        	// "checkbox" => $checkbox,
			          	"name" => $record->name,
			          	"email" => $record->email,
			          	"phone" => $record->phone,
			          	"city" => $record->city,
			          	"created_at" => date('d-m-Y h:i A', strtotime($record->created_at)),
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

	public function view(Request $request, $id) {

		if (!can('read', 'abandoned-cart')){
			return redirect(route('adminDashboard'));
		}

		$customerData = CustomerModel::where('id', $id)->first();

		if (!empty($customerData) && $customerData->count()){

			$userId = $customerData->id;
			$customerData = $customerData;

			$getAdminData = adminInfo();
			$cartItems = CartModel::
			join('product', 'cart.product_id', '=', 'product.id')
			->where('user_id', $userId)
			->select('cart.*', 'product.name', 'product.thumbnail_id')
			->orderBy('cart.id', 'desc')
			->get();

			$data = array(
				'title' => 'Abandoned Cart',
				'pageTitle' => 'Abandoned Cart',
				'menu' => 'abandoned-cart',
				'cartItems' => $cartItems,
				'customer' => $customerData,
				'adminData' => $getAdminData,
			);

			return view('admin/abandoned-cart/view', $data);
			
		} else {
			return redirect(route('adminAbandonedCart'));
		}

	}

	public function moveToOrders(Request $request) {

		if ($request->ajax()) {

			if (!can('read', 'abandoned-cart')){
				return redirect(route('adminDashboard'));
			}

			$userId = $request->post('userId');

			$customerData = CustomerModel::where('id', $userId)->first();

			if (!empty($customerData) && $customerData->count()) {
				
				$cartItems = CartModel::
				join('product', 'cart.product_id', '=', 'product.id')
				->where('user_id', $userId)
				->select('cart.*', 'product.name', 'product.thumbnail_id')
				->orderBy('cart.id', 'desc')
				->get();

				if (!empty($cartItems) && $cartItems->count()) {

					$customCartData = $cartItems->map(function($cartItem) {
				        return [
				            'user_id' => $cartItem->user_id,
				            'product_id' => $cartItem->product_id,
				            'paper_size_id' => $cartItem->paper_size_id,
				            'paper_gsm_id' => $cartItem->paper_gsm_id,
				            'paper_type_id' => $cartItem->paper_type_id,
				            'print_side' => $cartItem->print_side,
				            'color' => $cartItem->color,
				            'binding_id' => $cartItem->binding_id,
				            'lamination_id' => $cartItem->lamination_id,
				            'cover_id' => $cartItem->cover_id,
				            'qty' => $cartItem->qty,
				            'no_of_copies' => $cartItem->no_of_copies,
				            'document_link' => $cartItem->document_link,
				            'file_path' => $cartItem->file_path,
				            'file_name' => $cartItem->file_name,
				            'remark' => $cartItem->remark,
				        ];
				    })->toArray();


				    $isInserted = CustomCartModel::insert($customCartData);

				    if ($isInserted) {

				    	//remove data from cart
				    	CartModel::where('user_id', $userId)->delete();

				    	$this->status = array(
							'error' => false,							
							'msg' => 'The user cart data has been moved.'
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
						'msg' => 'The system is unable to find the cart data.'
					);
				}
				
			} else {
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'The system is unable to find the customer.'
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