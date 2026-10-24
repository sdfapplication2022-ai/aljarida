@extends('layouts.app')
@section('title', 'نتائج الرياضة')

@section('content')
@include('partials.page-header', ['title' => 'نتائج الرياضة المحلية', 'crumbs' => ['الرياضة' => null]])
<div class="blog1-scetion-area sp2">
  <div class="container">
    <p class="text-center mb-5">تابع نتائج المباريات والمسابقات الرياضية المحلية.</p>
    <div class="row g-3">
      @forelse ($results as $result)
        <div class="col-lg-6">
          <article class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between gap-3 mb-3">
                <span class="badge bg-success-subtle text-success">{{ $result->competition }}</span>
                <time class="small text-muted" datetime="{{ $result->played_at->toAtomString() }}">
                  {{ $result->played_at->locale('ar')->translatedFormat('d F Y · H:i') }}
                </time>
              </div>
              <div class="d-flex align-items-center justify-content-between gap-3 text-center">
                <strong class="flex-fill">{{ $result->home_team }}</strong>
                <span class="fs-3 fw-bold text-success" dir="ltr">{{ $result->home_score }} : {{ $result->away_score }}</span>
                <strong class="flex-fill">{{ $result->away_team }}</strong>
              </div>
              @if ($result->city || $result->venue)
                <p class="text-muted small text-center mt-3 mb-0">
                  {{ $result->venue }}@if ($result->venue && $result->city) · @endif{{ $result->city }}
                </p>
              @endif
            </div>
          </article>
        </div>
      @empty
        <div class="col-12"><p class="text-center">لا توجد نتائج منشورة حاليًا. تابعنا للاطلاع على آخر المباريات.</p></div>
      @endforelse
    </div>
    <div class="mt-5 d-flex justify-content-center">{{ $results->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
