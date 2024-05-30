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

var mediaTable = $('#kt_file_manager_list').DataTable();

const searchMedia = (val) => {
    mediaTable.search(val).draw();
}

function initMediaDatatable() {
    mediaTable.ajax.reload();
}

Dropzone.autoDiscover = false;
$("#upload").dropzone({
    url: uploadUrl,
    method: 'POST',
    parallelUploads: 10,
    uploadMultiple: true,
    maxFilesize: 10, //MB
    maxFiles: 10, //Cannot upload more than 10 files
    acceptedFiles: ".jpg, .jpeg, .png, .mp4, .mp3, application/pdf",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: function() {
        this.on("error", function(file, res) {
            // Handle errors if the file size exceeds the limit or for other reasons
            if (res) {
                console.log(res);
            }
        }),
        this.on("success", function(file, res) {
            // Handle errors if the file size exceeds the limit or for other reasons
    
            res = JSON.parse(res);
            $this = this;

            if (res.error) {
                
                if (res.eType == 'final') {
                    toastr.error(res.msg);
                } else {
                    $.each(res.errors, function(index, val) {
                       toastr.error(val);  
                    });
                }

            } else {

                toastr.success(res.msg);
                
                initMediaDatatable();

                timeout = setTimeout(function() {
                    $this.removeAllFiles();
                    clearTimeout(timeout);
                }, 3000);

            }

        })
    },
});

const updateAlt = (el) => {
    
    let alt = $(el).val();
    let id = $(el).attr('data-id');
    let url = $(el).attr('data-url');

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            alt: alt,
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
            }
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })
    
}

const deleteMedia = (el) => {

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
                initMediaDatatable();
            }
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

}

const bulkSelectMedia = (el) => {

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

const checkCheckboxMedia = (el) => {

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

const startBulkDeleteMedia = (el) => {

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
                initMediaDatatable();
            }
        },
        error: function (xhr, status, error) {
            // Handle any errors that occur during the request.
            var errorMessage = xhr.responseJSON;
            toastr.error(errorMessage.message);
        }
    })

}

const loadMedia = (args) => {

    mediaTable.destroy();

    mediaTable = $('#kt_file_manager_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": dataUrl, // Replace with your server-side script URL
            "type": "GET",
            "data": function (d) {
                d.addMediaBtn = args.addMediaBtn;
                d.multiple = args.multiple;
                d.mediaType = args.mediaType;
                d.field = args.field;
            }
        },
        "pageLength": 10,
        "deferRender": true,
        // "stateSave": true,
        "columns": [
            { "orderable": false, "searchable": false, "data": "checkbox" },
            { "data": "original_name" },
            { "data": "alt" },
            { "data": "size" },
            { "orderable": false, "searchable": false, "data": "action" }
        ]
    });

}

const insertMedia = (el) => {
    inputName = $(el).attr('data-input-name');
    multiple = $(el).attr('data-multiple');
    id = $(el).attr('data-id');
    mediaPath = $(el).attr('data-path');
    fileName = $(el).attr('data-filename');
    mediaType = $(el).attr('data-type');

    //console.log(inputName, multiple, id, mediaPath, fileName, mediaType);

    if (multiple == 'false') {
            
        $(inputName+"Display").css('background-image', 'url(' + mediaPath + ')');
        $(inputName+'RemoveBtn').css('display', 'inline-flex');
        $(inputName).val(id);
        toastr.success('The image has been added successfully.');
        $("#kt_app_engage_prebuilts_modal").modal('hide');

    } else {

        autoId = Math.floor(Math.random() * 26) + Date.now();
        $("#sortable").append(`
            <div id="box-${autoId}" class="draggable" draggable="true">
                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../public/backend/media/svg/avatars/blank.svg')">

                    <div id="galleryImages${autoId}" class="image-input-wrapper w-125px h-125px" style="background-image: url('${mediaPath}')"></div>
                    
                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" title="Remove" onclick="removeMedia({field: '#box-${autoId}', multiple: true})">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </label>

                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Remove">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </span>

                    <input type="hidden" name="galleryImages[]" id="galleryImages${autoId}" value="${id}">
                </div>
            </div>
        `);

        toastr.success('The image has been added.');

    }

}

const removeMedia = (el) => {
    inputName = el.field;
    multiple = el.multiple;
    defMedia = "https://preview.keenthemes.com/metronic8/demo27/assets/media/avatars/300-1.jpg";    

    if (multiple == false) {
        $(inputName).val('');
        $(inputName+'RemoveBtn').css('display', 'none');
        $(inputName+"Display").css('background-image', 'url(' + defMedia + ')');
    } else {
        
        $(inputName).remove();
        
    }

}