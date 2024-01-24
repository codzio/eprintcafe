@extends('admin.vwAdminMaster')

@section('content')

<style type="text/css">
    main {
      position: relative;
      width: 100%;
      min-height: 100vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    #sortable {
      position: relative;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: left;
      width: 80%;
      height: 200px;
/*      margin: 0 10%;*/
    }
    #sortable .draggable {
      width: 200px;
      height: 200px;
      cursor: grab;
      display: flex;
      align-items: center;
      justify-content: left;
      font-size: 72px;
      margin: 0 5px;
      color: #fff;
    }
    #sortable .black {
      background: black;
    }
</style>

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Product</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminProduct') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form_update" class="form" action="{{ route('adminDoUpdateProduct') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Category</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="category" data-control="select2" data-hide-search="true" data-placeholder="Select Category">
                                            <option value="">Select Category</option>
                                            @if(!empty($categoryList))
                                            @foreach($categoryList as $category)
                                            <option {{ $category->id == $product->category_id? 'selected':'' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="categoryErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Name</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input onkeyup="generateSlug(this, '#slug')" type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ $product->name }}">
                                        <span id="nameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Slug</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="slug" type="text" name="slug" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Slug" value="{{ $product->slug }}">
                                        <span id="slugErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold required fs-6">Description</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <textarea id="description" name="description" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 editor" placeholder="Description">{{ $product->description }}</textarea>
                                        <span id="descriptionErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Status</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select Status">
                                            <option value="">Select Status</option>
                                            <option {{ $product->is_active == 1? 'selected':'' }} selected value="1">Active</option>
                                            <option {{ $product->is_active == 0? 'selected':'' }} value="0">Inactive</option>
                                        </select>
                                        <span id="statusErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold required fs-6">Product Image</label>   
                            <div class="col-lg-9">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">
                                    <div id="productImgDisplay" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $productImg }}')"></div>
                                    
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" title="Change admin logo" onclick="loadMedia({addMediaBtn: true, multiple: false, mediaType: ['png', 'jpg', 'jpeg'], field: '#productImg'})">
                                        <i class="ki-outline ki-pencil fs-7"></i>
                                    </label>

                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel admin logo">
                                        <i class="ki-outline ki-cross fs-2"></i>                            
                                    </span>

                                    <span style="display:none;" id="productImgRemoveBtn" onclick="removeMedia({field: '#productImg', multiple: false})" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove admin logo">
                                        <i class="ki-outline ki-cross fs-2"></i>                            
                                    </span>

                                    <input type="hidden" name="productImg" id="productImg" value="{{ $product->thumbnail_id }}">
                                </div>
                                <div class="form-text">Allowed file types:  png, jpg, jpeg.</div>
                                <span id="productImgErr" class="text-danger"></span>
                            </div>                                  
                        </div>

                        <div class="row mb-6">
                            <label class="col-lg-3 col-form-label fw-semibold required fs-6">Product Gallery</label>   
                            <div class="col-lg-9">

                                <div class="text"><a data-bs-toggle="modal" data-bs-target="#kt_app_engage_prebuilts_modal" href="javascript:void(0)" onclick="loadMedia({addMediaBtn: true, multiple: true, mediaType: ['png', 'jpg', 'jpeg'], field: '#sortable'})">Click to Add Images</a></div>

                                <div id="sortable">
                                @if(!empty($galleryImgs))
                                @php $i=1; @endphp
                                @foreach($galleryImgs as $galleryImg)
                                <div id="box-{{ $i }}" class="draggable" draggable="true">
                                    <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">

                                        <div id="galleryImages{{ $i }}" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ getImg($galleryImg)  }}')"></div>
                                        
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" title="Remove" onclick="removeMedia({field: '#box-{{ $i }}', multiple: true})">
                                            <i class="ki-outline ki-cross fs-2"></i>
                                        </label>

                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Remove">
                                            <i class="ki-outline ki-cross fs-2"></i>
                                        </span>

                                        <input type="hidden" name="galleryImages[]" id="galleryImages{{ $i }}" value="{{ $galleryImg }}">
                                    </div>
                                </div>
                                @php $i++ @endphp
                                @endforeach
                                @endif
                                </div>
                            </div> 
                        </div>
                        
                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">

                        <input type="hidden" name="id" value="{{ $product->id }}">

                        <button type="submit" class="btn btn-primary" id="kt_form_update_submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">
                                Please wait...    
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>            
                </form>
            </div>
        </div>

    </div>
</div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">
        dataUrl = '{{ route("getAdminProduct") }}';  

        updateArray = [];        
        myList = $('#sortable');

        function doStartSorting() {
            $(() => {
                myList.sortable(
                    {
                        axis: 'x',
                        stop: function(event, ui){
                            updateArray = $("#sortable").sortable("toArray", {attribute: 'photoID'});
                            $('#array').html(updateArray.join(', '));
                        }
                    }
                );
              
                updateArray = $("#sortable").sortable("toArray", {attribute: 'photoID'});
                $('#array').html(updateArray.join(', '));
                myList.disableSelection();
            } );
        }

        doStartSorting();
    </script>
    <script src="{{ asset('public/backend/js/admin/product.js') }}"></script>

@endsection