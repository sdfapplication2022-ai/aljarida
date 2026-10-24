@extends('layouts.app')
@section('title', 'دليل الفعاليات في جندوبة')

@section('content')
@include('partials.page-header', ['title' => 'دليل الفعاليات في جندوبة', 'crumbs' => ['دليل الفعاليات' => null]])
<div class="blog1-scetion-area sp2">
  <div class="container">
    <p class="text-center mb-5">اكتشف الفعاليات والأنشطة القادمة في جندوبة ومعتمدياتها.</p>
    <div class="row g-4">
      @forelse ($events as $event)
        <div class="col-lg-6">
          <article class="card h-100 shadow-sm border-0">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                <span class="badge bg-success-subtle text-success">{{ $event->city }}</span>
                <time class="text-muted" datetime="{{ $event->starts_at->toAtomString() }}">
                  {{ $event->starts_at->locale('ar')->translatedFormat('l، d F Y · H:i') }}
                </time>
              </div>
              <h2 class="h4">{{ $event->title }}</h2>
              @if ($event->venue)<p class="text-muted mb-2"><i class="fa-solid fa-location-dot"></i> {{ $event->venue }}</p>@endif
              @if ($event->summary)<p>{{ $event->summary }}</p>@endif
              @if ($event->description)<div class="rich-text">{!! clean($event->description) !!}</div>@endif
              @if ($event->organizer)<p class="small text-muted mb-0">تنظيم: {{ $event->organizer }}</p>@endif
            </div>
            @if ($event->url)
              <div class="card-footer bg-white border-0 px-4 pb-4">
                <a href="{{ $event->url }}" target="_blank" rel="noopener" class="btn btn-outline-success">المزيد من المعلومات <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
              </div>
            @endif
          </article>
        </div>
      @empty
        <div class="col-12"><p class="text-center">لا توجد فعاليات قادمة حاليًا. عُد قريبًا للاطلاع على الجديد.</p></div>
      @endforelse
    </div>
    <div class="mt-5 d-flex justify-content-center">{{ $events->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
