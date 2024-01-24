<div id="kt_app_sidebar" class="app-sidebar  flex-column " 
data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle"      
>
    <div class="app-sidebar-header d-none d-lg-flex px-6 pt-8 pb-4" id="kt_app_sidebar_header">    
        <button type="button" data-kt-element="selected" class="btn btn-outline btn-custom btn-flex w-100" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, -1px">
            <span class="d-flex flex-center flex-shrink-0 w-40px me-3">
                <img alt="Logo" src="{{ getImg(setting('admin_logo')); }}" data-kt-element="logo" class="h-30px"/>
            </span>    
            <span class="d-flex flex-column align-items-start flex-grow-1">
                <span class="fs-5 fw-bold text-white text-uppercase" data-kt-element="title">{{ setting('website_name') }}</span>
            </span>        
        </button>
    </div>

    <!--begin::Navs-->
    <div class="app-sidebar-navs flex-column-fluid mx-2" id="kt_app_sidebar_navs">    
        <div 
        id="kt_app_sidebar_navs_wrappers" 
        class="hover-scroll-y my-2"        
        data-kt-scroll="true"
        data-kt-scroll-activate="true"
        data-kt-scroll-height="auto"     
        data-kt-scroll-dependencies="#kt_app_sidebar_header, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_navs" 
        data-kt-scroll-offset="5px">
            <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false" class="menu menu-column menu-rounded menu-sub-indention menu-active-bg">

                @can('read', 'dashboard')
                <div class="menu-item here">            
                    <a href="{{ route('adminDashboard') }}" class="menu-link {{ $menu == 'dashboard'? 'active':''; }}">                
                        <span class="menu-icon">
                            <i class="ki-outline ki-home-2 fs-2"></i>                
                        </span>
                        <span class="menu-title">Dashboards</span>
                    </a>            
                </div>
                @endcan

                @can('read', 'media')
                <div class="menu-item here">            
                    <a href="{{ route('adminMedia') }}" class="menu-link {{ $menu == 'media'? 'active':''; }}">                
                        <span class="menu-icon">
                            <i class="ki-outline ki-picture fs-2"></i>                
                        </span>
                        <span class="menu-title">Media Manager</span>
                    </a>            
                </div>
                @endcan
                
                @if(can('read', 'users') || can('read', 'roles'))
                <div  data-kt-menu-trigger="click"  class="menu-item here show menu-accordion" >
                    
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">User Roles & Permissions</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'users')
                            <a class="menu-link"  href="{{ route('adminUsers') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Users</span>
                            </a>
                            @endcan

                            @can('read', 'roles')
                            <a class="menu-link"  href="{{ route('adminRoles') }}" >
                                <span  class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Roles & Permissions</span>
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>
                @endif

                @can('read', 'products')
                <div  data-kt-menu-trigger="click"  class="menu-item here show menu-accordion" >
                    
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Products</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'category')
                            <a class="menu-link"  href="{{ route('adminCategory') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Category</span>
                            </a>
                            @endcan

                            @can('read', 'paper-size')
                            <a class="menu-link"  href="{{ route('adminPaperSize') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Paper Size</span>
                            </a>
                            @endcan

                            @can('read', 'paper-type')
                            <a class="menu-link"  href="{{ route('adminPaperType') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Paper Type</span>
                            </a>
                            @endcan

                            @can('read', 'binding')
                            <a class="menu-link"  href="{{ route('adminBinding') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Binding</span>
                            </a>
                            @endcan

                            @can('read', 'lamination')
                            <a class="menu-link"  href="{{ route('adminLamination') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Lamination</span>
                            </a>
                            @endcan

                            @can('read', 'cover')
                            <a class="menu-link"  href="{{ route('adminCover') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Cover</span>
                            </a>
                            @endcan

                            @can('read', 'gsm')
                            <a class="menu-link"  href="{{ route('adminGsm') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Paper GSM</span>
                            </a>
                            @endcan

                            @can('read', 'product')
                            <a class="menu-link"  href="{{ route('adminProduct') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Product</span>
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>
                @endcan

                @can('read', 'site_settings')
                <div class="menu-item here">            
                    <a href="{{ route('adminSiteSetting') }}" class="menu-link {{ $menu == 'site-settings'? 'active':''; }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-gear fs-2"></i>                
                        </span>
                        <span class="menu-title">Site Settings</span>
                    </a>            
                </div>
                @endcan
                
            </div>
        </div>
    </div>
    <!--end::Navs-->
</div>