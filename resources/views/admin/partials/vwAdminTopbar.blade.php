<div id="kt_app_header" class="app-header " 
data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}"  
>
    <div class="app-container  container-fluid d-flex flex-stack " id="kt_app_header_container">    
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
              <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>

            <a href="../index.html">
                <img alt="Logo" src="{{ asset('public/backend') }}/media/logos/default-small.svg" class="h-30px theme-light-show"/>
                <img alt="Logo" src="{{ asset('public/backend') }}/media/logos/default-small-dark.svg" class="h-30px theme-dark-show"/>
            </a>
        </div>

        <div class="d-flex flex-stack flex-lg-row-fluid" id="kt_app_header_wrapper">
            <div class="page-title gap-4 me-3 mb-5 mb-lg-0"  data-kt-swapper="1" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}" >
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 mb-2">        
                    <li class="breadcrumb-item text-gray-600 fw-bold lh-1">
                        <a href="{{ route('adminDashboard') }}" class="text-gray-700 text-hover-primary me-1">
                            <i class="ki-outline ki-home text-gray-700 fs-6"></i>       
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>                    
                    </li>
                    <li class="breadcrumb-item text-gray-600 fw-bold lh-1">Dashboards</li>
                </ul>
                <h1 class="text-gray-900 fw-bolder m-0">{{ $pageTitle }}</h1>
            </div>
                    
            <div class="">
                <div class="cursor-pointer symbol symbol-circle symbol-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-overflow="true" data-kt-menu-placement="top-start">
                     <img src="{{ getImg(adminInfo('profile')); }}" alt="image"/>
                </div>

                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ getImg(adminInfo('profile')); }}"/>
                            </div>                        
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    {{ adminInfo('name') }}
                                </div>
                                {{ adminInfo('email') }}
                            </div>
                        </div>
                    </div>                
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5 my-1">
                        <a href="{{ route('adminAccountSettings') }}" class="menu-link px-5">Account Settings</a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="{{ route('adminLogout') }}" class="menu-link px-5">Sign Out</a>
                    </div>                
                </div>
            </div>
        </div>
    </div>
</div>