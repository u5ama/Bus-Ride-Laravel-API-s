$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    const table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: APP_URL + '/on-boarding',
            type: 'GET',
        },
        columns: [
            {data: 'header_text', name: 'header_text'},
            {data: 'on_boarding_order_by', name: 'on_boarding_order_by'},
            {data: 'icon', name: 'icon'},
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
                url: APP_URL + '/on-boarding',
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loaderHide();
                    if (data.success === true) {
                        $form[0].reset()
                        successToast(data.message, 'success');
                        $(".dropify-clear").trigger("click");
                        setTimeout(function () {
                            window.location.href = APP_URL + '/on-boarding'
                        }, 1000);
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
            url: APP_URL + '/on-boarding' + '/' + value_id,
            success: function (data) {
                successToast(data.message, 'success');
                table.draw()
                loaderHide();
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    }

    $('.dropify').dropify();
    integerOnly()


    floatOnly()

    let $sortablerow = $("#sortable-row")
    if ($sortablerow.length > 0) {
        $sortablerow.sortable({
            placeholder: "ui-state-highlight"
        });
    }

    $(document).on('click', '#btnSave', function () {
        var selectedOrder = new Array();
        $('ul#sortable-row li').each(function () {
            selectedOrder.push($(this).attr("id"));
        });
        document.getElementById("row_order").value = selectedOrder;

        loaderView();

        $.ajax({
            type: 'POST',
            url: APP_URL + '/saveOrder',
            data: {
                selectedOrder: selectedOrder
            },
            success: function (data) {
                loaderHide();
                successToast(data.message, 'success')
            }, error: function (data) {
                console.log('Error:', data)
            }
        })
    })

})

