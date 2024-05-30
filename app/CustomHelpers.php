<?php

// namespace App;

// use Illuminate\Http\Request;


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Request;

use App\Models\AdminModel;
use App\Models\MediaModel;
use App\Models\SettingModel;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\CustomCartModel;
use App\Models\PaperSizeModel;
use App\Models\GsmModel;
use App\Models\PaperTypeModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\PricingModel;
use App\Models\CustomerModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\OrderItemModel;

function getImg($imageId) {
	$mediaData = MediaModel::where('id', $imageId)->first();
	if (!empty($mediaData)) {
		return url('public/').'/'.$mediaData->path;
	} else {
		return '';
	}
}

// Project Related Function End

function adminInfo($col='') {
	
	$adminSess = Session::get('adminSess');

	if (!empty($adminSess)) {
		
		$getAdminDetail = AdminModel::select('admins.*', 'roles.role_name', 'roles.permissions', 'media.path', 'media.alt')
		->join('roles', 'admins.role_id', '=', 'roles.id')
		->leftJoin('media', 'admins.profile', '=', 'media.id')
		->where('admins.id', $adminSess['adminId'])
		->first();

		if ($adminSess) {
			if (!empty($col)) {
				return $getAdminDetail->{$col};
			} else {
				return $getAdminDetail;
			}
		}

	} else {
		return false;
	}
	
}

function adminInfoById($id, $col='') {
	
	$getAdminDetail = AdminModel::select('admins.*', 'roles.role_name', 'roles.permissions', 'media.path', 'media.alt')
		->join('roles', 'admins.role_id', '=', 'roles.id')
		->leftJoin('media', 'admins.profile', '=', 'media.id')
		->where('admins.id', $id)
		->first();

	if (!empty($getAdminDetail)) {
		if (!empty($col)) {
			return $getAdminDetail->{$col};
		} else {
			return $getAdminDetail;
		}
	} else {
		return false;
	}
	
}

function adminId() {
	$adminSess = Session::get('adminSess');
	return $adminSess['adminId'];
}

function userInfo($col='') {
	
	$userSess = Session::get('userSess');

	if (!empty($userSess)) {
		
		$getUserDetail = UserModel::select('users.*', 'media.path', 'media.alt', 'badges.badge_img')
		->leftJoin('media', 'users.profile_picture', '=', 'media.id')
		->leftJoin('badges', 'users.badge_id', '=', 'badges.id')
		->where('users.id', $userSess['userId'])
		->first();

		if ($userSess) {
			if (!empty($col)) {
				return $getUserDetail->{$col};
			} else {
				return $getUserDetail;
			}
		}

	} else {
		return false;
	}
	
}

function userInfoById($id) {
	
	return UserModel::select('users.*', 'media.path', 'media.alt')
	->leftJoin('media', 'users.profile_picture', '=', 'media.id')
	->where('users.id', $id)
	->first();
}

function checkPermission($module, $permission) {
	$getAdminData = adminInfo();

	//super admin role id is 1
	if ($getAdminData->role_id == 1) {
		
		return true;

	} else {

		$getPermission = $getAdminData->permissions;

		if (!empty($getPermission)) {
			
			$getPermission = json_decode($getPermission);

			if (isset($getPermission->{$module}->{$permission}) && $getPermission->{$module}->{$permission}) {
				return true;
			}

			return false;

		} else {
			return false;
		}

	}
}

if (!function_exists('validateSlug')) {
	function validateSlug($tbl, $col, $slug, $id=null) {

		if (empty($id)) {
			$isSlugExist = DB::table($tbl)->where($col, 'like', $slug.'%')->select($col);
		} else {
			$isSlugExist = DB::table($tbl)->where($col, 'like', $slug.'%')->where('id', '!=', $id)->select($col);
		}

		$totalRow = $isSlugExist->count();
		$result = $isSlugExist->get();

		$data = array();

		if ($totalRow) {
			foreach ($result as $row) {
				$data[] = $row->{$col};
			}
		}

		if(in_array($slug, $data)) {
	    	$count = 0;
	    	while( in_array( ($slug . '-' . ++$count ), $data) );
	    	$slug = $slug . '-' . $count;
	   	}

	   	return $slug;
	}
}

if (!function_exists('validateMediaSlug')) {
	function validateMediaSlug($tbl, $col, $slug, $id=null) {

		if (empty($id)) {
			$isSlugExist = DB::table($tbl)
			->where($col, 'like', $slug.'%')
			->where('year', '=', date('Y'))
			->where('month', '=', date('m'))
			->where('date', '=', date('d'))
			->select($col);
		} else {
			$isSlugExist = DB::table($tbl)
			->where($col, 'like', $slug.'%')
			->where('year', '=', date('Y'))
			->where('month', '=', date('m'))
			->where('date', '=', date('d'))
			->where('id', '!=', $id)
			->select($col);
		}

		$totalRow = $isSlugExist->count();
		$result = $isSlugExist->get();

		$data = array();

		if ($totalRow) {
			foreach ($result as $row) {
				$data[] = $row->{$col};
			}
		}

		if(in_array($slug, $data)) {
	    	$count = 0;
	    	while( in_array( ($slug . '-' . ++$count ), $data) );
	    	$slug = $slug . '-' . $count;
	   	}

	   	return $slug;
	}
}

function formatSize($bytes){ 
	$kb = 1024;
	$mb = $kb * 1024;
	$gb = $mb * 1024;
	$tb = $gb * 1024;

	if (($bytes >= 0) && ($bytes < $kb)) {
		return $bytes . ' B';
	} elseif (($bytes >= $kb) && ($bytes < $mb)) {
		return ceil($bytes / $kb) . ' KB';
	} elseif (($bytes >= $mb) && ($bytes < $gb)) {
		return ceil($bytes / $mb) . ' MB';
	} elseif (($bytes >= $gb) && ($bytes < $tb)) {
		return ceil($bytes / $gb) . ' GB';
	} elseif ($bytes >= $tb) {
		return ceil($bytes / $tb) . ' TB';
	} else {
		return $bytes . ' B';
	}
}

function folderSize($dir){
	$total_size = 0;
	$count = 0;
	$dir_array = scandir($dir);
  	foreach($dir_array as $key=>$filename){
    	if($filename!=".." && $filename!="."){
       		if(is_dir($dir."/".$filename)){
          		$new_foldersize = foldersize($dir."/".$filename);
          		$total_size = $total_size+ $new_foldersize;
	        }else if(is_file($dir."/".$filename)){
	          	$total_size = $total_size + filesize($dir."/".$filename);
	         	$count++;
	        }
   		}
   	}
	return $total_size;
}

function setting($field) {
	$setting = new SettingModel();
	$data = $setting->getSetting($field);
	
	if (isset($data)) {
		return $data;
	}

	return false;
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "https://" . $url;
    }
    return $url;
}

function can($permission, $module) {
	if (!empty($permission) && !empty($module)) {
		
		$adminData = adminInfo();

		if (!empty($adminData)) {
			
			if ($adminData->role_id == 1) {
				return true;
			}

			if (!empty($adminData->permissions)) {
				
				$getPermission = json_decode($adminData->permissions);

				if (isset($getPermission->{$module}->{$permission})) {
					return true;
				} else {
					return false;				
				}

			}

			return false;

		} else {
			return false;
		}

	} else {
		return false;
	}
}


//Customer

function customerId() {
	$customerSess = Session::get('customerSess');
	if (isset($customerSess['customerId']) && !empty($customerSess['customerId'])) {
		return $customerSess['customerId'];
	} else {
		return null;
	}
}

function tempId() {
	$tempId = Request::cookie('tempUserId');
	if (!empty($tempId)) {
		return $tempId;
	} else {
		return null;
	}
}

function cartData() {
	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	$getCartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		// ->take(1)
		->get();

	if (!empty($getCartData) && $getCartData->count()) {
		return $getCartData;
	} else {
		return false;
	}

}

function getCartId() {
	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	return CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->orderBy('cart.id', 'desc')
		->take(1)
		->value('cart.id');
}

function getCartProductId() {
	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	return CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->orderBy('cart.id', 'desc')
		->take(1)
		->value('cart.product_id');
}

