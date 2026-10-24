@extends('layouts.app')
@section('title', 'مباريات اليوم')

@section('content')
@include('partials.page-header', ['title' => 'مباريات اليوم', 'crumbs' => ['نتائج الرياضة' => route('sports.results')]])
@include('partials.news-styles')

<div class="np-wrap">
  <div class="np-container">
    <div class="row g-4">
      <div class="col-lg-8">
        @include('partials.matches', [
            'matches' => $matches,
            'title' => 'مباريات اليوم',
            'emptyMessage' => 'لا توجد مباريات مبرمجة اليوم في الدوريات المتابعة.',
        ])
      </div>
      <div class="col-lg-4">
        <div class="np-box">
          <h3>عن هذه الصفحة</h3>
          <p class="text-muted">تُحدَّث نتائج المباريات تلقائيًا كل دقيقة عبر واجهة المباريات، دون الحاجة إلى إعادة تحميل الصفحة.</p>
          <a href="{{ route('sports.results') }}" class="header-btn20">نتائج الرياضة المحلية <i class="fa-solid fa-angle-left"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
