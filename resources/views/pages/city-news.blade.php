@extends('layouts.app')
@section('title', $city !== '' ? 'أخبار '.$city : 'أخبار المدن')

@section('content')
@include('partials.page-header', ['title' => $city !== '' ? 'أخبار '.$city : 'الأخبار حسب المدينة', 'crumbs' => ['أخبار المدن' => null]])
<div class="blog1-scetion-area sp2">
  <div class="container">
    <form action="{{ route('city-news') }}" method="GET" class="row g-2 justify-content-center mb-5">
      <div class="col-md-5">
        <label for="city-filter" class="form-label">اختر المدينة</label>
        <select id="city-filter" name="city" class="form-select">
          <option value="">كل المدن</option>
          @foreach ($cities as $availableCity)
            <option value="{{ $availableCity }}" @selected($city === $availableCity)>{{ $availableCity }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-auto d-flex align-items-end">
        <button type="submit" class="btn btn-success">عرض الأخبار</button>
      </div>
    </form>
    @if ($articles->count())<div class="row">@endif
    @forelse ($articles as $article)
      @include('partials.article-card-sm', ['a' => $article])
    @empty
      <p class="text-center">لا توجد أخبار منشورة لهذه المدينة بعد.</p>
    @endforelse
    @if ($articles->count())</div>@endif
    <div class="mt-4 d-flex justify-content-center">{{ $articles->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