function productSpec($cartId) {
	
	$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where(['cart.id' => $cartId, 'product.is_active' => 1])
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id;
		$paperGsmId = $cartData->paper_gsm_id;
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$spec = '';

		//Get Paper Size
		$getPaperSize = PaperSizeModel::where('id', $paperSizeId)->value('size');

		if (!empty($getPaperSize)) {
			$spec .= "<p><strong>Paper Size:</strong> ".$getPaperSize."</p>";
		}

		//Get Paper Gsm
		$getPaperGsm = GsmModel::where('id', $paperGsmId)->value('gsm');

		if (!empty($getPaperGsm)) {
			$spec .= "<p><strong>Paper GSM:</strong> ".$getPaperGsm."</p>";
		}

		//Get Paper Type
		$getPaperType = PaperTypeModel::where('id', $paperTypeId)->value('paper_type');

		if (!empty($getPaperType)) {
			$spec .= "<p><strong>Paper Type:</strong> ".$getPaperType."</p>";
		}

		//Get Print Side
		$spec .= "<p><strong>Print Side:</strong> ".$cartData->print_side."</p>";

		//Get Color
		$spec .= "<p><strong>Color:</strong> ".$cartData->color."</p>";

		//Get Binding
		$getBinding = BindingModel::where('id', $bindingId)->value('binding_name');

		if (!empty($getBinding)) {
			$spec .= "<p><strong>Binding:</strong> ".$getBinding."</p>";
		}

		//Get Lamination
		$getLamination = LaminationModel::where('id', $laminationId)->first();

		if (!empty($getLamination)) {
			$spec .= "<p><strong>Lamination:</strong> ".$getLamination->lamination.' - '.$getLamination->lamination_type."</p>";
		}

		//Get Cover
		$getCover = CoverModel::where('id', $coverId)->value('cover');

		if (!empty($getCover)) {
			$spec .= "<p><strong>Cover:</strong> ".$getCover."</p>";
		}

		return $spec;

	} else {
		return false;
	}
}

function productSpecForCusOrder($customCartId) {
	
	$cartData = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
		->where(['custom_cart.id' => $customCartId, 'product.is_active' => 1])
		->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('custom_cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id;
		$paperGsmId = $cartData->paper_gsm_id;
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$spec = '';

		//Get Paper Size
		$getPaperSize = PaperSizeModel::where('id', $paperSizeId)->value('size');

		if (!empty($getPaperSize)) {
			$spec .= "<p><strong>Paper Size:</strong> ".$getPaperSize."</p>";
		}

		//Get Paper Gsm
		$getPaperGsm = GsmModel::where('id', $paperGsmId)->value('gsm');

		if (!empty($getPaperGsm)) {
			$spec .= "<p><strong>Paper GSM:</strong> ".$getPaperGsm."</p>";
		}

		//Get Paper Type
		$getPaperType = PaperTypeModel::where('id', $paperTypeId)->value('paper_type');

		if (!empty($getPaperType)) {
			$spec .= "<p><strong>Paper Type:</strong> ".$getPaperType."</p>";
		}

		//Get Print Side
		$spec .= "<p><strong>Print Side:</strong> ".$cartData->print_side."</p>";

		//Get Color
		$spec .= "<p><strong>Color:</strong> ".$cartData->color."</p>";

		//Get Binding
		$getBinding = BindingModel::where('id', $bindingId)->value('binding_name');

		if (!empty($getBinding)) {
			$spec .= "<p><strong>Binding:</strong> ".$getBinding."</p>";
		}

		//Get Lamination
		$getLamination = LaminationModel::where('id', $laminationId)->first();

		if (!empty($getLamination)) {
			$spec .= "<p><strong>Lamination:</strong> ".$getLamination->lamination.' - '.$getLamination->lamination_type."</p>";
		}

		//Get Cover
		$getCover = CoverModel::where('id', $coverId)->value('cover');

		if (!empty($getCover)) {
			$spec .= "<p><strong>Cover:</strong> ".$getCover."</p>";
		}

		return $spec;

	} else {
		return false;
	}
}

function productSpecForSaveCusOrder($subOrderId) {
	
	$orderData = OrderItemModel::join('product', 'order_items.product_id', '=', 'product.id')
		->where(['order_items.id' => $subOrderId, 'product.is_active' => 1])
		->select('order_items.*', 'product.name', 'product.thumbnail_id')
		->orderBy('order_items.id', 'desc')
		->first();

	if (!empty($orderData)) {

		$idsList = json_decode($orderData->product_detail_ids);		
			
		$productId = $orderData->product_id;
		$paperSizeId = $idsList->paperSizeId;
		$paperGsmId = $idsList->paperGsmId;
		$paperTypeId = $idsList->paperTypeId;
		$printSide = $idsList->paperSides;
		$color = $idsList->color;
		$bindingId = $idsList->binding;
		$laminationId = $idsList->lamination;
		$coverId = $idsList->cover;

		$spec = '';

		//Get Paper Size
		$getPaperSize = PaperSizeModel::where('id', $paperSizeId)->value('size');

		if (!empty($getPaperSize)) {
			$spec .= "<p><strong>Paper Size:</strong> ".$getPaperSize."</p>";
		}

		//Get Paper Gsm
		$getPaperGsm = GsmModel::where('id', $paperGsmId)->value('gsm');

		if (!empty($getPaperGsm)) {
			$spec .= "<p><strong>Paper GSM:</strong> ".$getPaperGsm."</p>";
		}

		//Get Paper Type
		$getPaperType = PaperTypeModel::where('id', $paperTypeId)->value('paper_type');

		if (!empty($getPaperType)) {
			$spec .= "<p><strong>Paper Type:</strong> ".$getPaperType."</p>";
		}

		//Get Print Side
		$spec .= "<p><strong>Print Side:</strong> ".$printSide."</p>";

		//Get Color
		$spec .= "<p><strong>Color:</strong> ".$color."</p>";

		//Get Binding
		$getBinding = BindingModel::where('id', $bindingId)->value('binding_name');

		if (!empty($getBinding)) {
			$spec .= "<p><strong>Binding:</strong> ".$getBinding."</p>";
		}

		//Get Lamination
		$getLamination = LaminationModel::where('id', $laminationId)->first();

		if (!empty($getLamination)) {
			$spec .= "<p><strong>Lamination:</strong> ".$getLamination->lamination.' - '.$getLamination->lamination_type."</p>";
		}

		//Get Cover
		$getCover = CoverModel::where('id', $coverId)->value('cover');

		if (!empty($getCover)) {
			$spec .= "<p><strong>Cover:</strong> ".$getCover."</p>";
		}

		return $spec;

	} else {
		return false;
	}
}

function productSpecForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $printSide, $color, $bindingId, $laminationId, $coverId) {

	$spec = '';

	//Get Paper Size
	$getPaperSize = PaperSizeModel::where('id', $paperSizeId)->value('size');

	if (!empty($getPaperSize)) {
		$spec .= "<p><strong>Paper Size:</strong> ".$getPaperSize."</p>";
	}

	//Get Paper Gsm
	$getPaperGsm = GsmModel::where('id', $paperGsmId)->value('gsm');

	if (!empty($getPaperGsm)) {
		$spec .= "<p><strong>Paper GSM:</strong> ".$getPaperGsm."</p>";
	}

	//Get Paper Type
	$getPaperType = PaperTypeModel::where('id', $paperTypeId)->value('paper_type');

	if (!empty($getPaperType)) {
		$spec .= "<p><strong>Paper Type:</strong> ".$getPaperType."</p>";
	}

	//Get Print Side
	$spec .= "<p><strong>Print Side:</strong> ".$printSide."</p>";

	//Get Color
	$spec .= "<p><strong>Color:</strong> ".$color."</p>";

	//Get Binding
	$getBinding = BindingModel::where('id', $bindingId)->value('binding_name');

	if (!empty($getBinding)) {
		$spec .= "<p><strong>Binding:</strong> ".$getBinding."</p>";
	}

	//Get Lamination
	$getLamination = LaminationModel::where('id', $laminationId)->first();

	if (!empty($getLamination)) {
		$spec .= "<p><strong>Lamination:</strong> ".$getLamination->lamination.' - '.$getLamination->lamination_type."</p>";
	}

	//Get Cover
	$getCover = CoverModel::where('id', $coverId)->value('cover');

	if (!empty($getCover)) {
		$spec .= "<p><strong>Cover:</strong> ".$getCover."</p>";
	}

	return $spec;
}

