@extends('layouts.app')
@section('title', 'دليل الأماكن والخدمات في جندوبة')

@section('content')
@include('partials.page-header', ['title' => 'دليل الأماكن والخدمات', 'crumbs' => ['دليل الأماكن' => null]])
<div class="blog1-scetion-area sp2">
  <div class="container guide-places-page">
    <p class="text-center mb-4">اعثر على السكن والمطاعم والخدمات القريبة من مؤسساتك الجامعية في جندوبة.</p>

    <form action="{{ route('guide-places.index') }}" method="GET" class="guide-places-filters row g-3 align-items-end justify-content-center mb-5">
      <div class="col-12 col-md-6 col-lg-5">
        <label for="place-search" class="form-label fw-semibold">بحث</label>
        <input id="place-search" name="q" value="{{ $search }}" class="form-control form-control-lg" type="search" maxlength="100" placeholder="اسم المكان أو العنوان">
      </div>
      <div class="col-12 col-md-3 col-lg-2">
        <label for="place-type" class="form-label fw-semibold">التصنيف</label>
        <select id="place-type" name="type" class="form-select">
          <option value="">كل التصنيفات</option>
          @foreach ($types as $key => $label)
            <option value="{{ $key }}" @selected($type === $key)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12 col-md-3 col-lg-2">
        <label for="place-city" class="form-label fw-semibold">المدينة</label>
        <select id="place-city" name="city" class="form-select">
          <option value="">كل المدن</option>
          @foreach ($cities as $availableCity)
            <option value="{{ $availableCity }}" @selected($city === $availableCity)>{{ $availableCity }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12 col-lg-auto">
        <button type="submit" class="btn btn-success btn-lg w-100">بحث</button>
      </div>
    </form>

    <div class="row g-4">
      @forelse ($places as $place)
        <div class="col-lg-6">
          <article class="card h-100 shadow-sm border-0">
            @if ($place->image_url)
              <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="card-img-top" loading="lazy" style="height:220px;object-fit:cover">
            @endif
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                <span class="badge bg-success-subtle text-success">{{ $types[$place->type] ?? $place->type }}</span>
                <span class="badge bg-light text-dark">{{ $place->city }}</span>
              </div>
              <h2 class="h4">
                <a class="text-decoration-none text-dark" href="{{ route('guide-places.show', $place) }}">{{ $place->name }}</a>
                @if ($place->is_verified)<span class="text-success" title="مكان موثّق" aria-label="مكان موثّق"><i class="fa-solid fa-circle-check"></i></span>@endif
              </h2>
              @if ($place->near_campus)<p class="small text-muted mb-2"><i class="fa-solid fa-graduation-cap"></i> قريب من {{ $place->near_campus }}</p>@endif
              @if ($place->address)<p class="text-muted mb-2"><i class="fa-solid fa-location-dot"></i> {{ $place->address }}</p>@endif
              @if ($place->description)<p class="mb-2">{{ \Illuminate\Support\Str::limit(strip_tags($place->description), 160) }}</p>@endif
              @if ($place->price_from !== null)
                <p class="fw-semibold text-success mb-0">ابتداءً من {{ number_format($place->price_from) }} د.ت {{ $place->price_unit }}</p>
              @endif
            </div>
            <div class="card-footer bg-white border-0 px-4 pb-4">
              <a href="{{ route('guide-places.show', $place) }}" class="btn btn-outline-success">التفاصيل</a>
            </div>
          </article>
        </div>
      @empty
        <div class="col-12"><p class="text-center">لا توجد أماكن منشورة تطابق البحث حاليًا.</p></div>
      @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">{{ $places->links('pagination::bootstrap-5') }}</div>
  </div>
</div>

@push('styles')
<style>
  .guide-places-filters {
    max-width: 1080px;
    margin-inline: auto;
  }

  .guide-places-filters .form-label {
    display: block;
    margin-bottom: .5rem;
    text-align: right;
  }

  .guide-places-filters .form-control,
  .guide-places-filters .form-select {
    min-height: 48px;
  }

  @media (min-width: 992px) {
    .guide-places-filters .btn {
      min-width: 88px;
    }
  }
</style>
@endpush
@endsection
