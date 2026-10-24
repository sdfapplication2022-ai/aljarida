@extends('layouts.app')
@section('title', $place->name.' - دليل الأماكن')

@section('content')
@include('partials.page-header', ['title' => $place->name, 'crumbs' => ['دليل الأماكن' => route('guide-places.index'), $place->name => null]])
<div class="blog1-scetion-area sp2">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <article class="card shadow-sm border-0">
          @if ($place->image_url)
            <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="card-img-top" style="max-height:440px;object-fit:cover">
          @endif
          <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-wrap gap-2 mb-3">
              <span class="badge bg-success-subtle text-success">{{ $typeLabel }}</span>
              <span class="badge bg-light text-dark">{{ $place->city }}</span>
              @if ($place->is_verified)<span class="badge bg-primary-subtle text-primary"><i class="fa-solid fa-circle-check"></i> مكان موثّق</span>@endif
            </div>

            <h1 class="h2 mb-4">{{ $place->name }}</h1>

            @if ($place->description)
              <div class="rich-text mb-4">{!! clean($place->description) !!}</div>
            @endif

            <dl class="row">
              @if ($place->address)
                <dt class="col-sm-4">العنوان</dt><dd class="col-sm-8">{{ $place->address }}</dd>
              @endif
              @if ($place->near_campus)
                <dt class="col-sm-4">الكلية أو المعهد القريب</dt><dd class="col-sm-8">{{ $place->near_campus }}</dd>
              @endif
              @if ($place->price_from !== null)
                <dt class="col-sm-4">السعر</dt><dd class="col-sm-8">ابتداءً من {{ number_format($place->price_from) }} دينار {{ $place->price_unit }}</dd>
              @endif
              @if ($place->amenities)
                <dt class="col-sm-4">المرافق</dt>
                <dd class="col-sm-8">
                  <div class="d-flex flex-wrap gap-2">
                    @foreach ($place->amenities as $amenity)
                      <span class="badge bg-light text-dark">{{ $amenity }}</span>
                    @endforeach
                  </div>
                </dd>
              @endif
            </dl>

            <div class="d-flex flex-wrap gap-2 mt-4">
              @if ($place->phone)
                <a href="tel:{{ preg_replace('/\D+/', '', $place->phone) }}" class="btn btn-outline-success"><i class="fa-solid fa-phone"></i> اتصال</a>
              @endif
              @if ($place->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $place->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i> واتساب</a>
              @endif
              @if ($place->website)
                <a href="{{ $place->website }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">الموقع الإلكتروني <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
              @endif
            </div>
          </div>
        </article>
      </div>

      <aside class="col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">
            <h2 class="h5 mb-3">أماكن مشابهة</h2>
            @forelse ($relatedPlaces as $relatedPlace)
              <div class="border-bottom py-3">
                <a class="fw-semibold text-decoration-none" href="{{ route('guide-places.show', $relatedPlace) }}">{{ $relatedPlace->name }}</a>
                <div class="small text-muted mt-1">{{ $relatedPlace->city }} · {{ $typeLabel }}</div>
              </div>
            @empty
              <p class="text-muted mb-0">لا توجد أماكن مشابهة حاليًا.</p>
            @endforelse
            <a href="{{ route('guide-places.index', ['type' => $place->type, 'city' => $place->city]) }}" class="btn btn-outline-success mt-3">عرض المزيد</a>
          </div>
        </div>

        @if ($place->latitude !== null && $place->longitude !== null)
          <div class="card shadow-sm border-0 mt-4 overflow-hidden">
            <div class="card-body p-4 pb-3">
              <h2 class="h5 mb-1"><i class="fa-solid fa-map-location-dot text-success me-1"></i> الموقع على الخريطة</h2>
              <p class="small text-muted mb-0">{{ $place->address ?: $place->city }}</p>
            </div>
            <div class="guide-place-map">
              <iframe
                title="خريطة موقع {{ $place->name }}"
                src="https://maps.google.com/maps?q={{ $place->latitude }},{{ $place->longitude }}&z=15&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen>
              </iframe>
            </div>
            <div class="card-body p-3">
              <a href="https://www.google.com/maps/search/?api=1&query={{ $place->latitude }},{{ $place->longitude }}"
                 target="_blank" rel="noopener noreferrer" class="btn btn-outline-success w-100">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> فتح في خرائط Google
              </a>
            </div>
          </div>
        @endif
      </aside>
    </div>
  </div>
</div>

@push('styles')
<style>
  .guide-place-map {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    min-height: 220px;
    background: #f1f4f2;
  }

  .guide-place-map iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
  }

  @media (max-width: 575.98px) {
    .guide-place-map {
      aspect-ratio: 16 / 10;
      min-height: 200px;
    }
  }
</style>
@endpush
@endsection
