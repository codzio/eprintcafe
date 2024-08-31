toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": true,
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var table = $('#kt_file_manager_list').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": dataUrl,
    "pageLength": 10,
    "deferRender": true,
    // "stateSave": true,
    "columns": [
        { "orderable": false, "searchable": false, "data": "checkbox" },
        { "data": "name" },
        { "data": "email" },
        { "data": "phone" },
        { "data": "address" },
        { "data": "created_at" },
        { "orderable": false, "searchable": false, "data": "action" }
    ]
});

const search = (val) => {
    table.search(val).draw();
}

function initDatatable() {
    table.ajax.reload();
}


const deleteData = (el) => {

    let id = $(el).attr('data-id');
    let url = $(el).attr('data-url');

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,            
        },
        beforeSend: function() {
            
        },
        success: function (res) {
            console.log(res);
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        //$("#"+index+"Err").html(val);
                        toastr.error(val);
                    });
                } else {
                    toastr.error(res.msg);
                }
            } else {
                toastr.success(res.msg);
                initDatatable();
            }
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

}

const bulkSelectData = (el) => {

    isChecked = $(el).attr('data-kt-check');
    
    if (isChecked == 'false') {
        $(el).attr('data-kt-check', 'true');
        $('[data-kt-check-target="#media .single-check-input"]').prop('checked',true);
    } else {
        $(el).attr('data-kt-check', 'false');
        $("input:checkbox").prop('checked', false);
    }

    ids = [];

    $('[data-kt-check-target="#media .single-check-input"]').each(function(index, el) {    
        if ($(el).is(":checked")) {
            ids.push($(el).val())
        }
    });

    if (ids.length) {
        $('[data-kt-filemanager-table-select="selected_count"]').html(ids.length);
        $('[data-kt-filemanager-table-toolbar="selected"]').removeClass('d-none');

    } else {
        $('[data-kt-filemanager-table-toolbar="selected"]').addClass('d-none');
        $('[data-kt-filemanager-table-select="selected_count"]').html(0);
    }

}

const checkCheckbox = (el) => {

    ids = [];

    $('[data-kt-check-target="#media .single-check-input"]').each(function(index, el) {
        if ($(el).is(":checked")) {
            ids.push($(el).val())
        }
    });

    if (ids.length) {
        $('[data-kt-filemanager-table-select="selected_count"]').html(ids.length);
        $('[data-kt-filemanager-table-toolbar="selected"]').removeClass('d-none');
    } else {
        $('[data-kt-filemanager-table-toolbar="selected"]').addClass('d-none');
        $('[data-kt-filemanager-table-select="selected_count"]').html(0);
    }

}

const startBulkDelete = (el) => {

    url = $(el).attr('data-url');

    ids = [];

    $('[data-kt-check-target="#media .single-check-input"]').each(function(index, el) {
        if ($(el).is(":checked")) {
            ids.push($(el).val())
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            ids: ids,            
        },
        beforeSend: function() {
            
        },
        success: function (res) {
            console.log(res);
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        //$("#"+index+"Err").html(val);
                        toastr.error(val);
                    });
                } else {
                    toastr.error(res.msg);
                }
            } else {
                toastr.success(res.msg);
                initDatatable();
            }
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

}

$("#kt_form_submit_calculate").click(function (e) {
    e.preventDefault();

    url = $("#kt_form").attr('action');
    formData = new FormData($('#kt_form')[0]);
    formData.append('action', 'calculate');

    // var files = document.getElementById('uploadDocuments').files;
    // if (files.length === 0) {
    //     event.preventDefault(); // Prevent form submission
    //     $("#uploadDocumentsErr").html('Please select the file')
    //     return false;
    // }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_form_submit_calculate .indicator-label").hide();
            $("#kt_form_submit_calculate .indicator-progress").show();
            // $("#kt_form_submit").hide(); 
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });

                    toastr.error("Please fill all the mandatory fields.");

                } else {
                    toastr.error(res.msg);
                }
            } else {
                $("#cartWeight").html(res.totalWeight);
                $("#discountData").html(res.priceData.discount);
                $("#shippingData").html(res.priceData.shipping);
                $("#subTotalData").html(res.priceData.subTotal);
                
                if (res.additionalDiscount) {
                    $("#additionalDiscountData").html(res.additionalDiscount);
                    //$("#totalData").html(res.priceData.total-res.additionalDiscount);
                    //$("#totalData").html((res.priceData.total-res.additionalDiscount).toFixed(2));
                    $("#totalData").html((res.paidAmount-res.additionalDiscount).toFixed(2));
                } else {
                    //$("#totalData").html(res.priceData.total);
                    //$("#totalData").html((res.priceData.total).toFixed(2));
                    $("#totalData").html((res.paidAmount).toFixed(2));
                }

                $("#totalPackagingCharges").html(res.packagingCharges);
                $("#gstCharges").html(res.gstCharges);

                // $("#kt_form_submit_calculate").hide();
                // $("#kt_form_submit").show(); 

            }

            $("#kt_form_submit_calculate .indicator-label").show();
            $("#kt_form_submit_calculate .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })


});