function productPrice() {

	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id;
		$paperGsmId = $cartData->paper_gsm_id;
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$data = [
			'per_sheet_weight' => 0,
			'paper_type_price' => 0,
			'printSideAndColorPrice' => 0,
			'binding' => 0,
			'lamination' => 0,
			'cover' => 0,
			'price' => 0,
			'shipping' => 0,
			'discount' => 0,
			'total' => 0
		];

		$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

		if (!empty($getPaperGsm)) {
			$data['per_sheet_weight'] = $getPaperGsm->per_sheet_weight;
			$data['paper_type_price'] = $getPaperGsm->paper_type_price;
		}

		$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

		if (!empty($printSideAndColorPrice)) {
			$data['printSideAndColorPrice'] = $printSideAndColorPrice;
		}

		if (!empty($bindingId)) {
			
			$bindingData = BindingModel::where('id', $bindingId)->value('price');

			if (!empty($bindingData)) {
				$data['binding'] = $bindingData;
			}

		}

		if (!empty($laminationId)) {
			
			$laminationData = LaminationModel::where('id', $laminationId)->value('price');

			if (!empty($laminationData)) {
				$data['lamination'] = $laminationData;
			}

		}

		// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		$data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];

		$getCouponData = Session::get('couponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('shippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		//no of copies
		$noOfCopies = $cartData->no_of_copies;

		$getPriceCal = (($data['price']*$cartData->qty))+$data['binding']+$data['lamination']+$data['cover'];
		$getPriceCal = $getPriceCal*$noOfCopies;

		// if (!empty($noOfCopies)) {
				
		// 	$noOfCopies = $noOfCopies+1;
		// 	$getPriceCal = (($data['price']*$cartData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

		// } else {
		// 	$getPriceCal = ($data['price']*$cartData->qty)+$data['binding']+$data['lamination']+$data['cover'];
		// }

		$data['discount'] = $discount;
		$data['shipping'] = $shipping;
		// $data['subTotal'] = $getPriceCal;
		// $data['total'] = ($getPriceCal-$discount)+$shipping;

		$data['subTotal'] = round($getPriceCal, 2);
		$data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

		return (object) $data;

	} else {
		return false;
	}

}

