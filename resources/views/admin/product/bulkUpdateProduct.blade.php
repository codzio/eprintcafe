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
    <div id="kt_app_content_container" class="app-container container-fluid ">

        @if(Session::has('status'))
            @if(Session::get('status')['error'])
                <div class="alert alert-danger">{{ Session::get('status')['msg'] }}</div>
            @else
                <div class="alert alert-danger">{{ Session::get('status')['msg'] }}</div>
            @endif
        @endif

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Bulk Update Product</h3>
                </div>

                <div class="card-title">

                    <form method="get" style="display:flex; column-gap: 3%; margin-right: 10px;">
                        <select class="form-control" name="product">
                            <option value="all">All</option>
                            @if(!empty($productList))
                            @foreach($productList as $productL)
                                <option {{ Request::get('product') == $productL->id? 'selected':'' }} value="{{ $productL->id }}">{{ $productL->name }}</option>
                            @endforeach
                            @endif
                        </select>
                        <button name="action" value="export" type="submit" href="{{ route('adminProduct') }}" class="btn btn-success btn-sm">Download</button>
                    </form>

                    <a href="{{ route('adminProduct') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">

                <form id="kt_form_pricing" enctype="multipart/form-data" class="form" action="{{ route('adminDoUpdateProductPricing') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Select File</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="file" name="file" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="File" value="">
                                        <span id="fileErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary" id="kt_form_pricing_submit">
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
    </script>
    <script src="{{ asset('public/backend/js/admin/product.js?v=1') }}"></script>

@endsection