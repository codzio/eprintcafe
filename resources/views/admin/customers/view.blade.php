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
                <div class="card-body border-top p-9">

                   <table class="table table-bordered">
                        <tr>
                           <th>Wallet Amount</th>
                           <th>{{ $customer->wallet_amount }} Rs</th>
                       </tr>
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
            </div>

            <div id="wallletDiv" class="collapse show">                
                <form id="kt_form" class="form" action="{{ route('adminDoAddWalletAmount') }}" method="post" enctype="multipart/form-data">
                    <div class="card-body border-top p-9">
                       <table class="table table-bordered">
                           <thead>
                               <tr>
                                   <th>Amount</th>
                                   <th>Type</th>
                                   <th>Remark</th>
                                   <th>Action</th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr>
                                   <td>
                                        <input step=".01" type="number" name="amount" class="form-control">
                                        <span id="amountErr" class="text-danger removeErr error"></span>
                                    </td>
                                   <td>
                                       <select class="form-control" name="type" class="form-control">
                                           <option value="credit">Credit</option>
                                           <option value="debit">Debit</option>
                                       </select>
                                       <span id="typeErr" class="text-danger removeErr error"></span>
                                   </td>
                                   <td>
                                        <textarea class="form-control" name="narration"></textarea>
                                        <span id="narrationErr" class="text-danger removeErr error"></span>
                                   </td>
                                   <td>
                                        <input type="hidden" name="customerId" value="{{ $customer->id }}">
                                        <button type="submit" class="btn btn-success" id="kt_form_submit_save">
                                            <span class="indicator-label">Save</span>
                                            <span class="indicator-progress">
                                                Please wait...    
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </td>
                               </tr>
                           </tbody>
                       </table>

                    </div>
                </form>
            </div>

            <div id="kt_account_settings_profile_details" class="collapse show">                
                <div class="card-body border-top p-9">

                   <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($walletHistory && $walletHistory->count())
                            @php 
                                $i=1; 
                                $totalCredit = 0;
                                $totalDebit = 0;
                            @endphp
                            @foreach($walletHistory as $walletH)
                            @php 
                                $totalCredit += $walletH->credit;
                                $totalDebit += $walletH->debit;
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $walletH->credit }}</td>
                                <td>{{ $walletH->debit }}</td>
                                <td>{{ $walletH->narration }}</td>
                                <td>{{ date('d-m-Y', strtotime($walletH->created_at)) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <th>{{ $totalCredit }}</th>
                                <th>{{ $totalDebit }}</th>
                                <td colspan="2"><strong>Total Amount</strong> - {{ $totalCredit-$totalDebit }}</td>
                            </tr>
                        @endif
                    </tbody>
                   </table>

                </div>
            </div>

        </div>

    </div>
</div>

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">
        dataUrl = '{{ route("getAdminOrders") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/order.js') }}"></script>

@endsection