@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Order</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminOrders') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form_update" class="form" action="{{ route('adminDoUpdateOrders') }}" enctype="multipart/form-data">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Customers</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select data-url="{{ route('adminGetCustomerAddress') }}" id="customer" class="form-select mb-2" name="customer" data-control="select2" data-hide-search="false" data-placeholder="Select Customer">
                                            <option value="">Select Customer</option>
                                            @if(!empty($customerList))
                                            @foreach($customerList as $customer)
                                            <option {{ ($order->user_id == $customer->id)? 'selected':'' }} value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="customerErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <h2>Shipping Details</h2>

                        <div class="row mb-6">                    
                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Shipping Name</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="shippingName" type="text" name="shippingName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Shipping Name" value="{{ $customerAddress->shipping_name }}">
                                        <span id="shippingNameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div> 
                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6">Company Name</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="companyName" type="text" name="companyName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Company Name" value="{{ $customerAddress->shipping_company_name }}">
                                        <span id="companyNameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>    

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6">GST Number</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="gstNumber" type="text" name="gstNumber" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="GST Number" value="{{ $customerAddress->gst_number }}">
                                        <span id="gstNumberErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>   

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Shipping Address</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="shippingAddress" type="text" name="shippingAddress" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Shipping Address" value="{{ $customerAddress->shipping_address }}">
                                        <span id="shippingAddressErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>    

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Shipping City</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="shippingCity" type="text" name="shippingCity" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Shipping City" value="{{ $customerAddress->shipping_city }}">
                                        <span id="shippingCityErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Shipping State</label>
                                    <div class="col-lg-12 fv-row">
                                        <select id="shippingState" class="form-select mb-2" name="shippingState" data-control="select2" data-hide-search="false" data-placeholder="Select State">
                                            <option value="">Select State</option>
                                            @if(!empty($stateList))
                                            @foreach($stateList as $state)
                                            <option {{ $customerAddress->shipping_state == $state->state? 'selected':'' }} value="{{ $state->state }}">{{ $state->state }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="shippingStateErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Shipping Pincode</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="shippingPincode" type="text" name="shippingPincode" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Shipping Pincode" value="{{ $customerAddress->shipping_pincode }}">
                                        <span id="shippingPincodeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Shipping Email</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="shippingEmail" type="text" name="shippingEmail" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Shipping Email" value="{{ $customerAddress->shipping_email }}">
                                        <span id="shippingEmailErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Shipping Phone</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="shippingPhone" type="text" name="shippingPhone" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Shipping Phone" value="{{ $customerAddress->shipping_phone }}">
                                        <span id="shippingPhoneErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6">&nbsp;</label>
                                    <div class="col-lg-12 fv-row">
                                        <input {{ $customerAddress->is_billing_same? 'checked':'' }} id="isBillingAddressSame" type="checkbox" name="isBillingAddressSame" class=" mb-3 mb-lg-0" placeholder="Shipping Phone" value="true"> Is Billing Address Same as Shipping Address
                                        <span id="isBillingAddressSameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                        </div>

                        <h2 class="billing-details" style="{{ $customerAddress->is_billing_same? 'display:none':'' }}">Billing Details</h2>

                        <div class="row mb-6 billing-details" style="{{ $customerAddress->is_billing_same? 'display:none':'' }}">                    
                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Billing Name</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="billingName" type="text" name="billingName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Billing Name" value="{{ $customerAddress->billing_name }}">
                                        <span id="billingNameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div> 
                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6">Company Name</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="billingCompanyName" type="text" name="billingCompanyName" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Company Name" value="{{ $customerAddress->billing_company_name }}">
                                        <span id="billingCompanyNameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Billing Address</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="billingAddress" type="text" name="billingAddress" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Billing Address" value="{{ $customerAddress->billing_address }}">
                                        <span id="billingAddressErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>    

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Billing City</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="billingCity" type="text" name="billingCity" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Billing City" value="{{ $customerAddress->billing_city }}">
                                        <span id="billingCityErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Billing State</label>
                                    <div class="col-lg-12 fv-row">
                                        <select id="billingState" class="form-select mb-2" name="billingState" data-control="select2" data-hide-search="false" data-placeholder="Select State">
                                            <option value="">Select State</option>
                                            @if(!empty($stateList))
                                            @foreach($stateList as $state)
                                            <option {{ $customerAddress->billing_state == $state->state? 'selected':'' }} value="{{ $state->state }}">{{ $state->state }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="billingStateErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Billing Pincode</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="billingPincode" type="text" name="billingPincode" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Billing Pincode" value="{{ $customerAddress->billing_pincode }}">
                                        <span id="billingPincodeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Billing Email</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="billingEmail" type="text" name="billingEmail" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Billing Email" value="{{ $customerAddress->billing_email }}">
                                        <span id="billingEmailErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>

                            <div class="col-lg-3">                        
                                <div class="row">
                                    <label class="col-lg-12 col-form-label fw-semibold fs-6 required">Billing Phone</label>
                                    <div class="col-lg-12 fv-row">
                                        <input id="billingPhone" type="text" name="billingPhone" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Billing Phone" value="{{ $customerAddress->billing_phone }}">
                                        <span id="billingPhoneErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Product</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="product" class="form-select mb-2" name="product" data-control="select2" data-hide-search="false" data-placeholder="Select Product">
                                            <option value="">Select Product</option>
                                            @if(!empty($productList))
                                            @foreach($productList as $product)
                                            <option {{ $orderItem->product_id == $product->id? 'selected':'' }} value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span id="productErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Size</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="paperSize" class="form-select mb-2" name="paperSize" data-control="select2" data-hide-search="false" data-placeholder="Select Paper Size">
                                            <option value="">Select Paper Size</option>
                                            @if(!empty($paperSizeList))
                                                @foreach($paperSizeList as $paperSize)
                                                    <option {{ $paperSize->id == $productDetailIds->paperSizeId? 'selected':'' }} value="{{ $paperSize->id }}">{{ $paperSize->size }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="paperSizeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper GSM</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="paperGsm" class="form-select mb-2" name="paperGsm" data-control="select2" data-hide-search="false" data-placeholder="Select Paper GSM">
                                            <option value="">Select Paper GSM</option>
                                            @if(!empty($gsmList) && $gsmList->count())
                                                @foreach($gsmList as $gsm)
                                                    <option {{ $gsm->id == $productDetailIds->paperGsmId? 'selected':'' }} data-weight="{{ $gsm->per_sheet_weight }}" value="{{ $gsm->id }}">{{ $gsm->gsm }} GSM - {{ $gsm->paper_type_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="paperGsmErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Paper Type</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="paperType" class="form-select mb-2" name="paperType" data-control="select2" data-hide-search="false" data-placeholder="Select Paper Type">
                                            <option value="">Select Paper Type</option>
                                            @if(!empty($paperTypeList) && $paperTypeList->count())
                                                @foreach ($paperTypeList as $paperType)
                                                    <option {{ $paperType->paper_type_id == $productDetailIds->paperTypeId? 'selected':'' }} data-price="{{ $paperType->paper_type_price }}" value="{{ $paperType->paper_type_id }}">{{ $paperType->paper_type }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="paperTypeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Print Sides</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="sides" class="form-select mb-2" name="paperSides" data-control="select2" data-hide-search="false" data-placeholder="Select Print Sides">
                                            <option value="">Select Print Sides</option>
                                            @if(!empty($paperSideList) && $paperSideList->count())
                                                @foreach($paperSideList as $paperSide)
                                                    <option {{ $paperSide->side == $productDetailIds->paperSides? 'selected':'' }} value="{{ $paperSide->side }}">{{ $paperSide->side }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="paperSidesErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Color</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="color" class="form-select mb-2" name="color" data-control="select2" data-hide-search="false" data-placeholder="Select Color">
                                            <option value="">Select Color</option>
                                            @if(!empty($paperColorList) && $paperColorList->count())
                                                @foreach($paperColorList as $color)
                                                    <option {{ $color->color == $productDetailIds->color? 'selected':'' }} data-price="{{ $color->other_price }}" value="{{ $color->color }}">{{ $color->color }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="colorErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Binding</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="binding" class="form-select mb-2" name="binding" data-control="select2" data-hide-search="false" data-placeholder="Select Binding">
                                            <option value="">Select Binding</option>
                                            @if(!empty($bindingList) && $bindingList->count())
                                                @foreach ($bindingList as $binding)
                                                    <option {{ $binding->id == $productDetailIds->binding? 'selected':'' }} data-price="{{ $binding->price }}" data-split="{{ $binding->split }}" value="{{ $binding->id }}">{{ $binding->binding_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="bindingErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Lamination</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="lamination" class="form-select mb-2" name="lamination" data-control="select2" data-hide-search="false" data-placeholder="Select Lamination">
                                            <option value="">Select Lamination</option>
                                            @if(!empty($laminationList) && $laminationList->count())
                                                @foreach ($laminationList as $lamination) {
                                                    <option {{ $lamination->id == $productDetailIds->lamination? 'selected':'' }} data-price="{{ $lamination->price }}" value="{{ $lamination->id }}">{{ $lamination->lamination }} - {{ $lamination->lamination_type }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="laminationErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Cover</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="cover" class="form-select mb-2" name="cover" data-control="select2" data-hide-search="false" data-placeholder="Select Cover">
                                            <option value="">Select Cover</option>
                                        </select>
                                        <span id="coverErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">No of Pages</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="noOfPages" type="number" name="noOfPages" class="form-control" value="{{ $orderItem->qty }}">
                                        <span id="noOfPagesErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">No of Copies</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input id="noOfCopies" type="number" name="noOfCopies" class="form-control" value="{{ $orderItem->no_of_copies }}">
                                        <span id="noOfCopiesErr" class="text-danger"></span>
                                        <button style="display:none;" id="removeDocBtn" onclick="removeDoc()" type="button" class="btn btn-danger btn-sm mt-2">Remove Documents</button>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Upload Documents</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input multiple id="uploadDocuments" type="file" name="uploadDocuments[]" class="form-control">
                                        <span id="uploadDocumentsErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Status</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="status" class="form-select mb-2" name="status" data-control="select2" data-hide-search="false" data-placeholder="Select Status">
                                            <option {{ $order->status == 'paid'? 'selected':'' }} value="paid">PAID</option>
                                            <option {{ $order->status == 'unpaid'? 'selected':'' }} value="unpaid">UNPAID</option>
                                        </select>
                                        <span id="statusErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Shipping Free</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select id="shippingFree" class="form-select mb-2" name="shippingFree" data-control="select2" data-hide-search="false" data-placeholder="Select Shipping">
                                            <option {{ !$order->is_shipping_free? 'selected':'' }} value="0">No</option>
                                            <option {{ $order->is_shipping_free? 'selected':'' }} value="1">Yes</option>
                                        </select>
                                        <span id="shippingFreeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Additional Discount</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input step="any" type="number" name="additionalDiscount" class="form-control" value="{{ ($order->additional_discount)? $order->additional_discount:0 }}">
                                        <span id="additionalDiscountErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Remark</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <textarea  name="remark" class="form-control">{{ $order->remark }}</textarea>
                                        <span id="remarkErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Courier Partner</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="courier" data-control="select2" data-hide-search="false" data-placeholder="Select Courier Partner">
                                            <option {{ $order->courier == 'DTDC'? 'selected':'' }} value="DTDC">DTDC</option>
                                            <option {{ $order->courier == 'India Post'? 'selected':'' }} value="India Post">India Post</option>
                                        </select>
                                        <span id="courierErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Payment Method</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="paymentMethod" data-control="select2" data-hide-search="false" data-placeholder="Select Payment Method">
                                            <option {{ $order->payment_method == 'Payu'? 'selected':''; }} value="Payu">Payu</option>
                                            <option {{ $order->payment_method == 'Phonepe'? 'selected':''; }} value="Phonepe">Phonepe</option>
                                            <option {{ $order->payment_method == 'QR'? 'selected':''; }} value="QR">QR</option>
                                            <option {{ $order->payment_method == 'NEFT/IMPS'? 'selected':''; }} value="NEFT/IMPS">NEFT/IMPS</option>
                                        </select>
                                        <span id="paymentMethodErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="red_text">
                            <div class="my-1">
                                <span class="price-widget-sezzle" style="font-weight: 800; font-size: 22px;">₹{{ $priceDetails->subTotal }}</span>
                                <span style="color:#000; font-size: 16px;">&nbsp;inclusive of all taxes</span>
                            </div>
                           <div>
                                <span style="color:#000; font-size: 16px;">for</span>
                                <span style="color: rgba(0, 0, 0, 0.87); font-size: 16px;">&nbsp;1</span>
                                <span style="color: #000; font-size: 16px;">&nbsp;Qty (</span>
                                <span id="perSheetPrice" style="color: rgba(0, 0, 0, 0.87); font-size: 16px;">₹{{ $priceDetails->price }}</span>
                                <span style="color:#000;font-size: 16px;">&nbsp;/ piece)</span>
                                <p style="margin-top:10px" id="splitDetails">
                                    @if(!empty($priceDetails->split))
                                        <strong>Split:</strong> {{ $priceDetails->split }}
                                    @endif
                                </p>
                                
                                <p style="margin-top:10px"><strong>Weight:</strong>
                                    <span id="cartWeight">
                                        @if(!empty($priceDetails->split))
                                            {{ (($priceDetails->per_sheet_weight*$orderItem->qty)*$orderItem->no_of_copies)*$priceDetails->split }}
                                        @else
                                            {{ ($priceDetails->per_sheet_weight*$orderItem->qty)*$orderItem->no_of_copies }}
                                        @endif
                                    </span>
                                </p>

                                <p><strong>Discount:</strong><span id="discountData">{{ $order->discount }}</span></p>
                                <p><strong>Additional Discount:</strong><span id="additionalDiscountData">{{ ($order->additional_discount)? $order->additional_discount:0 }}</span></p>
                                <p><strong>Shipping Charge:</strong><span id="shippingData">{{ $order->shipping }}</span></p>
                                <p><strong>Sub Total:</strong><span id="subTotalData">{{ $priceDetails->subTotal }}</span></p>                                  
                                <p class="all-total"><strong>Total:</strong><span id="totalData">{{ $priceDetails->total-$order->additional_discount }}</span></p>
                            </div>
                        </div>                        

                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">

                        <input type="hidden" name="orderId" value="{{ $order->id }}">

                        @if($order->status == 'unpaid')

                        <button type="button" class="btn btn-primary" id="kt_form_submit_update_calculate">
                            <span class="indicator-label">Calculate</span>
                            <span class="indicator-progress">
                                Please wait...    
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                        <button style="margin-left: 20px;" name="action" value="submit" type="submit" class="btn btn-primary" id="kt_form_update_submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">
                                Please wait...    
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                        @endif
                        
                    </div>            
                </form>
            </div>
        </div>

    </div>
</div>

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">

        $("#isBillingAddressSame").change(function(event) {
            if($(this).is(":checked")) {
                $('.billing-details').hide();
            } else {
                $('.billing-details').show();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#product").change(function (e) {
            e.preventDefault();

            productId = $(this).find(':selected').val();

            $.ajax({
                url: '{{ route("adminGetPricing") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                  productId: productId,
                  action: 'size'
                },
                beforeSend: function() {

                }, success: function(res) {
                        
                    $("#paperSize").html(res.paperSizeOptions);

                    // calculatePrice();

                    // $("#paperGsm").html(res.gsmOptions);
                    // $("#binding").html(res.bindingOptions);
                    // $("#lamination").html(res.laminationOptions);

                }
            })

        });
        
        $("#paperSize").change(function(event) {
          
          paperSize = $(this).find(':selected').val();
          productId = $("#product").find(':selected').val();

          $.ajax({
            url: '{{ route("adminGetPricing") }}',
            type: 'POST',
            dataType: 'json',
            data: {
              productId: productId,
              paperSize: paperSize,
              action: 'gsm'
            },
            beforeSend: function() {

            }, success: function(res) {
                
                calculatePrice();

                $("#paperGsm").html(res.gsmOptions);
                $("#binding").html(res.bindingOptions);
                $("#lamination").html(res.laminationOptions);

            }
          })

        });

        $("#paperGsm").change(function (e) {
          
          productId = $("#product").find(':selected').val();
          paperSize = $("#paperSize").find(':selected').val();
          paperGsm = $(this).find(':selected').val();

          $.ajax({
            url: '{{ route("adminGetPricing") }}',
            type: 'POST',
            dataType: 'json',
            data: {
              productId: productId,
              paperSize: paperSize,
              paperGsm: paperGsm,
              action: 'paper_type'
            },
            beforeSend: function() {

            }, success: function(res) {
                
                calculatePrice();
                $("#paperType").html(res.paperOptions);

            }
          })

        });

        $("#paperType").change(function (e) {
          
          productId = $("#product").find(':selected').val();
          paperSize = $("#paperSize").find(':selected').val();
          paperGsm = $("#paperGsm").find(':selected').val();
          paperType = $(this).find(':selected').val();

          $.ajax({
            url: '{{ route("adminGetPricing") }}',
            type: 'POST',
            dataType: 'json',
            data: {
              productId: productId,
              paperSize: paperSize,
              paperGsm: paperGsm,
              paperType: paperType,
              action: 'paper_sides'
            },
            beforeSend: function() {

            }, success: function(res) {
                
                calculatePrice();
                $("#sides").html(res.paperSides);

            }
          })

        });

        $("#sides").change(function (e) {
          
          productId = $("#product").find(':selected').val();
          paperSize = $("#paperSize").find(':selected').val();
          paperGsm = $("#paperGsm").find(':selected').val();
          paperType = $("#paperType").find(':selected').val();
          paperSides = $(this).find(':selected').val();

          $.ajax({
            url: '{{ route("adminGetPricing") }}',
            type: 'POST',
            dataType: 'json',
            data: {
              productId: productId,
              paperSize: paperSize,
              paperGsm: paperGsm,
              paperType: paperType,
              paperSides: paperSides,
              action: 'paper_color'
            },
            beforeSend: function() {

            }, success: function(res) {
                
                calculatePrice();
                $("#color").html(res.paperColor);

            }
          })

        });

        $("#color").change(function (e) {
          calculatePrice();
        });

        $("#binding").change(function (e) {
          calculatePrice();
        });

        $("#lamination").change(function (e) {
          calculatePrice();
        });

        $("#noOfCopies").change(function (e) {
          calculatePrice();
        });

        $("#noOfPages").change(function (e) {
          calculatePrice();
        });

        function calculateBindingPrice() {

            bindingPrice = $("#binding").find(':selected').attr('data-price');
            bindingSplit = $("#binding").find(':selected').data('split');
            getPaperSide = $("#sides").find(':selected').val();
            qty = ($("#noOfPages").val() == '')? 0:$("#noOfPages").val();

            totalSplit = 1;

            if (bindingPrice) {
              
              if(bindingSplit > 0) {
            
                //proceed to calculate
                multiple = (getPaperSide == 'Double Side')? 2:1;
                bindingSplit = bindingSplit*multiple;

                if (qty > bindingSplit) {
                  totalSplit = Math.ceil(qty/bindingSplit); 
                }
              }

            } else {
              bindingPrice = 0;
            }

            if (totalSplit > 1) {
              newSplitPrice = totalSplit;
              bindingPrice = parseFloat(bindingPrice)*newSplitPrice;
            } else {
              newSplitPrice = totalSplit;
              bindingPrice = parseFloat(bindingPrice);
            }

            return {
              split: newSplitPrice,
              bindingPrice: bindingPrice,
            }

        }

        function calculatePrice() {

          paperGsmPrice = 0;
          paperTypePrice = 0;
          paperSidesPrice = 0;
          paperColorPrice = 0;
          bindingPrice = 0;
          laminationPrice = 0;

          qty = ($("#noOfPages").val() == '')? 0:$("#noOfPages").val();
          noOfCopies = ($("#noOfCopies").val() == '')? 1:$("#noOfCopies").val();

          // if ($("#paperGsm").find(':selected').val() != '') {
          //   paperGsmPrice = $("#paperGsm").find(':selected').attr('data-weight');
          // }

          if ($("#paperType").find(':selected').val() != '') {
            paperTypePrice = $("#paperType").find(':selected').attr('data-price');
          }

          // if ($("#sides").find(':selected').val() != '') {
          //   paperSidesPrice = $("#sides").find(':selected').attr('data-price');
          // }

          if ($("#color").find(':selected').val() != '') {
            paperColorPrice = $("#color").find(':selected').attr('data-price');
          }

          if ($("#binding").find(':selected').val() != '') {
            bindingPrice = $("#binding").find(':selected').attr('data-price');
          }

          if ($("#lamination").find(':selected').val() != '') {
            laminationPrice = $("#lamination").find(':selected').attr('data-price');
          }

          // totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice)+parseFloat(bindingPrice)+parseFloat(laminationPrice);

          totalPrice = parseFloat(paperGsmPrice)+parseFloat(paperTypePrice)+parseFloat(paperSidesPrice)+parseFloat(paperColorPrice);

            split = calculateBindingPrice().split;
            bindingPrice = calculateBindingPrice().bindingPrice;

            if (bindingPrice) {
                $("#splitDetails").html(`<strong>Split:</strong> ${split}`);
            } else {
                $("#splitDetails").html('');
            }

          // if(paperSidesPrice != 0 && paperColorPrice != 0) {
          //   totalPrice = parseFloat(totalPrice) - parseFloat(paperSidesPrice);
          // }

          finalPrice = (parseFloat(totalPrice)*parseInt(qty))+parseFloat(bindingPrice)+parseFloat(laminationPrice);;
          
          if(qty != 0) {
              
              // if(noOfCopies != 0) {

              //   noOfCopies = parseInt(noOfCopies)+1;

              //   $('.price-widget-sezzle').html(`₹`+(finalPrice*noOfCopies));
              // } else {
              //   $('.price-widget-sezzle').html(`₹`+finalPrice);
              // }

            $('.price-widget-sezzle').html(`₹`+(finalPrice*noOfCopies));

          }

          $("#perSheetPrice").html(`₹`+totalPrice)

          console.log(totalPrice, parseInt(qty));

        }

        function removeDoc() {
            $("#uploadDocuments").val(null);
            $("#removeDocBtn").hide();
        }

        $("#uploadDocuments").change(function(event) {
            $("#removeDocBtn").show();
        });

        dataUrl = '{{ route("getAdminCustomers") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/customers.js?v=2') }}"></script>

@endsection