function productPriceMulti() {

	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	$getCartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->get();

	if (!empty($getCartData)) {

		$data = [
			'per_sheet_weight' => 0,
			'paper_type_price' => 0,
			'printSideAndColorPrice' => 0,
			'binding' => 0,
			'split' => 0,
			'lamination' => 0,
			'cover' => 0,
			'price' => 0,
			'shipping' => 0,
			'discount' => 0,
			'subTotal' => 0,
			'total' => 0
		];
			
		foreach ($getCartData as $cartData) {
			
			$productId = $cartData->product_id;
			$paperSizeId = $cartData->paper_size_id;
			$paperGsmId = $cartData->paper_gsm_id;
			$paperTypeId = $cartData->paper_type_id;
			$printSide = $cartData->print_side;
			$color = $cartData->color;
			$bindingId = $cartData->binding_id;
			$laminationId = $cartData->lamination_id;
			$coverId = $cartData->cover_id;

			$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

			$per_sheet_weight = 0;
			$paper_type_price = 0;

			if (!empty($getPaperGsm)) {
				$per_sheet_weight = $getPaperGsm->per_sheet_weight;
				$paper_type_price = $getPaperGsm->paper_type_price;
			}

			$data['per_sheet_weight'] += $per_sheet_weight;
			$data['paper_type_price'] += $paper_type_price;

			$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

			if (!empty($printSideAndColorPrice)) {
				$data['printSideAndColorPrice'] += $printSideAndColorPrice;
			}

			$bindingPrice = 0;

			if (!empty($bindingId)) {
				
				//$bindingData = BindingModel::where('id', $bindingId)->value('price');
				$bindingData = BindingModel::where('id', $bindingId)->first();

				// if (!empty($bindingData)) {
				// 	$bindingPrice = $bindingData;
				// 	$data['binding'] += $bindingPrice;
				// }

				if (!empty($bindingData)) {

					$totalSplit = 1;
					$multiple = ($printSide == 'Double Side')? 2:1;
					$bindingSplit = $bindingData->split;
		            $bindingSplit = $bindingSplit*$multiple;

		            if (!empty($bindingSplit) && $cartData->qty > $bindingSplit) {
		              $totalSplit = ceil($cartData->qty/$bindingSplit); 
		            } else {
		            	$totalSplit = 0;
		            }
		            
					//check the binding split & update the price
					if ($totalSplit > 1) {
						$bindingPrice = $bindingData->price*$totalSplit;
					} else {
						$bindingPrice = $bindingData->price;
					}

					$data['binding'] += $bindingPrice;
					$data['split'] += $totalSplit;

				}

			}

			$laminationPrice = 0;

			if (!empty($laminationId)) {
				
				$laminationData = LaminationModel::where('id', $laminationId)->value('price');

				if (!empty($laminationData)) {
					$laminationPrice = $laminationData;
					$data['lamination'] += $laminationPrice;
				}

			}

			// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

			// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

			// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];
			$price = $paper_type_price+$printSideAndColorPrice;
			$data['price'] = $price;

			//no of copies
			$noOfCopies = $cartData->no_of_copies;

			$getPriceCal = (($price*$cartData->qty))+$bindingPrice+$laminationPrice+$data['cover'];
			$getPriceCal = $getPriceCal*$noOfCopies;

			// if (!empty($noOfCopies)) {
					
			// 	$noOfCopies = $noOfCopies+1;
			// 	$getPriceCal = (($data['price']*$cartData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

			// } else {
			// 	$getPriceCal = ($data['price']*$cartData->qty)+$data['binding']+$data['lamination']+$data['cover'];
			// }

			// $data['discount'] = $discount;
			// $data['shipping'] = $shipping;
			$data['subTotal'] += $getPriceCal;

		}

		$getCouponData = Session::get('couponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		$data['discount'] = $discount;

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('shippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		$data['shipping'] = $shipping;

		$data['total'] = round(($data['subTotal']-$data['discount'])+$data['shipping'], 2);
		return (object) $data;

	} else {
		return false;
	}

}

function productSinglePrice($productId) {

	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1, 'cart.product_id' => $productId];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id;
		$paperGsmId = $cartData->paper_gsm_id;
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$data = [
			'per_sheet_weight' => 0,
			'paper_type_price' => 0,
			'printSideAndColorPrice' => 0,
			'binding' => 0,
			'split' => 0,
			'lamination' => 0,
			'cover' => 0,
			'price' => 0,
			'shipping' => 0,
			'discount' => 0,
			'total' => 0
		];

		$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

		if (!empty($getPaperGsm)) {
			$data['per_sheet_weight'] = $getPaperGsm->per_sheet_weight;
			$data['paper_type_price'] = $getPaperGsm->paper_type_price;
		}

		$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

		if (!empty($printSideAndColorPrice)) {
			$data['printSideAndColorPrice'] = $printSideAndColorPrice;
		}

		if (!empty($bindingId)) {
			
			//$bindingData = BindingModel::where('id', $bindingId)->value('price');
			$bindingData = BindingModel::where('id', $bindingId)->first();

			if (!empty($bindingData)) {

				$totalSplit = 1;
				$multiple = ($printSide == 'Double Side')? 2:1;
				$bindingSplit = $bindingData->split;
	            $bindingSplit = $bindingSplit*$multiple;

	            if (!empty($bindingSplit) && $cartData->qty > $bindingSplit) {
	              $totalSplit = ceil($cartData->qty/$bindingSplit); 
	            } else {
	            	$totalSplit = 0;
	            }

				$data['binding'] = $bindingData->price;
				$data['split'] = $totalSplit;
			}

		}

		//check the binding split & update the price
		if ($data['split'] > 1) {
			$data['binding'] = $data['binding']*$data['split'];
		}

		if (!empty($laminationId)) {
			
			$laminationData = LaminationModel::where('id', $laminationId)->value('price');

			if (!empty($laminationData)) {
				$data['lamination'] = $laminationData;
			}

		}

		// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		$data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];

		$getCouponData = Session::get('couponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('shippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		//no of copies
		$noOfCopies = $cartData->no_of_copies;

		$getPriceCal = (($data['price']*$cartData->qty))+$data['binding']+$data['lamination']+$data['cover'];
		$getPriceCal = $getPriceCal*$noOfCopies;

		// if (!empty($noOfCopies)) {
				
		// 	$noOfCopies = $noOfCopies+1;
		// 	$getPriceCal = (($data['price']*$cartData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

		// } else {
		// 	$getPriceCal = ($data['price']*$cartData->qty)+$data['binding']+$data['lamination']+$data['cover'];
		// }

		$data['discount'] = $discount;
		$data['shipping'] = $shipping;
		// $data['subTotal'] = $getPriceCal;
		// $data['total'] = ($getPriceCal-$discount)+$shipping;

		$data['subTotal'] = round($getPriceCal, 2);
		$data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

		return (object) $data;

	} else {
		return false;
	}

}

function productSinglePriceForCusOrder($productId, $userId) {
	
	$userId = !empty($userId)? $userId:customerId();

	$cond = ['product.is_active' => 1, 'custom_cart.product_id' => $productId, 'custom_cart.user_id' => $userId];

	$cartData = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
		->where($cond)
		->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('custom_cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id;
		$paperGsmId = $cartData->paper_gsm_id;
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$data = [
			'per_sheet_weight' => 0,
			'paper_type_price' => 0,
			'printSideAndColorPrice' => 0,
			'binding' => 0,
			'split' => 0,
			'lamination' => 0,
			'cover' => 0,
			'price' => 0,
			'shipping' => 0,
			'discount' => 0,
			'total' => 0
		];

		$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

		if (!empty($getPaperGsm)) {
			$data['per_sheet_weight'] = $getPaperGsm->per_sheet_weight;
			$data['paper_type_price'] = $getPaperGsm->paper_type_price;
		}

		$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

		if (!empty($printSideAndColorPrice)) {
			$data['printSideAndColorPrice'] = $printSideAndColorPrice;
		}

		if (!empty($bindingId)) {
			
			//$bindingData = BindingModel::where('id', $bindingId)->value('price');
			$bindingData = BindingModel::where('id', $bindingId)->first();

			if (!empty($bindingData)) {

				$totalSplit = 1;
				$multiple = ($printSide == 'Double Side')? 2:1;
				$bindingSplit = $bindingData->split;
	            $bindingSplit = $bindingSplit*$multiple;

	            if (!empty($bindingSplit) && $cartData->qty > $bindingSplit) {
	              $totalSplit = ceil($cartData->qty/$bindingSplit); 
	            } else {
	            	$totalSplit = 0;
	            }

				$data['binding'] = $bindingData->price;
				$data['split'] = $totalSplit;
			}

		}

		//check the binding split & update the price
		if ($data['split'] > 1) {
			$data['binding'] = $data['binding']*$data['split'];
		}

		if (!empty($laminationId)) {
			
			$laminationData = LaminationModel::where('id', $laminationId)->value('price');

			if (!empty($laminationData)) {
				$data['lamination'] = $laminationData;
			}

		}

		// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		$data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];

		$getCouponData = Session::get('couponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('shippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		//no of copies
		$noOfCopies = $cartData->no_of_copies;

		$getPriceCal = (($data['price']*$cartData->qty))+$data['binding']+$data['lamination']+$data['cover'];
		$getPriceCal = $getPriceCal*$noOfCopies;

		// if (!empty($noOfCopies)) {
				
		// 	$noOfCopies = $noOfCopies+1;
		// 	$getPriceCal = (($data['price']*$cartData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

		// } else {
		// 	$getPriceCal = ($data['price']*$cartData->qty)+$data['binding']+$data['lamination']+$data['cover'];
		// }

		$data['discount'] = $discount;
		$data['shipping'] = $shipping;
		// $data['subTotal'] = $getPriceCal;
		// $data['total'] = ($getPriceCal-$discount)+$shipping;

		$data['subTotal'] = round($getPriceCal, 2);
		$data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

		return (object) $data;

	} else {
		return false;
	}

}

function productSinglePriceForSaveCusOrder($productId, $orderId) {

	$cond = ['product.is_active' => 1, 'order_items.product_id' => $productId, 'order_items.order_id' => $orderId];

	$orderData = OrderItemModel::join('product', 'order_items.product_id', '=', 'product.id')
		->where($cond)
		->select('order_items.*', 'product.name', 'product.thumbnail_id')
		->orderBy('order_items.id', 'desc')
		->first();

	if (!empty($orderData)) {

		$idsList = json_decode($orderData->product_detail_ids);		
			
		$productId = $orderData->product_id;
		$paperSizeId = $idsList->paperSizeId;
		$paperGsmId = $idsList->paperGsmId;
		$paperTypeId = $idsList->paperTypeId;
		$printSide = $idsList->paperSides;
		$color = $idsList->color;
		$bindingId = $idsList->binding;
		$laminationId = $idsList->lamination;
		$coverId = $idsList->cover;

		$data = [
			'per_sheet_weight' => 0,
			'paper_type_price' => 0,
			'printSideAndColorPrice' => 0,
			'binding' => 0,
			'split' => 0,
			'lamination' => 0,
			'cover' => 0,
			'price' => 0,
			'shipping' => 0,
			'discount' => 0,
			'total' => 0
		];

		$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

		if (!empty($getPaperGsm)) {
			$data['per_sheet_weight'] = $getPaperGsm->per_sheet_weight;
			$data['paper_type_price'] = $getPaperGsm->paper_type_price;
		}

		$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

		if (!empty($printSideAndColorPrice)) {
			$data['printSideAndColorPrice'] = $printSideAndColorPrice;
		}

		if (!empty($bindingId)) {
			
			//$bindingData = BindingModel::where('id', $bindingId)->value('price');
			$bindingData = BindingModel::where('id', $bindingId)->first();

			if (!empty($bindingData)) {

				$totalSplit = 1;
				$multiple = ($printSide == 'Double Side')? 2:1;
				$bindingSplit = $bindingData->split;
	            $bindingSplit = $bindingSplit*$multiple;

	            if (!empty($bindingSplit) && $orderData->qty > $bindingSplit) {
	              $totalSplit = ceil($orderData->qty/$bindingSplit); 
	            } else {
	            	$totalSplit = 0;
	            }

				$data['binding'] = $bindingData->price;
				$data['split'] = $totalSplit;
			}

		}

		//check the binding split & update the price
		if ($data['split'] > 1) {
			$data['binding'] = $data['binding']*$data['split'];
		}

		if (!empty($laminationId)) {
			
			$laminationData = LaminationModel::where('id', $laminationId)->value('price');

			if (!empty($laminationData)) {
				$data['lamination'] = $laminationData;
			}

		}

		// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		$data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];

		$getCouponData = Session::get('couponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('shippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		//no of copies
		$noOfCopies = $orderData->no_of_copies;

		$getPriceCal = (($data['price']*$orderData->qty))+$data['binding']+$data['lamination']+$data['cover'];
		$getPriceCal = $getPriceCal*$noOfCopies;

		// if (!empty($noOfCopies)) {
				
		// 	$noOfCopies = $noOfCopies+1;
		// 	$getPriceCal = (($data['price']*$orderData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

		// } else {
		// 	$getPriceCal = ($data['price']*$orderData->qty)+$data['binding']+$data['lamination']+$data['cover'];
		// }

		$data['discount'] = $discount;
		$data['shipping'] = $shipping;
		// $data['subTotal'] = $getPriceCal;
		// $data['total'] = ($getPriceCal-$discount)+$shipping;

		$data['subTotal'] = round($getPriceCal, 2);
		$data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

		return (object) $data;

	} else {
		return false;
	}

}

function productSinglePriceForSaveCusOrderUpdate($productId, $productDetailIds, $qty, $noOfCopies) {

	$cond = ['product.is_active' => 1, 'product.id' => $productId];

	$productData = ProductModel::
		where($cond)
		->select('product.name', 'product.thumbnail_id')
		->orderBy('id', 'desc')
		->first();

	if (!empty($productData)) {	
			
		$productId = $productId;
		$paperSizeId = $productDetailIds['paperSizeId'];
		$paperGsmId = $productDetailIds['paperGsmId'];
		$paperTypeId = $productDetailIds['paperTypeId'];
		$printSide = $productDetailIds['paperSides'];
		$color = $productDetailIds['color'];
		$bindingId = $productDetailIds['binding'];
		$laminationId = $productDetailIds['lamination'];
		$coverId = $productDetailIds['cover'];

		$data = [
			'per_sheet_weight' => 0,
			'paper_type_price' => 0,
			'printSideAndColorPrice' => 0,
			'binding' => 0,
			'split' => 0,
			'lamination' => 0,
			'cover' => 0,
			'price' => 0,
			'shipping' => 0,
			'discount' => 0,
			'total' => 0
		];

		$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

		if (!empty($getPaperGsm)) {
			$data['per_sheet_weight'] = $getPaperGsm->per_sheet_weight;
			$data['paper_type_price'] = $getPaperGsm->paper_type_price;
		}

		$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

		if (!empty($printSideAndColorPrice)) {
			$data['printSideAndColorPrice'] = $printSideAndColorPrice;
		}

		if (!empty($bindingId)) {
			
			//$bindingData = BindingModel::where('id', $bindingId)->value('price');
			$bindingData = BindingModel::where('id', $bindingId)->first();

			if (!empty($bindingData)) {

				$totalSplit = 1;
				$multiple = ($printSide == 'Double Side')? 2:1;
				$bindingSplit = $bindingData->split;
	            $bindingSplit = $bindingSplit*$multiple;

	            if (!empty($bindingSplit) && $qty > $bindingSplit) {
	              $totalSplit = ceil($qty/$bindingSplit); 
	            } else {
	            	$totalSplit = 0;
	            }

				$data['binding'] = $bindingData->price;
				$data['split'] = $totalSplit;
			}

		}

		//check the binding split & update the price
		if ($data['split'] > 1) {
			$data['binding'] = $data['binding']*$data['split'];
		}

		if (!empty($laminationId)) {
			
			$laminationData = LaminationModel::where('id', $laminationId)->value('price');

			if (!empty($laminationData)) {
				$data['lamination'] = $laminationData;
			}

		}

		// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		$data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];

		$getCouponData = Session::get('couponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('shippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		$getPriceCal = (($data['price']*$qty))+$data['binding']+$data['lamination']+$data['cover'];
		$getPriceCal = $getPriceCal*$noOfCopies;

		// if (!empty($noOfCopies)) {
				
		// 	$noOfCopies = $noOfCopies+1;
		// 	$getPriceCal = (($data['price']*$orderData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

		// } else {
		// 	$getPriceCal = ($data['price']*$orderData->qty)+$data['binding']+$data['lamination']+$data['cover'];
		// }

		$data['discount'] = $discount;
		$data['shipping'] = $shipping;
		// $data['subTotal'] = $getPriceCal;
		// $data['total'] = ($getPriceCal-$discount)+$shipping;

		$data['subTotal'] = round($getPriceCal, 2);
		$data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

		return (object) $data;

	} else {
		return false;
	}

}

function productSinglePriceAbdCart($productId, $userId) {

	$tempId = Request::cookie('tempUserId');

	$cond = ['cart.product_id' => $productId];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	}

	$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id;
		$paperGsmId = $cartData->paper_gsm_id;
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$data = [
			'per_sheet_weight' => 0,
			'paper_type_price' => 0,
			'printSideAndColorPrice' => 0,
			'binding' => 0,
			'lamination' => 0,
			'cover' => 0,
			'price' => 0,
			'shipping' => 0,
			'discount' => 0,
			'total' => 0
		];

		$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

		if (!empty($getPaperGsm)) {
			$data['per_sheet_weight'] = $getPaperGsm->per_sheet_weight;
			$data['paper_type_price'] = $getPaperGsm->paper_type_price;
		}

		$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

		if (!empty($printSideAndColorPrice)) {
			$data['printSideAndColorPrice'] = $printSideAndColorPrice;
		}

		if (!empty($bindingId)) {
			
			$bindingData = BindingModel::where('id', $bindingId)->value('price');

			if (!empty($bindingData)) {
				$data['binding'] = $bindingData;
			}

		}

		if (!empty($laminationId)) {
			
			$laminationData = LaminationModel::where('id', $laminationId)->value('price');

			if (!empty($laminationData)) {
				$data['lamination'] = $laminationData;
			}

		}

		// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

		$data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];

		$getCouponData = Session::get('couponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('shippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		//no of copies
		$noOfCopies = $cartData->no_of_copies;

		$getPriceCal = (($data['price']*$cartData->qty))+$data['binding']+$data['lamination']+$data['cover'];
		$getPriceCal = $getPriceCal*$noOfCopies;

		// if (!empty($noOfCopies)) {
				
		// 	$noOfCopies = $noOfCopies+1;
		// 	$getPriceCal = (($data['price']*$cartData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

		// } else {
		// 	$getPriceCal = ($data['price']*$cartData->qty)+$data['binding']+$data['lamination']+$data['cover'];
		// }

		$data['discount'] = $discount;
		$data['shipping'] = $shipping;
		// $data['subTotal'] = $getPriceCal;
		// $data['total'] = ($getPriceCal-$discount)+$shipping;

		$data['subTotal'] = round($getPriceCal, 2);
		$data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

		return (object) $data;

	} else {
		return false;
	}

}

function productPriceForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $printSide, $color, $bindingId, $laminationId, $coverId, $qty, $noOfCopies) {

	$data = [
		'per_sheet_weight' => 0,
		'paper_type_price' => 0,
		'printSideAndColorPrice' => 0,
		'binding' => 0,
		'split' => 0,
		'lamination' => 0,
		'cover' => 0,
		'price' => 0,
		'shipping' => 0,
		'discount' => 0,
		'total' => 0
	];

	$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

	if (!empty($getPaperGsm)) {
		$data['per_sheet_weight'] = $getPaperGsm->per_sheet_weight;
		$data['paper_type_price'] = $getPaperGsm->paper_type_price;
	}

	$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

	if (!empty($printSideAndColorPrice)) {
		$data['printSideAndColorPrice'] = $printSideAndColorPrice;
	}

	if (!empty($bindingId)) {
		
		// $bindingData = BindingModel::where('id', $bindingId)->value('price');

		// if (!empty($bindingData)) {
		// 	$data['binding'] = $bindingData;
		// }

		$bindingData = BindingModel::where('id', $bindingId)->first();

		// if (!empty($bindingData)) {
		// 	$bindingPrice = $bindingData;
		// 	$data['binding'] += $bindingPrice;
		// }

		if (!empty($bindingData)) {

			$totalSplit = 1;
			$multiple = ($printSide == 'Double Side')? 2:1;
			$bindingSplit = $bindingData->split;
            $bindingSplit = $bindingSplit*$multiple;

            if (!empty($bindingSplit) && ($qty > $bindingSplit)) {
              $totalSplit = ceil($qty/$bindingSplit); 
            } else {
            	$totalSplit = 0;
            }
            
			//check the binding split & update the price
			if ($totalSplit > 1) {
				$bindingPrice = $bindingData->price*$totalSplit;
			} else {
				$bindingPrice = $bindingData->price;
			}

			$data['binding'] += $bindingPrice;
			$data['split'] += $totalSplit;

		}

	}

	if (!empty($laminationId)) {
		
		$laminationData = LaminationModel::where('id', $laminationId)->value('price');

		if (!empty($laminationData)) {
			$data['lamination'] = $laminationData;
		}

	}

	// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

	// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

	$data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];

	$getCouponData = Session::get('adminCouponSess');

	$discount = 0;

	if (!empty($getCouponData)) {
		$discount = $getCouponData['discount'];
	}

	//Shipping
	$shipping = 0;
	$getShippingData = Session::get('adminShippingSess');

	if (!empty($getShippingData)) {
		$shipping = $getShippingData['shipping'];
	}

	//no of copies
	$noOfCopies = $noOfCopies;

	$getPriceCal = (($data['price']*$qty))+$data['binding']+$data['lamination']+$data['cover'];
	$getPriceCal = $getPriceCal*$noOfCopies;

	// if (!empty($noOfCopies)) {
			
	// 	$noOfCopies = $noOfCopies+1;
	// 	$getPriceCal = (($data['price']*$cartData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

	// } else {
	// 	$getPriceCal = ($data['price']*$cartData->qty)+$data['binding']+$data['lamination']+$data['cover'];
	// }

	$data['discount'] = $discount;
	$data['shipping'] = $shipping;
	// $data['subTotal'] = round($getPriceCal, 2);
	// $data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

	$data['subTotal'] = round($getPriceCal, 2);
	$data['total'] = round(($getPriceCal-$discount)+$shipping, 2);

	return (object) $data;

}

