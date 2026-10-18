@extends('layouts.admin')
@section('heading', 'نظرة عامة')

@section('content')
<div class="row g-4 mb-4">
    @foreach ($stats as [$label, $count, $key, $icon])
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.crud.index', $key) }}" class="text-decoration-none">
                <div class="card h-100 border-0">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold">{{ $label }}</p>
                            <h2 class="mb-0 fw-bold" style="color:var(--brand)">{{ $count }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; background:rgba(10, 122, 103, 0.1); color:var(--brand);">
                            <i class="fa-solid {{ $icon }} fa-2x"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">آخر المقالات</h5>
            </div>
            <div class="card-body p-0 mt-3">
                <ul class="list-group list-group-flush">
                    @forelse ($latestArticles as $a)
                        <li class="list-group-item px-4 py-3 border-light d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.crud.edit', ['articles', $a->id]) }}" class="text-decoration-none fw-semibold text-dark">{{ $a->title }}</a>
                            <i class="fa-solid fa-chevron-left text-muted" style="font-size:0.8rem"></i>
                        </li>
                    @empty 
                        <li class="list-group-item px-4 py-4 text-muted text-center">لا توجد مقالات</li> 
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">آخر الرسائل</h5>
            </div>
            <div class="card-body p-0 mt-3">
                <ul class="list-group list-group-flush">
                    @forelse ($latestMessages as $m)
                        <li class="list-group-item px-4 py-3 border-light">
                            <div class="d-flex justify-content-between">
                                <strong style="color:var(--brand)">{{ $m->first_name }} {{ $m->last_name }}</strong>
                                <small class="text-muted">{{ $m->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-muted mt-1" style="font-size:0.9rem">{{ \Illuminate\Support\Str::limit($m->message, 70) }}</div>
                        </li>
                    @empty 
                        <li class="list-group-item px-4 py-4 text-muted text-center">لا توجد رسائل</li> 
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection