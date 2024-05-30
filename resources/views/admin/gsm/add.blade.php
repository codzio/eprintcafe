@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Add GSM</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminGsm') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="{{ route('adminDoAddGsm') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Size</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="paperSize" class="form-select mb-2" name="paperSize" data-control="select2" data-hide-search="true" data-placeholder="Select Paper Size">
                                            <option value="">Select Paper Size</option>
                                            @if(!empty($paperSize))
                                            @foreach($paperSize as $paperSz)
                                            <option data-measurement="{{ $paperSz->measurement }}" value="{{ $paperSz->id }}">{{ $paperSz->size }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperSizeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Measurement</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="measurement" readonly type="number" step="0.01" name="measurement" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Measurement" value="">
                                        <span id="measurementErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">GSM</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="gsm" type="text" name="gsm" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="GSM" value="">
                                        <span id="gsmErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Weight</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="weight" readonly type="number" step="0.01" name="weight" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="weight" value="">
                                        <span id="weightErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Per Sheet Weight</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="rate" type="number" step="0.0001" name="perSheetWeight" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Per Sheet Weight" value="">
                                        <span id="perSheetWeightErr" class="text-danger"></span>
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
                                            <option value="{{ $paperType->id }}">{{ $paperType->paper_type }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="paperTypeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Type Price</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="rate" type="number" step="0.0001" name="paperTypePrice" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Paper Type Price" value="">
                                        <span id="paperTypePriceErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary" id="kt_form_submit">
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
        dataUrl = '{{ route("getAdminGsm") }}';

        $("#paperSize").change(function (e) {
            e.preventDefault();

            measurement = $(this).find(':selected').attr('data-measurement');
            $("#measurement").val(measurement);

            calculate();

        });

        $("#gsm").change(function (e) {
            calculate();
        });

        function calculate() {
            measurement = $("#paperSize").find(':selected').attr('data-measurement');
            gsm = $("#gsm").val();
            weight = (measurement*gsm)/3100;
            rate = weight/500;

            $("#weight").val(weight.toFixed(2));
            $("#rate").val(rate.toFixed(4));

        }

    </script>
    <script src="{{ asset('public/backend/js/admin/gsm.js') }}"></script>

@endsection