$("#kt_form_submit_save").click(function (e) {
    e.preventDefault();

    url = $("#kt_form").attr('action');
    formData = new FormData($('#kt_form')[0]);
    formData.append('action', 'save');

    // var files = document.getElementById('uploadDocuments').files;
    // if (files.length === 0) {
    //     event.preventDefault(); // Prevent form submission
    //     $("#uploadDocumentsErr").html('Please select the file')
    //     return false;
    // }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_form_submit_save .indicator-label").hide();
            $("#kt_form_submit_save .indicator-progress").show();
            // $("#kt_form_submit").hide(); 
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });

                    toastr.error("Please fill all the mandatory fields.");

                } else {
                    toastr.error(res.msg);
                }
            } else {
                $("#cartWeight").html(res.totalWeight);
                $("#discountData").html(res.priceData.discount);
                $("#shippingData").html(res.priceData.shipping);
                $("#subTotalData").html(res.priceData.subTotal);
                
                if (res.additionalDiscount) {
                    $("#additionalDiscountData").html(res.additionalDiscount);
                    //$("#totalData").html(res.priceData.total-res.additionalDiscount);
                    //$("#totalData").html((res.priceData.total-res.additionalDiscount).toFixed(2));
                    $("#totalData").html((res.paidAmount-res.additionalDiscount).toFixed(2));
                } else {
                    //$("#totalData").html(res.priceData.total);
                    // $("#totalData").html((res.priceData.total).toFixed(2));
                    $("#totalData").html((res.paidAmount).toFixed(2));
                }

                $("#totalPackagingCharges").html(res.packagingCharges);
                $("#gstCharges").html(res.gstCharges);

                $("#saveProductListTemp").html(res.saveProductListTemp);

                // $("#kt_form_submit_save").hide();
                // $("#kt_form_submit").show(); 

            }

            $("#kt_form_submit_save .indicator-label").show();
            $("#kt_form_submit_save .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })


});

$("#kt_form_submit_save_update").click(function (e) {
    e.preventDefault();

    url = $("#kt_form_update").attr('action');
    formData = new FormData($('#kt_form_update')[0]);
    formData.append('action', 'save');

    // var files = document.getElementById('uploadDocuments').files;
    // if (files.length === 0) {
    //     event.preventDefault(); // Prevent form submission
    //     $("#uploadDocumentsErr").html('Please select the file')
    //     return false;
    // }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_form_submit_save_update .indicator-label").hide();
            $("#kt_form_submit_save_update .indicator-progress").show();
            // $("#kt_form_submit").hide(); 
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });

                    toastr.error("Please fill all the mandatory fields.");

                } else {
                    toastr.error(res.msg);
                }
            } else {
                
                toastr.success(res.msg);

                setTimeout(function() {
                    location.reload();
                }, 1500);

                // $("#kt_form_submit_save_update").hide();
                // $("#kt_form_submit").show(); 

            }

            $("#kt_form_submit_save_update .indicator-label").show();
            $("#kt_form_submit_save_update .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })


});

$("#kt_form_submit_update_calculate").click(function (e) {
    e.preventDefault();

    url = $("#kt_form_update").attr('action');
    formData = new FormData($('#kt_form_update')[0]);
    formData.append('action', 'calculate');

    // var files = document.getElementById('uploadDocuments').files;
    // if (files.length === 0) {
    //     event.preventDefault(); // Prevent form submission
    //     $("#uploadDocumentsErr").html('Please select the file')
    //     return false;
    // }


    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_form_submit_update_calculate .indicator-label").hide();
            $("#kt_form_submit_update_calculate .indicator-progress").show();
            // $("#kt_form_submit").hide(); 
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });

                    toastr.error("Please fill all the mandatory fields.");

                } else {
                    toastr.error(res.msg);
                }
            } else {
                $("#cartWeight").html(res.totalWeight);
                $("#discountData").html(res.priceData.discount);
                $("#shippingData").html(res.priceData.shipping);
                $("#subTotalData").html(res.priceData.subTotal);
                //$("#totalData").html(res.priceData.total);                

                if (res.additionalDiscount) {
                    $("#additionalDiscountData").html(res.additionalDiscount);
                    //$("#totalData").html((res.priceData.total-res.additionalDiscount).toFixed(2));
                    //$("#totalData").html((res.paidAmount-res.additionalDiscount).toFixed(2));
                    $("#totalData").html((res.paidAmount).toFixed(2))
                } else {
                    //$("#totalData").html(res.priceData.total);
                    //$("#totalData").html((res.priceData.total).toFixed(2));
                    $("#totalData").html((res.paidAmount).toFixed(2));
                }

                $("#totalPackagingCharges").html(res.packagingCharges);

                // $("#kt_form_submit_update_calculate").hide();
                // $("#kt_form_update_submit").show(); 

            }

            $("#kt_form_submit_update_calculate .indicator-label").show();
            $("#kt_form_submit_update_calculate .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })


});

