<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Models\AdminModel;
use App\Models\MediaModel;
use App\Models\SettingModel;


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