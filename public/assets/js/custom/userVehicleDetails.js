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
            url: APP_URL + '/userVehicle',
            type: 'GET',
            data: function (d) {
                d.user_id = $('#user_id').val()
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'vehicle_type', name: 'vehicle_type'},
            {data: 'brand', name: 'brand'},
            {data: 'model', name: 'model'},
            {data: 'body', name: 'body'},
            {data: 'year', name: 'year'},
            {data: 'engine', name: 'engine'},
            {data: 'fuel', name: 'fuel'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>',
        },
        order: [[0, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })

    const address_table = $('#address-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: APP_URL + '/userAddress',
            type: 'GET',
            data: function (d) {
                d.user_id = $('#user_id').val()
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'address', name: 'address'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function () {
            funTooltip()
        },
        language: {
            processing: '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>',
        },
        order: [[0, 'DESC']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    })
})
