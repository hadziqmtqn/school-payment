@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span> {{ $title }}</h4>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <h5 class="card-header">{{ $title }}</h5>
                <div class="card-datatable">
                    <table class="table table-striped text-nowrap" id="datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Urut</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Opsi</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <h5 class="card-header">Tambah {{ $title }}</h5>
                <form action="{{ route('class-level.store') }}" id="form" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="number" class="form-control" name="serial_number" id="serial_number" placeholder="No. Urut" value="{{ old('serial_number') }}">
                            <label for="serial_number">No. Urut</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama" value="{{ old('name') }}">
                            <label for="name">Nama</label>
                        </div>
                        @include('layouts.session')
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--sub class level--}}
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <h5 class="card-header">Sub Level Kelas</h5>
                <div class="card-datatable">
                    <table class="table table-striped text-nowrap" id="datatableSubClassLevel">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Opsi</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <h5 class="card-header">Tambah Sub Level Kelas</h5>
                <form action="{{ route('sub-class-level.store') }}" id="formSubClassLevel" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama" value="{{ old('name') }}">
                            <label for="name">Nama</label>
                        </div>
                        @include('layouts.session')
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @can('class-level-write')
        @include('dashboard.references.class-level.modal-edit')
    @endcan
    @can('sub-class-level-write')
        @include('dashboard.references.sub-class-level.modal-edit')
    @endcan
@endsection

@section('scripts')
    <script src="{{ asset('js/class-level/datatable.js') }}"></script>
    <script src="{{ asset('js/class-level/validation.js') }}"></script>
    <script src="{{ asset('js/sub-class-level/datatable.js') }}"></script>
    <script src="{{ asset('js/sub-class-level/validation.js') }}"></script>
@endsection