$("#kt_form").submit(function(event) {
    event.preventDefault();

    url = $(this).attr('action');
    //formData = $(this).serialize();
    formData = new FormData(this);

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_form_submit .indicator-label").hide();
            $("#kt_form_submit .indicator-progress").show();
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });

                    toastr.error("Please fill all the mandatory fields.");

                } else {
                    toastr.error(res.msg);
                }
            } else {
                toastr.success(res.msg);
                $("#kt_form")[0].reset();
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }

            $("#kt_form_submit .indicator-label").show();
            $("#kt_form_submit .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

});

$("#kt_form_update").submit(function(event) {
    event.preventDefault();

    url = $(this).attr('action');
    formData = $(this).serialize();

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
            $(".text-danger").html('');
            $("#kt_form_update_submit .indicator-label").hide();
            $("#kt_form_update_submit .indicator-progress").show();
        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });

                    toastr.error("Please fill all the mandatory fields.");
                    
                } else {
                    toastr.error(res.msg);
                }
            } else {
                toastr.success(res.msg);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }

            $("#kt_form_update_submit .indicator-label").show();
            $("#kt_form_update_submit .indicator-progress").hide();
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

});

$("#customer").change(function(event) {
    customerId = $(this).find(':selected').val();
    url = $(this).attr('data-url');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: {customerId: customerId},
        beforeSend: function() {
            $("#shippingName").val('');
            $("#companyName").val('');
            $("#gstNumber").val('');
            $("#shippingAddress").val('');
            $("#shippingCity").val('');
            $("#shippingState").val('');
            $("#shippingPincode").val('');
            $("#shippingEmail").val('');
            $("#shippingPhone").val('');

            $("#billingName").val('');
            $("#billingCompanyName").val('');
            $("#billingAddress").val('');
            $("#billingCity").val('');
            $("#billingState").val('');
            $("#billingPincode").val('');
            $("#billingEmail").val('');
            $("#billingPhone").val('');
            $('#billingState').val('');

            $('.billing-details').hide();
            $("#isBillingAddressSame").prop('checked', true);

        },
        success: function (res) {
            if (res.error == true) {
                if (res.eType == 'field') {
                    $.each(res.errors, function(index, val) {
                        $("#"+index+"Err").html(val);
                    });
                } else {
                    toastr.error(res.msg);
                }
            } else {

                $("#saveProductListTemp").html(res.saveProductListTemp);

                $("#shippingName").val(res.address.shipping_name);
                $("#companyName").val(res.address.shipping_company_name);
                $("#gstNumber").val(res.address.gst_number);
                $("#shippingAddress").val(res.address.shipping_address);
                $("#shippingCity").val(res.address.shipping_city);
                $("#shippingState").val(res.address.shipping_state);
                $("#shippingPincode").val(res.address.shipping_pincode);
                $("#shippingEmail").val(res.address.shipping_email);
                $("#shippingPhone").val(res.address.shipping_phone);
                $('#shippingState').val(res.address.shipping_state).trigger('change');

                if (res.address.is_billing_same == 1) {
                    $('.billing-details').hide();
                    $("#isBillingAddressSame").prop('checked', true);
                } else {
                    $('.billing-details').show();
                    $("#isBillingAddressSame").prop('checked', false);


                    $("#billingName").val(res.address.billing_name);
                    $("#billingCompanyName").val(res.address.billing_company_name);
                    $("#billingAddress").val(res.address.billing_address);
                    $("#billingCity").val(res.address.billing_city);
                    $("#billingState").val(res.address.billing_state);
                    $("#billingPincode").val(res.address.billing_pincode);
                    $("#billingEmail").val(res.address.billing_email);
                    $("#billingPhone").val(res.address.billing_phone);
                    $('#billingState').val(res.address.billing_state).trigger('change');

                }

            }
        },
    })
});

// Checkbox

$('.allow').click(function(event) {
    $('.checkbox').each(function(index, el) {
        if (!$(el).is(':disabled')) {
           $(el).prop('checked', true);
        }        
    }); 
});

$('.disallow').click(function(event) {
    $('.checkbox').each(function(index, el) {
        $(el).prop('checked', false);     
    }); 
});

function generateSlug(el, id) {
    var inputValue = $(el).val()
    var slug = inputValue
        .toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')   // Remove invalid characters
        .replace(/\s+/g, '-')          // Replace spaces with hyphens
        .replace(/-+/g, '-');          // Replace multiple hyphens with a single hyphen

    $(id).attr('value', slug);
}