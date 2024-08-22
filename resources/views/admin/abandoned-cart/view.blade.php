@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Abandoned Cart Details</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminAbandonedCart') }}" class="btn btn-primary">Back</a>
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
                               <td>XXXXXXX</td>
                           </tr>
                           <tr>
                               <th>Customer Phone</th>
                               <td>XXXXXXXXXX</td>
                           </tr>
                           @endif
                           <tr>
                               <th>City</th>
                               <td>{{ $customer->city }}</td>
                           </tr>
                       </table>

                       @if(!empty($cartItems) && $cartItems->count())
                       
                       <div style="display: flex; align-items: center;margin: 1rem;">
                            <h3>Product Items</h3>
                            <button data-user="{{ $customer->id }}" data-action="{{ route('adminMoveToOrders') }}" onclick="moveToOrders(this)" type="button" style="margin-left: auto;" class="btn btn-primary btn-sm">Move To Orders</button>
                       </div>

                       <table class="table table-bordered">
                           <thead>
                               <th>#</th>
                               <th>Product Name</th>
                               <th>Product Details</th>
                               <th>Price</th>
                               <th>No of Pages</th>
                               <th>Copies</th>
                               <th>Total</th>
                               <th>Date</th>
                           </thead>
                           <tbody>
                            @php $i=1; @endphp
                            @foreach($cartItems as $cart)
                            @php
                                $price = 0;
                                $productId = $cart->product_id;
                                $userId = $cart->user_id;

                                if(isset(productSinglePriceAbdCart($productId, $userId)->price)) {
                                  $price = productSinglePriceAbdCart($productId, $userId)->price;
                                }
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $cart->name }}</td>
                                <td> 
                                    {!! productSpec($cart->id) !!}
                                    <p><strong>Binding:</strong> {{ productSinglePriceAbdCart($productId, $userId)->binding }}</p>
                                    <p><strong>Lamination:</strong> {{ productSinglePriceAbdCart($productId, $userId)->lamination }}</p>
                                    <p><strong>Cover:</strong> {{ productSinglePriceAbdCart($productId, $userId)->cover }}</p>
                                </td>
                                <td>{{ $price }}</td>
                                <td>{{ $cart->qty }}</td>
                                <td>{{ $cart->no_of_copies }}</td>
                                <td>{{ productSinglePriceAbdCart($productId, $userId)->total }}</td>
                                <td>{{ date('d-m-Y h:i A', strtotime($cart->created_at)) }}</td>
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
    </script>
    <script src="{{ asset('public/backend/js/admin/order.js?v=3') }}"></script>

@endsection