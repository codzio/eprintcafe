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
        { "data": "paper_size" },
        { "data": "measurement" },
        { "data": "gsm" },
        { "data": "weight" },
        { "data": "rate" },
        { "data": "paper_type" },
        { "data": "paper_type_price" },
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

$("#kt_form").submit(function(event) {
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
            $("#kt_form_submit .indicator-label").hide();
            $("#kt_form_submit .indicator-progress").show();
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
                toastr.success(res.msg);
                $("#kt_form")[0].reset();
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
                } else {
                    toastr.error(res.msg);
                }
            } else {
                toastr.success(res.msg);
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