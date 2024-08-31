<div class="shipping-tab-inner">
  <div class="shipping-heading">
    <h2>Shipping Address</h2>
    <a onclick="editAddress(this)" class="home_btn edit-address" style="margin:unset;">EDIT ADDRESS</a>
  </div>
  <div class="shipping-content">
    <p class="nobottommargin">
      @if(!empty($customerAddress))
      <span class="t600">Contact Person: {{ $customerAddress->shipping_name }}</span>
      <br>Company: {{ $customerAddress->shipping_company_name ?? '-' }}
      <br>Address: {{ $customerAddress->shipping_address }}
      <br>City: {{ $customerAddress->shipping_city }}
      <br>State: {{ $customerAddress->shipping_state }}
      <br>Pincode: {{ $customerAddress->shipping_pincode }}
      <br>Email: {{ $customerAddress->shipping_email }}
      <br>Mobile: {{ $customerAddress->shipping_phone }}
      <br>GST Number: {{ $customerAddress->gst_number ?? '-' }}
      @else
      <span class="t600">Shipping address is not yet saved.</span>
      @endif
    </p>
  </div>
  @if(!empty($customerAddress))
  <div class="order-confirmation">
    <h2>
      Order confirmation email will sent to : <a href="mailto:{{ $customerAddress->shipping_email }}">{{ $customerAddress->shipping_email }}</a>
    </h2>
    <p>Expected Delivery Timeline - Delhi NCR (4 to 7 working Days) and in other states (7 to 10 Working Days).</p>
  </div>
  @endif
</div>

<div class="shipping-tab-inner" style="margin-top:5%;">
  <div class="shipping-heading">
    <h2>Printing Order</h2>
    <a onclick="editOrder(this)" class="home_btn edit-order" style="margin:unset;">EDIT ORDER</a>
  </div>
  @if(!empty($cartData) && $cartData->count())
    @foreach($cartData as $cart)
                              
    @php
      $price = 0;
      $productId = $cart->product_id;
      $productSinglePrice = [];

      if(isset(productSinglePrice($productId)->price)) {
        $price = productSinglePrice($productId)->price;
        $productSinglePrice = productSinglePrice($productId);
      }

      $productSpecNew = productSpecNew($cart->id);

    @endphp

    <div class="edit-order-row">
      <p style="font-weight:700;">{{ $cart->file_name }}</p>
      <div class="edit-order-btns">
        <a href="#" class="active">{{ $cart->name }}</a>
        <a href="#">PRICE : <span>Rs {{ $price }}</span></a>
        <a href="#">PAGES : <span>{{ $cart->qty }}</span></a>
        <a href="#">COPIES : <span>{{ $cart->no_of_copies }}</span></a>
        <a href="#">PAPER TYPE : <span>{{ $productSpecNew->paperGsm }} GSM {{ $productSpecNew->paperType }}</span></a>
        <a href="#">PAPER SIZE : <span>{{ $productSpecNew->paperSize }}</span></a>
        <a href="#">PRINTED COLOUR : <span>{{ $productSpecNew->color }}</span></a>
        <a href="#">PRINTED SIDES : <span>{{ $productSpecNew->paperSide }}</span></a>
        <a href="#">BINDING OPTIONS : <span>{{ $productSpecNew->binding? $productSpecNew->binding:'NO BINDING' }}</span></a> 

        @if($productSpecNew->split)
        <a href="#">Split : <span>{{ $productSpecNew->split }}</span></a>   
        @endif

        <a> LAMINATION OPTIONS : {{ $productSpecNew->lamination? $productSpecNew->lamination:'NO LAMINATION' }} | </a>

        <a href="#">COVER OPTION : <span>NO COVER</span></a>

        @if(isset($productSinglePrice->binding) && $productSinglePrice->binding)
        <a href="#">Binding Price : <span>{{ $productSinglePrice->binding }}</span></a>
        @endif

        <!-- @if(isset($productSinglePrice->split) && $productSinglePrice->split)
        <a href="#">Split Price : <span>{{ $productSinglePrice->split }}</span></a>
        @endif -->

        @if(isset($productSinglePrice->lamination) && $productSinglePrice->lamination)
        <a href="#">Lamination Price : <span>{{ $productSinglePrice->lamination }}</span></a>
        @endif

        @if(isset($productSinglePrice->cover) && $productSinglePrice->cover)
        <a href="#">Cover Price : <span>{{ $productSinglePrice->cover }}</span></a>
        @endif

        @if(isset($productSinglePrice->total))
        <a href="#">Total : <span>{{ $productSinglePrice->total }}</span></a>
        @endif

      </div>
    </div>

    @endforeach

  @else
  <div class="edit-order-row-new">
    <p style="font-weight:700;">Your cart is empty.</p>
  </div>
  @endif
</div>