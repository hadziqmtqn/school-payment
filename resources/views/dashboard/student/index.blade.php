@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span> {{ $title }}
    </h4>
    <div class="card mb-3">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline mb-3 filter">
                        <select id="select-school-year" class="form-select select2">
                            <option value="{{ $schoolYearActive['id'] }}" selected>{{ $schoolYearActive['year'] }}</option>
                        </select>
                        <label for="select-school-year">Tahun Ajaran</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline mb-3 filter">
                        <select id="select-class-level" class="form-select select2" data-allow-clear="true"></select>
                        <label for="select-class-level">Level Kelas</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating form-floating-outline mb-3 filter">
                        <select id="select-sub-class-level" class="form-select select2" data-allow-clear="true"></select>
                        <label for="select-sub-class-level">Sub Level Kelas</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-datatable">
            <table class="table table-striped text-nowrap" id="datatable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>No. Reg</th>
                    <th>Kelas</th>
                    <th>No. Whatsapp</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    @can('student-write')
        @include('dashboard.student.modal-create')
    @endcan
@endsection

@section('scripts')
    <script src="{{ asset('js/student/datatable.js') }}"></script>
    <script src="{{ asset('js/student/validation.js') }}"></script>
    <script src="{{ asset('js/school-year/select.js') }}"></script>
    <script src="{{ asset('js/class-level/select.js') }}"></script>
    <script src="{{ asset('js/student/create-method.js') }}"></script>
@endsection