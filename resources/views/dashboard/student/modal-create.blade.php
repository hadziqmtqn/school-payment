<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="address-title mb-2 pb-1">Tambah {{ $title }}</h3>
                    <p class="address-subtitle">Pilih metode tambah baru</p>
                </div>
                <form class="row g-4">
                    <div class="col-md mb-md-0 mb-3">
                        <div class="form-check custom-option custom-option-icon custom-option-label">
                            <label class="form-check-label custom-option-content" for="createManualy">
                                  <span class="custom-option-body">
                                    <i class="mdi mdi-plus"></i>
                                    <span class="custom-option-title">Tambah Manual</span>
                                  </span>
                                <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="createManualy" checked />
                            </label>
                        </div>
                    </div>
                    <div class="col-md mb-md-0 mb-3">
                        <div class="form-check custom-option custom-option-icon custom-option-label">
                            <label class="form-check-label custom-option-content" for="createImport">
                                  <span class="custom-option-body">
                                    <i class="mdi mdi-file-excel-outline"></i>
                                    <span class="custom-option-title"> Import Excel </span>
                                  </span>
                                <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="createImport" />
                            </label>
                        </div>
                    </div>
                </form>
                <form action="{{ route('student.store') }}" id="manualForm" class="row g-4 mt-4 d-block" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="divider text-start">
                            <div class="divider-text">Data Pribadi</div>
                        </div>
                    </div>
                    <div class="row p-0 m-0">
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}"/>
                                <label for="name">Nama Lengkap</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email Valid" value="{{ old('email') }}"/>
                                <label for="email">Email Valid</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-2">Jenis Kelamin</div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="L">
                                <label class="form-check-label" for="male">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="famale" value="P">
                                <label class="form-check-label" for="famale">Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <input type="number" id="whatsapp_number" name="whatsapp_number" class="form-control" placeholder="No. Whatsapp" value="{{ old('whatsapp_number') }}"/>
                            <label for="whatsapp_number">No. Whatsapp</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <select name="class_level_id" id="select-class-level-alternative" class="form-select select2"></select>
                            <label for="select-class-level-alternative">Level Kelas</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <select name="sub_class_level_id" id="select-sub-class-level-alternative" class="form-select select2"></select>
                            <label for="select-sub-class-level-alternative">Sub Level Kelas</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="divider text-start">
                            <div class="divider-text">Keamanan</div>
                        </div>
                    </div>
                    <div class="row p-0 m-0">
                        <div class="col-12 col-md-6 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="password" id="newPassword" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <label for="newPassword">Kata Sandi Baru</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="password" name="password_confirmation" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <label for="confirmPassword">Konfirmasi Kata Sandi Baru</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="divider text-start">
                            <div class="divider-text">Lainnya</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-2">Kirim Detail Akun Ke Email/No. Whatsapp Siswa</div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="send_detail_account" id="yes" value="1">
                                <label class="form-check-label" for="yes">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="send_detail_account" id="no" value="0">
                                <label class="form-check-label" for="no">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
                <form action="#" id="importForm" class="row g-4 mt-4 d-none" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <input type="file" id="file" name="file" class="form-control" accept=".xls,.xlsx" />
                            <label for="file">File Excel</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>