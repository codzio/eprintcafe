@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Order Details</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminOrders') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="">
                    <div class="card-body border-top p-9">

                       <table class="table table-bordered">
                            @if($order->status == 'paid')
                            <tr>
                                <th>Action</th>
                                <td>
                                    <button type="button" data-orderid="{{ $order->id }}" data-action="sms" onclick="sendInvoice(this)" id="sendSms" class="btn btn-primary btn-sm">Send SMS</button>
                                    <button type="button" data-orderid="{{ $order->id }}" data-action="email" onclick="sendInvoice(this)" id="sendEmail" class="btn btn-success btn-sm">Send Email</button>
                                </td>
                            </tr>
                            @endif
                            <tr>
                               <th>Status</th>
                               @if($order->status == 'paid')
                               <td><div class="badge badge-success">PAID</div></td>
                               @else
                               <td>
                                    <div class="badge badge-danger">UNPAID</div>
                                    <p>{{ route('payNow', ['orderid' => $order->order_id]) }}</p>
                               </td>
                               @endif
                           </tr>
                           <tr>
                               <th>Order Status</th>
                               <td>
                                   @if($order->order_status == 'Confirm')
                                   <div class="badge badge-primary">Confirm</div>
                                   @elseif($order->order_status == 'Production')
                                   <div class="badge badge-warning">Production</div>
                                   @elseif($order->order_status == 'Dispatch')
                                   <div class="badge badge-success">Dispatch</div>
                                   @else
                                   <div class="badge badge-danger">Cancel</div>
                                   @endif

                                    @if($order->order_status != 'Cancel' && $order->order_status != 'Dispatch' && $order->status == 'paid')
                                    <div style="display:flex;">
                                        
                                        <select id="changeOrderStatus" class="form-control" style="width: 18%; margin-top: 10px;">
                                           <option value="">Select Status</option>
                                            @if($order->order_status == 'Confirm')
                                               <option value="Production">Production</option>
                                               <option value="Dispatch">Dispatch</option>
                                               <option value="Cancel">Cancel</option>
                                            @elseif($order->order_status == 'Production')
                                                <option value="Dispatch">Dispatch</option>
                                                <option value="Cancel">Cancel</option>
                                            @endif
                                        </select>

                                        <button type="button" data-id="{{ $order->id }}" data-url="{{ route('adminUpdateOrderStatus') }}" id="changeOrderStatusBtn" style="margin-left:10px" class="mt-4 btn btn-primary btn-sm">Update Order Status</button>

                                    </div>
                                    <p id="changeOrderStatusMsg"></p>
                                    @endif

                               </td>
                           </tr>
                           <tr>
                               <th>Order ID</th>
                               <td>{{ strtoupper($order->order_id) }}</td>
                           </tr>
                           <tr>
                               <th>Order Date</th>
                               <td>{{ date('d-m-Y h:i A', strtotime($order->created_at)) }}</td>
                           </tr>
                           <tr>
                               <th>Invoice Number</th>
                               <td>{{ strtoupper($order->invoice_number) }}</td>
                           </tr>
                           <tr>
                               <th>Tracking ID</th>
                               <td>
                                    @if(!$order->shipping_label_number && $order->order_status == 'Dispatch')
                                        <button data-id="{{ $order->id }}" data-url="{{ route('adminUpdateBarcode') }}" onclick="updateBarcode(this)" type="button" class="btn btn-primary">Update Tracking ID</button>
                                    @else
                                        {{ $order->shipping_label_number }}
                                    @endif
                               </td>
                           </tr>
                           <tr>
                               <th>Order Date</th>
                               <td>{{ date('d-m-Y h:i A', strtotime($order->created_at)) }}</td>
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
                           @endif

                           @if($order->product_id)
                           <tr>
                               <th>Product Name</th>
                               <td>{{ $order->product_name }}</td>
                           </tr>
                           <tr>
                               <th>Qty</th>
                               <td>{{ $order->qty }}</td>
                           </tr>
                           <tr>
                               <th>No of Copies</th>
                               <td>{{ $order->no_of_copies }}</td>
                           </tr>
                           @endif

                           <tr>
                               <th>Coupon Code</th>
                               <td>{{ $order->coupon_code }}</td>
                           </tr>
                           <tr>
                               <th>Discount</th>
                               <td>{{ $order->discount }}</td>
                           </tr>
                           <tr>
                               <th>Additional Discount</th>
                               <td>{{ $order->additional_discount }}</td>
                           </tr>
                           <tr>
                               <th>Shipping</th>
                               <td>{{ $order->shipping }}</td>
                           </tr>
                           <tr>
                               <th>Sub Total</th>
                               @if($adminData->role_id == 1)
                               <td style="color:red;font-weight:bold">{{ $order->paid_amount+$order->additional_discount }}</td>
                               @else
                               <td>XXXX</td>
                               @endif
                           </tr>

                           <tr>
                               <th>Packaging Charges</th>
                               @if($adminData->role_id == 1)
                               <td style="color:green;font-weight: bold;">{{ $order->packaging_charges }}</td>
                               @else
                               <td>XXXX</td>
                               @endif
                           </tr>

                           <tr>
                               <th>Paid Amount</th>
                               @if($adminData->role_id == 1)
                               <td style="color:green;font-weight: bold;">{{ $order->paid_amount }}</td>
                               @else
                               <td>XXXX</td>
                               @endif
                           </tr>

                           <tr>
                               <th>Payment Method</th>
                               <td style="color:blue;font-weight: bold;">{{ $order->payment_method }}</td>
                           </tr>

                           @if($order->product_id)
                           <tr>
                               <th>Product Detail</th>
                               <td>{!! json_decode($order->product_details) !!}</td>
                           </tr>
                           <tr>
                               <th>Price Details</th>
                               <td>
                                   <li><strong>Per Sheet Weight:</strong> {{ $priceDetail->per_sheet_weight }}</li>
                                   <li><strong>Paper Type Price:</strong> {{ $priceDetail->paper_type_price }}</li>
                                   <li><strong>Color & Print Side:</strong> {{ $priceDetail->printSideAndColorPrice }}</li>
                                   <li><strong>Binding:</strong> {{ $priceDetail->binding }}</li>
                                   <li><strong>Lamination:</strong> {{ $priceDetail->lamination }}</li>
                                   <li><strong>Cover:</strong> {{ $priceDetail->cover }}</li>
                               </td>
                           </tr>
                           @endif
                           <tr>
                               <th>Transactions Details</th>
                               <td>
                                   <li>
                                        Transaction Id: 
                                        @if(isset($transactionDetail->transactionId))
                                            {{ $transactionDetail->transactionId }}
                                        @elseif(isset($transactionDetail->payuMoneyId))
                                            {{ $transactionDetail->payuMoneyId }}
                                        @endif
                                   </li>
                                   <li>Status: 
                                        @if(isset($transactionDetail->responseCode))
                                            {{ $transactionDetail->responseCode }}
                                        @elseif(isset($transactionDetail->status))
                                            {{ $transactionDetail->status }}
                                        @endif
                                   </li>
                               </td>
                           </tr>

                           @if(!empty($order->wetransfer_link))
                           <tr>
                               <th>Wetransfer Link</th>
                               <td>
                                   <p><a target="_blank" href="{{ $order->wetransfer_link }}">Document Link</a></p>
                               </td>
                           </tr>
                           @else
                           <tr>
                               <th>Document Link</th>
                               <td>
                                   @if(!empty($documentLinks))
                                   @foreach($documentLinks as $docLink)

                                    @if(isset($docLink->fileId))
                                        <p><a target="_blank" href="https://drive.google.com/file/d/{{ $docLink->fileId }}">{{ $docLink->fileName }}</a></p>
                                    @else
                                    
                                    {{$docLink}}

                                    @endif

                                   @endforeach
                                   @endif
                               </td>
                           </tr>
                           @endif
                           <tr>
                               <th>Customer Address</th>
                               <td>
                                   <li><strong>Shipping Name:</strong> {{ $addressDetails->shipping_name }}</li>
                                   <li><strong>Shipping Company Name:</strong> {{ $addressDetails->shipping_company_name }}</li>
                                   <li><strong>Shipping Address:</strong> {{ $addressDetails->shipping_address }}</li>
                                   <li><strong>Shipping City:</strong> {{ $addressDetails->shipping_city }}</li>
                                   <li><strong>Shipping State:</strong> {{ $addressDetails->shipping_state }}</li>
                                   <li><strong>Shipping Pincode:</strong> {{ $addressDetails->shipping_pincode }}</li>
                                   <li><strong>Shipping Email:</strong> {{ $addressDetails->shipping_email }}</li>
                                   <li><strong>Shipping Phone:</strong> {{ $addressDetails->shipping_phone }}</li>
                                   <li><strong>Is Billing Address Same:</strong> {{ ($addressDetails->is_billing_same == 1)? 'Yes':'No';}}</li>
                               </td> 
                           </tr>
                           <tr>
                               <th>Admin User Info</th>
                               <td>
                                    @if($adminUserData)
                                       <li><strong>Name:</strong> {{ $adminUserData->name }}</li>
                                       <li><strong>Email:</strong> {{ $adminUserData->email }}</li>
                                       <li><strong>Phone:</strong> {{ $adminUserData->phone_number }}</li>
                                    @else
                                        <li>No Data Found</li>
                                    @endif
                               </td>
                           </tr>
                           @if($addressDetails->is_billing_same != 1)
                           <tr>
                               <th>Billing Address</th>
                               <td>
                                   <li><strong>Billing Name:</strong> {{ $addressDetails->billing_name }}</li>
                                   <li><strong>Billing Company Name:</strong> {{ $addressDetails->billing_company_name }}</li>
                                   <li><strong>Billing Address:</strong> {{ $addressDetails->billing_address }}</li>
                                   <li><strong>Billing City:</strong> {{ $addressDetails->billing_city }}</li>
                                   <li><strong>Billing State:</strong> {{ $addressDetails->billing_state }}</li>
                                   <li><strong>Billing Pincode:</strong> {{ $addressDetails->billing_pincode }}</li>
                                   <li><strong>Billing Email:</strong> {{ $addressDetails->billing_email }}</li>
                                   <li><strong>Billing Phone:</strong> {{ $addressDetails->billing_phone }}</li>
                               </td> 
                           </tr>
                           @endif

                           <tr>
                               <th>Courier</th>
                               <td><?php echo $order->courier ?? '-'; ?></td>
                           </tr>
                           
                       </table>

                       @if(!empty($orderItems) && $orderItems->count())
                       <h3>Product Items</h3>
                       <table class="table table-bordered">
                           <thead>
                               <th>#</th>
                               <th>Product Name</th>
                               <th>QTY</th>
                               <th>Copies</th>
                               <th>Product Details</th>
                               <th>Price Detail</th>
                           </thead>
                           <tbody>
                            @php $i=1; @endphp
                            @foreach($orderItems as $orderItem)
                            @php
                                $priceDetail = json_decode($orderItem->price_details);
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $orderItem->product_name }}</td>
                                <td>{{ $orderItem->qty }}</td>
                                <td>{{ $orderItem->no_of_copies }}</td>
                                <td>{!! json_decode($orderItem->product_details) !!}</td>
                                <td>
                                   <li><strong>Per Sheet Weight:</strong> {{ $priceDetail->per_sheet_weight }}</li>
                                   <li><strong>Paper Type Price:</strong> {{ $priceDetail->paper_type_price }}</li>
                                   <li><strong>Color & Print Side:</strong> {{ $priceDetail->printSideAndColorPrice }}</li>
                                   <li><strong>Binding:</strong> {{ $priceDetail->binding }}</li>
                                   
                                   @if(isset($priceDetail->split))
                                   <li><strong>Binding Split:</strong> {{ $priceDetail->split }}</li>
                                   @endif

                                   <li><strong>Lamination:</strong> {{ $priceDetail->lamination }}</li>
                                   <li><strong>Cover:</strong> {{ $priceDetail->cover }}</li>
                               </td>
                            </tr>
                            @endforeach
                           </tbody>
                       </table>
                       @endif

                    </div>      
                </form>
            </div>
        </div>

    </div>
</div>

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">
        dataUrl = '{{ route("getAdminOrders") }}';

        function sendInvoice(el) {

            orderId = $(el).data('orderid');
            action = $(el).data('action');

            $.ajax({
                url: '{{ route("adminDoSendInvoice") }}',
                type: 'POST',
                dataType: 'json',
                data: {orderId: orderId, action: action},
                beforeSend: function() {
                    $(el).html('Please Wait...').attr('disabled', true);
                },
                success: function(res) {
                    if (res.error == true) {
                        if (res.eType == 'field') {
                            toastr.error(res.msg);
                        } else {
                            toastr.error(res.msg);
                        }
                    } else {
                        toastr.success(res.msg);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }

                    if (action == 'sms') {
                        $(el).html('Send SMS').removeAttr('disabled');
                    } else {
                        $(el).html('Send Email').removeAttr('disabled');
                    }

                }
            });            
            
        }

    </script>
    <script src="{{ asset('public/backend/js/admin/order.js?v=1') }}"></script>

@endsection