function productPriceForCustomOrderMulti($customerId) {

	$data = [
		'per_sheet_weight' => 0,
		'paper_type_price' => 0,
		'printSideAndColorPrice' => 0,
		'binding' => 0,
		'split' => 0,
		'lamination' => 0,
		'cover' => 0,
		'price' => 0,
		'shipping' => 0,
		'discount' => 0,
		'subTotal' => 0,
		'total' => 0
	];

	$userId = $customerId;
	$cond = ['product.is_active' => 1, 'custom_cart.user_id' => $userId];

	$getCartData = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
		->where($cond)
		->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('custom_cart.id', 'desc')
		->get();

	if (!empty($getCartData)) {

		foreach ($getCartData as $cartData) {
			
			$productId = $cartData->product_id;
			$paperSizeId = $cartData->paper_size_id;
			$paperGsmId = $cartData->paper_gsm_id;
			$paperTypeId = $cartData->paper_type_id;
			$printSide = $cartData->print_side;
			$color = $cartData->color;
			$bindingId = $cartData->binding_id;
			$laminationId = $cartData->lamination_id;
			$coverId = $cartData->cover_id;

			$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

			$per_sheet_weight = 0;
			$paper_type_price = 0;

			if (!empty($getPaperGsm)) {
				$per_sheet_weight = $getPaperGsm->per_sheet_weight;
				$paper_type_price = $getPaperGsm->paper_type_price;
			}

			$data['per_sheet_weight'] += $per_sheet_weight;
			$data['paper_type_price'] += $paper_type_price;

			$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

			if (!empty($printSideAndColorPrice)) {
				$data['printSideAndColorPrice'] += $printSideAndColorPrice;
			}

			$bindingPrice = 0;

			if (!empty($bindingId)) {
				
				//$bindingData = BindingModel::where('id', $bindingId)->value('price');
				$bindingData = BindingModel::where('id', $bindingId)->first();

				// if (!empty($bindingData)) {
				// 	$bindingPrice = $bindingData;
				// 	$data['binding'] += $bindingPrice;
				// }

				if (!empty($bindingData)) {

					$totalSplit = 1;
					$multiple = ($printSide == 'Double Side')? 2:1;
					$bindingSplit = $bindingData->split;
		            $bindingSplit = $bindingSplit*$multiple;

		            if (!empty($bindingSplit) && $cartData->qty > $bindingSplit) {
		              $totalSplit = ceil($cartData->qty/$bindingSplit); 
		            } else {
		            	$totalSplit = 0;
		            }
		            
					//check the binding split & update the price
					if ($totalSplit > 1) {
						$bindingPrice = $bindingData->price*$totalSplit;
					} else {
						$bindingPrice = $bindingData->price;
					}

					$data['binding'] += $bindingPrice;
					$data['split'] += $totalSplit;

				}

			}

			$laminationPrice = 0;

			if (!empty($laminationId)) {
				
				$laminationData = LaminationModel::where('id', $laminationId)->value('price');

				if (!empty($laminationData)) {
					$laminationPrice = $laminationData;
					$data['lamination'] += $laminationPrice;
				}

			}

			// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

			// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

			// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];
			$price = $paper_type_price+$printSideAndColorPrice;
			$data['price'] = $price;

			//no of copies
			$noOfCopies = $cartData->no_of_copies;

			$getPriceCal = (($price*$cartData->qty))+$bindingPrice+$laminationPrice+$data['cover'];
			$getPriceCal = $getPriceCal*$noOfCopies;

			// if (!empty($noOfCopies)) {
					
			// 	$noOfCopies = $noOfCopies+1;
			// 	$getPriceCal = (($data['price']*$cartData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

			// } else {
			// 	$getPriceCal = ($data['price']*$cartData->qty)+$data['binding']+$data['lamination']+$data['cover'];
			// }

			// $data['discount'] = $discount;
			// $data['shipping'] = $shipping;
			$data['subTotal'] += $getPriceCal;

		}

		$getCouponData = Session::get('adminCouponSess');

		$discount = 0;

		if (!empty($getCouponData)) {
			$discount = $getCouponData['discount'];
		}

		$data['discount'] = $discount;

		//Shipping
		$shipping = 0;
		$getShippingData = Session::get('adminShippingSess');

		if (!empty($getShippingData)) {
			$shipping = $getShippingData['shipping'];
		}

		$data['shipping'] = $shipping;

		$data['total'] = round(($data['subTotal']-$data['discount'])+$data['shipping'], 2);
		return (object) $data;

	}

	return (object) $data;	

}

