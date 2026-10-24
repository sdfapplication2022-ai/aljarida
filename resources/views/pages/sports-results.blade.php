@extends('layouts.app')
@section('title', 'نتائج الرياضة')

@section('content')
@include('partials.page-header', ['title' => 'نتائج الرياضة المحلية', 'crumbs' => ['الرياضة' => null]])
@include('partials.sports-styles')

<div class="blog1-scetion-area sp2">
  <div class="container">
    <p class="text-center mb-5">تابع مواعيد المباريات القادمة ونتائج المباريات التي لُعبت.</p>

    <section aria-labelledby="upcoming-matches-title" class="mb-5">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h2 id="upcoming-matches-title" class="h3 fw-bold mb-0">
          <i class="fa-regular fa-calendar-check text-success me-2"></i>المباريات القادمة
        </h2>
        <span class="badge bg-success-subtle text-success">{{ $upcomingResults->total() }} مباراة</span>
      </div>
      <div class="row g-4">
        @forelse ($upcomingResults as $result)
          <div class="col-lg-6 {{ $upcomingResults->count() === 1 ? 'mx-auto' : '' }}">
            @include('partials.sports-card', ['result' => $result, 'isUpcoming' => true])
          </div>
        @empty
          <div class="col-12">
            <p class="text-center text-muted py-4 mb-0">لا توجد مباريات قادمة مبرمجة حاليًا.</p>
          </div>
        @endforelse
      </div>
      @if ($upcomingResults->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $upcomingResults->links('pagination::bootstrap-5') }}</div>
      @endif
    </section>

    <section aria-labelledby="played-matches-title">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h2 id="played-matches-title" class="h3 fw-bold mb-0">
          <i class="fa-solid fa-flag-checkered text-success me-2"></i>المباريات التي لُعبت
        </h2>
        <span class="badge bg-light text-dark">{{ $playedResults->total() }} مباراة</span>
      </div>
      <div class="row g-4">
        @forelse ($playedResults as $result)
          <div class="col-lg-6 {{ $playedResults->count() === 1 ? 'mx-auto' : '' }}">
            @include('partials.sports-card', ['result' => $result, 'isUpcoming' => false])
          </div>
        @empty
          <div class="col-12">
            <p class="text-center text-muted py-4 mb-0">لا توجد نتائج مباريات منشورة حاليًا.</p>
          </div>
        @endforelse
      </div>
      @if ($playedResults->hasPages())
        <div class="mt-4 d-flex justify-content-center">{{ $playedResults->links('pagination::bootstrap-5') }}</div>
      @endif
    </section>
  </div>
</div>
@endsection
