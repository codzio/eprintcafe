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
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ ($menu == 'users' || $menu == 'roles')? 'show':'' }} menu-accordion" >
                    
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
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ ($menu == 'products' || $menu == 'category' || $menu == 'paper-size' || $menu == 'paper-type' || $menu == 'binding' || $menu == 'lamination' || $menu == 'cover' || $menu == 'gsm' || $menu == 'product')? 'show':'' }} menu-accordion" >
                    
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

                <!-- Coupon -->

                @can('read', 'coupon')
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ $menu == 'coupon'? 'show':'' }} menu-accordion" >
                    
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Coupon</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'coupon')
                            <a class="menu-link"  href="{{ route('adminCoupon') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Coupon</span>
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>
                @endcan

                <!-- Shipping -->

                @can('read', 'shipping')
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ $menu == 'shipping'? 'show':'' }} menu-accordion" >
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Shipping</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'shipping')
                            <a class="menu-link"  href="{{ route('adminShipping') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Shipping</span>
                            </a>
                            @endcan

                        </div>
                    </div>

                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'bulk-import')
                            <a class="menu-link"  href="{{ route('adminShippingBulkImport') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Bulk Import</span>
                            </a>
                            @endcan

                        </div>
                    </div>


                </div>
                @endcan

                @can('read', 'barcode')
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ $menu == 'barcode'? 'show':'' }} menu-accordion" >
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Barcode</span>
                        <span  class="menu-arrow"></span>
                    </span>

                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item">
                            @can('read', 'barcode')
                            <a class="menu-link"  href="{{ route('adminBarcode') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Barcode</span>
                            </a>
                            @endcan
                        </div>
                    </div>

                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            @can('read', 'barcode')
                            <a class="menu-link"  href="{{ route('adminBarcodeBulkImport') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Bulk Import</span>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                @endcan

                <!-- contact -->

                @can('read', 'contact')
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ $menu == 'contact'? 'show':'' }} menu-accordion" >
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Enquiry</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'contact')
                            <a class="menu-link"  href="{{ route('adminContact') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Contact Queries</span>
                            </a>
                            @endcan

                            @can('read', 'landing_page_enquiry')
                            <a class="menu-link"  href="{{ route('adminLandingPageEnquiry') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Landing Page</span>
                            </a>
                            @endcan

                        </div>
                    </div>


                </div>
                @endcan

                <!-- contact -->

                @can('read', 'orders')
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ $menu == 'orders'? 'show':'' }} menu-accordion" >
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Orders</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'orders')
                            <a class="menu-link"  href="{{ route('adminOrders') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Orders</span>
                            </a>
                            @endcan

                            @can('read', 'orders')
                            <a class="menu-link"  href="{{ route('adminDeletedOrders') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Deleted Orders</span>
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>
                @endcan

                @can('read', 'customers')
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ $menu == 'customers'? 'show':'' }} menu-accordion" >
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Customers</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'customers')
                            <a class="menu-link"  href="{{ route('adminCustomers') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Customers</span>
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>
                @endcan

                @can('read', 'abandoned-cart')
                <div  data-kt-menu-trigger="click"  class="menu-item here {{ $menu == 'abandoned-cart'? 'show':'' }} menu-accordion" >
                    <span class="menu-link" >
                        <span  class="menu-icon" >
                            <i class="ki-outline ki-home-2 fs-2"></i>
                        </span>
                        <span  class="menu-title">Abandoned Cart</span>
                        <span  class="menu-arrow"></span>
                    </span>


                    <div  class="menu-sub menu-sub-accordion" >
                        <div  class="menu-item" >
                            
                            @can('read', 'abandoned-cart')
                            <a class="menu-link"  href="{{ route('adminAbandonedCart') }}" >
                                <span  class="menu-bullet" >
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span  class="menu-title">Abandoned Cart</span>
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