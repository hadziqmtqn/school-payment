<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body p-md-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="address-title mb-2 pb-1">Tambah {{ $title }}</h3>
                    <p class="address-subtitle">Pilih metode tambah baru</p>
                </div>
                <form class="row g-4">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md mb-md-0 mb-3">
                                <div class="form-check custom-option custom-option-icon custom-option-label">
                                    <label class="form-check-label custom-option-content" for="createManualy">
                                          <span class="custom-option-body">
                                            <i class="mdi mdi-plus"></i>
                                            <span class="custom-option-title">Tambah Manual</span>
                                          </span>
                                        <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="createManualy" checked />
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-3">
                                <div class="form-check custom-option custom-option-icon custom-option-label">
                                    <label class="form-check-label custom-option-content" for="createImport">
                                          <span class="custom-option-body">
                                            <i class="mdi mdi-file-excel-outline"></i>
                                            <span class="custom-option-title"> Import Excel </span>
                                          </span>
                                        <input name="customRadioIcon" class="form-check-input" type="radio" value="" id="createImport" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="{{ route('student.store') }}" id="manualForm" class="row g-4 mt-4 d-block" method="post">
                    @csrf
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="modalAddressFirstName" name="modalAddressFirstName" class="form-control" placeholder="John" />
                            <label for="modalAddressFirstName">First Name</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
                <form action="#" id="importForm" class="row g-4 mt-4 d-none" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <input type="file" id="file" name="file" class="form-control" accept=".xls,.xlsx" />
                            <label for="file">File Excel</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>