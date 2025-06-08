$(function () {
    const table = '#datatable';

    const dataTable = $(table).DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        order: [[1, 'asc']],
        ajax: {
            url: "/admin/datatable",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function (d) {
                d.search = $(table + '_filter ' + 'input[type="search"]').val();
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
            {data: 'whatsappNumber', name: 'whatsappNumber'},
            {data: 'markAsContact', name: 'markAsContact'},
            {data: 'is_active', name: 'is_active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        dom:
            '<"row mx-1"' +
            '<"col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start gap-3"l<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start mt-md-0 mt-3"B>>' +
            '<"col-12 col-md-6 d-flex align-items-center justify-content-end flex-column flex-md-row pe-3 gap-md-3"' +
            'f' + // Input pencarian DataTable
            '<"filters d-flex gap-2">' + // Container untuk filter tambahan
            '>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
        language: {
            sLengthMenu: '_MENU_',
            search: '',
            searchPlaceholder: 'Cari data'
        },
        buttons: [
            {
                text: '<i class="mdi mdi-plus me-1"></i>Tambah Baru',
                className: 'btn btn-primary waves-effect waves-light',
                attr: {
                    'data-bs-target': '#modalCreate',
                    'data-bs-toggle': 'modal',
                }
            }
        ],
        initComplete: function () {
            const select = $(
                '<select id="status" class="form-select">' +
                '<option value="">Pilih Status</option>' +
                '<option value="active">Aktif</option>' +
                '<option value="inactive">Tidak Aktif</option>' +
                '<option value="deleted">Terhapus</option>' +
                '</select>'
            )
                .appendTo('.admin_status')
                .on('change', function () {
                    dataTable.ajax.reload(); // Reload DataTables saat filter berubah
                });
        }
    });

    function handleAction(element, action, message, buttonText, buttonClass, method = 'delete') {
        let username = $(element).data('username');
        let url = `/admin/${username}/${action}`;
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Peringatan!',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: `YA, ${buttonText}`,
            customClass: {
                confirmButton: `btn btn-${buttonClass} me-3 waves-effect waves-light`,
                cancelButton: 'btn btn-label-secondary waves-effect'
            },
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                blockUi();

                axios[method](url, {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function (response) {
                    toastr.success(response.data.message);
                    dataTable.ajax.reload(null, false); // Reload tabel tanpa mengubah halaman
                    unBlockUi();
                }).catch(function (error) {
                    unBlockUi();
                    toastr.error(error.response.data.message);
                });
            }
        });
    }

    $(table).on('click', '.delete', function () {
        handleAction(this, 'delete', 'Apakah Anda ingin menghapus data ini?', 'HAPUS!', 'danger');
    });

    $(table).on('click', '.restore', function () {
        handleAction(this, 'restore', 'Apakah Anda ingin mengembalikan data ini?', 'KEMBALIKAN!', 'warning', 'post');
    });

    $(table).on('click', '.force-delete', function () {
        handleAction(this, 'force-delete', 'Apakah Anda ingin menghapus permanen data ini?', 'HAPUS PERMANEN!', 'danger');
    });
});
