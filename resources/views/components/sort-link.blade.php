@props(['column', 'label', 'currentSort' => '', 'currentDir' => 'asc'])

@php
    $isActive = ($currentSort === $column);
    $nextDir = ($isActive && $currentDir === 'asc') ? 'desc' : 'asc';
    $url = request()->fullUrlWithQuery(['sort' => $column, 'dir' => $nextDir, 'page' => 1]);
@endphp

<a href="{{ $url }}" class="text-decoration-none text-reset d-inline-flex align-items-center gap-1 user-select-none fw-semibold">
    <span>{{ $label }}</span>
    @if ($isActive)
        @if ($currentDir === 'asc')
            <i class="bi bi-arrow-up text-primary fw-bold"></i>
        @else
            <i class="bi bi-arrow-down text-primary fw-bold"></i>
        @endif
    @else
        <i class="bi bi-arrow-down-up text-muted opacity-50 small"></i>
    @endif
</a>
