@if(!empty($saveProductList) && $saveProductList->count())
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Products</th>
                <th>Price</th>
                <th>No Of Pages/Copies</th>
                <th>Total</th>
                <th>Remark</th>
                <th>File</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach($saveProductList as $cart)
            @php
                $price = 0;
                $productId = $cart->product_id;
                $userId = $cart->user_id;

                if(isset(productSinglePriceForCusOrder($productId, $userId)->price)) {
                  $price = productSinglePriceForCusOrder($productId, $userId)->price;
                }
            @endphp
            <tr>
                <td>{{ $i++ }}</td>
                <td>
                    {{ $cart->name }}
                    <div id="cart-item-{{$cart->id}}" class="product-desc" style="display:none">
                      {!! productSpecForCusOrder($cart->id) !!}
                      <p><strong>Binding:</strong> {{ productSinglePriceForCusOrder($productId, $userId)->binding }}</p>

                      @if(productSinglePriceForCusOrder($productId, $userId)->split)
                        <p><strong>Split:</strong> {{ productSinglePriceForCusOrder($productId, $userId)->split }}</p>
                      @endif

                      <p><strong>Lamination:</strong> {{ productSinglePriceForCusOrder($productId, $userId)->lamination }}</p>
                      <p><strong>Cover:</strong> {{ productSinglePriceForCusOrder($productId, $userId)->cover }}</p>
                    </div>
                    <p onclick="toggleDetail(this, '{{ $cart->id }}')" id="view-detail-{{$cart->id}}" style="cursor: pointer; font-weight: bold;">View Details</p>
                </td>
                <td>{{ $price }}</td>
                <td>
                    <input onchange="updateCustomCart(this)" data-url="{{ route('adminDoUpdateCustomCart') }}" data-action="qty" data-user="{{ $userId }}" data-id="{{ $cart->id }}" min="1" id="qty" type="number" style="width:95px; text-align: center;" name="qty[{{ $cart->id }}]" value="{{ $cart->qty }}" placeholder="No of Pages">
                    <input onchange="updateCustomCart(this)" data-url="{{ route('adminDoUpdateCustomCart') }}" data-action="copies" data-user="{{ $userId }}" data-id="{{ $cart->id }}" min="1" id="copies" type="number" style="width:95px; text-align: center;" name="copies[{{ $cart->id }}]" value="{{ $cart->no_of_copies }}" placeholder="No of Copies">
                </td>
                <td>{{ productSinglePriceForCusOrder($productId, $userId)->total }}</td>
                <td>{{ $cart->remark }}</td>
                <td>
                    @if(!empty($cart->file_name))
                        <a download href="{{ asset('public') }}/{{ $cart->file_path.'/'.$cart->file_name }}">Download</a>
                    @else
                        <a href="javascript:void(0)">No File</a>
                    @endif
                </td>
                <td>
                    <a onclick="updateCustomCart(this)" data-url="{{ route('adminDoUpdateCustomCart') }}" data-action="delete" data-user="{{ $userId }}" data-id="{{ $cart->id }}" href="javascript:void(0)"><i class="text-danger ki-outline ki-trash fs-2"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif