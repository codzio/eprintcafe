@extends('admin.vwAdminMaster')

@section('content')

<style type="text/css">
    .dropzone {
      width: 98%;
      margin: 1%;
      border: 2px dashed #3498db !important;
      border-radius: 5px;
      transition: 0.2s;
    }

    .dropzone.dz-drag-hover {
      border: 2px solid #3498db !important;
    }

    .dz-message.needsclick img {
      width: 50px;
      display: block;
      margin: auto;
      opacity: 0.6;
      margin-bottom: 15px;
    }

    span.plus {
      display: none;
    }

    .dropzone .dz-message {
        display: block;
        text-align: center;
    }

    .card .card-header {
        border-bottom: none;
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<link href="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>

    
    <div id="kt_app_content" class="app-content  flex-column-fluid " >
    
           
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-fluid ">
            <!--begin::Card-->
<div 
    class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10" 
    style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('{{ asset("public/backend") }}/media/illustrations/sketchy-1/4.png')">

    <!--begin::Card header-->
    <div class="card-header pt-10">
        <div class="d-flex align-items-center">
            <!--begin::Icon-->
            <div class="symbol symbol-circle me-5">
                <div class="symbol-label bg-transparent text-primary border border-secondary border-dashed">
                    <i class="ki-outline ki-abstract-47 fs-2x text-primary"></i>                </div>
            </div>
            <!--end::Icon-->

            <!--begin::Title-->
            <div class="d-flex flex-column">
                <h2 class="mb-1">{{ $pageTitle }}</h2>
                <div class="text-muted fw-bold">
                    {{ $totalSize }} <span class="mx-3">|</span> {{ $totalItems }} items
                </div> 
            </div>
            <!--end::Title-->
        </div>
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pb-0">
        <!--begin::Navs-->
        <div class="d-flex overflow-auto h-55px">
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-semibold flex-nowrap">
                <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6 active" data-bs-toggle="pill" href="#kt_list_widget_10_tab_1">
                        All Media
                    </a>
                </li>
                <!--end::Nav item-->

                <!--begin::Nav item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6" data-bs-toggle="pill" href="#kt_list_widget_10_tab_2">Add Media</a>
                </li>
                <!--end::Nav item-->
            </ul>
        </div>
        <!--begin::Navs-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->

<!--begin::Card-->
<div class="card card-flush">

    <div class="tab-content">
                            
        <!--begin::Tap pane-->
        <div class="tab-pane fade show active" id="kt_list_widget_10_tab_1">
            
            <!--begin::Card header-->
            <div class="card-header pt-8">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-1 position-absolute ms-6"></i>    
                        <input type="text" onkeyup="search(this.value)" class="form-control form-control-solid w-250px ps-15" placeholder="Search Media" />
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-filemanager-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-filemanager-table-select="selected_count"></span> Selected
                        </div>
                        <button onclick="startBulkDelete(this)" data-url="{{ route('adminDoMediaBulkDelete') }}" type="button" class="btn btn-danger" data-kt-filemanager-table-select="delete_selected">
                            Delete Selected
                        </button>
                    </div>
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
                                    <input onclick="bulkSelect(this)" class="form-check-input" type="checkbox" data-kt-check="false" value="1" />
                                </div>
                            </th>
                            <th class="min-w-100px">Name</th>
                            <th class="min-w-10px">ALT</th>
                            <th class="min-w-10px">Size</th>
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
                    
        <!--begin::Tap pane-->
        <div class="tab-pane fade" id="kt_list_widget_10_tab_2">
            
            <div id="dropzone">
              <form action="/upload" class="dropzone needsclick" id="upload">
                <div class="dz-message needsclick">
                  <span class="text">
                  <img src="{{ asset('public/backend/media/camera.png') }}" alt="Camera" />
                  Drop files here or click to upload.
                  </span>
                  <span class="plus">+</span>
                </div>
              </form>
            </div>
        </div>
        <!--end::Tap pane-->               
    </div>
</div>
<!--end::Card-->

        </div>
        <!--end::Content container-->
    </div>

    <script type="text/javascript">
        uploadUrl = '{{ route("adminMediaDoUpload") }}';
        dataUrl = '{{ route("adminGetMedia") }}';        
    </script>
    <script src="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.js"></script>
    <!-- <script src="{{ asset('public/backend') }}/js/custom/apps/file-manager/list.js"></script> -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="{{ asset('public/backend/js/admin/media.js') }}"></script>

@endsection
