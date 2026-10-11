@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">{{ session('warning') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-x-octagon-fill me-2 fs-5"></i>
            <strong class="flex-grow-1">Terdapat kesalahan dalam pengisian formulir:</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
