@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span>
        <span class="text-muted fw-light"><a href="{{ route('admin.index') }}">{{ $title }}</a> /</span>
        {{ $subTitle }}
    </h4>
    <div class="card mb-4">
        <h5 class="card-header">{{ $subTitle }}</h5>
        <div class="card-body">
            <form action="{{ route('admin.update', $user->username) }}" id="form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="divider text-start">
                    <div class="divider-text">Data Pribadi</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama" value="{{ $user->name }}">
                            <label for="name">Nama</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ $user->email }}">
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="number" class="form-control" name="whatsapp_number" id="whatsapp_number" placeholder="No. Whatsapp" value="{{ optional($user->admin)->whatsapp_number }}">
                            <label for="whatsapp_number">No. Whatsapp</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">Jadikan Kontak Utama</div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="mark_as_contact" id="yes" value="1" @checked($user->admin?->mark_as_contact)>
                                <label class="form-check-label" for="yes">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="mark_as_contact" id="no" value="0" @checked(!$user->admin?->mark_as_contact)>
                                <label class="form-check-label" for="no">Bukan</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="file" class="form-control" name="photo" id="photo" accept=".jpg,.jpeg,.png">
                            <label for="photo">Foto</label>
                        </div>
                    </div>
                    @if($user->roles->first()->name != 'super-admin')
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline mb-3">
                                <select name="is_active" id="active" class="form-select select2" @disabled($user->hasRole('super_admin'))>
                                    <option value="1" @selected($user->is_active == 1)>Aktif</option>
                                    <option value="0" @selected($user->is_active == 0)>Tidak Aktif</option>
                                </select>
                                <label for="active">Status Aktif</label>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="divider text-start">
                    <div class="divider-text">Keamanan</div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 form-password-toggle mb-3">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="password" id="newPassword" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <label for="newPassword">Kata Sandi Baru</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                        </div>
                        <small class="fst-italic text-danger">Abaikan jika tidak ingin mengubah kata sandi</small>
                    </div>
                    <div class="col-md-6 form-password-toggle mb-3">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="password" name="password_confirmation" id="confirmPassword" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <label for="confirmPassword">Konfirmasi Kata Sandi Baru</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                        </div>
                        <small class="fst-italic text-danger">Abaikan jika tidak ingin mengubah kata sandi</small>
                    </div>
                    @include('layouts.session')
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                    <button type="reset" class="btn btn-outline-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/update-validation.js') }}"></script>
@endsection