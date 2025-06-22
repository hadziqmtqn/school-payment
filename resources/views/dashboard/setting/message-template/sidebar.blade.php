<button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#modalCreate">Tambah Baru</button>
<div class="demo-inline-spacing">
    <div class="list-group">
        @foreach($messageTemplates as $messageTemplate)
            <a href="{{ route('message-template.show', $messageTemplate->slug) }}" class="list-group-item list-group-item-action flex-column align-items-start {{ url()->current() == route('message-template.show', $messageTemplate->slug) ? 'active' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-1">{{ Str::limit($messageTemplate->title, 25) }}</h4>
                    <small class="badge bg-primary">{{ $messageTemplate->recipient }}</small>
                </div>
                <p class="mb-1">{{ Str::limit($messageTemplate->message, 50) }}</p>
            </a>
        @endforeach
    </div>
</div>
@include('dashboard.setting.message-template.modal-create')