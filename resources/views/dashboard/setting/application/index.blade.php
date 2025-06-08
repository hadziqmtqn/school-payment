@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span> {{ $title }}</h4>
    <div class="card mb-4">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="card-body">
            <form action="{{ route('application.store') }}" id="form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama" value="{{ $application['name'] }}">
                            <label for="name">Nama</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" name="school_name" id="school_name" placeholder="Nama Sekolah" value="{{ $application['schoolName'] }}">
                            <label for="school_name">Nama Sekolah</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <select name="notification_method" class="form-select select2" id="notification_method">
                                @foreach(['whatsapp','email'] as $method)
                                    <option value="{{ $method }}" @selected($application['notificationMethod'] == $method)>{{ ucfirst($method) }}</option>
                                @endforeach
                            </select>
                            <label for="notification_method">Metode Notifikasi</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="file" class="form-control" name="logo" id="logo" accept=".jpg,.jpeg,.png">
                            <label for="logo">Foto</label>
                        </div>
                    </div>
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
    <script src="{{ asset('js/application/validation.js') }}"></script>
@endsection