function productPriceForSaveCustomOrderMulti($orderId) {

	$data = [
		'per_sheet_weight' => 0,
		'paper_type_price' => 0,
		'printSideAndColorPrice' => 0,
		'binding' => 0,
		'split' => 0,
		'lamination' => 0,
		'cover' => 0,
		'price' => 0,
		'shipping' => 0,
		'discount' => 0,
		'subTotal' => 0,
		'total' => 0
	];

	$cond = ['product.is_active' => 1, 'order_items.order_id' => $orderId];

	$getOrderData = OrderModel::where('id', $orderId)->first(); 

	$getCartData = OrderItemModel::join('product', 'order_items.product_id', '=', 'product.id')
		->where($cond)
		->select('order_items.*', 'product.name', 'product.thumbnail_id')
		->orderBy('order_items.id', 'desc')
		->get();

	if (!empty($getCartData)) {

		foreach ($getCartData as $orderData) {

			$idsList = json_decode($orderData->product_detail_ids);
			
			$productId = $orderData->product_id;
			$paperSizeId = $idsList->paperSizeId;
			$paperGsmId = $idsList->paperGsmId;
			$paperTypeId = $idsList->paperTypeId;
			$printSide = $idsList->paperSides;
			$color = $idsList->color;
			$bindingId = $idsList->binding;
			$laminationId = $idsList->lamination;
			$coverId = $idsList->cover;

			$getPaperGsm = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId, 'paper_type' => $paperTypeId])->first();

			$per_sheet_weight = 0;
			$paper_type_price = 0;

			if (!empty($getPaperGsm)) {
				$per_sheet_weight = $getPaperGsm->per_sheet_weight;
				$paper_type_price = $getPaperGsm->paper_type_price;
			}

			$data['per_sheet_weight'] += $per_sheet_weight;
			$data['paper_type_price'] += $paper_type_price;

			$printSideAndColorPrice = PricingModel::where(['product_id' => $productId, 'paper_size_id' => $paperSizeId, 'paper_gsm_id' => $paperGsmId, 'paper_type_id' => $paperTypeId, 'side' => $printSide, 'color' => $color])->value('other_price');

			if (!empty($printSideAndColorPrice)) {
				$data['printSideAndColorPrice'] += $printSideAndColorPrice;
			}

			$bindingPrice = 0;

			if (!empty($bindingId)) {
				
				//$bindingData = BindingModel::where('id', $bindingId)->value('price');
				$bindingData = BindingModel::where('id', $bindingId)->first();

				// if (!empty($bindingData)) {
				// 	$bindingPrice = $bindingData;
				// 	$data['binding'] += $bindingPrice;
				// }

				if (!empty($bindingData)) {

					$totalSplit = 1;
					$multiple = ($printSide == 'Double Side')? 2:1;
					$bindingSplit = $bindingData->split;
		            $bindingSplit = $bindingSplit*$multiple;

		            if (!empty($bindingSplit) && $orderData->qty > $bindingSplit) {
		              $totalSplit = ceil($orderData->qty/$bindingSplit); 
		            } else {
		            	$totalSplit = 0;
		            }
		            
					//check the binding split & update the price
					if ($totalSplit > 1) {
						$bindingPrice = $bindingData->price*$totalSplit;
					} else {
						$bindingPrice = $bindingData->price;
					}

					$data['binding'] += $bindingPrice;
					$data['split'] += $totalSplit;

				}

			}

			$laminationPrice = 0;

			if (!empty($laminationId)) {
				
				$laminationData = LaminationModel::where('id', $laminationId)->value('price');

				if (!empty($laminationData)) {
					$laminationPrice = $laminationData;
					$data['lamination'] += $laminationPrice;
				}

			}

			// $data['price'] = $data['per_sheet_weight']+$data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

			// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice']+$data['binding']+$data['lamination']+$data['cover'];

			// $data['price'] = $data['paper_type_price']+$data['printSideAndColorPrice'];
			$price = $paper_type_price+$printSideAndColorPrice;
			$data['price'] = $price;

			//no of copies
			$noOfCopies = $orderData->no_of_copies;

			$getPriceCal = (($price*$orderData->qty))+$bindingPrice+$laminationPrice+$data['cover'];
			$getPriceCal = $getPriceCal*$noOfCopies;

			// if (!empty($noOfCopies)) {
					
			// 	$noOfCopies = $noOfCopies+1;
			// 	$getPriceCal = (($data['price']*$orderData->qty)*$noOfCopies)+$data['binding']+$data['lamination']+$data['cover'];

			// } else {
			// 	$getPriceCal = ($data['price']*$orderData->qty)+$data['binding']+$data['lamination']+$data['cover'];
			// }

			// $data['discount'] = $discount;
			// $data['shipping'] = $shipping;
			$data['subTotal'] += $getPriceCal;

		}

		$discount = $getOrderData->discount;
		$data['discount'] = $discount;

		//Shipping
		$shipping = $getOrderData->shipping;
		$data['shipping'] = $shipping;

		$data['total'] = round(($data['subTotal']-$data['discount'])+$data['shipping'], 2);
		return (object) $data;

	}

	return (object) $data;	

}

