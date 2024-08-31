<span class="shipping-name" style="margin-bottom:4%; display:block;">Shipping Details</span>

@if(!empty($customerAddress))
<p class="shipping-datail-value-sec-inner" style="text-transform: uppercase; font-size: 11px;">
  <p>{{ $customerAddress->shipping_name }}</p>
  <p>Company: {{ $customerAddress->shipping_company_name ?? '-' }}</p>
  <p>Address: {{ $customerAddress->shipping_address }}</p>
  <p>City: {{ $customerAddress->shipping_city }}</p>
  <p>State: {{ $customerAddress->shipping_state }}</p>
  <p>Pincode: {{ $customerAddress->shipping_pincode }}</p>
  <p>Email: {{ $customerAddress->shipping_email }}</p>
  <p>Mobile: {{ $customerAddress->shipping_phone }}</p>
  <p>GST Number: {{ $customerAddress->gst_number ?? '-' }}</p>
</p>

<span class="shipping-name" style="margin-top:4%;margin-bottom:4%; display:block;">Billing Details</span>
<p class="shipping-datail-value-sec-inner" style="text-transform: uppercase; font-size: 11px;">

  @if(!$customerAddress->is_billing_same)
  <p>{{ $customerAddress->billing_name }}</p>
  <p>Company: {{ $customerAddress->billing_company_name ?? '-' }}</p>
  <p>Address: {{ $customerAddress->billing_address }}</p>
  <p>City: {{ $customerAddress->billing_city }}</p>
  <p>State: {{ $customerAddress->billing_state }}</p>
  <p>Pincode: {{ $customerAddress->billing_pincode }}</p>
  <p>Email: {{ $customerAddress->billing_email }}</p>
  <p>Mobile: {{ $customerAddress->billing_phone }}</p>
  @else
  <p>The billing address is same as shipping address.</p>
  @endif
</p>
@else
<p class="shipping-datail-value-sec-inner" style="text-transform: uppercase; font-size: 11px;">
  <p>Shipping address is not yet saved.</p>                              
</p>
@endif

<!-- <p class="delete">
  <b class="rvpric">
    <i class="icon-rupee"></i> <span class="delete-price">0.63</span> 
     <span class="text-danger small delete-icon" title="banner-1-newjpg-1723629445.jpg">
     <i class="icon-trash"></i>
      Delete File</span>
    </b>
</p> -->
