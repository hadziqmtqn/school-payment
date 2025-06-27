<div class="modal fade" id="editUser" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2 pb-1">Update {{ $title }}</h3>
                </div>
                <form action="{{ route('student.update', $user->username) }}" id="formEdit" class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-12 fv-plugins-icon-container">
                        <div class="divider text-start">
                            <div class="divider-text">Data Pribadi</div>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ $user->name }}"/>
                            <label for="name">Nama Lengkap</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email Valid" value="{{ $user->email }}"/>
                            <label for="email">Email Valid</label>
                        </div>
                        <div class="mb-2">Jenis Kelamin</div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="L" @checked($user->student?->gender == 'L')>
                                <label class="form-check-label" for="male">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="famale" value="P" @checked($user->student?->gender == 'P')>
                                <label class="form-check-label" for="famale">Perempuan</label>
                            </div>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" id="reg_number" name="reg_number" class="form-control" placeholder="Nomor Registrasi" value="{{ $user->student?->reg_number }}"/>
                            <label for="reg_number">Nomor Registrasi</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="number" id="whatsapp_number" name="whatsapp_number" class="form-control" placeholder="No. Whatsapp" value="{{ $user->student?->whatsapp_number }}"/>
                            <label for="whatsapp_number">No. Whatsapp</label>
                        </div>
                        <input type="hidden" name="is_graduate" value="{{ $user->student?->studentLevel?->is_graduate ? 1 : 0 }}">
                        @if($user->student?->studentLevel && !$user->student?->studentLevel?->is_graduate)
                            <div class="divider text-start">
                                <div class="divider-text">Data Level Kelas</div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select name="class_level_id" id="select-class-level-alternative" class="form-select select2">
                                            <option value="{{ $user->student?->studentLevel?->class_level_id }}" selected>{{ $user->student?->studentLevel?->classLevel?->name }}</option>
                                        </select>
                                        <label for="select-class-level-alternative">Level Kelas</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select name="sub_class_level_id" id="select-sub-class-level-alternative" class="form-select select2">
                                            <option value="{{ $user->student?->studentLevel?->sub_class_level_id }}" selected>{{ $user->student?->studentLevel?->subClassLevel?->name }}</option>
                                        </select>
                                        <label for="select-sub-class-level-alternative">Sub Level Kelas</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="divider text-start">
                            <div class="divider-text">Keamanan</div>
                        </div>
                        <div class=" mb-3 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="password" id="newPassword" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <label for="newPassword">Kata Sandi Baru</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                            <small class="fst-italic text-danger">Abaikan jika tidak ingin mengubah kata sandi</small>
                        </div>
                        <div class=" mb-3 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="password" name="password_confirmation" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <label for="confirmPassword">Konfirmasi Kata Sandi Baru</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                            <small class="fst-italic text-danger">Abaikan jika tidak ingin mengubah kata sandi</small>
                        </div>
                        <div class="divider text-start">
                            <div class="divider-text">Lainnya</div>
                        </div>
                        <div class="mb-2">Status Aktif</div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="editYes" value="1" @checked($user->is_active)>
                                <label class="form-check-label" for="editYes">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="editNo" value="0" @checked(!$user->is_active)>
                                <label class="form-check-label" for="editNo">Tidak</label>
                            </div>
                        </div>
                        @include('layouts.session')
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light" id="btn-edit">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
