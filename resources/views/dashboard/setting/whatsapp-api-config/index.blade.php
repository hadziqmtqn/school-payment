@extends('layouts.master')
@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('dashboard') }}">Dashboard</a> /</span> {{ $title }}</h4>
    <div class="card mb-4">
        <h5 class="card-header">{{ $title }}</h5>
        <div class="card-body">
            <form action="{{ route('whatsapp-api-config.store') }}" id="form" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="url" class="form-control" name="endpoint" id="endpoint" placeholder="Endpoint" value="{{ $whatsappApiConfig->endpoint }}">
                            <label for="endpoint">Endpoint</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" name="api_key" id="api_key" placeholder="API Key" value="{{ $whatsappApiConfig->api_key }}">
                            <label for="api_key">API Key</label>
                        </div>
                    </div>
                </div>
                @include('layouts.session')
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                    <button type="reset" class="btn btn-outline-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/whatsapp-api-config/validation.js') }}"></script>
@endsection