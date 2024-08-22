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
use Illuminate\Support\Facades\Log;

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

use App\Models\ContactModel;
use App\Models\LandingPageEnquiryModel;

use Storage;
use League\Flysystem\Filesystem;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Spatie\Dropbox\Client as DropboxClient; // Import the DropboxClient

class Upload extends Controller {

	private $status = array();

	public function index(Request $request) {

		$data = array(
			'title' => 'Upload',
			'pageTitle' => 'Upload',
			'menu' => 'upload',
			'customerData' => customerData(),
		);

		return view('frontend/fileUpload', $data);

	}

}