@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span> {{ $title }}
    </h4>
    <div class="card mb-3">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="card-datatable">
            <table class="table table-striped text-nowrap" id="datatable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>No. Whatsapp</th>
                    <th>Kontak Utama</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    @if(auth()->user()->can('admin-write'))
        @include('dashboard.setting.admin.modal-create')
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/datatable.js') }}"></script>
    <script src="{{ asset('js/admin/validation.js') }}"></script>
@endsection