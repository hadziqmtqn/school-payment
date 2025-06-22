<div class="modal fade" id="modalCreate" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2 pb-1">Tambah {{ $title }}</h3>
                </div>
                <form action="{{ route('message-template.store') }}" id="form" class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework" method="post">
                    @csrf
                    <div class="col-12 fv-plugins-icon-container">
                        <div class="form-floating form-floating-outline mb-3">
                            <select class="form-select select2" name="category" id="createCategory" required>
                                <option value=""></option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" @selected(old('category') == $category)>{{ Str::title(str_replace('-', ' ', $category)) }}</option>
                                @endforeach
                            </select>
                            <label for="createCategory">Kategori</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <select class="form-select select2" name="recipient" id="createRecipient" required>
                                <option value=""></option>
                                <option value="siswa" @selected(old('recipient') == 'siswa')>Siswa</option>
                                <option value="admin" @selected(old('recipient') == 'admin')>Admin</option>
                            </select>
                            <label for="createRecipient">Penerima</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" name="title" class="form-control" id="createTitle" value="{{ old('title') }}" placeholder="Judul" required>
                            <label for="createTitle">Judul</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <textarea name="message" class="form-control" id="createMessage" placeholder="Pesan" style="min-height: 200px" required>{{ old('message') }}</textarea>
                            <label for="createMessage">Pesan</label>
                        </div>
                        @include('layouts.session')
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light" id="btn-edit">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