function cartWeight() {

	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id; 
		$paperGsmId = $cartData->paper_gsm_id; //weight
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$noOfCopies = $cartData->no_of_copies;

		$weight = 0;

		//Get GSM Data
		$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

		$weight = $getWeight;
		$totalWeight = ($weight*$cartData->qty)*$noOfCopies;
		
		// if (!empty($noOfCopies)) {
		// 	$noOfCopies = $noOfCopies+1;
		// 	$totalWeight = (($weight*$cartData->qty)*$noOfCopies);
		// } else {
		// 	$totalWeight = $weight*$cartData->qty;
		// }

		return $totalWeight;

	} else {
		return false;
	}

}

function cartWeightSingle($cartId) {

	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1, 'cart.id' => $cartId];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id; 
		$paperGsmId = $cartData->paper_gsm_id; //weight
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$noOfCopies = $cartData->no_of_copies;

		$weight = 0;

		//Get GSM Data
		$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

		$weight = $getWeight;
		$totalWeight = ($weight*$cartData->qty)*$noOfCopies;
		
		// if (!empty($noOfCopies)) {
		// 	$noOfCopies = $noOfCopies+1;
		// 	$totalWeight = (($weight*$cartData->qty)*$noOfCopies);
		// } else {
		// 	$totalWeight = $weight*$cartData->qty;
		// }

		return $totalWeight;

	} else {
		return false;
	}

}

function cartWeightSingleCusOrder($cartId, $userId) {

	$cond = ['product.is_active' => 1, 'custom_cart.id' => $cartId, 'custom_cart.user_id' => $userId];

	$cartData = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
		->where($cond)
		->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('custom_cart.id', 'desc')
		->first();

	if (!empty($cartData)) {
			
		$productId = $cartData->product_id;
		$paperSizeId = $cartData->paper_size_id; 
		$paperGsmId = $cartData->paper_gsm_id; //weight
		$paperTypeId = $cartData->paper_type_id;
		$printSide = $cartData->print_side;
		$color = $cartData->color;
		$bindingId = $cartData->binding_id;
		$laminationId = $cartData->lamination_id;
		$coverId = $cartData->cover_id;

		$noOfCopies = $cartData->no_of_copies;

		$weight = 0;

		//Get GSM Data
		$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

		$weight = $getWeight;
		$totalWeight = ($weight*$cartData->qty)*$noOfCopies;
		
		// if (!empty($noOfCopies)) {
		// 	$noOfCopies = $noOfCopies+1;
		// 	$totalWeight = (($weight*$cartData->qty)*$noOfCopies);
		// } else {
		// 	$totalWeight = $weight*$cartData->qty;
		// }

		return $totalWeight;

	} else {
		return false;
	}

}

function cartWeightSingleSaveCusOrder($paperSizeId, $paperGsmId, $qty, $noOfCopies) {

	$weight = 0;

	//Get GSM Data
	$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

	$weight = $getWeight;
	$totalWeight = ($weight*$qty)*$noOfCopies;

	return $totalWeight;

}

function cartWeightMulti() {

	$tempId = Request::cookie('tempUserId');
	$userId = customerId();

	$cond = ['product.is_active' => 1];
	if (!empty($userId)) {
		$cond['cart.user_id'] = $userId;
	} else {
		$cond['cart.temp_id'] = $tempId;
	}

	$getCartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->select('cart.*', 'product.name', 'product.thumbnail_id')
		->orderBy('cart.id', 'desc')
		->get();

	if (!empty($getCartData)) {

		$totalWeight = 0;
		$weight = 0;
			
		foreach ($getCartData as $cartData) {
			
			$productId = $cartData->product_id;
			$paperSizeId = $cartData->paper_size_id; 
			$paperGsmId = $cartData->paper_gsm_id; //weight
			$paperTypeId = $cartData->paper_type_id;
			$printSide = $cartData->print_side;
			$color = $cartData->color;
			$bindingId = $cartData->binding_id;
			$totalSplit = 1;

			if (!empty($bindingId)) {
				$bindingData = BindingModel::where('id', $bindingId)->first();
				if (!empty($bindingData)) {
					
					$multiple = ($printSide == 'Double Side')? 2:1;
					$bindingSplit = $bindingData->split;
		            $bindingSplit = $bindingSplit*$multiple;

		            if (!empty($bindingSplit) && $cartData->qty > $bindingSplit) {
		              $totalSplit = ceil($cartData->qty/$bindingSplit); 
		            } else {
		              $totalSplit = 1;
		            }
				}
			}

			$laminationId = $cartData->lamination_id;
			$coverId = $cartData->cover_id;

			$noOfCopies = $cartData->no_of_copies;

			//Get GSM Data
			$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

			$weight += $getWeight;
			
			if ($totalSplit > 1) {
				$totalWeight += (($weight*$cartData->qty)*$noOfCopies)*$totalSplit;
			} else {
				$totalWeight += ($weight*$cartData->qty)*$noOfCopies;
			}
			
			// if (!empty($noOfCopies)) {
			// 	$noOfCopies = $noOfCopies+1;
			// 	$totalWeight = (($weight*$cartData->qty)*$noOfCopies);
			// } else {
			// 	$totalWeight = $weight*$cartData->qty;
			// }

		}

		return $totalWeight;

	} else {
		return false;
	}

}

function cartWeightForCustomOrder($productId, $paperSizeId, $paperGsmId, $paperTypeId, $printSide, $color, $bindingId, $laminationId, $coverId, $qty, $noOfCopies) {

	$weight = 0;
	$totalSplit = 1;

	if (!empty($bindingId)) {
		$bindingData = BindingModel::where('id', $bindingId)->first();
		if (!empty($bindingData)) {
			
			$multiple = ($printSide == 'Double Side')? 2:1;
			$bindingSplit = $bindingData->split;
            $bindingSplit = $bindingSplit*$multiple;

            // if ($qty > $bindingSplit) {
            //   $totalSplit = ceil($qty/$bindingSplit); 
            // }

            if (!empty($bindingSplit) && ($qty > $bindingSplit)) {
              $totalSplit = ceil($qty/$bindingSplit); 
            } else {
              $totalSplit = 1;
            }

		}
	}

	//Get GSM Data
	$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

	$weight = $getWeight;

	if ($totalSplit > 1) {
		$totalWeight = (($weight*$qty)*$noOfCopies)*$totalSplit;
	} else {
		$totalWeight = ($weight*$qty)*$noOfCopies;
	}
	return $totalWeight;
}

