<!DOCTYPE html>
<html lang="en" >    
    <head>
        <title>{{ setting('website_name') }} | {{ $title }}</title>
        <meta charset="utf-8"/>
        <meta name="description" content=""/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">  
        <link rel="shortcut icon" href="{{ getImg(setting('favicon')); }}"/>

        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>        <!--end::Fonts-->

        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href="{{ asset('public/backend') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('public/backend') }}/css/style.bundle.css" rel="stylesheet" type="text/css"/>
        <!--end::Global Stylesheets Bundle-->

        <link href="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>

        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="{{ asset('public/backend') }}/plugins/global/plugins.bundle.js"></script>
        <script src="{{ asset('public/backend') }}/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->

        <script src="{{ asset('public/backend') }}/plugins/custom/datatables/datatables.bundle.js"></script>

        <script>
            // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
            if (window.top != window.self) {
                window.top.location.replace(window.self.location.href);
            }
        </script>

        @if(isset($editor) && $editor)
        <script src="https://cdn.tiny.cloud/1/r22x6ervjq57zyw7ir7bmn1t7vbuoo2t419ium62xoeakdul/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

        <script>
          tinymce.init({
            selector: '.editor',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount advlist print preview code',
            toolbar: 'code preview undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            valid_elements : '*[*]',
          });
        </script>
        @endif
        
    </head>
    <!--end::Head-->

    <!--begin::Body-->
    <body  id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"  class="app-default" >
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
                
                <!--begin::Header-->
                @include('admin.partials.vwAdminTopbar')
                <!--end::Header-->

                <!--begin::Wrapper-->
                <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">

                    <!--begin::Sidebar-->
                    @include('admin.partials.vwAdminSidebar')
                    <!--end::Sidebar-->
                
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                        
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            
                            <!--begin::Content-->
                            @yield('content')
                            <!--end::Content--> 

                        </div>
                        <!--end::Content wrapper-->

                        <!--begin::Footer-->
                        @include('admin.partials.vwAdminFooterBar')
                        <!--end::Footer-->

                    </div>
                    <!--end:::Main-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::App-->

        @if(isset($allowMedia) && $allowMedia)

        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
        
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

        <!-- Media Modal Start -->
        <div class="modal fade bg-dark bg-opacity-75" id="kt_app_engage_prebuilts_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen p-5 p-lg-10" id="kt_engage_prebuilts_modal_dialog">        
                <div class="modal-content rounded-4">            
                    <div class="modal-header flex-stack border-0 px-10 pt-5 pb-0" id="kt_engage_prebuilts_header">
                        <div id="kt_app_engage_prebuilts_view_menu" class="position-relative z-index-1"></div>
                        <div class="btn btn-sm btn-icon btn-active-color-primary me-n2 position-relative z-index-1" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross-square fs-1"></i>                
                        </div>                
                    </div>

                    <div class="modal-body pt-0 pb-5 px-15 mt-n5" id="kt_app_engage_prebuilts_body">
                        <div class="container-fluid">
                            <style>
                                .app-prebuilts-thumbnail {
                                    border: 1px solid var(--kt-body-bg);
                                    filter: drop-shadow(0px 0px 50px rgba(49, 52, 122, 0.12));
                                }
                            </style>

                            <div class="d-block" id="kt_app_engage_prebuilts_view_image">    
                                <div class="d-flex flex-center" id="kt_app_engage_prebuilts_view_image_tabs">
                                    <ul class="nav nav-tabs border-0 mb-5">
                                        <li class="nav-item px-2">
                                            <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-gray-800 fs-5 fw-bold active"data-bs-toggle="tab" href="#kt_app_engage_prebuilts_tab_demos">All Media</a>
                                        </li>
                                        <li class="nav-item px-2">
                                            <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-gray-800 fs-5 fw-bold" data-bs-toggle="tab" href="#kt_app_engage_prebuilts_tab_dashboards">Add Media</a>
                                        </li>
                                    </ul>        
                                </div>
                                
                                <div class="tab-content">
                                    <div class="pt-5 tab-pane fade show active" id="kt_app_engage_prebuilts_tab_demos" role="tabpanel">            
                                        <div class="hover-scroll-y pe-12 me-n12" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_app_engage_prebuilts_modal, #kt_app_engage_prebuilts_modal_dialog, #kt_app_engage_prebuilts_body" data-kt-scroll-dependencies="#kt_app_engage_prebuilts_header, #kt_app_engage_prebuilts_view_image_tabs" data-kt-scroll-offset="215px">                    
                                            <div class="row gy-10">
                                                
                                                <!--begin::Card header-->
                                                <div class="card-header pt-8">
                                                    <div class="card-title">
                                                        <div class="d-flex align-items-center position-relative my-1">
                                                            <i class="ki-outline ki-magnifier fs-1 position-absolute ms-6"></i>    
                                                            <input type="text" onkeyup="searchMedia(this.value)" class="form-control form-control-solid w-250px ps-15" placeholder="Search Media" />
                                                        </div>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="d-flex justify-content-end align-items-center d-none" data-kt-filemanager-table-toolbar="selected">
                                                            <div class="fw-bold me-5">
                                                                <span class="me-2" data-kt-filemanager-table-select="selected_count"></span> Selected
                                                            </div>
                                                            <button onclick="startBulkDeleteMedia(this)" data-url="{{ route('adminDoMediaBulkDelete') }}" type="button" class="btn btn-danger" data-kt-filemanager-table-select="delete_selected">
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
                                                                        <input onclick="bulkSelectMedia(this)" class="form-check-input" type="checkbox" data-kt-check="false" value="1" />
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
                                        </div>
                                    </div>
                                    <div class="pt-5 tab-pane fade" id="kt_app_engage_prebuilts_tab_dashboards" role="tabpanel">
                                        <div class="hover-scroll-y pe-12 me-n12" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_app_engage_prebuilts_modal, #kt_app_engage_prebuilts_modal_dialog, #kt_app_engage_prebuilts_body" data-kt-scroll-dependencies="#kt_app_engage_prebuilts_header, #kt_app_engage_prebuilts_view_image_tabs" data-kt-scroll-offset="215px">
                                            <div class="row gy-10">
                                                
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
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Media Modal End -->

        <script type="text/javascript">
            uploadUrl = '{{ route("adminMediaDoUpload") }}';
            dataUrl = '{{ route("adminGetMedia") }}';        
        </script>

        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <script src="{{ asset('public/backend/js/admin/media-modal.js') }}"></script>

        @endif

        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-outline ki-arrow-up"></i>
        </div>
        <!--end::Scrolltop-->
    </body>
</html>