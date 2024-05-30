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

use App\Models\MediaModel;
use App\Models\AdminModel;
use App\Models\SettingModel;

class SiteSettings extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'site_settings')){
			return redirect(route('adminDashboard'));
		}

		$adminId = adminId();
		$adminData = AdminModel::where('id', $adminId)->first();
		$setting = new SettingModel();

		$adminLogo = "../public/backend/media/svg/avatars/blank.svg";
		$websiteLogo = "../public/backend/media/svg/avatars/blank.svg";
		$favicon = "../public/backend/media/svg/avatars/blank.svg";
		$backgroundImage = "../public/backend/media/svg/avatars/blank.svg";

		$settingData = (object) array(
			'admin_logo' => $setting->getSetting('admin_logo'),
			'website_logo' => $setting->getSetting('website_logo'),
			'favicon' => $setting->getSetting('favicon'),
			'background_image' => $setting->getSetting('background_image'),
			'website_name' => $setting->getSetting('website_name'),
			'copyright' => $setting->getSetting('copyright'),
			
			'header_scripts' => $setting->getSetting('header_scripts'),
			'body_scripts' => $setting->getSetting('body_scripts'),
			'footer_scripts' => $setting->getSetting('footer_scripts'),
			'custom_css' => $setting->getSetting('custom_css'),
			'custom_js' => $setting->getSetting('custom_js'),
			
			'facebook_url' => $setting->getSetting('facebook_url'),
			'twitter_url' => $setting->getSetting('twitter_url'),
			'linkedin_url' => $setting->getSetting('linkedin_url'),
			'youtube_url' => $setting->getSetting('youtube_url'),
			'instagram_url' => $setting->getSetting('instagram_url'),
			'whatsapp_url' => $setting->getSetting('whatsapp_url'),

			'email_encryption' => $setting->getSetting('email_encryption'),
			'smtp_host' => $setting->getSetting('smtp_host'),
			'smtp_port' => $setting->getSetting('smtp_port'),
			'smtp_email' => $setting->getSetting('smtp_email'),
			'from_address' => $setting->getSetting('from_address'),
			'from_name' => $setting->getSetting('from_name'),
			'smtp_username' => $setting->getSetting('smtp_username'),
			'smtp_password' => $setting->getSetting('smtp_password'),
		);

		if (getImg($setting->getSetting('admin_logo'))) {
			$adminLogo = getImg($setting->getSetting('admin_logo'));
		}

		if (getImg($setting->getSetting('website_logo'))) {
			$websiteLogo = getImg($setting->getSetting('website_logo'));
		}

		if (getImg($setting->getSetting('favicon'))) {
			$favicon = getImg($setting->getSetting('favicon'));
		}

		if (getImg($setting->getSetting('background_image'))) {
			$backgroundImage = getImg($setting->getSetting('background_image'));
		}
		
		$data = array(
			'title' => 'Site Settings',
			'pageTitle' => 'Site Settings',
			'menu' => 'site-settings',
			'allowMedia' => true,
			'setting' => $settingData,
			'adminLogo' => $adminLogo,
			'websiteLogo' => $websiteLogo,
			'favicon' => $favicon,
			'backgroundImage' => $backgroundImage,
		);
		
		return view('admin/settings/vwAdminSiteSettings', $data);

	}

	public function doUpdateGeneralSetting(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'site_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'adminLogo' => 'required|numeric',
	            'websiteLogo' => 'required|numeric',
	            'favicon' => 'required|numeric',
	            'backgroundImage' => 'required|numeric',
	            'websiteName' => 'required',
	            'copyright' => 'required',
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

	        	$setting = new SettingModel();

	        	$setting->updateSetting([
				    'admin_logo' => $request->post('adminLogo'),
				    'website_logo' => $request->post('websiteLogo'),
				    'favicon' => $request->post('favicon'),
				    'background_image' => $request->post('backgroundImage'),
				    'website_name' => $request->post('websiteName'),
				    'copyright' => $request->post('copyright'),
				]);
	        	
	        	$this->status = array(
					'error' => false,
					'msg' => 'General settings has been successfully updated.'
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

	public function doUpdateScripts(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'site_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'headerScripts' => 'sometimes|nullable',
	            'bodyScripts' => 'sometimes|nullable',
	            'footerScripts' => 'sometimes|nullable',
	            'customCss' => 'sometimes|nullable',
	            'customJs' => 'sometimes|nullable',
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

	        	$setting = new SettingModel();

	        	$setting->updateSetting([
				    'header_scripts' => $request->post('headerScripts'),
				    'body_scripts' => $request->post('bodyScripts'),
				    'footer_scripts' => $request->post('footerScripts'),
				    'custom_css' => $request->post('customCss'),
				    'custom_js' => $request->post('customJs'),
				]);
	        	
	        	$this->status = array(
					'error' => false,
					'msg' => 'Scripts has been successfully updated.'
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

	public function doUpdateSocialIcons(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'site_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'facebookUrl' => 'sometimes|nullable',
	            'twitterUrl' => 'sometimes|nullable',
	            'linkedinUrl' => 'sometimes|nullable',
	            'youtubeUrl' => 'sometimes|nullable',
	            'instagramUrl' => 'sometimes|nullable',
	            'whatsAppUrl' => 'sometimes|nullable',
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

	        	$setting = new SettingModel();

	        	$setting->updateSetting([
				    'facebook_url' => $request->post('facebookUrl'),
				    'twitter_url' => $request->post('twitterUrl'),
				    'linkedin_url' => $request->post('linkedinUrl'),
				    'youtube_url' => $request->post('youtubeUrl'),
				    'instagram_url' => $request->post('instagramUrl'),
				    'whatsapp_url' => $request->post('whatsAppUrl'),
				]);
	        	
	        	$this->status = array(
					'error' => false,
					'msg' => 'Social icons has been successfully updated.'
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

	public function doUpdateSMTP(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'site_settings')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'emailEncryption' => 'required',
	            'smtpHost' => 'required',
	            'smtpPort' => 'required|numeric',
	            'smtpEmail' => 'required|email',
	            'fromAddress' => 'required|email',
	            'fromName' => 'required',
	            'smtpUsername' => 'required',
	            'smtpPassword' => 'required',
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

	        	$setting = new SettingModel();

	        	$setting->updateSetting([
				    'email_encryption' => $request->post('emailEncryption'),
				    'smtp_host' => $request->post('smtpHost'),
				    'smtp_port' => $request->post('smtpPort'),
				    'smtp_email' => $request->post('smtpEmail'),
				    'from_address' => $request->post('fromAddress'),
				    'from_name' => $request->post('fromName'),
				    'smtp_username' => $request->post('smtpUsername'),
				    'smtp_password' => $request->post('smtpPassword'),
				]);
	        	
	        	$this->status = array(
					'error' => false,
					'msg' => 'SMTP configuration has been successfully updated.'
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