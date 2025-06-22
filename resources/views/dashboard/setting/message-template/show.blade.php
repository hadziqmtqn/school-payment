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
                <div class="col-md-8">
                    <form action="{{ route('message-template.update', $messageTemplate->slug) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-floating form-floating-outline mb-3">
                            <select class="form-select select2" name="category" id="category">
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" @selected($messageTemplate->category == $category)>{{ Str::title(str_replace('-', ' ', $category)) }}</option>
                                @endforeach
                            </select>
                            <label for="category">Kategori</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <select class="form-select select2" name="recipient" id="recipient">
                                <option value=""></option>
                                <option value="siswa" @selected($messageTemplate->recipient == 'siswa')>Siswa</option>
                                <option value="admin" @selected($messageTemplate->recipient == 'admin')>Admin</option>
                            </select>
                            <label for="recipient">Penerima</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" name="title" class="form-control" id="title" value="{{ $messageTemplate->title }}" placeholder="Judul">
                            <label for="title">Judul</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <textarea name="message" class="form-control" id="message" placeholder="Pesan" style="min-height: 200px">{{ $messageTemplate->message }}</textarea>
                            <label for="message">Pesan</label>
                        </div>
                        @include('layouts.session')
                        <button type="submit" class="btn btn-primary waves-light waves-effect">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection