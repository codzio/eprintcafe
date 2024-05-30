<?php

namespace App\Http\Controllers;

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

 use App\Http\Controllers\SmsSending;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\PricingModel;
use App\Models\BindingModel;
use App\Models\LaminationModel;
use App\Models\CoverModel;
use App\Models\ShippingModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CartModel;
use App\Models\CouponModel;
use App\Models\CustomerAddressModel;
use App\Models\CustomerModel;
use App\Models\BarcodeModel;

class PriceCalculator extends Controller {

	private $status = array();

	public function index(Request $request) {

		$categoryList = CategoryModel::
		select('category.*', DB::raw('(SELECT COUNT(*) FROM product as b WHERE b.category_id = category.id) as totalProducts'))
		->where('category.is_active', 1)
		->having('totalProducts', '>', 0)
		->get();

		$defCat = CategoryModel::
		select('category.*', DB::raw('(SELECT COUNT(*) FROM product as b WHERE b.category_id = category.id) as totalProducts'))
		->where('category.is_active', 1)
		->having('totalProducts', '>', 0)
		->first();

		$getProducts = ProductModel::where([['category_id', $defCat->id], ['is_active', 1]])->get();

		$defProduct = ProductModel::where([['category_id', $defCat->id], ['is_active', 1]])->first();
		$productId = $defProduct->id;

		$paperSize = PricingModel::
		join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
		->where('pricing.product_id', $productId)
		->select('paper_size.*')
		->distinct('paper_size.id')
		->get();

		$defPaperSize = PricingModel::defPaperSize($productId);
		$defGsm = PricingModel::defPaperGsm($productId, $defPaperSize);
		$defPaperType = PricingModel::defPaperType($productId, $defPaperSize, $defGsm->gsmId);
		
		$defPaperSizeId = isset($defPaperSize->id)? $defPaperSize->id:null;
		$defPaperSides = PricingModel::defPaperSides($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId);

		$defPaperColor = PricingModel::defPaperColor($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId, $defPaperSides->paperSideId);

		$defGsmOpt = $defGsm->gsmOptions;			
		$defPaperTypeOpt = $defPaperType->paperOptions;
		$defPaperSidesOpt = $defPaperSides->paperSidesOptions;
		$defPaperColorOpt = $defPaperColor->paperColorOptions;
		$defBindingOpt = $defGsm->bindingOptions;
		$defLaminationOpt = $defGsm->laminationOptions;

		$covers = CoverModel::get();

		$data = array(
			'title' => 'Home',
			'pageTitle' => 'Home',
			'menu' => 'home',
			'categoryList' => $categoryList,
			'productList' => $getProducts,

			'paperSize' => $paperSize,
			'covers' => $covers,

			'defPaperSize' => $defPaperSize,
			'defGsmOpt' => $defGsmOpt,
			'defPaperTypeOpt' => $defPaperTypeOpt,
			'defPaperSidesOpt' => $defPaperSidesOpt,
			'defPaperColorOpt' => $defPaperColorOpt,
			'defBindingOpt' => $defBindingOpt,
			'defLaminationOpt' => $defLaminationOpt,
		);

		return view('frontend/price-calculator', $data);

	}

	public function getOptionsByCat(Request $request) {

		$catId = $request->post('categoryId');
		$getProducts = ProductModel::where([['category_id', $catId], ['is_active', 1]])->get();

		$productList = '';
		$optionTemplate = '';

		if (!empty($getProducts)) {

			$defProduct = ProductModel::where([['category_id', $catId], ['is_active', 1]])->first();
			$productId = $defProduct->id;

			$paperSize = PricingModel::
			join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
			->where('pricing.product_id', $productId)
			->select('paper_size.*')
			->distinct('paper_size.id')
			->get();

			$defPaperSize = PricingModel::defPaperSize($productId);
			$defGsm = PricingModel::defPaperGsm($productId, $defPaperSize);
			$defPaperType = PricingModel::defPaperType($productId, $defPaperSize, $defGsm->gsmId);
			
			$defPaperSizeId = isset($defPaperSize->id)? $defPaperSize->id:null;
			$defPaperSides = PricingModel::defPaperSides($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId);

			$defPaperColor = PricingModel::defPaperColor($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId, $defPaperSides->paperSideId);

			$defGsmOpt = $defGsm->gsmOptions;			
			$defPaperTypeOpt = $defPaperType->paperOptions;
			$defPaperSidesOpt = $defPaperSides->paperSidesOptions;
			$defPaperColorOpt = $defPaperColor->paperColorOptions;
			$defBindingOpt = $defGsm->bindingOptions;
			$defLaminationOpt = $defGsm->laminationOptions;

			$covers = CoverModel::get();

			$optionTemplate = view('frontend/components/price-calculator', compact('paperSize', 'defPaperSize', 'defGsm', 'defPaperType', 'defPaperSizeId', 'defPaperSides', 'defPaperColor', 'defGsmOpt', 'defPaperTypeOpt', 'defPaperSidesOpt', 'defPaperColorOpt', 'defBindingOpt', 'defLaminationOpt', 'covers'))->render();

			foreach ($getProducts as $product) {
				$productList .= '<option value="'.$product->id.'">'.$product->name.'</option>';
			}
		}

		return response(array('optionTemplate' => $optionTemplate, 'productList' => $productList));

	}

	public function getOptionsByProduct(Request $request) {

		$productList = '';
		$optionTemplate = '';

		$productId = $request->post('productId');

		$paperSize = PricingModel::
		join('paper_size', 'pricing.paper_size_id', '=', 'paper_size.id')
		->where('pricing.product_id', $productId)
		->select('paper_size.*')
		->distinct('paper_size.id')
		->get();

		$defPaperSize = PricingModel::defPaperSize($productId);
		$defGsm = PricingModel::defPaperGsm($productId, $defPaperSize);
		$defPaperType = PricingModel::defPaperType($productId, $defPaperSize, $defGsm->gsmId);
		
		$defPaperSizeId = isset($defPaperSize->id)? $defPaperSize->id:null;
		$defPaperSides = PricingModel::defPaperSides($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId);

		$defPaperColor = PricingModel::defPaperColor($productId, $defPaperSizeId, $defGsm->gsmId, $defPaperType->paperTypeId, $defPaperSides->paperSideId);

		$defGsmOpt = $defGsm->gsmOptions;			
		$defPaperTypeOpt = $defPaperType->paperOptions;
		$defPaperSidesOpt = $defPaperSides->paperSidesOptions;
		$defPaperColorOpt = $defPaperColor->paperColorOptions;
		$defBindingOpt = $defGsm->bindingOptions;
		$defLaminationOpt = $defGsm->laminationOptions;

		$covers = CoverModel::get();

		$optionTemplate = view('frontend/components/price-calculator', compact('paperSize', 'defPaperSize', 'defGsm', 'defPaperType', 'defPaperSizeId', 'defPaperSides', 'defPaperColor', 'defGsmOpt', 'defPaperTypeOpt', 'defPaperSidesOpt', 'defPaperColorOpt', 'defBindingOpt', 'defLaminationOpt', 'covers'))->render();

		return response(array('optionTemplate' => $optionTemplate));

	}

	
}