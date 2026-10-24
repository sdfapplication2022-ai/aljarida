@extends('layouts.app')
@section('title', 'للقراءة لاحقًا')

@section('content')
@include('partials.page-header', ['title' => 'للقراءة لاحقًا', 'crumbs' => ['للقراءة لاحقًا' => null]])
<div class="blog1-scetion-area sp2">
  <div class="container">
    @if ($articles->count())<div class="row">@endif
    @forelse ($articles as $a)
      @include('partials.article-card-sm', ['a' => $a])
    @empty
      <p class="text-center">لا توجد مقالات محفوظة حتى الآن. احفظ أي مقال من صفحة تفاصيله.</p>
    @endforelse
    @if ($articles->count())</div>@endif
    <div class="mt-4 d-flex justify-content-center">{{ $articles->links('pagination::bootstrap-5') }}</div>
  </div>
</div>
@endsection
