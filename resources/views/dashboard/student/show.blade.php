@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span>
        <span class="text-muted fw-light"><a href="{{ route('student.index') }}">{{ $title }}</a> /</span>
        {{ $subTitle }}
    </h4>
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <img class="img-fluid rounded mb-3 mt-4" src="{{ $user->avatar() }}" height="120" width="120" alt="User avatar">
                            <div class="user-info text-center">
                                <h4>{{ $user->name }}</h4>
                                <span class="badge bg-label-danger rounded-pill">{{ $user->student?->reg_number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap my-2 py-3">
                        <div class="d-flex align-items-center me-4 mt-3 gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-check mdi-24px"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-0">1.23k</h4>
                                <span>Tasks Done</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3 gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-star-outline mdi-24px"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-0">568</h4>
                                <span>Projects Done</span>
                            </div>
                        </div>
                    </div>
                    <h5 class="pb-3 border-bottom mb-3">Detail</h5>
                    <div class="info-container">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <span class="fw-medium text-heading me-2">Jenis Kelamin:</span>
                                <span>{{ $user->student?->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium text-heading me-2">No. Whatsapp:</span>
                                <span>{{ $user->student?->whatsapp_number }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium text-heading me-2">Email:</span>
                                <span>{{ $user->email }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium text-heading me-2">Status:</span>
                                <span class="badge bg-label-{{ $user->is_active ? 'success' : 'danger' }} rounded-pill">{{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-medium text-heading me-2">Level Kelas:</span>
                                <span>{{ $user->student?->studentLevel?->classLevel?->name }} - {{ $user->student?->studentLevel?->subClassLevel?->name }}</span>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-primary me-3 waves-effect waves-light" data-bs-target="#editUser" data-bs-toggle="modal">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('student-write')
        @include('dashboard.student.modal-edit')
    @endcan
@endsection

@section('scripts')
    <script src="{{ asset('js/class-level/select.js') }}"></script>
@endsection