$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    const table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        dom: '<"toolbar">lfrtip',
        ajax: {
            url: APP_URL + '/dealer',
            type: 'GET',
            data: function (d) {
                d.search = $('input[name=search]').val()
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'mobile_no', name: 'mobile_no'},
            {data: 'business_name', name: 'business_name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
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

    $('#search-form').keyup(function () {
        table.draw();
    });
    $(document).on('click', '.delete-single', function () {
        const value_id = $(this).data('id')

        swal({
            title: sweetalert_title,
            text: sweetalert_text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonColor: "#067CBA",
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

    let $form = $('#addEditForm')
    $form.on('submit', function (e) {
        e.preventDefault()
        $form.parsley().validate();
        if ($form.parsley().isValid()) {
            loaderView();
            let formData = new FormData($('#addEditForm')[0])
            $.ajax({
                url: APP_URL + '/dealer',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    $form[0].reset()
                    $form.parsley().reset();
                    successToast(data.message, 'success')
                    setTimeout(function () {
                        window.location.href = APP_URL + '/dealer'
                    }, 1000);
                },
                error: function (data) {
                    loaderHide();
                    successToast(data.responseJSON.message, 'warning')
                    console.log('Error:', data)
                }
            })
        }
    })

    function deleteRecord(value_id) {
        $.ajax({
            type: 'DELETE',
            url: APP_URL + '/dealer' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $("#emergency_service_id").select2({
            'placeholder': 'Select Emergency Service'
        }
    )
    $("#quick_service_id").select2({
        'placeholder': 'Select Quick Service'
    })
    $('.dropify').dropify();
    $('#country_code').select2();

})

