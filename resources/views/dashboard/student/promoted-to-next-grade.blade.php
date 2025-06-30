@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span> {{ $title }}
    </h4>
    <div class="card mb-3">
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mb-3 filter">
                        <input type="text" class="form-control" value="{{ $schoolYearActive['year'] }}" readonly>
                        <label for="select-school-year">Tahun Ajaran Saat Ini</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mb-3 filter">
                        <input type="text" class="form-control" value="{{ $nextSchoolYear['year'] }}" readonly>
                        <label for="select-school-year">Tahun Ajaran Berikutnya</label>
                    </div>
                </div>
            </div>
            <div class="form-floating form-floating-outline mb-3 filter">
                <select id="select-class-level" class="form-select select2" data-allow-clear="true"></select>
                <label for="select-class-level">Level Kelas Saat Ini</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <h5 class="card-header" id="currentLevelName">Dari Kelas -</h5>
                <div class="card-datatable">
                    <table class="table table-striped text-nowrap" id="datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Opsi</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <h5 class="card-header" id="nextLevelName">Naik Ke Kelas -</h5>
                <div class="card-datatable">
                    <table class="table table-striped text-nowrap" id="datatableNextLevel">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Opsi</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/class-level/select.js') }}"></script>
@endsection