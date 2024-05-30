@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit {{ $pricing->name }} Pricing</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminPricing', $pricing->product_id) }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form_update" class="form" action="{{ route('adminDoUpdatePricing') }}">
                    <div class="card-body border-top p-9">
                            
                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Size</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="paperSize" class="form-select mb-2" name="paperSize" data-control="select2" data-hide-search="true" data-placeholder="Select Paper Size">
                                            <option value="">Select Paper Size</option>
                                            @if(!empty($paperSize))
                                            @foreach($paperSize as $paperSize)
                                            <option {{ ($pricing->paper_size_id == $paperSize->id)? 'selected':''; }} data-url="{{ route('getAdminPaperGsm'); }}" data-measurement="{{ $paperSize->measurement }}" value="{{ $paperSize->id }}">{{ $paperSize->size }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperSizeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper GSM</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="paperGsm" class="form-select mb-2" name="paperGsm" data-control="select2" data-hide-search="true" data-placeholder="Select Paper GSM">
                                            <option value="">Select Paper GSM</option>
                                            @if(!empty($paperGsm))
                                            @foreach($paperGsm as $paperGsm)
                                            <option {{ ($pricing->paper_gsm_id == $paperGsm->id)? 'selected':''; }} data-url="{{ route('getAdminPaperType'); }}" value="{{ $paperGsm->id }}">{{ $paperGsm->gsm }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperGsmErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Type</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="paperType" class="form-select mb-2" name="paperType" data-control="select2" data-hide-search="true" data-placeholder="Select Paper Type">
                                            <option value="">Select Paper Type</option>
                                            @if(!empty($paperType))
                                            @foreach($paperType as $paperType)
                                            <option {{ ($pricing->paper_type_id == $paperType->id)? 'selected':''; }} data-url="{{ route('getAdminPricing'); }}" value="{{ $paperType->id }}">{{ $paperType->paper_type }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperTypeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Per Sheet Weight</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input readonly id="perSheetWeight" step=".01" type="number" name="perSheetWeight" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Per Sheet Weight" value="{{ $pricing->per_sheet_weight }}">
                                        <span id="perSheetWeightErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Size Rate</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input readonly id="paperSizePrice" step=".01" type="number" name="paperSizePrice" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Paper Size Rate" value="{{ $pricing->paper_type_price }}">
                                        <span id="paperSizePriceErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Sides</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="sides" data-control="select2" data-hide-search="true" data-placeholder="Select Sides">
                                            <option value="">Select Sides</option>
                                            <option {{ $pricing->side == 'Single Side'? 'selected':''; }} >Single Side</option>
                                            <option {{ $pricing->side == 'Double Side'? 'selected':''; }} >Double Side</option>
                                            <option {{ $pricing->side == 'Plotting'? 'selected':''; }} >Plotting</option>
                                            <option {{ $pricing->side == 'Digital Print'? 'selected':''; }} >Digital Print</option>
                                            <option {{ $pricing->side == 'Poster'? 'selected':''; }} >Poster</option>
                                            <option {{ $pricing->side == 'Sticker'? 'selected':''; }} >Sticker</option>
                                            <option {{ $pricing->side == 'Polyester Film'? 'selected':''; }} >Polyester Film</option>
                                            <option {{ $pricing->side == 'Envelop'? 'selected':''; }} >Envelop</option>
                                            <option {{ $pricing->side == 'Cold'? 'selected':''; }} >Cold</option>
                                            <option {{ $pricing->side == 'Thermal'? 'selected':''; }} >Thermal</option>
                                            <option {{ $pricing->side == 'Velvet'? 'selected':''; }} >Velvet</option>
                                        </select>
                                        <span id="sidesErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Color</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="color" data-control="select2" data-hide-search="true" data-placeholder="Select Color">
                                            <option value="">Select Color</option>
                                            <option {{ $pricing->color == 'Black and White'? 'selected':''; }}>Black and White</option>
                                            <option {{ $pricing->color == 'Color'? 'selected':''; }}>Color</option>
                                        </select>
                                        <span id="colorErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Other Price</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="otherPrice" step=".01" type="number" name="otherPrice" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Other Price" value="{{ $pricing->other_price }}">
                                        <span id="otherPriceErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Total Price</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input readonly id="totalPrice" step=".0001" type="number" name="totalPrice" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Total Price" value="{{ $pricing->other_price+$pricing->rate+$pricing->paper_type_price }}">
                                        <span id="totalPriceErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">

                        <input type="hidden" name="id" value="{{ $pricing->id }}">
                        <input type="hidden" name="productId" value="{{ $pricing->product_id }}">

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

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">
        dataUrl = '{{ route("getAdminPricing") }}';        
        productId = {{ $pricing->product_id }}; 
    </script>
    <script src="{{ asset('public/backend/js/admin/pricing.js') }}"></script>

@endsection