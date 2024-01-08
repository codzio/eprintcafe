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

class MediaManager extends Controller {

	private $status = array();

	public function index(Request $request) {

		if (!can('read', 'media')){
			return redirect(route('adminMedia'));
		}

		$adminSess = Session::get('adminSess');		
		
		$data = array(
			'title' => 'Media Manager',
			'pageTitle' => 'Media Manager',
			'menu' => 'media',
			'totalItems' => MediaModel::count(),
			'totalSize' => formatSize(folderSize(public_path('media'))),
		);

		return view('admin/mediaManager/index', $data);
	}

	public function doUpload(Request $request) {
		if ($request->ajax()) {

			if (!can('create', 'media')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);
			}

			$validator = Validator::make($request->all(), [
	            'file.*' => 'required|mimes:png,jpg,jpeg,pdf,mp4,mp3|max:10024',
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

	        	$files = $request->file('file');

	        	$year = date('Y');
	        	$month = date('m');
	        	$date = date('d');

	        	$isUploaded = false;

	        	foreach ($files as $file) {
	        		
	        		$originalName = $file->getClientOriginalName();
	        		$originalNameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

	        		$ext = $file->extension();
	        		//$size = formatSize($file->getSize());
	        		$size = $file->getSize();

	        		$nameWithoutExtSlugify = Str::slug($originalNameWithoutExt);
		        	$validateNewName = validateMediaSlug('media', 'new_name', $nameWithoutExtSlugify);
		        	$finalName = $validateNewName.'.'.$ext;

		        	$destinationPath = 'media/'.$year.'/'.$month.'/'.$date;
		        	File::makeDirectory(public_path($destinationPath), $mode = 0777, true, true);
		   			
		   			$isUploaded = $file->move(public_path($destinationPath), $finalName);

		   			$obj = array(
	        			'admin_id' => adminId(),
	        			'original_name' => $originalNameWithoutExt,
	        			'new_name' => $validateNewName,
	        			'ext' => $ext,
	        			'path' => $destinationPath.'/'.$validateNewName.'.'.$ext,
	        			'year' => $year,
	        			'month' => $month,
	        			'date' => $date,
	        			'alt' => $originalNameWithoutExt,
	        			'size' => $size,
	        		);

	        		MediaModel::create($obj);   	

	        	}

	        	if ($isUploaded) {
	        		
	        		$this->status = array(
						'error' => false,
						'msg' => 'Media has been uploaded successfully.'
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

	public function getMedia(Request $request) {
		if ($request->ajax()) {

			$draw = $request->get('draw');

			if (!can('read', 'media')){
				$response = array(
			        "draw" => intval($draw),
			        "iTotalRecords" => 0,
			        "iTotalDisplayRecords" => 0,
			        "aaData" => []
			    );

			    echo json_encode($response);
			    exit;
			}

			$addMedia = $request->get('addMediaBtn');
			$multiple = $request->get('multiple');
			$mediaType = $request->get('mediaType');

			
		    $start = $request->get("start");
		    $rowperpage = $request->get("length"); // Rows display per page

		    $allowMultiple = "false";
		    $inputName = $request->get('field');

		    if (!empty($multiple) && $multiple == "true") {
		    	$allowMultiple = "true";
		    }

		    //get type

		    $type = $request->get('type');

		    $columnIndex_arr = $request->get('order');
		    $columnName_arr = $request->get('columns');
		    $order_arr = $request->get('order');
		    $search_arr = $request->get('search');

		    $columnIndex = isset($columnIndex_arr[0]['column'])? $columnIndex_arr[0]['column']:''; // Column index
		    $columnName = !empty($columnIndex)? $columnName_arr[$columnIndex]['data']:''; // Column name
		    $columnSortOrder = !empty($order_arr)? $order_arr[0]['dir']:''; // asc or desc
		    $searchValue = $search_arr['value']; // Search value

		     // Total records
		    $totalRecords = MediaModel::select('count(*) as allcount');

		    if (!empty($mediaType)) {
		    	$totalRecords = $totalRecords->whereIn('media.ext', $mediaType)->count();
		    } else {
		    	$totalRecords = $totalRecords->count();
		    }

		    $totalRecordswithFilter = MediaModel::select('count(*) as allcount');

		    if (!empty($searchValue)) {
		    	$totalRecordswithFilter->where('original_name', 'like', '%' .$searchValue . '%');
		    }

		    if (!empty($mediaType)) {
		    	$totalRecordswithFilter = $totalRecordswithFilter->whereIn('media.ext', $mediaType)->count();
		    } else {
		    	$totalRecordswithFilter = $totalRecordswithFilter->count();
		    }

		     // Fetch records
		    $records = MediaModel::select('media.*')->skip($start)->take($rowperpage);

		    if (!empty($searchValue)) {
		    	$records->where('media.original_name', 'like', '%' .$searchValue . '%');
		    }

		    if (!empty($mediaType)) {
		    	$records->whereIn('media.ext', $mediaType);
		    }

		    if (!empty($columnName) && !empty($columnSortOrder)) {
		    	$records->orderBy($columnName, $columnSortOrder);
		    } elseif (!empty($columnName)) {
		    	$records->orderBy($columnName, 'desc');	
		    } else {
		    	$records->orderBy('id','desc');
		    }

		    $records = $records->get();

		    $data_arr = array();
		     
		    if (!empty($records)) {
		    	foreach($records as $record){
			        $id = $record->id;
			        $filePath = url('public/'.$record->path);
			        $fileName = $record->original_name;
			        $fileType = $record->ext;
			        $completeName = $fileName.'.'.$fileType;

			        $updateAltUrl = route('adminDoUpdateAlt');
			        $singleDeleteMediaUrl = route('adminDoSingleMediaDel');	        

			        $isImgExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
			        $isVideoExt = ['mp4', 'mov', 'wmv', 'flv'];

			        if (in_array($fileType, $isImgExt)) {
			        	$render = '<img style="width:100px; height:100px" src="'.$filePath.'">';
			        } elseif (in_array($fileType, $isVideoExt)) {
			        	$render = '<video style="width:220px" class="img-fluid img-thumbnail p-0 mb-1 image-id neg-transition-scale" controls="">
                            <source src="'.$filePath.'">
                        </video>';
			        } else {
			        	$render = '<img style="width:100px; height:100px" src="'.asset('public/backend/media/svg/files/upload.svg').'">';
			        }

			        $renderMedia = '<div class="px-3">
                        <div class="thumb-1159">
                            <div>
                               '.$render.'
                            </div>
                            <div class="pb-1">
                                <p class="m-0 font-weight-bold">'.$completeName.'</p>
                                <small class="image-size-name">'.formatSize($record->size).'</small>
                            </div>
                        </div>
                    </div>';

			        $addImageBtn = '';

				    if (!empty($addMedia) && $addMedia == 'true') {
				    	
				    	$addImageBtn = '<div onclick="insertMedia(this)" data-input-name="'.$inputName.'" data-multiple="'.$allowMultiple.'" class="ms-2" data-id="'.$id.'" data-path="'.$filePath.'" data-filename="'.$fileName.'" data-type="'.$fileType.'" data-icon="" data-kt-filemanger-table="copy_link">
							<button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
								<span class="svg-icon svg-icon-5 m-0">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path opacity="0.3" d="M18.4 5.59998C18.7766 5.9772 18.9881 6.48846 18.9881 7.02148C18.9881 7.55451 18.7766 8.06577 18.4 8.44299L14.843 12C14.466 12.377 13.9547 12.5887 13.4215 12.5887C12.8883 12.5887 12.377 12.377 12 12C11.623 11.623 11.4112 11.1117 11.4112 10.5785C11.4112 10.0453 11.623 9.53399 12 9.15698L15.553 5.604C15.9302 5.22741 16.4415 5.01587 16.9745 5.01587C17.5075 5.01587 18.0188 5.22741 18.396 5.604L18.4 5.59998ZM20.528 3.47205C20.0614 3.00535 19.5074 2.63503 18.8977 2.38245C18.288 2.12987 17.6344 1.99988 16.9745 1.99988C16.3145 1.99988 15.661 2.12987 15.0513 2.38245C14.4416 2.63503 13.8876 3.00535 13.421 3.47205L9.86801 7.02502C9.40136 7.49168 9.03118 8.04568 8.77863 8.6554C8.52608 9.26511 8.39609 9.91855 8.39609 10.5785C8.39609 11.2384 8.52608 11.8919 8.77863 12.5016C9.03118 13.1113 9.40136 13.6653 9.86801 14.132C10.3347 14.5986 10.8886 14.9688 11.4984 15.2213C12.1081 15.4739 12.7616 15.6039 13.4215 15.6039C14.0815 15.6039 14.7349 15.4739 15.3446 15.2213C15.9543 14.9688 16.5084 14.5986 16.975 14.132L20.528 10.579C20.9947 10.1124 21.3649 9.55844 21.6175 8.94873C21.8701 8.33902 22.0001 7.68547 22.0001 7.02551C22.0001 6.36555 21.8701 5.71201 21.6175 5.10229C21.3649 4.49258 20.9947 3.93867 20.528 3.47205Z" fill="currentColor" />
										<path d="M14.132 9.86804C13.6421 9.37931 13.0561 8.99749 12.411 8.74695L12 9.15698C11.6234 9.53421 11.4119 10.0455 11.4119 10.5785C11.4119 11.1115 11.6234 11.6228 12 12C12.3766 12.3772 12.5881 12.8885 12.5881 13.4215C12.5881 13.9545 12.3766 14.4658 12 14.843L8.44699 18.396C8.06999 18.773 7.55868 18.9849 7.02551 18.9849C6.49235 18.9849 5.98101 18.773 5.604 18.396C5.227 18.019 5.0152 17.5077 5.0152 16.9745C5.0152 16.4413 5.227 15.93 5.604 15.553L8.74701 12.411C8.28705 11.233 8.28705 9.92498 8.74701 8.74695C8.10159 8.99737 7.5152 9.37919 7.02499 9.86804L3.47198 13.421C2.52954 14.3635 2.00009 15.6417 2.00009 16.9745C2.00009 18.3073 2.52957 19.5855 3.47202 20.528C4.41446 21.4704 5.69269 21.9999 7.02551 21.9999C8.35833 21.9999 9.63656 21.4704 10.579 20.528L14.132 16.975C14.5987 16.5084 14.9689 15.9544 15.2215 15.3447C15.4741 14.735 15.6041 14.0815 15.6041 13.4215C15.6041 12.7615 15.4741 12.108 15.2215 11.4983C14.9689 10.8886 14.5987 10.3347 14.132 9.86804Z" fill="currentColor" />
									</svg>
								</span>
							</button>
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-300px" data-kt-menu="true">
								<div class="card card-flush">
									<div class="card-body p-5">
										<div class="d-flex" data-kt-filemanger-table="copy_link_generator">
											<!--begin::Spinner-->
											<div class="me-5" data-kt-indicator="on">
												<span class="indicator-progress">
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
												</span>
											</div>
											<div class="fs-6 text-dark">Adding...</div>
										</div>
										<div class="d-flex flex-column text-start d-none" data-kt-filemanger-table="copy_link_result">
											<div class="d-flex">
												<span class="svg-icon svg-icon-2 svg-icon-success me-3">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M9.89557 13.4982L7.79487 11.2651C7.26967 10.7068 6.38251 10.7068 5.85731 11.2651C5.37559 11.7772 5.37559 12.5757 5.85731 13.0878L9.74989 17.2257C10.1448 17.6455 10.8118 17.6455 11.2066 17.2257L18.1427 9.85252C18.6244 9.34044 18.6244 8.54191 18.1427 8.02984C17.6175 7.47154 16.7303 7.47154 16.2051 8.02984L11.061 13.4982C10.7451 13.834 10.2115 13.834 9.89557 13.4982Z" fill="currentColor" />
													</svg>
												</span>
												<div class="fs-6 text-dark">Media Added</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';

				    }
			       
			        $data_arr[] = array(
			        	"checkbox" => '<div onclick="checkCheckboxMedia(this)" class="form-check form-check-sm form-check-custom form-check-solid">
							<input name="delete[]" data-kt-check-target="#media .single-check-input" class="form-check-input" type="checkbox" value="'.$id.'" />
						</div>',
			          	"original_name" => $renderMedia,
			          	"alt" => '<input onchange="updateAlt(this)" data-url="'.$updateAltUrl.'" data-id="'.$id.'" id="alt-'.$id.'" style="width:50%;" type="text" value="'.$record->alt.'" class="form-control">',
			          	"size" => formatSize($record->size),
			          	"action" => '
			          	<td class="text-end" data-kt-filemanager-table="action_dropdown">
						<div class="d-flex justify-content-end">
							'.$addImageBtn.'
							<div class="ms-2">
								<div class="menu menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
									
									<div class="menu-item">
										<a title="View" target="_blank" href="'.$filePath.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-eye fs-2"></i></span>
										</a>
									</div>
								
									<div class="menu-item">
										<a title="Download" download href="'.$filePath.'" class="menu-link px-3">
											<span class="menu-icon"><i class="ki-outline ki-cloud-download fs-2"></i></span>
										</a>
									</div>
									
									<div class="menu-item">
										<a title="Delete" href="javascript:void(0)" data-url="'.$singleDeleteMediaUrl.'" onclick="deleteMedia(this)" data-id="'.$id.'" class="menu-link text-danger px-3">
											<span class="menu-icon"><i class="ki-outline ki-trash fs-2"></i></span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</td>'
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

	public function doUpdateAlt(Request $request) {
		if ($request->ajax()) {

			if (!can('update', 'media')){
				
				$this->status = array(
					'error' => true,
					'eType' => 'final',
					'msg' => 'Permission Denied.'
				);

				return json_encode($this->status);

			}

			$validator = Validator::make($request->post(), [
	            'id' => 'required|numeric',
	            'alt' => 'sometimes|nullable',
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

	        	$mediaId = $request->post('id');
	        	$altText = $request->post('alt');

	        	$isUpdated = MediaModel::where('id', $mediaId)->update(['alt' => $altText]);

	        	if ($isUpdated) {

	        		$this->status = array(
						'error' => false,
						'msg' => 'Alt text has been successfully updated.'
					);

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

	public function doSingleMediaDel(Request $request) {
		if ($request->ajax()) {

			if (!can('delete', 'media')){
				
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

	        	$mediaId = $request->post('id');

	        	//check if media data exist

	        	$getMediaData = MediaModel::where('id', '=', $mediaId)->first();	        	
	        	
	        	if (!empty($getMediaData)) {
	        		
	        		//delete file
	        		$isFileDeleted = File::delete(public_path($getMediaData->path));

	        		if ($isFileDeleted) {
	        			
	        			$isDeleted = MediaModel::where('id', $mediaId)->delete();

	        			if ($isDeleted) {
	        				$this->status = array(
								'error' => false,								
								'msg' => 'Media has been deleted successfully.'
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
							'msg' => 'Error while deleting the file'
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

	public function doMediaBulkDelete(Request $request) {
		if ($request->ajax()) {

			if (!can('delete', 'media')){
				
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

	        	$mediaIds = $request->post('ids');

	        	//check if media data exist
	        	$getMediaData = MediaModel::whereIn('id', $mediaIds)->get();	        	
	        	
	        	if (!empty($getMediaData)) {

	        		foreach ($getMediaData as $mediaData) {

	        			//delete all file
	        			$isFileDeleted = File::delete(public_path($mediaData->path));

	        		}

	        		$isDeleted = MediaModel::whereIn('id', $mediaIds)->delete();
	        		
	        		if ($isDeleted) {
        				$this->status = array(
							'error' => false,								
							'msg' => 'Media has been deleted successfully.'
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