$(function () {
    const table = '#datatableSubClassLevel';

    const dataTable = $(table).DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        order: [[1, 'asc']],
        ajax: {
            url: "/sub-class-level/datatable",
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
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    // Menyimpan halaman saat ini sebelum reload
    function reloadTable() {
        const currentPage = dataTable.page();
        dataTable.ajax.reload();
        dataTable.page(currentPage).draw('page');
    }

    $('#modalEditSubClassLevel').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const slug = button.data('slug');
        const name = button.data('name');

        // Isi nilai modal
        $('#editNameSubClassLevel').val(name);

        // Atur event handler untuk tombol "Simpan"
        $('#btn-edit-sub-class-level').off('click').on('click', function() {
            blockUi();
            toastrOption();

            // Clear previous errors
            const form = document.getElementById('formEditSubClassLevel');
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
            axios.put(`/sub-class-level/${slug}/update`, $('#formEditSubClassLevel').serialize())
                .then(response => {
                    unBlockUi();
                    $('#modalEditSubClassLevel').modal('hide');
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

    dataTable.off('click').on('click', '.delete', function () {
        let slug = $(this).data('slug');
        let url = '/sub-class-level/' + slug + '/delete';
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Peringatan!',
            text: "Apakah Anda ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, HAPUS!',
            customClass: {
                confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect'
            },
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                blockUi();

                axios.delete(url, {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function (response) {
                    toastr.success(response.data.message);
                    reloadTable();
                    unBlockUi();
                }).catch(function (error) {
                    unBlockUi();
                    toastr.error(error.response.data.message);
                });
            }
        });
    });
});
