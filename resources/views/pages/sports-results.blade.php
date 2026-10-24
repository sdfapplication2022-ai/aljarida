@extends('layouts.app')
@section('title', 'نتائج الرياضة')

@section('content')
@include('partials.page-header', ['title' => 'نتائج الرياضة المحلية', 'crumbs' => ['الرياضة' => null]])
@include('partials.sports-styles')

<div class="blog1-scetion-area sp2">
  <div class="container">
    <p class="text-center mb-5">تابع نتائج المباريات والمسابقات الرياضية المحلية.</p>
    <div class="row g-4">
      @forelse ($results as $result)
        <div class="col-lg-6">
          @include('partials.sports-card', ['result' => $result])
        </div>
      @empty
        <div class="col-12"><p class="text-center">لا توجد نتائج منشورة حاليًا. تابعنا للاطلاع على آخر المباريات.</p></div>
      @endforelse
    </div>
    <div class="mt-5 d-flex justify-content-center">{{ $results->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