function cartWeightForCustomOrderMulti($customerId) {

	$userId = $customerId;

	$cond = ['product.is_active' => 1, 'custom_cart.user_id' => $userId];

	$getCartData = CustomCartModel::join('product', 'custom_cart.product_id', '=', 'product.id')
	->where($cond)
	->select('custom_cart.*', 'product.name', 'product.thumbnail_id')
	->orderBy('custom_cart.id', 'desc')
	->get();

	if (!empty($getCartData)) {

		$totalWeight = 0;
		$weight = 0;
			
		foreach ($getCartData as $cartData) {
			
			$productId = $cartData->product_id;
			$paperSizeId = $cartData->paper_size_id; 
			$paperGsmId = $cartData->paper_gsm_id; //weight
			$paperTypeId = $cartData->paper_type_id;
			$printSide = $cartData->print_side;
			$color = $cartData->color;
			$bindingId = $cartData->binding_id;
			$totalSplit = 1;

			if (!empty($bindingId)) {
				$bindingData = BindingModel::where('id', $bindingId)->first();
				if (!empty($bindingData)) {
					
					$multiple = ($printSide == 'Double Side')? 2:1;
					$bindingSplit = $bindingData->split;
		            $bindingSplit = $bindingSplit*$multiple;

		            if (!empty($bindingSplit) && $cartData->qty > $bindingSplit) {
		              $totalSplit = ceil($cartData->qty/$bindingSplit); 
		            } else {
		              $totalSplit = 1;
		            }
				}
			}

			$laminationId = $cartData->lamination_id;
			$coverId = $cartData->cover_id;

			$noOfCopies = $cartData->no_of_copies;

			//Get GSM Data
			$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

			$weight += $getWeight;
			
			if ($totalSplit > 1) {
				$totalWeight += (($weight*$cartData->qty)*$noOfCopies)*$totalSplit;
			} else {
				$totalWeight += ($weight*$cartData->qty)*$noOfCopies;
			}
			
			// if (!empty($noOfCopies)) {
			// 	$noOfCopies = $noOfCopies+1;
			// 	$totalWeight = (($weight*$cartData->qty)*$noOfCopies);
			// } else {
			// 	$totalWeight = $weight*$cartData->qty;
			// }

		}

		return $totalWeight;

	} else {
		return false;
	}
}

function cartWeightForSaveCustomOrderMulti($orderId) {

	$cond = ['product.is_active' => 1, 'order_items.order_id' => $orderId];

	$getCartData = OrderItemModel::join('product', 'order_items.product_id', '=', 'product.id')
	->where($cond)
	->select('order_items.*', 'product.name', 'product.thumbnail_id')
	->orderBy('order_items.id', 'desc')
	->get();

	if (!empty($getCartData)) {

		$totalWeight = 0;
		$weight = 0;
			
		foreach ($getCartData as $orderData) {

			$idsList = json_decode($orderData->product_detail_ids);		
			
			$productId = $orderData->product_id;
			$paperSizeId = $idsList->paperSizeId;
			$paperGsmId = $idsList->paperGsmId;
			$paperTypeId = $idsList->paperTypeId;
			$printSide = $idsList->paperSides;
			$color = $idsList->color;
			$bindingId = $idsList->binding;
			$laminationId = $idsList->lamination;
			$coverId = $idsList->cover;
			$totalSplit = 1;

			if (!empty($bindingId)) {
				$bindingData = BindingModel::where('id', $bindingId)->first();
				if (!empty($bindingData)) {
					
					$multiple = ($printSide == 'Double Side')? 2:1;
					$bindingSplit = $bindingData->split;
		            $bindingSplit = $bindingSplit*$multiple;

		            if (!empty($bindingSplit) && $orderData->qty > $bindingSplit) {
		              $totalSplit = ceil($orderData->qty/$bindingSplit); 
		            } else {
		              $totalSplit = 1;
		            }
				}
			}

			$laminationId = $orderData->lamination_id;
			$coverId = $orderData->cover_id;

			$noOfCopies = $orderData->no_of_copies;

			//Get GSM Data
			$getWeight = GsmModel::where(['paper_size' => $paperSizeId, 'id' => $paperGsmId])->value('per_sheet_weight');

			$weight += $getWeight;
			
			if ($totalSplit > 1) {
				$totalWeight += (($weight*$orderData->qty)*$noOfCopies)*$totalSplit;
			} else {
				$totalWeight += ($weight*$orderData->qty)*$noOfCopies;
			}
			
			// if (!empty($noOfCopies)) {
			// 	$noOfCopies = $noOfCopies+1;
			// 	$totalWeight = (($weight*$orderData->qty)*$noOfCopies);
			// } else {
			// 	$totalWeight = $weight*$orderData->qty;
			// }

		}

		return $totalWeight;

	} else {
		return false;
	}
}

function updateUserIdInCart() {

	$tempId = Request::cookie('tempUserId');

	$cond = ['product.is_active' => 1];
	$cond['cart.temp_id'] = $tempId;

	$cartData = CartModel::join('product', 'cart.product_id', '=', 'product.id')
		->where($cond)
		->count();

	if ($cartData) {
		$userId = customerId();
		CartModel::where('temp_id', $tempId)->update(['user_id' => $userId]);
	}

	return $cartData;
}

function customerData($col='') {
	$customerSess = Session::get('customerSess');

	if (!empty($customerSess)) {

		$customerId = $customerSess['customerId'];
		
		$getCusDetail = CustomerModel::where('id', $customerId)->first();

		if (!empty($getCusDetail) && $getCusDetail->count()) {
			if (!empty($col)) {
				return $getCusDetail->{$col};
			} else {
				return $getCusDetail;
			}
		} else {
			return false;
		}

	} else {
		return false;
	}
}

function isPaymentInit() {
	$isPaymentInit = Session::get('paymentSess');

	if (!empty($isPaymentInit)) {
		return true;
	} else {
		return false;
	}
}

//Product Related

function getProductCatList() {
	return CategoryModel::where('is_active', 1)->get();
}

function getProductList($catId) {
	return ProductModel::where(['is_active' => 1, 'category_id' => $catId])->get();
}

//GST
function calculateGSTComponents($totalAmount, $gstPercentage) {

	$cgst_sgst_percentage = $gstPercentage / 2;
    $cgst = $sgst = ($totalAmount * $cgst_sgst_percentage) / 100;

	$igst = ($totalAmount * $gstPercentage) / 100;

	return (object) compact('cgst', 'sgst', 'igst');
}

function checkDecimal($number) {
	if (floor($number) != $number) {
	    // Number has decimals, show them
	    $decimal = $number - floor($number);
	    return $decimal;
	} else {
	    // Number does not have decimals, remove them
	    $number = floor($number);
	    return $number;
	}
}

function amountToWords($amount) {
    $words = [
        '',
        'One',
        'Two',
        'Three',
        'Four',
        'Five',
        'Six',
        'Seven',
        'Eight',
        'Nine'
    ];

    $teens = [
        'Eleven',
        'Twelve',
        'Thirteen',
        'Fourteen',
        'Fifteen',
        'Sixteen',
        'Seventeen',
        'Eighteen',
        'Nineteen'
    ];

    $tens = [
        '',
        '',
        'Twenty',
        'Thirty',
        'Forty',
        'Fifty',
        'Sixty',
        'Seventy',
        'Eighty',
        'Ninety'
    ];

    $crore = floor($amount / 10000000);
    $amount %= 10000000;

    $lakh = floor($amount / 100000);
    $amount %= 100000;

    $thousand = floor($amount / 1000);
    $amount %= 1000;

    $hundred = floor($amount / 100);
    $amount %= 100;

    $output = '';

    if ($crore > 0) {
        $output .= amountToWords($crore) . ' Crore ';
    }

    if ($lakh > 0) {
        $output .= amountToWords($lakh) . ' Lakh ';
    }

    if ($thousand > 0) {
        $output .= amountToWords($thousand) . ' Thousand ';
    }

    if ($hundred > 0) {
        $output .= amountToWords($hundred) . ' Hundred ';
    }

    if ($amount > 0) {
        if ($output != '') {
            $output .= 'and ';
        }

        if ($amount < 10) {
            $output .= $words[$amount];
        } elseif ($amount < 20) {
            $output .= $teens[$amount - 11];
        } else {
            $output .= $tens[floor($amount / 10)];
            $remainder = $amount % 10;

            if ($remainder > 0) {
                $output .= ' ' . $words[$remainder];
            }
        }
    }

    return $output;
}