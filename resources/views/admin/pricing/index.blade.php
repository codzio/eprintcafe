@extends('admin.vwAdminMaster')

@section('content')

<link href="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>

    
    <div id="kt_app_content" class="app-content  flex-column-fluid " >
    
           
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-fluid ">
            
            <!--begin::Card-->
            <div class="card card-flush">

                <div class="tab-content">
                                        
                    <!--begin::Tap pane-->
                    <div class="tab-pane fade show active" id="kt_list_widget_10_tab_1">

                        <form id="filterForm" class="row mb-6" style="padding: 0 2.25rem;">                    
                            <div class="col-lg-2">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Paper Size</label>
                                        <select id="paperSize" class="form-select mb-2" name="paperSize" data-control="select2" data-hide-search="false" data-placeholder="Select Paper Size">
                                            <option value="">Select Paper Size</option>
                                            @if(!empty($paperSize))
                                            @foreach($paperSize as $paperSize)
                                            <option {{ Request::get("paperSize") == $paperSize->id ? 'selected' : '' }} value="{{ $paperSize->id }}">{{ $paperSize->size }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperSizeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>    
                            <div class="col-lg-2">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Gsm</label>
                                        <select id="gsm" class="form-select mb-2" name="gsm" data-control="select2" data-hide-search="false" data-placeholder="Select Gsm">
                                            <option value="">Select Gsm</option>
                                            @if(!empty($gsm))
                                            @foreach($gsm as $gsm)
                                            <option {{ Request::get("gsm") == $gsm->gsm ? 'selected' : '' }} value="{{ $gsm->gsm }}">{{ $gsm->gsm }} GSM</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="gsmErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div> 
                            <div class="col-lg-2">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Paper Type</label>
                                        <select id="paperType" class="form-select mb-2" name="paperType" data-control="select2" data-hide-search="false" data-placeholder="Select Paper Type">
                                            <option value="">Select Paper Type</option>
                                            @if(!empty($paperType))
                                            @foreach($paperType as $paperType)
                                            <option {{ Request::get("paperType") == $paperType->id ? 'selected' : '' }} value="{{ $paperType->id }}">{{ $paperType->paper_type }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperTypeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div> 
                            <div class="col-lg-2">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Side</label>
                                        <select id="side" class="form-select mb-2" name="side" data-control="select2" data-hide-search="true" data-placeholder="Select Side">
                                            <option value="">Select Side</option>
                                            @if(!empty($side))
                                            @foreach($side as $side)
                                            <option {{ Request::get("side") == $side->side ? 'selected' : '' }} value="{{ $side->side }}">{{ $side->side }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="sideErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div> 
                            <div class="col-lg-2">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Color</label>
                                        <select id="color" class="form-select mb-2" name="color" data-control="select2" data-hide-search="true" data-placeholder="Select Color">
                                            <option value="">Select Color</option>
                                            @if(!empty($color))
                                            @foreach($color as $color)
                                            <option {{ Request::get("color") == $color->color ? 'selected' : '' }} value="{{ $color->color }}">{{ $color->color }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="colorErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>  
                            <div class="col-lg-2">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Action</label>
                                        <br>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('adminPricing', $product->id) }}" class="btn btn-danger">Reset</a>
                                    </div>                            
                                </div>
                            </div>                    
                        </form>
                        
                        <!--begin::Card header-->
                        <div class="card-header pt-8">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-outline ki-magnifier fs-1 position-absolute ms-6"></i>    
                                    <input type="text" onkeyup="search(this.value)" class="form-control form-control-solid w-250px ps-15" placeholder="Search Pricing" />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-filemanager-table-toolbar="selected" style="padding-right: 8px">
                                    <div class="fw-bold me-5">
                                        <span class="me-2" data-kt-filemanager-table-select="selected_count"></span> Selected
                                    </div>
                                    <button onclick="startBulkDelete(this)" data-url="{{ route('adminBulkDeletePricing') }}" type="button" class="btn btn-danger" data-kt-filemanager-table-select="delete_selected">
                                        Delete Selected
                                    </button>
                                </div>

                                <a href="{{ route('adminAddPricing', $product->id) }}" class="btn btn-primary">Add Pricing</a>

                                <a href="{{ route('adminProduct') }}" class="btn btn-primary" style="margin-left:10px">Products</a>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <!--begin::Table-->
                            <table id="kt_file_manager_list" data-kt-filemanager-table="files" class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input onclick="bulkSelectData(this)" class="form-check-input" type="checkbox" data-kt-check="false" value="1" />
                                            </div>
                                        </th>
                                        <th class="min-w-100px">Paper Size</th>
                                        <th class="min-w-100px">GSM</th>
                                        <th class="min-w-100px">Paper Type</th>
                                        <th class="min-w-100px">Side</th>
                                        <th class="min-w-100px">Color</th>
                                        <th class="min-w-100px">Weight</th>
                                        <th class="min-w-100px">Per Sheet Weight</th>
                                        <th class="min-w-100px">Paper Price</th>
                                        <th class="min-w-100px">Other Price</th>
                                        <th class="min-w-100px">Total</th>
                                        <th class="w-125px">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600"></tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->

                    </div>
                    <!--end::Tap pane-->

                </div>
            </div>
            <!--end::Card-->

        </div>
        <!--end::Content container-->
    </div>

    <script type="text/javascript">
        dataUrl = '{{ route("getAdminPricingData") }}';
        productId = {{ $product->id }};       
    </script>

    <script src="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="{{ asset('public/backend/js/admin/pricing.js?v=1') }}"></script>

@endsection
