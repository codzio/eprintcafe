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
                        
                        <!--begin::Card header-->
                        <div class="card-header pt-8">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-outline ki-magnifier fs-1 position-absolute ms-6"></i>    
                                    <input type="text" onkeyup="search(this.value)" class="form-control form-control-solid w-250px ps-15" placeholder="Search Coupon" />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-filemanager-table-toolbar="selected" style="padding-right: 8px">
                                    <div class="fw-bold me-5">
                                        <span class="me-2" data-kt-filemanager-table-select="selected_count"></span> Selected
                                    </div>
                                    <button onclick="startBulkDelete(this)" data-url="{{ route('adminBulkDeleteCoupon') }}" type="button" class="btn btn-danger" data-kt-filemanager-table-select="delete_selected">
                                        Delete Selected
                                    </button>
                                </div>

                                <a href="{{ route('adminAddCoupon') }}" class="btn btn-primary">Add Coupon</a>
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
                                        <th class="min-w-100px">Coupon Name</th>
                                        <th class="min-w-100px">Coupon Code</th>
                                        <th class="min-w-100px">Coupon Type</th>
                                        <th class="min-w-100px">Coupon Discount</th>
                                        <th class="min-w-100px">Status</th>
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
        dataUrl = '{{ route("getAdminCoupon") }}';        
    </script>

    <script src="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="{{ asset('public/backend/js/admin/coupon.js') }}"></script>

@endsection
