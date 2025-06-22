@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span> {{ $title }}</h4>
    <div class="card mb-4">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @include('dashboard.setting.message-template.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection