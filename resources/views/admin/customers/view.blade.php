@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Customer Details</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminCustomers') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="">
                    <div class="card-body border-top p-9">

                       <table class="table table-bordered">
                           <tr>
                               <th>Customer Name</th>
                               <td>{{ strtoupper($customer->name) }}</td>
                           </tr>
                           @if($adminData->role_id == 1)
                           <tr>
                               <th>Customer Email</th>
                               <td>{{ $customer->email }}</td>
                           </tr>
                           <tr>
                               <th>Customer Phone</th>
                               <td>{{ $customer->phone }}</td>
                           </tr>
                           @else
                           <tr>
                               <th>Customer Email</th>
                               <td>XXXXXXXXXX</td>
                           </tr>
                           <tr>
                               <th>Customer Phone</th>
                               <td>XXXXXXXXXX</td>
                           </tr>
                           @endif
                           <tr>
                               <th>Customer Address</th>
                               <td>{{ $customer->address }}</td>
                           </tr>
                           <tr>
                               <th>Customer City</th>
                               <td>{{ $customer->city }}</td>
                           </tr>
                           <tr>
                               <th>Customer State</th>
                               <td>{{ $customer->state }}</td>
                           </tr>

                           @if(!empty($customerAddress))
                           <tr>
                               <th>Customer Address</th>
                               <td>
                                   <li><strong>Shipping Name:</strong> {{ $customerAddress->shipping_name }}</li>
                                   <li><strong>GST Number:</strong> {{ $customerAddress->gst_number }}</li>
                                   <li><strong>Shipping Company Name:</strong> {{ $customerAddress->shipping_company_name }}</li>
                                   <li><strong>Shipping Address:</strong> {{ $customerAddress->shipping_address }}</li>
                                   <li><strong>Shipping City:</strong> {{ $customerAddress->shipping_city }}</li>
                                   <li><strong>Shipping State:</strong> {{ $customerAddress->shipping_state }}</li>
                                   <li><strong>Shipping Pincode:</strong> {{ $customerAddress->shipping_pincode }}</li>
                                   <li><strong>Shipping Email:</strong> {{ $customerAddress->shipping_email }}</li>
                                   <li><strong>Shipping Phone:</strong> {{ $customerAddress->shipping_phone }}</li>
                                   <li><strong>Is Billing Address Same:</strong> {{ ($customerAddress->is_billing_same == 1)? 'Yes':'No';}}</li>
                               </td> 
                           </tr>
                           @if($customerAddress->is_billing_same != 1)
                           <tr>
                               <th>Billing Address</th>
                               <td>
                                   <li><strong>Billing Name:</strong> {{ $customerAddress->billing_name }}</li>
                                   <li><strong>Billing Company Name:</strong> {{ $customerAddress->billing_company_name }}</li>
                                   <li><strong>Billing Address:</strong> {{ $customerAddress->billing_address }}</li>
                                   <li><strong>Billing City:</strong> {{ $customerAddress->billing_city }}</li>
                                   <li><strong>Billing State:</strong> {{ $customerAddress->billing_state }}</li>
                                   <li><strong>Billing Pincode:</strong> {{ $customerAddress->billing_pincode }}</li>
                                   <li><strong>Billing Email:</strong> {{ $customerAddress->billing_email }}</li>
                                   <li><strong>Billing Phone:</strong> {{ $customerAddress->billing_phone }}</li>
                               </td> 
                           </tr>
                           @endif
                           @endif

                           <tr>
                               <th>Registered Date</th>
                               <td>{{ date('d-m-Y h:i A', strtotime($customer->created_at)); }}</td>
                           </tr>
                           
                       </table>

                    </div>      
                </form>
            </div>
        </div>

    </div>
</div>

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">
        dataUrl = '{{ route("getAdminOrders") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/orders.js') }}"></script>

@endsection