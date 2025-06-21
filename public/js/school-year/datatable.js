$(function () {
    const table = '#datatable';

    const dataTable = $(table).DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        order: [[1, 'asc']],
        ajax: {
            url: "/school-year/datatable",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function (d) {
                d.search = $(table + '_filter ' + 'input[type="search"]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'year', name: 'year'},
            {data: 'is_active', name: 'is_active', orderable: false, searchable: false},
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
        ]
    });

    // Menyimpan halaman saat ini sebelum reload
    function reloadTable() {
        const currentPage = dataTable.page();
        dataTable.ajax.reload();
        dataTable.page(currentPage).draw('page');
    }

    $('#modalEdit').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const slug = button.data('slug');
        const firstYear = button.data('first-year');
        const lastYear = button.data('last-year');
        const active = button.data('active');

        // Isi nilai modal
        $('#editFirstYear').val(firstYear);
        $('#editLastYear').val(lastYear);

        if (active === 1) {
            $('#editYes').prop('checked', true);
        } else {
            $('#editNo').prop('checked', true);
        }

        // Atur event handler untuk tombol "Simpan"
        $('#btn-edit').off('click').on('click', function() {
            blockUi();
            toastrOption();

            // Clear previous errors
            const form = document.getElementById('formEdit');
            form.querySelectorAll('.is-invalid').forEach(element => {
                element.classList.remove('is-invalid');
            });
            form.querySelectorAll('.invalid-feedback').forEach(element => {
                element.remove();
            });
            form.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const radioGroup = form.querySelectorAll(`[name="${this.name}"]`);
                    radioGroup.forEach(r => r.classList.remove('is-invalid'));

                    const errorMessage = document.getElementById(`${this.name}-error`);
                    if (errorMessage) {
                        errorMessage.remove();
                    }
                });
            });

            // menggunakan axios
            axios.put(`/school-year/${slug}/update`, $('#formEdit').serialize())
                .then(response => {
                    unBlockUi();
                    $('#modalEdit').modal('hide');
                    toastr.success(response.data.message);
                    reloadTable();
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        const errors = error.response.data.errors;
                        for (const key in errors) {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                if (input.type === 'radio') {
                                    // Temukan semua radio button dengan name yang sama
                                    const radioGroup = form.querySelectorAll(`[name="${key}"]`);
                                    if (radioGroup.length) {
                                        // Tambahkan kelas is-invalid pada setiap radio button
                                        radioGroup.forEach(radio => radio.classList.add('is-invalid'));

                                        // Cek apakah sudah ada pesan error sebelumnya
                                        let errorMessage = form.querySelector(`#${key}-error`);
                                        if (!errorMessage) {
                                            // Buat elemen error baru
                                            errorMessage = document.createElement('div');
                                            errorMessage.classList.add('invalid-feedback', 'd-block');
                                            errorMessage.id = `${key}-error`;
                                            errorMessage.innerHTML = errors[key].join('<br>');

                                            // Sisipkan error setelah grup radio terakhir
                                            radioGroup[radioGroup.length - 1].parentNode.insertAdjacentElement('afterend', errorMessage);
                                        }
                                    }
                                } else {
                                    // Tambahkan is-invalid untuk input biasa
                                    input.classList.add('is-invalid');

                                    // Buat error message element
                                    const errorMessage = document.createElement('div');
                                    errorMessage.classList.add('invalid-feedback');
                                    errorMessage.innerHTML = errors[key].join('<br>');

                                    if ($(input).hasClass('select2-hidden-accessible')) {
                                        const select2Container = $(input).next('.select2-container');
                                        if (select2Container.length) {
                                            select2Container.addClass('is-invalid');
                                            select2Container.after(errorMessage);
                                        }
                                    } else {
                                        if (input.parentNode.classList.contains('form-floating')) {
                                            input.parentNode.appendChild(errorMessage);
                                        } else {
                                            input.parentNode.insertBefore(errorMessage, input.nextSibling);
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        toastr.error(error.response.data.message);
                    }
                    unBlockUi();
                });
        });
    });
});
