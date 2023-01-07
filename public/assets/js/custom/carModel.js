$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    const table = $('#data-table').DataTable({
        processing: true,
        dom: '<"toolbar">lfrtip',
        serverSide: true,
        responsive: true,
        searching: false,
        ajax: {
            url: APP_URL + '/car-model',
            data: function (d) {
                d.search = $('input[name=search]').val();
                d.model = $('#model').val();
                d.brand_id = $('#brand').val();
            },
            type: 'GET',
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'vehicle_type', name: 'vehicle_type'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'brand_name', name: 'brand_name'},
            {data: 'model_name', name: 'model_name'},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "15%"},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>'
        },
        order: [[0, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })
    $("div.toolbar").html(
        '<div class="col-sm-12 col-md-6 float-right">' +
        '<div id="data-table_filter" class="dataTables_filter">' +
        '<label>Search:<input type="text" name="search" id="search-form" class="form-control form-control-sm" placeholder="" aria-controls="data-table"></label>' +
        '</div>' +
        '</div>');
    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id')

        swal({
            title: sweetalert_title,
            text: sweetalert_text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#067CBA",
            confirmButtonClass: "btn-danger",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                deleteRecord(value_id)
            }
        });
    })

    $('#search-form').keyup(function () {
        table.draw();
    });

    $("#filter").click(function () {
        table.draw()
    })
    let $form = $('#addEditForm')
    $form.on('submit', function (e) {
        e.preventDefault()
        $form.parsley().validate();
        if ($form.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#addEditForm')[0])
            $.ajax({
                url: APP_URL + '/car-model',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        $(".dropify-clear").trigger("click");
                        if ($("#edit_value").val() === '') {
                            $form.parsley().reset();
                            successToast(data.message, 'success')
                        } else {
                            successToast(data.message, 'success')
                            setTimeout(function () {
                                window.location.href = APP_URL + '/car-model'
                            }, 1000);
                        }

                    } else if (data.success === false) {
                        successToast(data.message, 'warning')
                    }
                },
                error: function (data) {
                    loaderHide();
                    console.log('Error:', data)
                }
            })
        }
    })

    function deleteRecord(value_id) {
        $.ajax({
            type: 'DELETE',
            url: APP_URL + '/car-model' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }


    $("#make,#engine_id,#body_id").select2();
    $('.dropify').dropify();
    floatOnly();
    integerOnly();


    $(document).on('click', '.ryde-details', function () {
        const value_id = $(this).data('id');

        loaderView();
        let effect = $(this).attr('data-effect');
        $('#globalModal').addClass(effect).modal('show');

        $.ajax({
            type: 'GET',
            url: APP_URL + '/carModelDetails' + '/' + value_id,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#globalModalTitle").html(data.data.globalModalTitle);
                $("#globalModalDetails").html(data.data.globalModalDetails);
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    })
})


